<?php


namespace App\Services;

use App\Exceptions\CustomErrorException;
use App\Exports\Excel\StatisticAttendanceExportMultiple;
use App\Mail\sendStatisticToStudent;
use App\Models\Assign;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
/**
 * 
 */
class StatisticService
{
	// get select subject and view statistic
	public function getView(Request $request)
	{
		if ($request->ajax()) {
			return $this->ajaxRequest($request);
		}

		$grades = Grade::whereIn('id', function($query) {
			$query->selectRaw('id_grade')->from('assigns');
		})->get();

		return view('admins.statistics.attendance')->with('grades', $grades);
	}

	// handle ajax request
	public function ajaxRequest(Request $request)
	{
		$action = $request->action ?? '';

		switch($action) {
			case 'get_subject':
				// check input
				if (! isset($request->id_grade)) {
					throw new CustomErrorException('miss data');
				}

				// get input
				$idGrade = $request->id_grade;
				
				// get subject to return
				$subjects = Subject::whereIn('id', function($query) 
							use ($idGrade) {
								$query->select('id_subject')
								->from('assigns')
								->whereRaw('id_grade = ?', $idGrade);
							})->get();

				return response()->json(['subjects' => $subjects], 200);
				break;

			case 'get_statistic_attendance':
				// check input
				if (! isset($request->id_grade) 
					|| ! isset($request->id_subject)) {
					throw new CustomErrorException('miss data');
				}

				// get input
				$idGrade    = $request->id_grade;
				$grade      = Grade::find($idGrade);
				$students   = $grade->students->where('status', '1');
				$idSubjects = $request->id_subject;
				$html       = '';

				// render view  statistic for each subject
				foreach ($idSubjects as $key => $idSubject) {
					$subject = Subject::find($idSubject);

					$assign = Assign::where('id_grade', $idGrade)
									->where('id_subject', $idSubject)
									->first();

					foreach ($students as $key => $student) {
						$students[$key]->fetchInfoAttendance($assign);
					}

					// render view
					$html .= view('admins.statistics.load_statistic_attendance')	->with([
								'students' => $students, 
								'subject' => $subject,
								'assign' => $assign
							])->render();

				}

				return response()->json(['html' => $html], 200);
				break;

				default: 
					throw new CustomErrorException('not found action');
					break;
		}
	}

	// export excel
	public function exportExcel(Request $request)
	{
		$request->validate([
            'id_grade' => 'required',
            'id_subject' => 'required'
        ]);

        $idGrade = $request->id_grade;
        $idSubjects = $request->id_subject;
        
        try {
            $export = new StatisticAttendanceExportMultiple(
                $idGrade, $idSubjects
            );

            return $export->download('statistic_attendance_' . time() .'.xlsx');
        } catch (\Exception $e) {
            throw new CustomErrorException($e->getMessage());
        }
	}

	public function sendEmail(Request $request) 
	{
		$request->validate([
            'id_grade' => 'required',
            'id_subject' => 'required'
        ]);

        $idGrade = $request->id_grade;
        $idSubjects = $request->id_subject;
        
        try {
            $export = new StatisticAttendanceExportMultiple(
                $idGrade, $idSubjects
            );

            $fileName = 'statistic_attendance_' . time() .'.xlsx';
            $export->store($fileName);

            $filePath = storage_path('app/' . $fileName);

            $emails = Student::where('id_grade', $idGrade)
        				->get()
        				->map(function($student) {
        				 	return $student->email;
        				})->toArray();
        	// $emails = [
        	// 	'chaungoclong2411@gmail.com', 
        	// 	'longcn2411@gmail.com',
        	// 	'sepptdat@gmail.com',
        	// 	'datseppy@gmail.com',
        	// 	'datdv@tigren.com',
        	// 	'chauvietnam30475@gmail.com',
        	// ];

        	Mail::to($emails)->send(new sendStatisticToStudent($filePath));
        	// foreach ($emails as $key => $email) {
        	// 	Mail::to($email)->send(new sendStatisticToStudent($filePath));
        	// }

            return redirect()->route('admin.statistic.attendance')
            	   			 ->with('success', 'send successfully');
        } catch (\Exception $e) {
            throw new CustomErrorException($e->getMessage());
        }
	}
}