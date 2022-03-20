<table>
    <thead>
    <tr>
        <th colspan="4" align="center">{{ $assign->grade->name." - ".$assign->subject->name." - ".$assign->teacher->name }}</th>
    </tr>
    <tr>
        <th align="center">Class Room</th>
        <th align="center">Day</th>
        <th align="center">Lesson</th>
        <th align="center">Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($assign->schedules as $schedule)
        <tr align="center">
            <td align="center">{{ $schedule->classRoom->name }}</td>
            <td align="center">{{ "Thứ " . ($schedule->day+1) }}</td>
            <td align="center">{{ $schedule->lesson->start." - ".$schedule->lesson->end }}</td>
            <td align="center">{{ $schedule->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td align="center" colspan="4">Start At: {{ $assign->start_at }}</td>
        </tr>
    </tfoot>
</table>
