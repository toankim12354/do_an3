<div style="display: flex;">
    <form action="{{ route('admin.schedule.export.multiple') }}" method="POST">
        @csrf
        <div class="col-md-10 form-group">
            <select multiple class="input form-control" id="assigns" name="id_assigns[]">
                @foreach ($assigns as $assign)
                    @if(count($assign->schedules))
                        <option value="{{ $assign->id }}">
                            {{ $assign->grade->name . " - " . $assign->subject->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-md-1 form-group">
            <button class="btn btn-round btn-success" type="submit" id="exort_multiple">Export Custom</button>
        </div>
    </form>
</div>
<div>
    @foreach ($assigns as $assign)
        <div class="info">
            @if(count($assign->schedules))
                <a data-toggle="collapse" href="#assign{{ $assign->id }}" class="collapsed" style="width: 100%;">
                    <div style="display: flex; justify-content: space-between; align-items:center;" class="alert">
                            <span style="font-size: 20px; font-weight: bold;">{{ $assign->grade->name . " - " . $assign->subject->name}}
                                <b class="caret"></b>
                            </span>
                        <a href="{{ route('admin.schedule.export.signal', $assign->id) }}" class="btn btn-round btn-success">export</a>
                    </div>
                </a>
            @endif

            <div class="clearfix"></div>
            <div class="collapse" id="assign{{ $assign->id }}">
                @if(count($assign->schedules))
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Class Room</th>
                            <th>Day</th>
                            <th>Lesson</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assign->schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->classRoom->name }}</td>
                                <td>{{ "Thứ " . ($schedule->day+1) }}</td>
                                <td>{{ $schedule->lesson->start." - ".$schedule->lesson->end }}</td>
                                <td>{{ $schedule->created_at }}</td>
                                <td>
                                    <a data-toggle="tooltip" title="Edit" data-placement="left" href="{{ route('admin.schedule.edit', $assign->id) }}" class="btn btn-info btn-round">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
</div>
