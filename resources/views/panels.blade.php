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
        <strong>Rebuild</strong> applies the group's latest GPM checkpoint, replays every general event since that
        checkpoint, then pushes the result to ProcessWire. <strong>Preview</strong> shows what would be applied without
        changing anything.
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
                {{ $result['dry_run'] ? 'Preview' : 'Rebuild' }} of {{ $result['name'] }}:
                {{ $result['ok'] ? ($result['dry_run'] ? 'nothing was changed' : 'done') : 'failed' }}
            </strong>
            <pre style="margin-top:10px; max-height:400px; overflow:auto; white-space:pre;">{{ $result['output'] }}</pre>

            @if ($result['ok'] && $result['dry_run'])
                <form action="{{ route('gpm-rebuild.rebuild', $result['panel_id']) }}" method="POST"
                      onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerText = 'Rebuilding...';">
                    @csrf
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
            <th>Last updated</th>
            <th style="width:230px;">Actions</th>
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
                <td>{{ optional($panel->updated_at)->format('Y-m-d H:i') }}</td>
                <td>
                    @if ($panel->gpm_id)
                        <form action="{{ route('gpm-rebuild.preview', $panel->id) }}" method="POST" style="display:inline;"
                              onsubmit="this.querySelector('button').disabled = true;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">Preview</button>
                        </form>
                        <form action="{{ route('gpm-rebuild.rebuild', $panel->id) }}" method="POST" style="display:inline;"
                              onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerText = 'Rebuilding...';">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Rebuild</button>
                        </form>
                    @else
                        <span class="text-muted">No GPM uuid</span>
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
