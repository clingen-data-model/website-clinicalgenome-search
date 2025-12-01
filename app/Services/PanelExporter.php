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
        if ($this->panel->affiliate_type === 'cdwg') {
            $data = $this->cdwgData();
            $response = $this->HttpRequest()->post($this->processWireUrl() . '/', $data);
            return $response->body();
        } else if ($this->panel->affiliate_type === 'wg') {
            $data = $this->wgData();
            $response = $this->HttpRequest()->post($this->processWireUrl() . '/', $data);
            return $response->body();
        } else if ($this->panel->affiliate_type === 'vcep' || $this->panel->affiliate_type === 'gcep') {
            $data = $this->getProcessWireData();
            $response = $this->HttpRequest()->post($this->processWireUrl().'/', $data);
            return $response->body();
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

        if ($type == 'gcep') {
            $panel->url_curations = 'https://search.clinicalgenome.org/kb/affiliate/' . $panel->affiliate_id;
        } else if ($type == 'vcep') {
            $base_url = "https://erepo.genome.network/evrepo/ui/classifications";
            $params = array(
                'matchMode' => 'exact',
                'expertpanel' =>  $panel->name . ' VCEP'
            );
            $panel->url_erepo = $base_url . '?' . http_build_query($params);

            if ($panel->group_clinvar_org_id && $panel->affiliate_type === 'vcep') {
                $panel->url_clinvar = 'https://www.ncbi.nlm.nih.gov/clinvar/submitters/' . $panel->group_clinvar_org_id;
            }
        }

        //map process wire fields
        $processWireFields = [
            'name' => $panel->affiliate_id,
            'title' => $panel->title,
            'title_short' => $panel->title_short,
            'title_abbreviated' => $panel->title_abbreviated,
            'summary' => $panel->description,
            'body_1' => $panel->summary,
            'type' => $panel->affiliate_type,
            'expert_panel_type' => $panel->affiliate_type === 'gcep' ? [1] : [2] ,
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
            'relate_user_curators' => $panel->getMembersByType(Member::CURATOR),
            'relate_user_committee' => $panel->getMembersByType(Member::COMMITTEE),
            'relate_user_members' => $panel->getMembersByType([Member::MEMBER, 'expert']),
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
                //$processWireFields['url_clinvar'] = 'https://www.ncbi.nlm.nih.gov/clinvar/submitters/' . $panel->group_clinvar_org_id;
            }

        }

        return $processWireFields;
    }


    private function cdwgData()
    {
        $panel = $this->panel;
        return [
            'name' => $panel->title . ' CDWG',
            'title' => $panel->title . ' CDWG',
            'title_short' => $panel->title_short,
            'title_abbreviated' => $panel->title_abbreviated,
            'summary' => $panel->description,
            'body_1' => $panel->summary,
            'type' => $panel->affiliate_type,
            'images_1' => [],
            'relate_user_leaderships' => $panel->getMembersByType(Member::LEADER),
            'relate_user_coordinators' => $panel->getMembersByType(Member::COORDINATOR),
            //'relate_user_experts' => $panel->getMembersByType('expert'),
            'relate_user_curators' => $panel->getMembersByType(Member::CURATOR),
            'relate_user_committee' => $panel->getMembersByType(Member::COMMITTEE),
            'relate_user_members' => $panel->getMembersByType([Member::MEMBER, 'expert']),
            //'relate_user_members_past' => $panel->getMembersByType(Member::PAST_MEMBER),
            'metadata_search_terms' => $panel->metadata_search_terms,
            'gpm_id' => $panel->gpm_id
        ];
    }

    private function wgData()
    {
        $panel = $this->panel;
        return [
            'name' => $panel->title,
            'title' => $panel->title,
            'title_short' => $panel->title_short,
            'summary' => $panel->description,
            'body_1' => $panel->summary,
            'images_icon_url' => $panel->icon_url,
            'images_1' => [],
            'relate_user_leaderships' => $panel->getMembersByType(Member::LEADER),
            'relate_user_coordinators' => $panel->getMembersByType(Member::COORDINATOR),
            'relate_user_experts' => $panel->getMembersByType('expert'),
            'relate_user_curators' => $panel->getMembersByType(Member::CURATOR),
            'relate_user_committee' => $panel->getMembersByType(Member::COMMITTEE),
            'relate_user_members' => $panel->getMembersByType([Member::MEMBER, 'expert']),
            //'relate_user_members_past' => $panel->getMembersByType(Member::PAST_MEMBER),
            'metadata_search_terms' => $panel->metadata_search_terms,
            'gpm_id' => $panel->gpm_id
        ];
    }
}
