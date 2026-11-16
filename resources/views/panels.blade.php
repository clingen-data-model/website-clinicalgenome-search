{{-- resources/views/panels.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="toolbar">
        <h1>Panels &amp; Groups</h1>

        <form action="{{ route('panels.syncGpm') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                Sync with GPM
            </button>
        </form>
    </div>

    <p class="text-muted">
        <strong>Rebuild</strong> re-reads <code>gpm-general-events</code> from Kafka starting at the offset you enter,
        and processes only that panel's / group's messages &mdash; every other group is ignored, and the live consumer
        position is not moved. <strong>Preview</strong> lists the matching messages without applying anything.
    </p>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('rebuild_error'))
        <div class="alert alert-danger">{{ session('rebuild_error') }}</div>
    @endif

    @if ($result = session('rebuild_result'))
        <div class="alert {{ $result['ok'] ? ($result['dry_run'] ? 'alert-info' : 'alert-success') : 'alert-danger' }}">
            <strong>
                {{ $result['dry_run'] ? 'Preview' : 'Rebuild' }} of {{ $result['name'] }} from offset {{ $result['from_offset'] }}:
                {{ $result['ok'] ? ($result['dry_run'] ? 'nothing was changed' : 'done') : 'failed' }}
            </strong>
            <pre style="margin-top:10px; max-height:400px; overflow:auto; white-space:pre;">{{ $result['output'] }}</pre>

            @if ($result['ok'] && $result['dry_run'])
                <form action="{{ route('gpm-rebuild.rebuild', $result['panel_id']) }}" method="POST"
                      onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerText = 'Rebuilding...';">
                    @csrf
                    <input type="hidden" name="from_offset" value="{{ $result['from_offset'] }}">
                    <button type="submit" class="btn btn-sm btn-danger">Rebuild {{ $result['name'] }} now</button>
                </form>
            @endif
        </div>
    @endif

    <p>
        <strong>{{ $panels->count() }}</strong> panels and groups.
        <input type="text" id="panel-filter" class="form-control d-inline-block ml-3" style="width:350px;"
               placeholder="Filter by name, affiliate ID, type or status">
    </p>

    <table class="table table-striped" id="panel-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Affiliate ID</th>
            <th>Status</th>
            <th>Members</th>
            <th>Last updated</th>
            <th style="width:330px;">Rebuild from offset</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($panels->sortBy(function ($p) { return [$p->affiliate_type, $p->name]; }) as $panel)
            <tr>
                <td>
                    <a href="{{ route('panels.show', $panel->id) }}">
                        {{ $panel->name ?: $panel->title }}
                    </a>
                </td>
                <td>{{ strtoupper((string) $panel->affiliate_type) }}</td>
                <td>{{ $panel->affiliate_id }}</td>
                <td>{{ $panel->wg_status }}</td>
                <td>{{ $panel->members_count }}</td>
                <td>{{ optional($panel->updated_at)->format('Y-m-d H:i') }}</td>
                <td>
                    @if ($panel->affiliate_id)
                        <form action="{{ route('gpm-rebuild.rebuild', $panel->id) }}" method="POST" class="form-inline"
                              onsubmit="this.querySelectorAll('button').forEach(function (b) { b.disabled = true; });">
                            @csrf
                            <input type="number" name="from_offset" min="0" required class="form-control form-control-sm mr-1"
                                   style="width:110px;" placeholder="offset"
                                   value="{{ data_get(session('rebuild_result'), 'panel_id') == $panel->id ? data_get(session('rebuild_result'), 'from_offset') : '' }}">
                            <button type="submit" formaction="{{ route('gpm-rebuild.preview', $panel->id) }}"
                                    class="btn btn-sm btn-secondary mr-1">Preview</button>
                            <button type="submit" class="btn btn-sm btn-primary">Rebuild</button>
                        </form>
                    @else
                        <span class="text-muted">No affiliation ID</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('script_js')
<script>
    (function () {
        var input = document.getElementById('panel-filter');
        if (!input) return;
        input.addEventListener('input', function () {
            var term = this.value.toLowerCase();
            document.querySelectorAll('#panel-table tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().indexOf(term) === -1 ? 'none' : '';
            });
        });
    })();
</script>
@endsection
