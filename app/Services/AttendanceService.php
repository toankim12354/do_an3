<?php 

namespace App\Services;

use App\Exceptions\CanNotInsertException;
use App\Exceptions\CanNotUpdateException;
use App\Exceptions\CustomErrorException;
use App\Exceptions\NotFoundException;
use App\Models\Assign;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Teacher;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

/**
 * 
 */
class AttendanceService
{
	public function getView(Request $request, Teacher $teacher, $viewName)
	{
		// request ajax : get subject & get students
		if ($request->ajax()) {
			// check action
			$action = $request->action ?? '';

			switch ($action) {
				case 'get_subject':
					if (! isset($request->id_grade)) {
						throw new NotFoundException('Miss Data');
					}

					$idGrade = $request->id_grade;
					$subjects = $teacher->getMySubjects(null, $idGrade);

					return response()->json(['subjects' => $subjects], 200);
					break;

				case 'get_data':
					if (! (isset($request->id_grade) 
						&& isset($request->id_subject))) {
						throw new NotFoundException('Miss Data');
					}

					$idGrade     = $request->id_grade;
					$idSubject   = $request->id_subject;
					$idTeacher   = $teacher->id;
					$currentDate = $request->current_date ?? '';

					return $this->getDataForCreateOrEdit(
						$idGrade, $idSubject, $idTeacher, $currentDate
					);
					break;

				case 'get_attendance_history':
					if (! (isset($request->id_grade) 
						&& isset($request->id_subject))) {
						throw new NotFoundException('Miss Data');
					}

					$idGrade     = $request->id_grade;
					$idSubject   = $request->id_subject;
					$idTeacher   = $teacher->id;

					return $this->getAttendanceHistory(
						$idGrade, $idSubject, $idTeacher
					);
					break;
				
				default:
					throw new NotFoundException("Not Found Action");
					break;
			}
		}

		// get grades
		$grades = $teacher->getMyGrades();

		return view($viewName)->with('grades', $grades);
	}

	/**
	 * lay danh sach sinh vien cua lop can diem danh
	 * @param  [type] $idGrade   [description]
	 * @param  [type] $idSubject [description]
	 * @param  [type] $idTeacher [description]
	 * @return [type]            [description]
	 */
	public function getDataForCreateOrEdit(
		$idGrade, $idSubject, $idTeacher, $currentDate = '')
	{
		// dd($currentDate);
		$now = $currentDate ? t_now($currentDate) : t_now();

		// check assign
		$check = $this->checkAssign(
			$idGrade, $idSubject, $idTeacher, $currentDate
		);

		// handle error
		if ($check['status'] === 'error') {
			return response()->json($check, 422);
		}

		// bind data after check
		list($assign, $schedule) = $check['data'];

		// get data for view
		$data = [];

		if ($this->isCreated($assign->id, $now->date) || $assign->status == 2) {
			// $idAttendance = $assign->findAttendanceByDate($now->date)->id;
			$data = $this->getDataForEdit($assign, $now);
		} else {
			$data = $this->getDataForCreate($assign);
		}

		// make response success
		$responseSuccess = $this->makeResponse($assign, $data);

		return response()->json($responseSuccess, 200);
	}

	// get html to response
	public function getHtml($data)
	{
		return view('teachers.attendances.load_create')
				->with('listData', $data)
				->render();
	}

	// get list student with their attendance record
	public function getDataForEdit($assign, $now)
	{	
		$attendance = null;

		if ($assign->status == 2) {
			$attendance = $assign->attendances->last();
		} else {
			$attendance = $assign->findAttendanceByDate($now->date);
		}
	
		$attendanceDetails = $attendance->attendanceDetails;
		$grade             = $assign->grade;
		$students          = $grade->students->where('status', '1');
		$record            = [];

		$attendanceDetailsMapping = [];

		foreach ($attendanceDetails as $key => $attendanceDetail) {
			$idStudent = $attendanceDetail->id_student;
			$status    = $attendanceDetail->status;
			$note      = $attendanceDetail->note;

	     	$attendanceDetailsMapping[$idStudent] = (object) [
	     		'status' => $status, 
	     		'note' => $note
	     	];
		}

		foreach ($students as $key => $student) {
		    $students[$key]->fetchInfoAttendance($assign);

			$status = $attendanceDetailsMapping[$student->id]->status ?? null;
			$note   = $attendanceDetailsMapping[$student->id]->note ?? null;

		    $record[] = (object) [
				"id"             => $students[$key]->id,
				"code"           => $students[$key]->code,
				"name"           => $students[$key]->name,
				"infoAttendance" => $students[$key]->infoAttendance,
				"status"         => $status,
				"note"           => $note
		    ];
	  	}

		// get note
		$mainNote = $attendance->note;

		$data = (object) [
			'record' => $record,
			'mainNote' => $mainNote
		];

		return $data;
	}

	//
	public function getDataForCreate($assign)
	{
		$grade = $assign->grade;
		$students = $grade->students->where('status', '1');

		// buid data
		$data = [];
		foreach ($students as $key => $student) {
			$students[$key]->fetchInfoAttendance($assign);

			$object = (object) [
				"id"             => $students[$key]->id,
				"code"           => $students[$key]->code,
				"name"           => $students[$key]->name,
				"infoAttendance" => $students[$key]->infoAttendance,
				"status"         => 1,
				"note"           => ''
			];

			$data[] = $object;
		}

		return (object) [
			'record' => $data
		];
	}

	/**
	 * Lưu điểm danh vào database
	 * @param  Request $request [description]
	 * @param  Teacher $teacher [description]
	 * @return [type]           [description]
	 */
	public function save(Request $request, Teacher $teacher)
	{
		// validate input
		$input       = $this->validate($request);
		$idGrade     = $input['id_grade'];
		$idSubject   = $input['id_subject'];
		$mainNote    = $input['main_note'];
		$record      = $input['record'];
		$currentDate = $input['current_date'] ?? '';

		// time now
		$now = $currentDate ? t_now($currentDate) : t_now();

		// check assign
		$check = $this->checkAssign(
			$idGrade, $idSubject, $teacher->id, $currentDate
		);

		// handle error
		if ($check['status'] === 'error') {
			return response()->json($check, 422);
		}

		// bind data after check assign
		list($assign, $schedule) = $check['data'];

		// time per lesson
		$timePerLesson = $schedule->lesson->time;
		
		try {
			if ($this->isCreated($assign->id, $now->date) 
				|| $assign->status == 2) {
				// update attendance
				if ($assign->status == 2) {
					$attendance = $assign->attendances->last();
				} else {
					$attendance = $assign->findAttendanceByDate($now->date);
				}

				$data = [
					'note'       => $mainNote, 
					'updated_at' => $now->time
				];

				$idAttendance = $this->updateAttendance($attendance, $data);

				// update attendance detail
				$this->updateAttendanceDetail($idAttendance, $record);
			} else {
				// add attendance
				$idAttendance = $this->addAttendance(
					$assign->id, $timePerLesson, $mainNote, $now->time
				);

				// add attendance detail
				if ($this->addAttendanceDetail($idAttendance, $record)) {
					// update assign (time done, status)
					$this->updateAssign($assign, $timePerLesson, $now);
				}
			}

			$responseSuccess = $this->makeResponse($assign);

			return response()->json($responseSuccess, 200);

		} catch (CanNotInsertException $e) {
			throw new CustomErrorException($e->getMessage());
		} catch (CanNotUpdateException $e) {
			throw new CustomErrorException($e->getMessage());
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}

	// validate input save
	public function validate($request)
	{
		$mustExist = ['id_grade', 'id_subject', 'main_note', 'record'];

		$validated = [];

		foreach ($mustExist as $key => $varName) {
			if (! $request->has($varName)) {
				throw new NotFoundException('miss data');
			}
			$validated[$varName] = $request->get($varName);
		}

		if ($request->has('current_date')) {
			$validated['current_date'] = $request->current_date;
		}

		return $validated;
	}

	// add attendance
	public function addAttendance($idAssign, $timePerLesson, $note, $createdAt)
	{
		$attendance = null;

		try {
			$attendance = Attendance::create([
				'id_assign'  => $idAssign,
				'time'       => $timePerLesson,
				'note'       => $note,
				'created_at' => $createdAt,
				'updated_at' => $createdAt
			]);
		} catch (\Exception $e) {
			throw new CanNotInsertException("cannot create attendance");
		}

		return $attendance->id;
	}

	// add attendance detail
	public function addAttendanceDetail($idAttendance, $record)
	{
		// handle data insert
		foreach ($record as $key => $row) {
			$record[$key]['id_attendance'] = $idAttendance;
		}

		try {
			AttendanceDetail::insert($record);
		} catch (\Exception $e) {
			throw new CanNotInsertException("cannot insert record");
		}

		return true;
	}

	// update attendance
	public function updateAttendance($attendance, $data)
	{
		try {
			$attendance->update($data);
		} catch (\Exception $e) {
			throw new CanNotUpdateException("cannot update attendance");
		}

		return $attendance->id;
	}
	

	// update attendance detail
	public function updateAttendanceDetail($idAttendance, $record)
	{
		try {
			DB::beginTransaction();

			foreach ($record as $key => $row) {
				DB::table('attendance_details')
					->updateOrInsert(
						[
							'id_attendance' => $idAttendance,
							'id_student'    => $row['id_student'],
						],
						['status' => $row['status'], 'note' => $row['note']]
					);
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new CanNotUpdateException('cannot update record');
		}

		return true;
	}

	// update assign
	public function updateAssign($assign, $timePerLesson, $now)
	{
		$newTimeDone = (float)$assign->time_done + (float)$timePerLesson;
		$subjectDuration = (float)$assign->subject->duration;
		$status = (round($newTimeDone) >= round($subjectDuration)) ? 2 : 1;
		
		try {
			$assign->update([
				'status' => $status,
				'time_done' => $newTimeDone
			]);

			if ($status == 2) {
				$assign->schedules()->update(['day_finish' => $now->time]);
			}
		} catch (\Exception $e) {
			throw new CanNotUpdateException("cannot update assign");
		}

		return true;
	}


	// ============================ ATTENDANCE HISTORY =========================
	public function getAttendanceHistory($idGrade, $idSubject, $idTeacher)
	{
		$assign = $this->getAssign($idGrade, $idSubject, $idTeacher);
		$now = t_now();

		if ($assign === null) {
			return response()->json([
				'status' => 'error',
				'message' => 'Bạn không được phân công'
			], 422);
		}

		$data = $assign->getHistoryAttendance();
		// dd($data);

		$html = view('teachers.attendances.load_history', ['data' => $data])
				->render();

		return response()->json(['html' => $html], 200);
	}

	// update history
	public function updateHistory(Request $request, Teacher $teacher)
	{
		// validated input
		$validator = Validator::make($request->all(), [
			'id_grade' => 'required',
			'id_subject' => 'required',
			'record' => 'required'
		]);

		if ($validator->fails()) {
			throw new NotFoundException('miss data');
		}

		// get data after validate input
		$validated = $validator->validated();

		$idGrade = $validated['id_grade'];
		$idSubject = $validated['id_subject'];
		$record = json_decode($validated['record']);
		// dd($record);
		$now = t_now();

		// check assign
		$assign = $this->getAssign($idGrade, $idSubject, $teacher->id);
		if ($assign === null) {
			return response()->json([
				'status' => 'error',
				'message' => 'ban khong duoc phan cong'
			], 422);
		}

		try {
			// update record in attendance table
			$assign->attendances()->update(['updated_at' => $now->time]);

			// update or insert attendance detail table
			DB::beginTransaction();
			foreach ($record as $key => $row) {
				DB::table('attendance_details')
					->updateOrInsert(
						[
							'id_attendance' => $row->id_attendance,
							'id_student'    => $row->id_student,
						],
						['status' => $row->status]
					);
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new CustomErrorException($e->getMessage());
		}

		return response()->json(['updatedAt' => $now->custom], 200);
	}

	// lay so tiet nghi cua sinh vien
	public function getLate($idStudent, $assign)
	{
		 $attendances = DB::table('attendances')
                    ->join('attendance_details', 'attendances.id', '=', 'attendance_details.id_attendance')
                    ->where('attendances.id_assign', $assign->id)
                    ->select([
                        'attendance_details.id_student', 
                        'attendance_details.status', 
                    ]);

	    // left join hoc sinh voi cac ban ghi diem danh de lay duoc cac hoc sinh chua duoc diem danh
	    $query = DB::table('students')
	            ->leftJoinSub($attendances, 'a', function($join) {
	                $join->on('students.id', '=', 'a.id_student');
	            })
	            ->where('students.id', $idStudent)
	            ->groupBy('students.id')
	            ->selectRaw(
	                'SUM(IF(a.status = 0, 1, IF(a.status = 2, 0.5, 0))) as late'
	            );
	           
	    $result = $query->get();
	    return $result[0]->late;
	}

	// get info assign (time done, duration subject)
	public function makeResponse($assign, $data = '')
	{
		// info of this assign (time done, time remaining, ...)
		$response = $assign->info;

		// html view
		if ($data) {
			$html = $this->getHtml($data);
			$response['html'] = $html;
		}

		// last time update of this attendance of this assign
		$lastUpdate = '-';
		if (count($assign->attendances) > 0) {
			$lastUpdate = $assign->attendances->last()->updated_at;
			$lastUpdate = Carbon::parse($lastUpdate)->locale('vi')->calendar();
		}

		$response['updateAt'] = $lastUpdate;

		return $response;
	}

	/**
	 * kiem tra truoc khi tra ve danh sach sinh vien || them diem danh
	 * @param  [type] $idGrade   [description]
	 * @param  [type] $idSubject [description]
	 * @param  [type] $idTeacher [description]
	 * @return [type]            [description]
	 */
	public function checkAssign(
		$idGrade, $idSubject, $idTeacher, $currentDate = '')
	{
		$now = $currentDate ? t_now($currentDate) : t_now();

		$assign = $this->getAssign($idGrade, $idSubject, $idTeacher);
		// khong co phan cong
		if ($assign === null) {
			return [
				'status' => 'error',
				'message' => 'Bạn không được phân công'
			];
		}

		// // da day xong
		// if ($assign->status == 2) {
		// 	return [
		// 		'status' => 'error',
		// 		'message' => 'Đã dạy xong'
		// 	];
		// }

		$schedule = $assign->findScheduleByDay($now->day);
		// khong dung ngay (khong co lich hoc hom nay)
		if ($schedule === null) {
			return [
				'status' => 'error',
				'message' => 'Hôm nay không có lịch học'
			];
		}

		return [
			'status' => 'success',
			'data' => [$assign, $schedule]
		];
	}

	/**
	 * lay object diem danh (assign) tu id_grade, id_subject, id_teacher
	 * @param  [type] $idGrade   [description]
	 * @param  [type] $idSubject [description]
	 * @param  [type] $idTeacher [description]
	 * @return [type]            [description]
	 */
	public function getAssign($idGrade, $idSubject, $idTeacher)
	{
		return Assign::where('id_grade', $idGrade)
					    ->where('id_subject', $idSubject)
					    ->where('id_teacher', $idTeacher)
					    ->first();
	}

	/**
	 * lay lich hoc theo id phan cong va thu(thu hai, thu ba ...)
	 * can use: $assign->findScheduleByDay($day)
	 * @param  [type] $idAssign [description]
	 * @param  [type] $day      [description]
	 * @return [type]           [description]
	 */
	public function getSchedule($idAssign, $day)
	{
		return Schedule::where('id_assign', $idAssign)
					    ->where('day', $day)
					    ->first();
	}

	/**
	 * kiem tra da diem danh cua mot lop tai mot mon hay chua
	 * can use: return $assign->findAttendanceByDate($date)
	 * @param  [type]  $idAssign [description]
	 * @param  [type]  $time     [description]
	 * @return boolean           [description]
	 */
	public function isCreated($idAssign, $time)
	{
		$count = DB::table('attendances')
					->where('id_assign', $idAssign)
					->whereRaw('DATE(created_at) = ?', [$time])
					->count();

		return $count > 0 ? true : false;
	}

}