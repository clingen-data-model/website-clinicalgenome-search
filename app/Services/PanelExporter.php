<?php

namespace App\Services;

use App\Concerns\HttpClient;
use App\Member;
use App\Panel;

class PanelExporter
{
    use HttpClient;
    protected $panel;

    public function __construct(Panel $panel)
    {
        $this->panel = $panel;
    }

    public function createWGData() {

    }

    public function createEpData()
    {

    }

    public function pushToProcessWire()
    {
        $data = $this->dataToSend();
        if ($this->panel->affiliate_type === 'cdwg' || $this->panel->affiliate_type === 'sccdwg') {
            $response = $this->HttpRequest()->post($this->processWireUrl() . '/', $data);
            return $response->body();
        } else if ($this->panel->affiliate_type === 'wg') {
            $response = $this->HttpRequest()->post($this->processWireUrl() . '/', $data);
            return $response->body();
        } else if ($this->panel->affiliate_type === 'vcep' || $this->panel->affiliate_type === 'gcep' || $this->panel->affiliate_type === 'scvcep') {
            $response = $this->HttpRequest()->post($this->processWireUrl().'/', $data);
            return $response->body();
        }
    }

    public function dataToSend()
    {
        if ($this->panel->affiliate_type === 'cdwg' || $this->panel->affiliate_type === 'sccdwg') {
            return $this->cdwgData();
        } else if ($this->panel->affiliate_type === 'wg') {
            return $this->wgData();
        } else if ($this->panel->affiliate_type === 'vcep' || $this->panel->affiliate_type === 'gcep' || $this->panel->affiliate_type === 'scvcep') {
            return $this->getProcessWireData();
        }
    }


    private function processWireUrl()
    {
        return sprintf('%s/api/panels', config('processwire.url'));
    }

    public function getProcessWireData()
    {
        $panel = $this->panel;
        $panel->load('activities');

        $type = $panel->affiliate_type;
        $expertPanelType = [];

        if ($type == 'gcep') {
            $panel->url_curations = 'https://search.clinicalgenome.org/kb/affiliate/' . $panel->affiliate_id;
            $expertPanelType = [1];
        } else if ($type == 'vcep' || $type == 'scvcep') {
            if ($type === 'vcep') {
               $expertPanelType = [2];
            } else if ($type === 'scvcep') {
                $expertPanelType = [5];
            }

            // CGWM-443: ERepo is addressed by affiliation id, not by matching the
            // panel's name. The old form built
            //   /ui/classifications?matchMode=exact&expertpanel=<name> VCEP
            // which broke whenever a panel was renamed, or whenever the VCEP /
            // SC-VCEP suffix on our side drifted from ERepo's spelling.
            //
            //   https://erepo.genome.network/evrepo/ui/summary/affiliation/50091?pgSize=25&matchMode=and
            if ($panel->affiliate_id) {
                $panel->url_erepo = 'https://erepo.genome.network/evrepo/ui/summary/affiliation/'
                    . rawurlencode($panel->affiliate_id)
                    . '?' . http_build_query(array(
                        'pgSize'    => 25,
                        'matchMode' => 'and',
                    ));
            } else {
                // No affiliate id means no addressable ERepo page; emit nothing
                // rather than a URL that resolves to someone else's panel.
                $panel->url_erepo = null;
            }

            if ($panel->group_clinvar_org_id && ($panel->affiliate_type === 'vcep' || $panel->affiliate_type === 'scvcep')) {
                $panel->url_clinvar = 'https://www.ncbi.nlm.nih.gov/clinvar/submitters/' . $panel->group_clinvar_org_id;
            }

        }

        //map process wire fields
        $processWireFields = [
            'name' => $panel->affiliate_id,
            'title' => $panel->effective_title,
            'title_short' => $panel->effective_short_title,
            'title_abbreviated' => $panel->effective_abbreviated_title,
            'summary' => $panel->description,
            'markdown_summary' => $panel->summary,
            'body_1' => $panel->summary,
            'type' => $panel->affiliate_type,
            'is_private' => $panel->isPrivate(),
            'affiliation_id' => $panel->affiliate_id,
            'expert_panel_type' => $expertPanelType,
            'affiliate_status_gene' => $panel->getProcessWirePanelStatus(),
            'affiliate_status_variant' => $panel->getProcessWirePanelStatus(),
            'ep_status_inactive' => $panel->is_inactive ? 1 : 0,
            'ep_status_inactive_date' => $panel->inactive_date ?? '',
            'group_clinvar_org_id' => $panel->group_clinvar_org_id,
            'url_cspec' => $panel->url_cspec,
            'url_curations' => $panel->url_curations,
            'url_erepo' => $panel->url_erepo,
            'url_clinvar' => $panel->url_clinvar,
            'relate_cdwg' => optional($panel->parent)->gpm_id,
            'relate_user_leaderships' => $panel->getMembersByType(Member::LEADER),
            'relate_user_coordinators' => $panel->getMembersByType(Member::COORDINATOR),
            //'relate_user_experts' => $panel->getMembersByType(['expert']),
            'relate_user_grant_liaison' => $panel->getMembersByType(Member::GRANT_LIAISON),
            'relate_user_committee' => $panel->getMembersByType(Member::COMMITTEE),
            'relate_user_members' => $panel->getMembersByType([Member::MEMBER, 'expert', Member::CURATOR, MEMBER::GRANT_LIAISON]),
            'relate_user_members_past' => $panel->getMembersByType(Member::PAST_MEMBER),
            'metadata_search_terms' => $panel->metadata_search_terms,
            'gpm_id' => $panel->gpm_id
        ];

        if ($panel->affiliate_type === 'gcep') {
            $processWireFields['affiliate_status_gene_date_step_1'] = $panel->getActivityValue('ep_definition_approved');
            $processWireFields['affiliate_status_gene_date_step_2'] = $panel->getActivityValue('ep_final_approval');
        } else {
            $processWireFields['affiliate_status_variant_date_step_1'] = $panel->getActivityValue('ep_definition_approved');
            $processWireFields['affiliate_status_variant_date_step_2'] = $panel->getActivityValue('vcep_draft_specifications_approved');
            $processWireFields['affiliate_status_variant_date_step_3'] = $panel->getActivityValue('vcep_pilot_approved');
            $processWireFields['affiliate_status_variant_date_step_4'] = $panel->getActivityValue('ep_final_approval');

            if ($panel->group_clinvar_org_id) {
                $processWireFields['url_clinvar'] = 'https://www.ncbi.nlm.nih.gov/clinvar/submitters/' . $panel->group_clinvar_org_id;
            }

        }

        return $processWireFields;
    }


    private function cdwgData()
    {
        $cdwgType = [];
        $panel = $this->panel;
        $name = !empty($panel->title) ? $panel->title : $panel->name;
        // Canonical abbreviations: "CDWG" and "SC-CDWG". Never SCCDWG or "SC CDWG".
        // getEffectiveBaseName() strips every legacy spelling before this is
        // appended, so re-syncing an existing "... SC CDWG" page corrects it in
        // place and is idempotent.
        if ($panel->affiliate_type === 'cdwg') {
            $type = ' CDWG';
            $cdwgType = [7];
        } else {
            $type = ' SC-CDWG';
            $cdwgType = [6];
        }

        // CDWGs/SC-CDWGs have no explicit parent_id in GPM: they all hang off the
        // "Clinical Domain Working Group" WG. Resolve that WG's gpm_id here so
        // ProcessWire can attach the page under the right parent by gpm_id
        // rather than relying on a hardcoded page name.
        $parentGpmId = $this->clinicalDomainParentGpmId();

        return [
            // 'name' becomes the ProcessWire page slug. Expert panels have always
            // used the affiliate id (giving /affiliation/50140/); WGs and CDWGs
            // now do the same, so a group renamed in GPM keeps a stable URL.
            // The human-readable value stays in 'title'.
            // PagePathHistory (installed) records the old path and 301s it.
            'name' => $panel->affiliate_id ? $panel->affiliate_id : ($name . $type),
            'title' => $name . $type,
            'title_short' => $panel->title_short,
            'cdwg_type' => $cdwgType,
            'parent_id' => $parentGpmId,
            'has_parent' => !empty($parentGpmId),
            'title_abbreviated' => $panel->title_abbreviated,
            'summary' => $panel->description,
            'markdown_summary' => $panel->summary,
            'body_1' => $panel->summary,
            'type' => $panel->affiliate_type,
            'is_private' => $panel->isPrivate(),
            'affiliation_id' => $panel->affiliate_id,
            'images_1' => [],
            'relate_user_leaderships' => $panel->getMembersByType(Member::LEADER),
            'relate_user_coordinators' => $panel->getMembersByType(Member::COORDINATOR),
            //'relate_user_experts' => $panel->getMembersByType('expert'),
            //'relate_user_curators' => $panel->getMembersByType(Member::CURATOR),
            'relate_user_committee' => $panel->getMembersByType(Member::COMMITTEE),
            'relate_user_members' => $panel->getMembersByType([Member::MEMBER, 'expert', Member::CURATOR]),
            //'relate_user_members_past' => $panel->getMembersByType(Member::PAST_MEMBER),
            'metadata_search_terms' => $panel->metadata_search_terms,
            'gpm_id' => $panel->gpm_id
        ];
    }

    /**
     * gpm_id of the "Clinical Domain Working Group" WG, which is the implicit
     * parent of every CDWG / SC-CDWG.
     *
     * The relationship is not modelled in GPM (cdwg rows carry no parent_id),
     * so it is resolved by matching a wg-type panel whose title/name contains
     * "Clinical Domain Working".
     *
     * @return string|null
     */
    private function clinicalDomainParentGpmId()
    {
        static $gpmId = false; // false = not looked up yet, null = looked up, not found

        if ($gpmId !== false) {
            return $gpmId;
        }

        $parent = Panel::query()
            ->where('affiliate_type', 'wg')
            ->whereNotNull('gpm_id')
            ->where(function ($query) {
                $query->where('title', 'like', '%Clinical Domain Working%')
                      ->orWhere('name', 'like', '%Clinical Domain Working%');
            })
            ->first();

        $gpmId = optional($parent)->gpm_id;

        return $gpmId;
    }

    private function wgData()
    {
        $panel = $this->panel;
        return [
            // See cdwgData(): the slug is the affiliate id, the label is 'title'.
            'name' => $panel->affiliate_id ? $panel->affiliate_id : $panel->title,
            'title' => $panel->title,
            'type' => 'wg',
            'is_private' => $panel->isPrivate(),
            'affiliation_id' => $panel->affiliate_id,
            'parent_id' => optional($panel->parent)->gpm_id,
            'title_short' => $panel->title_short,
            'has_parent' => $panel->hasParent(),
            'summary' => $panel->summary,
            'markdown_summary' => $panel->description,
            'body_1' => $panel->summary,
            'body_2' => $panel->description,
            'images_icon_url' => $panel->icon_url,
            'images_1' => [],
            'relate_user_leaderships' => $panel->getMembersByType(Member::LEADER),
            'relate_user_coordinators' => $panel->getMembersByType(Member::COORDINATOR),
            'relate_user_experts' => $panel->getMembersByType('expert'),
            //'relate_user_curators' => $panel->getMembersByType(Member::CURATOR),
            'relate_user_committee' => $panel->getMembersByType(Member::COMMITTEE),
            //'relate_user_grant_liaison' => $panel->getMembersByType(Member::GRANT_LIAISON),
            'relate_user_members' => $panel->getMembersByType([Member::MEMBER, 'expert', Member::CURATOR, Member::GRANT_LIAISON]),
            //'relate_user_members_past' => $panel->getMembersByType(Member::PAST_MEMBER),
            'metadata_search_terms' => $panel->metadata_search_terms,
            'gpm_id' => $panel->gpm_id
        ];
    }
}
