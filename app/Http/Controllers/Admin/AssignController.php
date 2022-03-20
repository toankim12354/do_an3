<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assign\AssignStoreFormRequest;
use App\Http\Requests\Assign\AssignUpdateFormRequest;
use App\Models\Assign;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\AssignService;
use Illuminate\Http\Request;

class AssignController extends Controller
{
    private AssignService $service;
    /**
     * [__construct description]
     */
    public function __construct(AssignService $service)
    {
        $this->middleware('preventCache');

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rowPerPage = 10;

        if ($request->ajax()) {
            return $this->service->search($request, $rowPerPage);
        }

        return $this->service->getStaticDataForView($rowPerPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $grades = Grade::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        $data = [
            'grades' => $grades,
            'subjects' => $subjects,
            'teachers' => $teachers
        ];

        return view('admins.assigns.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->save($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Assign  $assign
     * @return \Illuminate\Http\Response
     */
    public function show(Assign $assign)
    {
        $grades = Grade::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        $data = [
            'grades' => $grades,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'assign' => $assign
        ];

        return view('admins.assigns.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Assign  $assign
     * @return \Illuminate\Http\Response
     */
    public function edit(Assign $assign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assign  $assign
     * @return \Illuminate\Http\Response
     */
    public function update(AssignUpdateFormRequest $request, Assign $assign)
    {
        $validated = $request->validated();

        try {
            $assign->update($validated);
        } catch (\Exception $e) {
            return redirect()
                    ->route('admin.assign.index')
                    ->with('error', 'Update failed');
        }

        return redirect()
                    ->route('admin.assign.index')
                    ->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assign  $assign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assign $assign)
    {
        try {
            if (count($assign->attendances) == 0 
                && count($assign->schedules) == 0) {
               $assign->delete();
            } else {
                return redirect()->back()->with('error', 'khong the xoa');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'da xoa');
    }
}
