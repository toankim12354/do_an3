<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assign;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\ScheduleService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *
 */
class ScheduleController extends Controller
{
    private $service;

    /**
     * [__construct description]
     */
    public function __construct(ScheduleService $service)
    {
        $this->middleware('preventCache');
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('admins.schedules.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        //
        $grades = Grade::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $classrooms = ClassRoom::all();
        $lessons = Lesson::all();
        session()->forget('schedule_edit');

        $data = [
            'classrooms' => $classrooms,
            'grades' => $grades,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'lessons' => $lessons
        ];

        return view('admins.schedules.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $error = $this->service->validateSchedule($request);

        if ($error) {
            return response()->json($error, 422);
        }

        try {
            $this->service->storeRepeat($request);
        } catch (Exception $e) {
            var_dump($e);
            $error = [
                "code" => 2,
                'message' => 'some error, try again'
            ];

            return response()->json($error, 422);
        }

        $success = ['url' => route('admin.schedule.indexAll')];

        $request->session()->flash('success', 'add schedules successfully');

        return response()->json($success, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $assign = Assign::find($id);
        $classrooms = ClassRoom::all();
        $lessons = Lesson::all();
        $schedules = $assign->schedules;
        $schedule = [];
        foreach ($schedules as $value) {
            $schedule[] = [
                'id_class_room' => $value->id_class_room,
                'day' => $value->day,
                'id_lesson' => $value->id_lesson,
                'id' => $value->id
            ];
        }
        session()->forget('schedule_edit');
        session(['schedule_edit' => $schedule]);

        $data = [
            'schedule' => $schedule,
            'assign' => $assign,
            'classrooms' => $classrooms,
            'lessons' => $lessons
        ];

        return view('admins.schedules.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateMultiSchedule(Request $request)
    {
        $id_assign = $request->id_assign;
        $resultValidate = $this->service->validateUpdateMultiSchedule($request);

        if ($resultValidate['status'] === false) {
            $result['message'] = 'already exist';
            $result['errorRows'] = $resultValidate['error_rows'];
            $result['code'] = 1;
            return response()->json($result, 422);
        } else {
            foreach ($resultValidate['success_row'] as $scheduleUpdate) {
                $schedule = Schedule::find($scheduleUpdate['id']);
                try {
                    $schedule->update($scheduleUpdate);
                } catch (Exception $e) {
                    return redirect()
                        ->route('admin.schedule.edit', $id_assign)
                        ->with('error', 'Update failed');
                }
            }
            $success = ['url' => route('admin.schedule.indexAll')];
            session()->forget('schedule_edit');
            $request->session()->flash('success', 'add schedules successfully');
            return response()->json($success, 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @return Application|Factory|View
     */
    public function indexAll(Request $request)
    {
        $paginate = 16;

        if ($request->ajax()) {
            return $this->service->search($request, $paginate);
        }

        $schedules = Schedule::paginate($paginate);
        $classrooms = ClassRoom::all();
        $lessons = Lesson::all();

        $data = [
            'schedules' => $schedules,
            'classrooms' => $classrooms,
            'lessons' => $lessons
        ];

        return view('admins.schedules.index_all', $data);
    }

    /**
     * @return Application|Factory|View
     */
    public function indexTeacher()
    {
        $teachers = Teacher::all();
        $data = [
            'teachers' => $teachers
        ];

        return view('admins.schedules.index_teacher', $data);
    }

    /**
     * @return Application|Factory|View
     */
    public function indexClass()
    {
        $grades = Grade::all();
        $grade = Grade::find(5);
        $data = [
            'grades' => $grades,
            'grade' => $grade
        ];
        return view('admins.schedules.index_class', $data);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function requestAjax(Request $request)
    {
        if ($request->ajax()) {
            // update assign for schedule
            if ($request['action'] == 'postAssign') {
                $req = $request->all();
                $assign = $this->service->getAssign($req['id_grade'], $req['id_subject'], $req['id_teacher']);
                $data = [];
                if (!empty($assign->id)) {
                    $data = [
                        'status' => true,
                        'assign' => $assign->id,
                        'grade' => $assign->grade->name,
                        'subject' => $assign->subject->name,
                        'teacher' => $assign->teacher->name
                    ];
                } else {
                    $data = [
                        'status' => false
                    ];
                }

                return response()->json($data);
            }

            // update subject when select grade
            if ($request['action'] == 'updateSubject') {
                $subjects = $this->service->getSubjectsForSchedule($request['id_grade']);
                $data = [];
                foreach ($subjects as $subject) {
                    $data[] = [
                        'id' => $subject->id,
                        'name' => $subject->name
                    ];
                }

                return response()->json($data);
            }

            // update teacher when select subject
            if ($request['action'] == 'updateTeacher') {
                $teachers = $this->service->getTeacherForSchedule($request['id_grade'], $request['id_subject']);
                $data = [];
                foreach ($teachers as $teacher) {
                    $data[] = [
                        'id' => $teacher->id,
                        'name' => $teacher->name
                    ];
                }
                return response()->json($data);
            }

            // update days
            if ($request['action'] == 'updateDay') {
                $data = $this->service->getDays($request['id_class_room']);
                return response()->json($data);
            }

            // update lessons
            if ($request['action'] == 'updateLesson') {
                $data = $this->service->getLessons($request['id_class_room'], $request['day']);
                return response()->json($data);
            }

            // update Schedule Of Teacher
            if ($request['action'] == 'updateScheduleTeacher') {
                $req = $request->all();
                $teacher = Teacher::find($req['id_teacher']);
                $scheduleOfTeacher = [];
                $assigns = $teacher->assigns->where('status', 1);
                foreach ($assigns as $assign) {
                    if (count($assign->schedules)) {
                        $scheduleOfTeacher[] = $assign->schedules;
                    }
                }
                if (!count($assigns) || !count($scheduleOfTeacher)) {
                    return response()->json([], 422);
                }
                $html = view('admins.schedules.schedules_teacher')
                    ->with('assigns', $assigns)
                    ->render();
                $result = [
                    'html' => $html
                ];
                return response()->json($result, 200);
            }

            // update Schedule Of Grade
            if ($request['action'] == 'updateScheduleGrade') {
                $req = $request->all();
                $grade = Grade::find($req['id_grade']);
                $assigns = $grade->assigns->where('status', 1);
                $scheduleOfGrade = [];
                foreach ($assigns as $assign) {
                    if (count($assign->schedules)) {
                        $scheduleOfGrade[] = $assign->schedules;
                    }
                }
                if (!count($assigns) || !count($scheduleOfGrade)) {
                    return response()->json([], 422);
                }
                $html = view('admins.schedules.schedules_grade')
                    ->with('assigns', $assigns)
                    ->render();
                $result = [
                    'html' => $html
                ];
                return response()->json($result, 200);
            }
        }
    }
}
