<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Itinerary Export</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
    </style>
</head>
<body>
    <h1>{{ $itinerary->title }}</h1>
    <p>{{ $itinerary->description }}</p>
    <p>Start: {{ $itinerary->start_date->format('M j, Y') }} | End: {{ $itinerary->end_date->format('M j, Y') }}</p>

    <h2>Activities</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Location</th>
                <th>Scheduled At</th>
                <th>Budget</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itinerary->activities as $activity)
                <tr>
                    <td>{{ $activity->title }}</td>
                    <td>{{ $activity->location }}</td>
                    <td>{{ optional($activity->scheduled_at)->format('M j, Y H:i') }}</td>
                    <td>{{ $activity->budget }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Group Members</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itinerary->groupMembers as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Place</th>
                <th>Location</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itinerary->bookings as $booking)
                <tr>
                    <td>{{ $booking->place }}</td>
                    <td>{{ $booking->location }}</td>
                    <td>{{ optional($booking->check_in)->format('M j, Y') }}</td>
                    <td>{{ optional($booking->check_out)->format('M j, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Budget Entries</h2>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Spent</th>
                <th>Date</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itinerary->budgetEntries as $entry)
                <tr>
                    <td>{{ $entry->description }}</td>
                    <td>{{ $entry->amount }}</td>
                    <td>{{ $entry->spent_amount }}</td>
                    <td>{{ $entry->entry_date->format('M j, Y') }}</td>
                    <td>{{ $entry->category }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
