<?php

namespace App\Services;

use App\Models\Assign;
use App\Models\Lesson;
use App\Models\Schedule;

/**
 *
 */
class ScheduleService
{
    protected $class_room_time = 8.0;

    /**
     * [search description]
     * @param  [type] $request    [description]
     * @param  [type] $rowPerPage [description]
     * @return [type]             [description]
     */

    // search
    public function search($request, $rowPerPage)
    {
        // query builder
        $query = Schedule::select('*');
        $rowPerPage = $request->row ?? $rowPerPage;

        // class room filter
        if (isset($request->classroom)) {
            $query->where('id_class_room', $request->classroom);
        }

        // day filter
        if (isset($request->day)) {
            $query->where('day', $request->day);
        }

        // lesson filter
        if (isset($request->lesson)) {
            $query->where('id_lesson', $request->lesson);
        }

        // get list of schedule match with key
        $schedules = $query->paginate($rowPerPage);

        $html = view('admins.schedules.load_index_all')
            ->with(['schedules' => $schedules])
            ->render();

        return response()->json(['html' => $html], 200);
    }

    public function getSubjectsForSchedule($grade)
    {
        $assigns = Assign::where('id_grade', $grade)
            ->where('status', 1)
            ->get();
        $subjects = [];
        foreach ($assigns as $assign) {
            if (!in_array($assign->subject, $subjects)) {
                $subjects[] = $assign->subject;
            }
        }
        return $subjects;
    }

    public function getTeacherForSchedule($grade, $subject)
    {
        $assigns = Assign::where('id_grade', $grade)
            ->where('id_subject', $subject)
            ->where('status', 1)
            ->get();
        $teachers = [];
        foreach ($assigns as $assign) {
            $teachers[] = $assign->teacher;
        }
        return $teachers;
    }

    public function getAssign($id_grade, $id_subject, $id_teacher)
    {
        return Assign::where('id_grade', $id_grade)
            ->where('id_subject', $id_subject)
            ->where('id_teacher', $id_teacher)
            ->first();
    }

    public function getDays($id_class_room)
    {
        $data = [];
        for ($i = 1; $i < 7; $i++) {
            $schedules = Schedule::where('day_finish', null)
                ->where('id_class_room', $id_class_room)
                ->where('day', $i)
                ->get();
            $timeOfDay = 0.0;
            foreach ($schedules as $schedule) {
                $start = $schedule->lesson->start;
                $end = $schedule->lesson->end;
                $timeOfDay += (float)$end - (float)$start;
            }

            if ($timeOfDay < $this->class_room_time) {
                $data[] = $i;
            }
        }

        if (session()->has('schedule_edit')) {
            $oldSchedules = session('schedule_edit');
            foreach ($oldSchedules as $oldSchedule) {
                if ($oldSchedule['id_class_room'] == $id_class_room && !in_array($oldSchedule['day'], $data)) {
                    $data[] = $oldSchedule['day'];
                }
            }
        }

        if (!empty($data)) {
            return response()->json($data);
        } else {
            return false;
        }
    }

    public function getLessons($id_class_room, $day)
    {
        $schedules = Schedule::where('day_finish', null)
            ->where('id_class_room', $id_class_room)
            ->where('day', $day)
            ->get();

        $idLessonInSchedule = [];
        foreach ($schedules as $schedule) {
            $idLessonInSchedule[] = $schedule->id_lesson;
        }

        if (session()->has('schedule_edit')) {
            $oldSchedules = session('schedule_edit');
            $oldLesson = null;
            foreach ($oldSchedules as $oldschedule) {
                if ($oldschedule['id_class_room'] == $id_class_room && $oldschedule['day'] == $day) {
                    $oldLesson = $oldschedule['id_lesson'];
                }
            }
            if (!empty($oldLesson)) {
                $idLessonInSchedule = array_diff($idLessonInSchedule, [$oldLesson]);
            }
        }

        $lessons = Lesson::whereNotIn('id', $idLessonInSchedule)
            ->get();

        if (!empty($lessons)) {
            return response()->json($lessons);
        } else {
            return false;
        }
    }

    public function storeRepeat($request)
    {
        $id_assign = $request->id_assign;
        $id_class_rooms = $request->id_class_room;
        $days = $request->day;
        $id_lessons = $request->id_lesson;

        for ($i = 0; $i < count($id_class_rooms); $i++) {
            $row = [
                'id_assign' => $id_assign,
                'id_class_room' => $id_class_rooms[$i],
                'day' => $days[$i],
                'id_lesson' => $id_lessons[$i],
                'day_finish' => NULL
            ];

            Schedule::create($row);
        }
    }

    public function validateSchedule($request)
    {
        $id_assign = $request->id_assign;
        $id_class_rooms = $request->id_class_room;
        $days = $request->day;
        $id_lessons = $request->id_lesson;

        $errorRows = [];
        $result = [];
        for ($i = 0; $i < count($id_class_rooms); $i++) {
            $row = [
                'id_assign' => $id_assign,
                'id_class_room' => $id_class_rooms[$i],
                'day' => $days[$i],
                'id_lesson' => $id_lessons[$i],
                'day_finish' => NULL
            ];

            if (!$this->checkSchedule($row)) {
                $errorRows[] = $i;
            }
        }

        if (!empty($errorRows)) {
            $result['message'] = 'already exist';
            $result['errorRows'] = $errorRows;
            $result['code'] = 1;
        }

        return $result;
    }

    public function checkSchedule($schedule)
    {
        $teacher = $this->getTeacherByIdAssign($schedule['id_assign']);
        $assigns = $this->getAssignsByIdTeacher($teacher->id);
        $id_assigns = [];
        foreach ($assigns as $assign) {
            $id_assigns[] = $assign->id;
        }
        $day = $schedule['day'];
        $allSchedulesTeacherOfDay = $this->getSchedulesOfTeacher($id_assigns, $day);
        $allSchedulesClassRoomOfDay = $this->getScheduleOfClassRoom($schedule['id_class_room'], $day);
        $allLessonToCheckOfDay = [];
        $lessonNeedCheck = $this->getLessonById($schedule['id_lesson']);

        foreach ($allSchedulesTeacherOfDay as $scheduleTeacher) {
            $allLessonToCheckOfDay[] = $scheduleTeacher->lesson;
        }

        foreach ($allSchedulesClassRoomOfDay as $scheduleClassRoom) {
            if (!in_array($scheduleClassRoom->lesson, $allLessonToCheckOfDay)) {
                $allLessonToCheckOfDay[] = $scheduleClassRoom->lesson;
            }
        }
        return $this->checkLessonInDay($allLessonToCheckOfDay, $lessonNeedCheck);
    }

    public function getTeacherByIdAssign($id_assign)
    {
        $assign = Assign::find($id_assign);

        return $assign->teacher;
    }

    public function getAssignsByIdTeacher($id_teacher)
    {
        return Assign::where('id_teacher', $id_teacher)
            ->get();
    }

    public function getSchedulesOfTeacher($id_assigns, $day)
    {
        return Schedule::where('day_finish', NULL)
            ->whereIn('id_assign', $id_assigns)
            ->where('day', $day)
            ->get();
    }

    public function getScheduleOfClassRoom($id_class_room, $day)
    {
        return Schedule::where('day_finish', NULL)
            ->where('id_class_room', $id_class_room)
            ->where('day', $day)
            ->get();
    }

    public function getLessonById($id_lesson)
    {
        return Lesson::find($id_lesson);
    }

    public function checkLessonInDay($input, $lessonNew)
    {
        foreach ($input as $value) {
            if (((float)$lessonNew->start >= (float)$value->start && (float)$lessonNew->start < (float)$value->end)
                || ((float)$lessonNew->end <= (float)$value->end && (float)$lessonNew->end > (float)$value->start)
                || ((float)$lessonNew->start < (float)$value->start && (float)$lessonNew->end > (float)$value->end)
            ) {
                return false;
            }
        }
        return true;
    }

    public function validateUpdateMultiSchedule($request)
    {
        $id_assign = $request->id_assign;
        $id_schedule = $request->id_schedule;
        $id_class_rooms = $request->id_class_room;
        $days = $request->day;
        $id_lessons = $request->id_lesson;
        $oldSchedules = session('schedule_edit');
        $successRows = [];
        $errorRows = [];

        for ($i = 0; $i < count($id_schedule); $i++) {
            $row = [
                'id_class_room' => (int)$id_class_rooms[$i],
                'day' => (int)$days[$i],
                'id_lesson' => (int)$id_lessons[$i],
                'id' => (int)$id_schedule[$i]
            ];

            if (!in_array($row, $oldSchedules)) {
                $row += [
                    'id_assign' => $id_assign,
                    'day_finish' => NULL
                ];
                $lessonNew = Lesson::find($row['id_lesson']);
                $lessonOld = Lesson::find($oldSchedules[$i]['id_lesson']);
                if ($oldSchedules[$i]['day'] == $row['day'] && $oldSchedules[$i]['id_class_room'] == $row['id_class_room']) {
                    if ($this->checkScheduleUpdateSameClassAndDay($lessonOld, $lessonNew, $id_assign, $oldSchedules[$i]['day'])) {
                        $successRows[] = $row;
                        continue;
                    }
                }
                if ($this->checkSchedule($row)) {
                    $successRows[] = $row;
                } else {
                    $errorRows[] = $i;
                }
            }
        }
        if (!empty($errorRows)) {
            return [
                'status' => false,
                'error_rows' => $errorRows
            ];
        }

        return [
            'status' => true,
            'success_row' => $successRows
        ];
    }

    public function checkScheduleUpdateSameClassAndDay($oldLesson, $newLesson, $id_assign, $oldDay)
    {
        if ($this->checkLessonUpdateSameClassAndDay($oldLesson, $newLesson)) {
            return true;
        }
        $teacher = $this->getTeacherByIdAssign($id_assign);
        $allAssignOfTeacher = $teacher->assigns->where('status', 1);
        $allLessonOfTeacher = [];
        foreach ($allAssignOfTeacher as $assign) {
            foreach ($assign->schedules as $schedule) {
                if ($schedule->day == $oldDay) {
                    $allLessonOfTeacher[] = $schedule->lesson;
                }
            }
        }
        $allLessonOfTeacher = array_diff($allLessonOfTeacher, [$oldLesson]);
        if (!count($allLessonOfTeacher)) {
            return true;
        }
        foreach ($allLessonOfTeacher as $lessonOfTeacher) {
            if ($this->checkLessonUpdateSameClassAndDay($lessonOfTeacher, $oldLesson)) {
                return true;
            }
        }
        return false;
    }

    public function checkLessonUpdateSameClassAndDay($oldLesson, $newLesson)
    {
        return (float)$newLesson->start >= (float)$oldLesson->start && (float)$newLesson->end <= (float)$oldLesson->end;
    }
}
