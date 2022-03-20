<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\YearSchool;
use App\Http\Requests\GradeRequest;

class GradeController extends Controller
{
    protected $message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dataGrades = Array();
        $yearSchools = YearSchool::orderByRaw("CAST(substring(name, 2) AS UNSIGNED)")->paginate(6);

        return view('grades.index')->with('yearSchools', $yearSchools);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $YearSchools = YearSchool::all();

        return view('grades.create')->with('yearschools', $YearSchools);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GradeRequest $request)
    {
        //
        $grade = new Grade();
        $grade->name = $request->grade;
        $grade->id_year_school = $request->idyearschool;
        try {
            $grade->save();
            $this->message['content'] = "Create Success";
            $this->message['status'] = 1;
        } catch (Exception $e) {
            $this->message['content'] = "Create Error";
            $this->message['status'] = 0;
        }

        return redirect()->route('admin.grade.index')->with('message', $this->message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $yearSchools = YearSchool::all();
        $grade = Grade::find($id);

        return view('grades.edit', [
            'grade' => $grade,
            'yearschools' => $yearSchools
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GradeRequest $request, $id)
    {
        //
        $grade = Grade::find($id);
        $grade->id_year_school = $request->idyearschool;
        $grade->name = $request->grade;
        try {
            $grade->save();
            $this->message['content'] = "Update Success";
            $this->message['status'] = 1;
        } catch (Exception $e) {
            $this->message['content'] = "Update Error";
            $this->message['status'] = 0;
        }

        return redirect()->route('admin.grade.index')->with('message', $this->message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $grade = Grade::find($id);

        if (count($grade->students) || count($grade->assigns)) {
            $this->message['content'] = "Can't delete this grade";
            $this->message['status'] = 0;
        } else {
            try {
                Grade::destroy($id);
                $this->message['content'] = "Delete Success";
                $this->message['status'] = 1;
            } catch (Exception $e) {
                $this->message['content'] = "Delete Error";
                $this->message['status'] = 0;
            }
        }

        return redirect()->route('admin.grade.index')->with('message', $this->message);
    }
}
