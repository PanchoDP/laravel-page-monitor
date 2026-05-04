<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Monitor</title>
    <link rel="stylesheet" href="{{ asset('vendor/laravel-page-monitor/css/page-monitor.css') }}">
</head>
<body>
<div class="container">
    <header>
        <span class="dot"></span>
        <h1>Page Monitor</h1>
        <form method="POST" action="{{ route('page-monitor.clear') }}" style="margin-left: auto;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-clear" onclick="return confirm('Clear all page visit records?')">Clear</button>
        </form>
    </header>

    @if($visits->isEmpty())
        <p class="empty">No page visits recorded yet.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Page</th>
                    <th>User</th>
                    <th>Device</th>
                    <th>IP Address</th>
                    <th>Visited At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visits as $visit)
                    <tr>
                        <td>{{ $visit->page }}</td>
                        <td>{{ $visit->relationLoaded('user') ? ($visit->user?->name ?? 'Guest') : '—' }}</td>
                        <td>{{ $visit->device_type }}</td>
                        <td>{{ $visit->ip_address }}</td>
                        <td class="time">{{ $visit->visited_at->format('d M Y, H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>