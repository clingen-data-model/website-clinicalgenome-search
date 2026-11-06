<?php

namespace App\Services;

use App\Panel;
use App\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
     * Handle a single gpm-general-events message.
     *
     * Every general event -- whatever its event_type -- carries the FULL group
     * (data.group, with expert_panel and parent nested inside). So each event is
     * processed in two passes:
     *
     *   1. Snapshot: refresh every panel field, approval activity and parent link
     *      from data.group via PanelImportService::create(). Members are NOT
     *      passed, so the roster is left alone -- only a checkpoint carries the
     *      authoritative full roster.
     *   2. Delta: apply the event-specific payload (new_status, members, ...) on
     *      top. The snapshot can predate the change it announces (e.g. a
     *      group_status_updated whose data.group.status is still the OLD status),
     *      so the delta must run after, and win over, the snapshot.
     */
    public function syncFromKafka(array $data, ?string $timestamp = null): ?Panel
    {
        $schema = data_get($data, 'schema_version');
        $eventType = (string) data_get($data, 'event_type');

        // Only handle the 2.0.x schema family (2.0.0, 2.0.1, 2.0.2, ...).
        // Pinning to a single exact patch version silently drops events when
        // the producer bumps the patch, so match on the major.minor prefix.
        if (!is_string($schema) || strpos($schema, '2.0.') !== 0) {
            return null;
        }

        // funding_* events (funding_source_created, funding_award_created, ...)
        // are not about a group at all -- their data payload is a funding source.
        if (strpos($eventType, 'funding_') === 0) {
            return null;
        }

        if ($eventType === 'group_checkpoint_event') {
            $groupData = data_get($data, 'data');
            $members = data_get($data, 'data.members');

            // A checkpoint is a FULL SNAPSHOT of the group. No members key
            // means the group has no members, not "no information" -- so
            // coerce to an empty array, which assignMembers() treats as an
            // authoritative empty roster and detaches everyone.
            if (!is_array($members)) {
                $members = array();
            }

            if (!is_array($groupData) || !data_get($groupData, 'uuid')) {
                return null;
            }

            return $this->panelImportService->create($groupData, $members);
        }

        $groupData = data_get($data, 'data.group');

        // Without a group there is nothing to sync.
        if (!is_array($groupData) || !data_get($groupData, 'uuid')) {
            return null;
        }

        // Pass 1: full snapshot of every field. null members = leave the roster
        // alone. (Passing data.members here used to sync() a member_added
        // event's single member as the whole roster, detaching everyone else.)
        $panel = $this->panelImportService->create($groupData, null);

        if (!$panel || !$panel->exists) {
            return null;
        }

        // Pass 2: event-specific delta.
        switch ($eventType) {
            case 'group_status_updated':
                $this->applyGroupStatusUpdated($panel, $data);
                break;

            case 'scope_description_updated':
                $this->applyScopeDescriptionUpdated($panel, $data);
                break;

            case 'group_description_updated':
                $this->applyGroupDescriptionUpdated($panel, $data);
                break;

            case 'group_name_updated':
                $this->applyGroupNameUpdated($panel, $data);
                break;

            case 'group_visibility_updated':
                $this->applyGroupVisibilityUpdated($panel, $data);
                break;

            case 'website_excerpt_updated':
                $this->applyWebsiteExtractedUpdated($panel, $data);
                break;

            case 'parent_updated':
                $this->handleParentUpdated($panel, $data);
                break;

            case 'ep_info_updated':
                $this->applyEpInfoUpdated($panel, $data);
                break;

            // Approval dates are also in the snapshot (expert_panel.*_approval);
            // the event's own date is applied in case the snapshot lags.
            case 'vcep_definition_approval':
            case 'scvcep_definition_approval':
                $this->recordActivity($panel, 'ep_definition_approved', $this->eventDate($data));
                break;

            case 'vcep_draft_specification_approval':
            case 'scvcep_draft_specification_approval':
                $this->recordActivity($panel, 'vcep_draft_specifications_approved', $this->eventDate($data));
                break;

            case 'vcep_pilot_approval':
            case 'scvcep_pilot_approval':
                $this->recordActivity($panel, 'vcep_pilot_approved', $this->eventDate($data));
                break;

            case 'vcep_final_approval':
            case 'scvcep_final_approval':
            case 'gcep_final_approval':
                $this->recordActivity($panel, 'ep_final_approval', $this->eventDate($data));
                break;

            case 'sustained_curation_review_completed':
                $this->recordActivity($panel, 'sustained_curation_review_completed', $this->eventDate($data));
                break;

            // ---- membership ----
            // data.members on a general event is ONLY the member(s) the event is
            // about -- never the full roster. So every handler below acts on the
            // listed members alone and must never sync()/detach anyone not in
            // the list. (Only group_checkpoint_event carries the full roster.)
            //
            // For each listed member, member.roles is that member's full CURRENT
            // role set (on member_role_removed the removed role only appears in
            // data.roles), so their pivot is replaced with exactly those roles.
            case 'member_added':
            case 'member_unretired':
            case 'member_role_assigned':
            case 'member_role_removed':
                $this->handleMemberAddedOrRoleAssigned($panel, $data);
                break;

            case 'member_removed':
            case 'member_retired':
                $this->handleMemberRemoved($panel, $data);
                break;

            case 'member_permission_granted':
            case 'member_updated':
                $this->handleMemberUpdated($panel, $data);
                break;

            default:
                // Everything else (coi_completed, membership_description_updated,
                // step_date_approved_updated, ...) is fully covered by the snapshot.
                break;
        }

        return $panel;
    }

    /**
     * Pull a scalar out of a GPM "new_*" value, which may be a plain string or
     * an {id, name} object (e.g. new_status).
     */
    protected function scalarValue($value, string $key = 'name')
    {
        if (is_array($value)) {
            return data_get($value, $key);
        }

        return $value;
    }

    /**
     * The date an approval event refers to. GPM puts it in data.date_approved;
     * fall back to the envelope date.
     */
    protected function eventDate(array $data): ?string
    {
        return data_get($data, 'data.date_approved')
            ?? data_get($data, 'data.date')
            ?? data_get($data, 'date');
    }

    /**
     * Group status updated. data.new_status is {id, name}; the snapshot's
     * group.status can still hold the OLD status, so this overrides it.
     */
    protected function applyGroupStatusUpdated(Panel $panel, array $data): void
    {
        $newStatus = $this->scalarValue(data_get($data, 'data.new_status'));

        if ($newStatus) {
            $panel->wg_status = $newStatus;
            $panel->is_inactive = PanelImportService::isInactiveStatus($newStatus)
                || !empty($panel->inactive_date);
            $panel->save();
        }
    }

    protected function applyWebsiteExtractedUpdated(Panel $panel, array $data): void
    {
        $newExcerpt = $this->scalarValue(data_get($data, 'data.new_excerpt'));

        if ($newExcerpt) {
            $panel->summary = $newExcerpt;
            $panel->save();
        }
    }

    /**
     * Scope description updated.
     */
    protected function applyScopeDescriptionUpdated(Panel $panel, array $data): void
    {
        $scope = $this->scalarValue(data_get($data, 'data.new_scope_description'))
            ?? $this->scalarValue(data_get($data, 'data.scope_description'));

        if ($scope) {
            $panel->description = $scope;
            $panel->save();
        }
    }

    /**
     * Group description updated. Mirrors where the snapshot stores the group
     * description: description for working groups, summary for expert panels.
     */
    protected function applyGroupDescriptionUpdated(Panel $panel, array $data): void
    {
        if ($newDescription = $this->scalarValue(data_get($data, 'data.new_description'))) {
            if (data_get($data, 'data.group.expert_panel')) {
                $panel->summary = $newDescription;
            } else {
                $panel->description = $newDescription;
            }
            $panel->save();
        }
    }

    /**
     * Group visibility updated – a private group must not surface on the site.
     * Downstream: is_private is exported to ProcessWire, which unpublishes/hides
     * the page (and re-publishes it when the group goes public again).
     */
    protected function applyGroupVisibilityUpdated(Panel $panel, array $data): void
    {
        $newVisibility = $this->scalarValue(data_get($data, 'data.new_visibility'));

        if ($newVisibility) {
            $panel->is_private = $newVisibility !== 'public';
            $panel->save();
        }
    }

    /**
     * Group name updated.
     */
    protected function applyGroupNameUpdated(Panel $panel, array $data): void
    {
        if ($newName = $this->scalarValue(data_get($data, 'data.new_name'))) {
            $panel->name = $newName;
            $panel->title = $newName;
            $panel->save();
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

        if ($clinvarId = data_get($data, 'data.expert_panel.clinvar_org_id')) {
            $panel->group_clinvar_org_id = $clinvarId;
        }

        if ($panel->isDirty()) {
            $panel->save();
        }
    }

    /**
     * Parent updated – create/link parent panel.
     */
    protected function handleParentUpdated(Panel $panel, array $data): void
    {
        $parentData = data_get($data, 'data.new_parent');

        if (!$parentData || !($gpmId = data_get($parentData, 'uuid'))) {
            return;
        }

        $parentPanel = Panel::firstOrNew([
            'gpm_id' => $gpmId
        ]);

        if (!$parentPanel->exists) {
            $parentPanel->name = data_get($parentData, 'name');
            $parentPanel->affiliate_type = data_get($parentData, 'type');
            $parentPanel->wg_status = data_get($parentData, 'status');
            $parentPanel->save();
        }

        $panel->parent_id = $parentPanel->id;
        $panel->save();
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
     * Member added / unretired / role assigned / role removed – set the
     * member's pivot to their current role set (member.roles).
     */
    protected function handleMemberAddedOrRoleAssigned(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        if (!is_array($members)) {
            return;
        }

        foreach ($members as $member) {
            // Members holding an excluded role (e.g. NIH Extramural Rep) never
            // appear on the website: don't create/update a Member record for
            // them, just detach in case an earlier event attached them.
            if ($this->panelImportService->hasExcludedRole($member)) {
                if ($gpmId = data_get($member, 'uuid')) {
                    $existing = Member::where('gpm_id', $gpmId)->first();
                    if ($existing) {
                        $panel->members()->detach($existing->id);
                    }
                }
                continue;
            }

            $memberObj = $this->validateMemberFromKafka($member);

            if (!$memberObj) {
                continue;
            }

            // member.roles is the member's FULL current role set in this group.
            // Any role not in it -- whatever was stored before -- must go, so
            // the existing pivot row(s) are dropped and one fresh row written
            // with exactly these roles. Detaching first (rather than
            // syncWithoutDetaching) also collapses legacy duplicate rows:
            // member_panel has no unique (panel_id, member_id) index and older
            // code attach()ed per role.
            $roles = data_get($member, 'roles', []);
            if (!is_array($roles)) {
                $roles = [];
            }
            $roles = array_values(array_unique($roles));

            DB::transaction(function () use ($panel, $memberObj, $roles) {
                $panel->members()->detach($memberObj->id);
                $panel->members()->attach($memberObj->id, [
                    'role' => $memberObj->panelPosition($roles),
                    'group_roles' => json_encode($roles),
                ]);
            });
        }
    }

    /**
     * Member removed / retired – detach entirely.
     */
    protected function handleMemberRemoved(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        if (!is_array($members)) {
            return;
        }

        foreach ($members as $member) {
            $memberObj = $this->validateMemberFromKafka($member);

            if ($memberObj) {
                $panel->members()->detach($memberObj->id);
            }
        }
    }

    /**
     * Member updated / permission granted – refresh basic profile fields.
     */
    protected function handleMemberUpdated(Panel $panel, array $data): void
    {
        $members = data_get($data, 'data.members', []);

        if (!is_array($members)) {
            return;
        }

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

        if (!$gpmId) {
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
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
