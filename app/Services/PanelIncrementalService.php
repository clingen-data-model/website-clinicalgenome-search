<?php

namespace App\Services;

use App\Panel;
use App\Member;
use Carbon\Carbon;

class PanelIncrementalService
{
    /**
     * @var PanelImportService
     */
    protected $panelImportService;

    public function __construct(PanelImportService $panelImportService)
    {
        $this->panelImportService = $panelImportService;
    }

    /**
     * Handle a single Kafka event for panels.
     */
    public function syncFromKafka(array $data, ?string $timestamp = null): ?Panel
    {
        $schema    = data_get($data, 'schema_version');
        $eventType = data_get($data, 'event_type');

        // Only handle schema 2.0.0
        if ($schema !== '2.0.1') {
            return null;
        }

        // Full snapshot import – delegate to original importer
        if ($eventType === 'group_checkpoint_event') {
            return $this->panelImportService->create($data);
        }

        $panel = $this->resolvePanelFromKafka($data);

        if (! $panel) {
            // No panel => nothing to update incrementally
            return null;
        }

        switch ($eventType) {
            // ---- meta / COI / status / descriptions / parent ----
            case 'coi_completed':
                $this->applyCoiCompleted($panel, $data);
                break;

            case 'group_status_updated':
                $this->applyGroupStatusUpdated($panel, $data);
                break;

            case 'membership_description_updated':
                $this->applyMembershipDescriptionUpdated($panel, $data);
                break;

            case 'scope_description_updated':
                $this->applyScopeDescriptionUpdated($panel, $data);
                break;

            case 'group_description_updated':
                $this->applyGroupDescriptionUpdated($panel, $data);
                break;

            case 'parent_updated':
                $this->handleParentUpdated($panel, $data);
                break;

            case 'ep_info_updated':
                $this->applyEpInfoUpdated($panel, $data);
                break;

            case 'ep_definition_approved':
                $this->recordActivity($panel, 'ep_definition_approved', data_get($data, 'date'));
                break;

            case 'ep_final_approval':
                $this->recordActivity($panel, 'ep_final_approval', data_get($data, 'date'));
                break;

            case 'vcep_draft_specifications_approved':
                $this->recordActivity($panel, 'vcep_draft_specifications_approved', data_get($data, 'date'));
                break;

            case 'vcep_pilot_approved':
                $this->recordActivity($panel, 'vcep_pilot_approved', data_get($data, 'date'));
                break;

            case 'sustained_curation_review_completed':
                $this->recordActivity($panel, 'sustained_curation_review_completed', data_get($data, 'date'));
                break;

            case 'step_date_approved_updated':
                $this->handleStepDateApprovedUpdated($panel, $data);
                break;

            // ---- membership / members ----
            case 'member_added':
            case 'member_unretired':
                $this->handleMemberAddedOrRoleAssigned($panel, $data);
                break;

            case 'member_role_assigned': // role added
                $this->handleMemberRoleAdded($panel, $data);
                break;

            case 'member_removed':
                $this->handleMemberRemoved($panel, $data);
                break;

            case 'member_retired':
                $this->handleMemberRetired($panel, $data);
                break;

            case 'member_role_removed':
                $this->handleMemberRoleRemoved($panel, $data);
                break;

            case 'member_permission_granted':
                $this->handleMemberPermissionGranted($panel, $data);
                break;

            case 'member_updated':
                $this->handleMemberUpdated($panel, $data);
                break;

            default:
                // unhandled event, ignore
                break;
        }

        return $panel;
    }

    /**
     * Resolve the Panel for this event.
     * uuid from Kafka always maps to Panel.gpm_id.
     */
    protected function resolvePanelFromKafka(array $data)
    {
        // Expert panel shape (gcep/vcep etc) under data.group.expert_panel or data.expert_panel
        if ($expertPanel = data_get($data, 'data.group.expert_panel') ?: data_get($data, 'data.expert_panel')) {
            $gpmId = data_get($expertPanel, 'uuid');
            if (! $gpmId) {
                return null;
            }

            $affiliateId = data_get($expertPanel, 'affiliation_id');

            if (! $affiliateId) {
                return null;
            }

            $panel = Panel::firstOrNew([
                'gpm_id' => $gpmId,
            ]);

            if (! $panel->exists) {
                $panel->affiliate_id    = data_get($expertPanel, 'affiliation_id');
                $panel->affiliate_type  = data_get($expertPanel, 'type');
                $panel->name            = data_get($expertPanel, 'name');
                $panel->title_short     = data_get($expertPanel, 'short_name');
                $panel->title           = data_get($expertPanel, 'name');
                $panel->summary         = data_get($expertPanel, 'scope_description');

                if ($inactiveDate = data_get($expertPanel, 'inactive_date')) {
                    $panel->inactive_date = Carbon::parse($inactiveDate)->format('Y-m-d H:i:s');
                    $panel->is_inactive   = true;
                }

                 if ($iconUrl = data_get($expertPanel, 'icon_url')) {
                    $panel->icon_url = $iconUrl;
                }

                if ($caption = data_get($expertPanel, 'caption')) {
                    $panel->caption = $caption;
                }

                $panel->save();

                if ($gcepDefineGroup = data_get($expertPanel, 'gcep_define_group')) {
                   $this->recordActivity($panel, 'ep_definition_approved', Carbon::parse($gcepDefineGroup)->format('Y-m-d H:i:s'));
                }

                if ($gcepApproval = data_get($expertPanel, 'gcep_approval')) {
                   $this->recordActivity($panel, 'ep_final_approval',  Carbon::parse($gcepApproval)->format('Y-m-d H:i:s'));
                }

                if ($vcepDefineGroup = data_get($expertPanel, 'vcep_define_group')) {
                   $this->recordActivity($panel, 'vcep_draft_specifications_approved', Carbon::parse($vcepDefineGroup)->format('Y-m-d H:i:s'));
                }

                if ($vcepClassify = data_get($expertPanel, 'vcep_classification_rules')) {
                   $this->recordActivity($panel, 'vcep_draft_specifications_approved', Carbon::parse($vcepClassify)->format('Y-m-d H:i:s'));
                }

                //WE'LL UPDATE THE PARENT HERE AS WELL
            }

            return $panel;
        }

        // Working group / generic group shape under data.group
        if ($group = data_get($data, 'data.group')) {
            $gpmId = data_get($group, 'uuid');
            if (! $gpmId) {
                return null;
            }

            $panel = Panel::firstOrNew([
                'gpm_id' => $gpmId,
            ]);

            if (! $panel->exists) {
                $panel->name           = data_get($group, 'name');
                $panel->title          = data_get($group, 'name');
                $panel->summary        = data_get($group, 'description');
                $panel->wg_status      = data_get($group, 'status');
                $panel->affiliate_type = data_get($group, 'type');
                $panel->coi_url        = data_get($group, 'coi');
                $panel->save();
            }

            return $panel;
        }

        return null;
    }

    /**
     * COI completed event.
     */
    protected function applyCoiCompleted(Panel $panel, array $data): void
    {
        $coi = data_get($data, 'data.group.coi')
            ?? data_get($data, 'data.expert_panel.coi')
            ?? data_get($data, 'data.coi');

        if ($coi) {
            $panel->coi_url = $coi;
        }

        if ($date = data_get($data, 'date')) {
            //$panel->coi_completed_at = $this->safeCarbon($date);
        }

        //$panel->save();
    }

    /**
     * Group status updated – keep wg_status and is_inactive in sync.
     */
    protected function applyGroupStatusUpdated(Panel $panel, array $data): void
    {
        $newStatus = data_get($data, 'data.new_status')
            ?? data_get($data, 'data.group.status');

        if ($newStatus) {
            $panel->wg_status   = $newStatus;
            $panel->is_inactive = $newStatus !== 'active';
        }

        $panel->save();
    }

    /**
     * Membership description updated.
     */
    protected function applyMembershipDescriptionUpdated(Panel $panel, array $data): void
    {
        $membershipDescription = data_get($data, 'data.membership_description')
            ?? data_get($data, 'data.group.membership_description')
            ?? data_get($data, 'data.expert_panel.membership_description');

        if ($membershipDescription) {
            $panel->membership_description = $membershipDescription;
            $panel->save();
        }
    }

    /**
     * Scope description updated – typically maps to summary.
     */
    protected function applyScopeDescriptionUpdated(Panel $panel, array $data): void
    {
        $scope = data_get($data, 'data.scope_description')
            ?? data_get($data, 'data.group.scope_description')
            ?? data_get($data, 'data.expert_panel.scope_description');

        if ($scope) {
            $panel->summary = $scope;
            $panel->save();
        }
    }

    /**
     * Group description updated (new_description field).
     */
    protected function applyGroupDescriptionUpdated(Panel $panel, array $data): void
    {
        if ($newDescription = data_get($data, 'data.new_description')) {
            $panel->summary = $newDescription;
            $panel->save();
            //Do we want to update the activities?
        }
    }

    /**
     * ep_info_updated – expert panel metadata changes.
     */
    protected function applyEpInfoUpdated(Panel $panel, array $data): void
    {
        if ($shortName = data_get($data, 'data.expert_panel.short_name')) {
            $panel->title_short = $shortName;
        }

        if ($longName = data_get($data, 'data.expert_panel.long_name')) {
            $panel->title = $longName;
        }

        if ($cspecUrl = data_get($data, 'data.expert_panel.cspec_url')) {
            $panel->url_cspec = $cspecUrl;
        }

        if ($clinvarUrl = data_get($data, 'data.expert_panel.clinvar_url')) {
            $panel->url_clinvar = $clinvarUrl;
        }

        if ($clinvarId = data_get($data, 'data.expert_panel.clinvar_id')) {
            $panel->group_clinvar_org_id = $clinvarId;
        }

        if ($scope = data_get($data, 'data.scope_description')) {
            $panel->summary = $scope;
        }

        if ($status = data_get($data, 'data.group.status')) {
            $panel->is_inactive = $status !== 'active';
        }

        $panel->save();
    }

    /**
     * Parent updated – create/link parent panel.
     */
    protected function handleParentUpdated(Panel $panel, array $data): void
    {
        $parentData = data_get($data, 'data.group.parent');

        if (! $parentData) {
            return;
        }

        if ($gpmId = data_get($parentData, 'uuid')) {
            $parentPanel = Panel::firstOrNew([
                'gpm_id' => $gpmId,
            ]);

            $parentPanel->name           = data_get($parentData, 'name', $parentPanel->name);
            $parentPanel->affiliate_type = data_get($parentData, 'type', $parentPanel->affiliate_type);
            $parentPanel->wg_status      = data_get($parentData, 'status', $parentPanel->wg_status);
            $parentPanel->save();

            $panel->parent_id = $parentPanel->id;
            $panel->save();
        }
    }

    /**
     * Generic activity recorder.
     */
    protected function recordActivity(Panel $panel, string $activityKey, ?string $date = null): void
    {
        $activity = $panel->activities()->firstOrNew([
            'activity' => $activityKey,
        ]);

        if ($date) {
            $activity->activity_date = $this->safeCarbon($date);
        }

        $activity->save();
    }

    /**
     * step_date_approved_updated – treat as step-specific activity.
     */
    protected function handleStepDateApprovedUpdated(Panel $panel, array $data): void
    {
        $stepName = data_get($data, 'data.step') ?? 'step_date_approved_updated';
        $date     = data_get($data, 'data.date') ?? data_get($data, 'date');

        $activityKey = 'step_' . $stepName . '_approved';

        $this->recordActivity($panel, $activityKey, $date);
    }

    /**
     * Member added / unretired – create membership from roles.
     */
    protected function handleMemberAddedOrRoleAssigned(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        foreach ($members as $member) {
            $memberObj = $this->validateMemberFromKafka($member);

            if (! $memberObj) {
                continue;
            }

            $roles = data_get($member, 'roles', []);
            // roles is guaranteed an array in schema 2.0.0

            $panel->members()->syncWithoutDetaching([
                $memberObj->id => [
                    'role'        => $memberObj->panelPosition($roles),
                    'group_roles' => json_encode($roles),
                ],
            ]);
        }
    }

    /**
     * Member role added / assigned – merge new roles into pivot.
     * Reverse of handleMemberRoleRemoved.
     */
    protected function handleMemberRoleAdded(Panel $panel, array $data): void
    {
        $members     = data_get($data, 'data.members', []);
        $globalRoles = data_get($data, 'data.roles'); // optional top-level roles

        foreach ($members as $member) {
            $memberObj = $this->validateMemberFromKafka($member);

            if (! $memberObj) {
                continue;
            }

            // Existing membership (if any)
            $existing = $panel->members()->find($memberObj->id);

            $currentRoles = [];
            if ($existing) {
                $currentRoles = json_decode($existing->pivot->group_roles ?? '[]', true);
                if (! is_array($currentRoles)) {
                    $currentRoles = [];
                }
            }

            // Roles to add – prefer member.roles, fallback to global data.roles
            $rolesToAdd = data_get($member, 'roles', $globalRoles ?? []);
            if (! is_array($rolesToAdd)) {
                $rolesToAdd = [$rolesToAdd];
            }

            $updatedRoles = array_values(array_unique(array_merge($currentRoles, $rolesToAdd)));

            if (empty($updatedRoles)) {
                continue;
            }

            $panel->members()->syncWithoutDetaching([
                $memberObj->id => [
                    'role'        => $memberObj->panelPosition($updatedRoles),
                    'group_roles' => json_encode($updatedRoles),
                ],
            ]);
        }
    }

    /**
     * Member removed – detach entirely.
     */
    protected function handleMemberRemoved(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        foreach ($members as $member) {
            $memberObj = $this->validateMemberFromKafka($member);

            if ($memberObj) {
                $panel->members()->detach($memberObj->id);
            }
        }
    }

    /**
     * Member retired – detach entirely.
     */
    protected function handleMemberRetired(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        foreach ($members as $member) {
            $memberObj = $this->validateMemberFromKafka($member);

            if ($memberObj) {
                $panel->members()->detach($memberObj->id);
            }
        }
    }

    /**
     * Member role removed – subtract roles from pivot, detach if none left.
     */
    protected function handleMemberRoleRemoved(Panel $panel, array $data): void
    {
        $members     = data_get($data, 'data.members', []);
        $globalRoles = data_get($data, 'data.roles'); // optional

        foreach ($members as $member) {
            $memberObj = $this->validateMemberFromKafka($member);

            if (! $memberObj) {
                continue;
            }

            $existing = $panel->members()->find($memberObj->id);

            if (! $existing) {
                continue;
            }

            $currentRoles = json_decode($existing->pivot->group_roles ?? '[]', true);
            if (! is_array($currentRoles)) {
                $currentRoles = [];
            }

            // Roles to remove – prefer member.roles, fallback to global data.roles
            $rolesToRemove = data_get($member, 'roles', $globalRoles ?? []);
            if (! is_array($rolesToRemove)) {
                $rolesToRemove = [$rolesToRemove];
            }

            $updatedRoles = array_values(array_diff($currentRoles, $rolesToRemove));

            if (empty($updatedRoles)) {
                // No roles left => detach membership
                $panel->members()->detach($memberObj->id);
                continue;
            }

            $panel->members()->syncWithoutDetaching([
                $memberObj->id => [
                    'role'        => $memberObj->panelPosition($updatedRoles),
                    'group_roles' => json_encode($updatedRoles),
                ],
            ]);
        }
    }

    /**
     * Member permission granted – ensure members exist.
     * (Extend later if you add permissions storage.)
     */
    protected function handleMemberPermissionGranted(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        foreach ($members as $member) {
            $this->validateMemberFromKafka($member);
        }
    }

    /**
     * Member updated – refresh basic profile fields.
     */
    protected function handleMemberUpdated(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        foreach ($members as $member) {
            $this->validateMemberFromKafka($member);
        }
    }

    /**
     * Create or update a Member based on Kafka payload.
     * uuid always maps to Member.gpm_id.
     */
    protected function validateMemberFromKafka(array $member): ?Member
    {
        $gpmId = data_get($member, 'uuid');

        if (! $gpmId) {
            return null;
        }

        $memberObj = Member::firstOrNew([
            'gpm_id' => $gpmId,
        ]);

        // Update basic fields if present
        if ($firstName = data_get($member, 'first_name')) {
            $memberObj->first_name = $firstName;
        }

        if ($lastName = data_get($member, 'last_name')) {
            $memberObj->last_name = $lastName;
        }

        if ($email = data_get($member, 'email')) {
            $memberObj->email = $email;
        }

        if ($credentials = data_get($member, 'credentials')) {
            $memberObj->credentials = is_array($credentials)
                ? implode(', ', $credentials)
                : $credentials;
        }

        if ($institution = data_get($member, 'institution')) {
            $memberObj->institution = is_array($institution)
                ? json_encode($institution)
                : json_encode([$institution]);
        }

        if ($photo = data_get($member, 'profile_photo')) {
            $memberObj->profile_photo = $photo;
        }

        $memberObj->save();

        return $memberObj;
    }

    /**
     * Safely parse a date string to Carbon or null.
     */
    protected function safeCarbon(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
