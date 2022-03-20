<?php 

namespace App\Services;

use App\Models\Teacher;

/**
 * 
 */
class TeacherService
{
	/**
	 * [search description]
	 * @param  [type] $request    [description]
	 * @param  [type] $rowPerPage [description]
	 * @return [type]             [description]
	 */
	public function search($request, $rowPerPage)
    {
        // query builder
        $query = Teacher::select('*');
        $rowPerPage = $request->row ?? $rowPerPage;

        // gender filter
        if (isset($request->gender)) {
            $query->where('gender', $request->gender);
        }

        // status filter
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }

        // search: name, email, address, phone
        if (isset($request->search)) {
            $query->where(function($subQuery) use($request) {
                $search = $request->search;

                $subQuery->where('name', 'LIKE', "%$search%")
                         ->orWhere('email', 'LIKE', "%$search%")
                         ->orWhere('address', 'LIKE', "%$search%")
                         ->orWhere('phone', 'LIKE', "%$search%");
            });
        }

        // get list of teachers match with key
        $teachers = $query->paginate($rowPerPage);

        $html = view('admins.teachers.load_index')
                ->with(['teachers' => $teachers])
                ->render();

        return response()->json(['html' => $html], 200);
    }
}