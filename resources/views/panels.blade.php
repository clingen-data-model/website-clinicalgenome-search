{{-- resources/views/panels.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="toolbar">
            <h1>Panels</h1>

            <form action="{{ route('panels.syncGpm') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Sync with GPM
                </button>
            </form>
        </div>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <h1>Expert Panels</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Affiliate ID</th>
            <th>Summary</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($panels as $panel)
            <tr>
                <td>
                    <a href="{{ route('panels.show', $panel->id) }}">
                        {{ $panel->title }}
                    </a>
                </td>
                <td>{{ $panel->affiliate_id }}</td>
                <td>{{ \Illuminate\Support\Str::limit($panel->summary, 120) }}</td>
                <td>{{ $panel->affiliate_type }}</td>
                <td>
                    <form action="{{ route('panels.sync', $panel->affiliate_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            Sync with GPM
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
