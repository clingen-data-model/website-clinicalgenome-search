<?php

namespace App\Services;
use App\Member;
use App\Panel;
use Carbon\Carbon;

class PanelImportService
{

 public function create($data, $members = [])
{
    $panel = null;
    $expertPanel = data_get($data, 'expert_panel');

    if ($expertPanel) {
        if (!data_get($expertPanel, 'affiliation_id')) {
            return null;
        }

        $panel = $this->findOrCreatePanel($data);
        $this->createActivities($panel, $data);
    } else {
        $panel = $this->findOrCreateWorkingGroup($data);
    }

    if ($panel) {
        $this->assignMembers($panel, $members);

        $parent = data_get($data, 'data.parent');
        if (!is_null($parent)) {
            $this->assignParent($panel, $parent);
        }
    }

    return $panel;
}
    public function update($data, $event = null)
    {
        if ($gpmId = data_get($data, 'group.id')) {
            $panel = Panel::where('gpm_id', $gpmId)->first();
            if (null === $panel) {
                $panel = new Panel();
                if ($affiliationId = data_get($data, 'group.id')) {
                    $panel->affiliation_id = $affiliationId;
                    $panel->gpm_id = $gpmId;
                    $panel->save();
                }
            }
        }
    }

    public function updateEvent($data, $event) {

    }

    public function findOrCreatePanel($data)
    {
        $expertPanel = data_get($data, 'expert_panel');
        if ($affiliateId = data_get($expertPanel, 'affiliation_id')) {
            $panel = Panel::firstOrNew([
                //'gpm_id' => $expertPanel['uuid'],
                'affiliate_id' => $expertPanel['affiliation_id']
            ]);

            $type = data_get($expertPanel, 'type');

            $titleSuffix = '';

            if ($type == 'gcep') {
                $titleSuffix = ' Gene Curation Expert Panel';
                $panel->url_curations = 'https://search.clinicalgenome.org/kb/affiliate/' . $affiliateId;
            } else if ($type == 'vcep') {
                $titleSuffix = ' Variant Curation Expert Panel';
                $base_url = "https://erepo.genome.network/evrepo/ui/classifications";
                $params = array(
                   'matchMode' => 'exact',
                   'expertpanel' =>  data_get($expertPanel, 'name')
                );
                $panel->url_erepo = $base_url . '?' . http_build_query($params);
            }

            $panel->affiliate_type = $type;
            $panel->name = data_get($expertPanel, 'name');
            $panel->title_short = data_get($expertPanel, 'short_name') ?? ' ';
            $panel->title = data_get($expertPanel, 'name') . $titleSuffix;
            $panel->summary = data_get($data, 'description');
            $panel->url_cspec = 'https://cspec.genome.network/cspec/ui/svi/affiliation/' . $affiliateId;
            $panel->group_clinvar_org_id = data_get($expertPanel, 'clinvar_org_id');
            $panel->gpm_id = $expertPanel['uuid'];

            if ($inactiveDate = data_get($expertPanel, 'inactive_date')) {
                $panel->inactive_date = Carbon::parse($inactiveDate)->format('Y-m-d');
                $panel->is_inactive = true;
            }

            if ($iconUrl = data_get($data, 'icon_url')) {
                $panel->icon_url = $iconUrl;
            }

            if ($caption = data_get($data, 'caption')) {
                $panel->caption = $caption;
            }

            //parent_id
            if ($parent = data_get($data, 'parent')) {
                $parentPanel = Panel::firstOrNew([
                    'gpm_id' => $parent['uuid']
                ]);

                if ($parentPanel->exists) {
                    $panel->parent_id = $parentPanel->id;
                } else {
                    //create a new one
                    $parentPanel->name = $parent['name'];
                    $parentPanel->affiliate_type = $parent['type'];
                    $parentPanel->save();

                    $panel->parent_id = $parentPanel->id;
                }
            }

            //if (isset($panel->group_clinvar_org_id)) {}

            $panel->save();

            dd($panel);

            return $panel;
        }

        //panel will
        return new Panel();
    }

    public function findOrCreateWorkingGroup($data)
    {
        $panel = Panel::firstOrNew([
            'gpm_id' => $data['uuid']
        ]);

        $panel->wg_status = $data['status'];
        $panel->name = $data['name'];
        $panel->title = $data['name'];
        $panel->summary = $data['description'];
        $panel->affiliate_type = $data['type'];
        $panel->icon_url = data_get($data, 'icon_url');
        $panel->caption = data_get($data, 'caption');
        $panel->description = data_get($data, 'description');

        $panel->save();

        return $panel;
    }

    public function createMeta($panel, $data)
    {
        //if ($)
    }

    protected function assignParent($panel, $data) {
        if ($gpmId = data_get($data, 'uuid')) {
            $parentPanel = Panel::firstOrNew([
                'gpm_id' => $gpmId
            ]);

            $parentPanel->name = $data['name'];
            $parentPanel->affiliate_type = $data['type'];
            $parentPanel->save();

            $panel->parent_id = $parentPanel->id;
            $panel->save();
        }
    }

    protected function createActivities(Panel $panel, $data)
    {
        if ($type = data_get($data, 'type')) {
            if ($type == 'vcep') {
                $this->createVCEPActivities($panel, $data);
            } else if ($type == 'gcep') {
                $this->createGCEPActivities($panel, $data);
            }
        }
    }

    protected function createVCEPActivities(Panel $panel, $data)
    {
        if ($ep_definition_approved = data_get($data, 'expert_panel.vcep_definition_approval')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_definition_approved'
            ]);

            $activity->activity_date = Carbon::parse($ep_definition_approved);
            $activity->save();

        }

        if ($vcep_classification_rules = data_get($data, 'expert_panel.vcep_draft_specification_approval')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'vcep_draft_specifications_approved'
            ]);

            $activity->activity_date = Carbon::parse($vcep_classification_rules);
            $activity->save();
        }

        if ($vcep_pilot_rules = data_get($data, 'expert_panel.vcep_pilot_approval')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'vcep_pilot_approved'
            ]);

            $activity->activity_date = Carbon::parse($vcep_pilot_rules);
            $activity->save();
        }

        if ($vcep_approval = data_get($data, 'expert_panel.vcep_final_approval')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_final_approval'
            ]);

            $activity->activity_date = Carbon::parse($vcep_approval);
            $activity->save();
        }

    }

    protected function createGCEPActivities(Panel $panel, $data)
    {
        if ($gcepDefineGroup = data_get($data, 'status_date')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_definition_approved'
            ]);

            $activity->activity_date = Carbon::parse($gcepDefineGroup);
            $activity->save();

        }

        if ($gcepApproval = data_get($data, 'expert_panel.gcep_final_approval')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_final_approval'
            ]);

            $activity->activity_date = Carbon::parse($gcepApproval);
            $activity->save();
        }
    }

    public function assignMembers(Panel $panel, $members = null)
    {
        if (null !== $members && count($members)) {
            foreach ($members as $member) {
                $memberObj = Member::firstOrNew([
                    'gpm_id' => data_get($member, 'uuid')
                ]);

                if ($name = data_get($member, 'name')) {
                    $names = explode(' ', $name, 2);
                    $memberObj->first_name = $names[0];
                    if (isset($names[1])) {
                        $memberObj->last_name = $names[1];
                    }
                }

                if ($firstName = data_get($member, 'first_name')) {
                    $memberObj->first_name = $firstName;
                }

                 if ($email = data_get($member, 'email')) {
                    $memberObj->email = $email;
                }

                if ($lastName = data_get($member, 'last_name')) {
                    $memberObj->last_name = $lastName;
                }

                if ($credentials = data_get($member, 'credentials')) {
                    $memberObj->credentials = is_array($credentials) ? implode(', ', $credentials) : $credentials;
                }

                if ($institution = data_get($member, 'institution')) {
                    $memberObj->institution = is_array($credentials) ? json_encode($institution) : json_encode([$credentials]);
                }

                //$memberObj->email = $member['email'];

                $memberObj->profile_photo = data_get($member, 'profile_photo');
                $memberObj->save();

                $role = [
                    'role' => $memberObj->panelPosition($member['roles']),
                    'group_roles' => json_encode($member['roles'])
                ];

                $panel->members()->syncWithoutDetaching([$memberObj->id => $role]);

                //$member->
            }
        }
    }
}
