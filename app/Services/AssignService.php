<?php 

namespace App\Services;

use App\Exceptions\CustomErrorException;
use App\Models\Assign;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;

/**
 * 
 */
class AssignService
{
	
	// search
	public function search($request, $rowPerPage)
    {
        // query builder
        $query = Assign::select('*');
        $rowPerPage = $request->row ?? $rowPerPage;

        // grade filter
        if (isset($request->grade)) {
            $query->where('id_grade', $request->grade);
        }

         // subject filter
        if (isset($request->subject)) {
            $query->where('id_subject', $request->subject);
        }

         // teacher filter
        if (isset($request->teacher)) {
            $query->where('id_teacher', $request->teacher);
        }

        // get list of assign match with key
        $assigns = $query->paginate($rowPerPage);

        $html = view('admins.assigns.load_index')
                ->with(['assigns' => $assigns])
                ->render();

        return response()->json(['html' => $html], 200);
    }

    // get data for view
    public function getStaticDataForView($rowPerPage)
    {
    	$assigns = Assign::paginate($rowPerPage);
        $grades = Grade::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        $data = [
            'assigns' => $assigns,
            'grades' => $grades,
            'subjects' => $subjects,
            'teachers' => $teachers
        ];

        return view('admins.assigns.index', $data);
    }

    // save
    public function save($request)
    {
    	$check = $this->checkAssignExist($request);

    	if ($check['status'] === 'error') {
    		return response()->json($check, 422);
    	}

    	$data = $this->formatData($request);
    	// dd($data);

    	try {
    		Assign::insert($data);
    	} catch (\Exception $e) {
    		throw new CustomErrorException("OPPS! SEVER ERROR");
    	}

    	// redirect after create success
        $success = ['url' => route('admin.assign.index')];

        $request->session()->flash('success', 'add assign successfully');

        return response()->json($success, 200);
    }

    // check assign exist
    public function checkAssignExist($request)
    {
    	// input
		$idGrades   = $request->id_grade;
		$idSubjects = $request->id_subject;
		$idTeachers = $request->id_teacher;

		// mang chua STT cua hang loi
        $rowErrors = [];

        // data sau khi chuan hoa
        $data = [];
        

        // check duplicate
        for ($i = 0; $i < count($idGrades); $i++) { 
            $count = Assign::where('id_grade', $idGrades[$i])
                           ->where('id_subject', $idSubjects[$i])
                           // ->where('id_teacher', $idTeachers[$i])
                           ->count();

        	// them STT cua hang vao mang loi neu ton tai ban ghi cua hang do trong DB
            if ($count > 0) { 
            	$rowErrors[] = $i; 
            }

            // data
        }

        if (count($rowErrors) > 0) {
        	return [
        		"status" => "error",
        		"message" => "Phân công đã tồn tại hoặc môn này đã được dạy bởi giáo viên khác",
        		"rowErrors" => $rowErrors
        	];
        }

        return [
        	"status" => "success"
        ];
    }

    // format data
    public function formatData($request)
    {
		$idGrades   = $request->id_grade;
		$idSubjects = $request->id_subject;
		$idTeachers = $request->id_teacher;
		$startAt    = $request->start_at;

        $data = [];

        for ($i = 0; $i < count($idGrades); $i++) { 
           $row = [
				'id_grade'   => $idGrades[$i],
				'id_subject' => $idSubjects[$i],
				'id_teacher' => $idTeachers[$i],
				'start_at'   => Carbon::parse($startAt[$i])->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            $data[] = $row;
        }

        return $data;
    }
    
}