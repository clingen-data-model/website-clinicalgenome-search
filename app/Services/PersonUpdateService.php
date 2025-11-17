<?php

namespace App\Services;

use App\Member;
use Carbon\Carbon;

class PersonUpdateService
{
    /**
     * Handle a single Kafka event for person updates.
     *
     * @param  array $data  The decoded Kafka payload
     * @return \App\Member|null
     */
    public function syncFromKafka(array $data)
    {
        $schema    = data_get($data, 'schema_version');
        $eventType = data_get($data, 'event_type');

        // Only process schema 2.0.0
        if ($schema !== '2.0.0') {
            return null;
        }

        // Only process person_created / person_updated
        if (!in_array($eventType, ['person_created', 'person_updated'], true)) {
            return null;
        }

        $person = data_get($data, 'data.person');
        if (!$person) {
            return null;
        }

        $personId = data_get($person, 'id');
        if (!$personId) {
            return null;
        }

        // person.id <=> member.gpm_id
        $member = Member::firstOrNew([
            'gpm_id' => $personId,
        ]);

        $this->applyPersonDataToMember($member, $person);

        $member->save();

        return $member;
    }

    /**
     * Map a person payload array onto a Member model instance.
     *
     * @param  \App\Member $member
     * @param  array       $person
     * @return void
     */
    protected function applyPersonDataToMember(Member $member, array $person)
    {
        // Core identity fields
        $firstName = data_get($person, 'first_name');
        $lastName  = data_get($person, 'last_name');

        if ($firstName !== null) {
            $member->first_name = $firstName;
        }

        if ($lastName !== null) {
            $member->last_name = $lastName;
        }

        if ($email = data_get($person, 'email')) {
            $member->email = $email;
        }

        // Display name (fallback to "First Last")
        if ($member->first_name || $member->last_name) {
            $member->display_name = trim($member->first_name . ' ' . $member->last_name);
        }

        // Institution (Member::casts['institution' => 'array'])
        // person.institution can be null or an object/array
        if (array_key_exists('institution', $person)) {
            $institution = $person['institution'];
            // For casts, you can just assign the array/object directly
            $member->institution = $institution;
        }

        // Credentials:
        // - Sometimes an array of objects with "name"
        // - Sometimes an empty array
        // Member.credentials is a string, so we store a comma-separated list
        if (array_key_exists('credentials', $person)) {
            $credentials = $person['credentials'];

            if (is_array($credentials)) {
                $names = [];

                foreach ($credentials as $cred) {
                    // object-like array with "name"
                    if (is_array($cred) && isset($cred['name'])) {
                        $names[] = $cred['name'];
                    } elseif (is_string($cred)) {
                        $names[] = $cred;
                    }
                }

                $member->credentials = implode(', ', $names);
            } elseif (is_string($credentials)) {
                $member->credentials = $credentials;
            }
        }

        // Biography, profile photo, timezone
        if (array_key_exists('biography', $person)) {
            $member->biography = $person['biography'];
        }

        if (array_key_exists('profile_photo', $person)) {
            $member->profile_photo = $person['profile_photo'];
        }

        if (array_key_exists('timezone', $person)) {
            $member->timezone = $person['timezone'];
        }

        // ORCID / Hypothesis mapping
        // NOTE: person uses "orcid_id", model uses "orchid_id" (typo in model)
        if (array_key_exists('orcid_id', $person)) {
            $member->orchid_id = $person['orcid_id'];
        }

        if (array_key_exists('hypothesis_id', $person)) {
            $member->hypothesis_id = $person['hypothesis_id'];
        }

        // Ensure gpm_id always matches person.id
        if (array_key_exists('id', $person)) {
            $member->gpm_id = $person['id'];
        }

        // ident: if empty on creation, you can default to id or a slug
        if (empty($member->ident) && isset($person['id'])) {
            $member->ident = $person['id'];
        }
    }
}

