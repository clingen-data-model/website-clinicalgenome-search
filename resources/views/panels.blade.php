{{-- resources/views/panels.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panels</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: .5rem .75rem;
        }
        th {
            background: #f3f3f3;
            text-align: left;
        }
        .btn {
            display: inline-block;
            padding: .5rem 1rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
        }
        .alert {
            margin-top: 1rem;
            padding: .5rem .75rem;
            border-radius: 4px;
            background: #ecfdf5;
            color: #166534;
        }
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
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
            <div class="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($panels->count())
            <table>
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
                            <td>{{ $panel->summary }}</td>
                            <td>{{ $panel->type }}</td>
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
        @else
            <p>No panels found.</p>
        @endif
    </div>
</body>
</html>
