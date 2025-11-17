@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('panels.index') }}" class="btn btn-link mb-3">&larr; Back to Panels</a>

    <div class="card mb-4">
        <div class="card-header">
            <h2 class="mb-0">{{ $panel->title ?: $panel->name }}</h2>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Affiliate ID</dt>
                <dd class="col-sm-9">{{ $panel->affiliate_id }}</dd>

                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">{{ $panel->affiliate_type ?: 'N/A' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    @if(!empty($panel->wg_status))
                        {{ $panel->wg_status }}
                    @else
                        {{ $panel->is_inactive ? 'Inactive' : 'Active' }}
                    @endif
                </dd>

                @if(!empty($panel->summary))
                    <dt class="col-sm-3">Summary</dt>
                    <dd class="col-sm-9">{{ $panel->summary }}</dd>
                @endif

                @if(!empty($panel->coi_url))
                    <dt class="col-sm-3">COI</dt>
                    <dd class="col-sm-9">
                        <a href="{{ $panel->coi_url }}" target="_blank">{{ $panel->coi_url }}</a>
                    </dd>
                @endif

                @if(!empty($panel->url_cspec))
                    <dt class="col-sm-3">CSPEC URL</dt>
                    <dd class="col-sm-9">
                        <a href="{{ $panel->url_cspec }}" target="_blank">{{ $panel->url_cspec }}</a>
                    </dd>
                @endif

                @if(!empty($panel->url_clinvar))
                    <dt class="col-sm-3">ClinVar URL</dt>
                    <dd class="col-sm-9">
                        <a href="{{ $panel->url_clinvar }}" target="_blank">{{ $panel->url_clinvar }}</a>
                    </dd>
                @endif
            </dl>
        </div>
        <div class="card-footer">
            <form action="{{ route('panels.sync', $panel->affiliate_id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Sync with GPM
                </button>
            </form>
        </div>
    </div>

    {{-- Members --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">Members ({{ $panel->members->count() }})</h3>
        </div>
        <div class="card-body p-0">
            @if($panel->members->isEmpty())
                <p class="p-3 mb-0">No members found.</p>
            @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role (Pivot)</th>
                            <th>All Roles</th>
                            <th>Institution</th>
                            <th>Credentials</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($panel->members as $member)
                            <tr>
                                <td>
                                    {{ $member->first_name }} {{ $member->last_name }}
                                </td>
                                <td>
                                    {{ $member->pivot->role ?: 'Member' }}
                                </td>
                                <td>
                                    @php
                                        $roles = [];
                                        if (!empty($member->pivot->group_roles)) {
                                            $decoded = json_decode($member->pivot->group_roles, true);
                                            if (is_array($decoded)) {
                                                $roles = $decoded;
                                            }
                                        }
                                    @endphp
                                    {{ implode(', ', $roles) }}
                                </td>
                                <td>
                                    @if(is_array($member->institution))
                                        {{ implode(', ', $member->institution) }}
                                    @else
                                        {{ $member->institution }}
                                    @endif
                                </td>
                                <td>{{ $member->credentials }}</td>
                                <td>{{ $member->email }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Activities --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">Activities</h3>
        </div>
        <div class="card-body p-0">
            @if($panel->activities->isEmpty())
                <p class="p-3 mb-0">No activities recorded.</p>
            @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($panel->activities as $activity)
                            <tr>
                                <td>{{ $activity->activity }}</td>
                                <td>
                                    {{ optional($activity->activity_date)->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
