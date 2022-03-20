<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assign;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkController extends Controller
{
    public function assign(Request $request)
    {
        $assigns = Assign::where('id_teacher', \Auth::id())->get();
        $data = [];
        $subjects = [];

        foreach ($assigns as $key => $assign) {
            $data[$assign->id_subject]['subject'] = $assign->subject;
            $data[$assign->id_subject]['assignsOfSubject'][] = $assign;
        }

        return view('teachers.works.assign')->with('data', $data);
    }

    public function schedule(Request $request)
    {
        $teacher = \Auth::user();
        $assigns = $teacher->assigns->filter(function ($assign) {
            $now = date('Y');
            $year_item = date('Y', strtotime($assign->start_at));
            return $now == $year_item;
        });
        $time = function ($dates) {
            return strtotime($dates->start_at);
        };
        $fromDate = $assigns->min($time);
        $listDate = dateGroupByDayName($fromDate);
        $listSchedule = null;

        // get all teacher's Schedule
        $i = 0;
        foreach ($assigns as $key => $assign) {
           if ($i === 0) {
                $listSchedule = $assign->schedules;
           } else {
                $listSchedule = $listSchedule->merge($assign->schedules);
           }
           $i++;
        }

        // get lesson group by day name
        $scheduleInfo = [];

        foreach ($listSchedule as $key => $schedule) {
            $lesson = $schedule->lesson;
            $assign = $schedule->assign;
            $classRoom = $schedule->classRoom;

            $scheduleInfo[$schedule->day][] = [
                'start' => $lesson->start,
                'end' => $lesson->end,
                'title' => "\n" . $assign->grade->name
                            . ":"
                            . $assign->subject->name
                            . "-"
                            . $classRoom->name,
                'dayFinish' => $schedule->day_finish
            ];
        }

        // dd($scheduleInfo);
        // make event
        $data = [];
        $days = array_keys($scheduleInfo);
        // dd($days);

        foreach ($scheduleInfo as $key => $infos) {
            foreach ($infos as $info) {
                foreach ($listDate[$key] as $date) {
                    $start     = $date . ' ' . $info['start'];
                    $end       = $date . '' . $info['end'];
                    $title     = $info['title'];
                    $dayFinish = $info['dayFinish'] !== null
                                ? Carbon::parse($info['dayFinish'])
                                ->toDateString() : null;

                    // if finish color red
                    $className = ($dayFinish !== null && $date == $dayFinish)
                                 ? 'event-red' : 'event-azure';

                    // if date greate than day finish continue
                    if ($dayFinish !== null && $date > $dayFinish) {
                       continue;
                    }

                    $data[] = [
                        'start' => Carbon::parse($start)->toDateTimeString(),
                        'end'   => Carbon::parse($end)->toDateTimeString(),
                        'title' => $title,
                        'url'   => route('teacher.attendance.create'),
                        'className' => $className
                    ];
                }
            }
        }

        if ($request->ajax()) {
            return response()->json($data);
        }
        return view('teachers.works.schedule');
    }
}
