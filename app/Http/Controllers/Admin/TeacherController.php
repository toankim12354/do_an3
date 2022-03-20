<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Excel\TeachersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherStoreFormRequest;
use App\Http\Requests\TeacherUpdateFormRequest;
use App\Imports\Excel\TeachersImport;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    private TeacherService $service;

    public function __construct(TeacherService $service)
    {
        // ngan khong luu flash session vao cache
        // $this->middleware('preventCache');

        // teacher service
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

        $teachers = Teacher::paginate($rowPerPage);

        return view('admins.teachers.index')
                    ->with(['teachers' => $teachers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admins.teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherStoreFormRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        try {
            Teacher::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('admin.teacher-manager.index')
                             ->with('error', 'Create failed');
        }

        return redirect()->route('admin.teacher-manager.index')
                             ->with('success', 'Create successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher_manager)
    {
        return view('admins.teachers.show')->with('teacher', $teacher_manager);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherUpdateFormRequest $request, Teacher $teacher_manager)
    {
        $validated = $request->validated();

        // if teacher are teaching then do not disable
        if ($validated['status'] == '0' && $teacher_manager->hasAssign(1)) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'cannot disable teacher while they are teaching');
        }

        try {
            $teacher_manager->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('admin.teacher-manager.index')
                             ->with('error', 'Update failed');
        }

        return redirect()->route('admin.teacher-manager.index')
                         ->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher_manager)
    {
        // if teacher teached do not delete
        if ($teacher_manager->hasAssign()) {
            return redirect()->route('admin.teacher-manager.index')
                             ->with('error', 'Cannot delete Teacher when they teached');
        }

        try {
            $teacher_manager->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.teacher-manager.index')
                             ->with('error', 'Delete failed');
        }

        return redirect()->route('admin.teacher-manager.index')
                         ->with('success', 'Delete successfully');
    }

    public function showFormImport()
    {
        return view('admins.teachers.import');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx']);

        $import = new TeachersImport;
        try {
            $import->import($request->file);
        } catch (\Exception $e) {
            var_dump($e);
        }

        $failures = $import->failures();

        if ($failures->count()) {
            return redirect()->back()->with('failures', $failures);
        }

        return redirect()->back()->with('success', 'import successfully');
    }

    public function exportExcel()
    {
        return (new TeachersExport)->download('list_teacher_' . time() . '.xlsx');
    }
    /**
     * [search description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    // public function search($request, $rowPerPage)
    // {
    //     // query builder
    //     $query = Teacher::select('*');
    //     $rowPerPage = $request->row ?? $rowPerPage;

    //     // gender filter
    //     if (isset($request->gender)) {
    //         $query->where('gender', $request->gender);
    //     }

    //     // status filter
    //     if (isset($request->status)) {
    //         $query->where('status', $request->status);
    //     }

    //     // search: name, email, address, phone
    //     if (isset($request->search)) {
    //         $query->where(function($subQuery) use($request) {
    //             $search = $request->search;

    //             $subQuery->where('name', 'LIKE', "%$search%")
    //                      ->orWhere('email', 'LIKE', "%$search%")
    //                      ->orWhere('address', 'LIKE', "%$search%")
    //                      ->orWhere('phone', 'LIKE', "%$search%");
    //         });
    //     }

    //     // get list of teachers match with key
    //     $teachers = $query->paginate($rowPerPage);

    //     $html = view('admins.teachers.load_index')
    //             ->with(['teachers' => $teachers])
    //             ->render();

    //     return response()->json(['html' => $html], 200);
    // }
}
