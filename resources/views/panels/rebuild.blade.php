{{-- resources/views/panels/rebuild.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="col-12">
    <h1>Rebuild Panels from GPM</h1>
    <p class="text-muted">
        Applies the group's latest GPM checkpoint, then replays every general event since that checkpoint,
        then pushes the result to ProcessWire. <strong>Preview</strong> shows what would be applied without changing anything.
    </p>

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

    <p><strong>{{ $panels->count() }}</strong> panels and groups found in the panels table.</p>

    <div class="form-inline" style="margin-bottom:15px;">
        <input type="text" id="rebuild-filter" class="form-control" placeholder="Filter by name, affiliate ID or type" style="width:350px;">
    </div>

    <table class="table table-striped table-condensed" id="rebuild-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Affiliate ID</th>
            <th>Status</th>
            <th>Members</th>
            <th>Last updated</th>
            <th style="width:190px;">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($panels as $panel)
            <tr>
                <td><a href="{{ route('panels.show', $panel->id) }}">{{ $panel->name }}</a></td>
                <td>{{ strtoupper($panel->affiliate_type) }}</td>
                <td>{{ $panel->affiliate_id }}</td>
                <td>{{ $panel->wg_status }}</td>
                <td>{{ $panel->members_count }}</td>
                <td>{{ optional($panel->updated_at)->format('Y-m-d H:i') }}</td>
                <td>
                    @if ($panel->gpm_id)
                        <form action="{{ route('gpm-rebuild.preview', $panel->id) }}" method="POST" style="display:inline;"
                              onsubmit="this.querySelector('button').disabled = true;">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-default">Preview</button>
                        </form>
                        <form action="{{ route('gpm-rebuild.rebuild', $panel->id) }}" method="POST" style="display:inline;"
                              onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerText = 'Rebuilding...';">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-primary">Rebuild</button>
                        </form>
                    @else
                        <span class="text-muted">No GPM uuid</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No panels or groups found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('script_js')
<script>
    document.getElementById('rebuild-filter').addEventListener('input', function () {
        var term = this.value.toLowerCase();
        document.querySelectorAll('#rebuild-table tbody tr').forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().indexOf(term) === -1 ? 'none' : '';
        });
    });
</script>
@endsection
