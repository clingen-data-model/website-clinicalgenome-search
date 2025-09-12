<?php

namespace App\Services;
use App\Member;
use App\Panel;
use Carbon\Carbon;

class PanelImportService
{
    public function create($data)
    {
        //we are only creating expert panels here ...
        $panel = null;
        if ($expertPanel = data_get($data, 'data.expert_panel')) {
            if (data_get($expertPanel, 'affiliation_id')) {
                $panel = $this->findOrCreatePanel($expertPanel);
                $this->createActivities($panel, $expertPanel);
            }
        } else {
            //This is a working group
            $panel = $this->findOrCreateWorkingGroup($data['data']);
        }

        if ($panel) {
            $this->assignMembers($panel, $data['data']);

            if ($parent = data_get($data, 'data.parent')) {
                $this->assignParent($panel, $parent);
            }
        }


        return $panel;
    }

    public function findOrCreatePanel($expertPanel)
    {
        if ($affiliateId = data_get($expertPanel, 'affiliation_id')) {
            $panel = Panel::firstOrNew([
                'gpm_id' => $expertPanel['uuid'],
                'affiliate_id' => $expertPanel['affiliation_id']
            ]);

            $type = data_get($expertPanel, 'type');

            $panel->affiliate_type = $type;
            $panel->name = data_get($expertPanel, 'name');
            $panel->title_short = data_get($expertPanel, 'short_name');
            $panel->title = data_get($expertPanel, 'name');
            $panel->summary = data_get($expertPanel, 'scope_description');

            if ($inactiveDate = data_get($expertPanel, 'inactive_date')) {
                $panel->inactive_date = Carbon::parse($inactiveDate)->format('Y-m-d H:i:s');
                $panel->is_inactive = false;
            }

            $panel->save();

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
        if ($ep_definition_approved = data_get($data, 'vcep_define_group')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_definition_approved'
            ]);

            $activity->activity_date = Carbon::parse($ep_definition_approved);
            $activity->save();

        }

        if ($vcep_classification_rules = data_get($data, 'vcep_classification_rules')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'vcep_draft_specifications_approved'
            ]);

            $activity->activity_date = Carbon::parse($vcep_classification_rules);
            $activity->save();
        }

        if ($vcep_pilot_rules = data_get($data, 'vcep_pilot_rules')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'vcep_pilot_approved'
            ]);

            $activity->activity_date = Carbon::parse($vcep_pilot_rules);
            $activity->save();
        }

        if ($vcep_approval = data_get($data, 'vcep_approval')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_final_approval'
            ]);

            $activity->activity_date = Carbon::parse($vcep_approval);
            $activity->save();
        }

    }

    protected function createGCEPActivities(Panel $panel, $data)
    {
        if ($gcepDefineGroup = data_get($data, 'gcep_define_group')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_definition_approved'
            ]);

            $activity->activity_date = Carbon::parse($gcepDefineGroup);
            $activity->save();

        }

        if ($gcepApproval = data_get($data, 'gcep_approval')) {
            $activity = $panel->activities()->firstOrNew([
                'activity' => 'ep_final_approval'
            ]);

            $activity->activity_date = Carbon::parse($gcepApproval);
            $activity->save();
        }
    }

    public function assignMembers(Panel $panel, $data)
    {
        if ($members = data_get($data, 'members')) {
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
