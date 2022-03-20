<?php

namespace App\Http\Controllers;

use App\Http\Requests\YearSchoolRequest;
use App\Models\YearSchool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *
 */
class YearSchoolController extends Controller
{
    /**
     * @var
     */
    protected $message;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $yearSchools = YearSchool::paginate(8);

        return view('yearschools.index')->with('yearSchools', $yearSchools);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(YearSchoolRequest $request)
    {
        //
        $yearSchool = new YearSchool();
        $yearSchool->name = $request->yearschool;
        try {
            $yearSchool->save();
            $this->message['content'] = "Create Success";
            $this->message['status'] = 1;
        } catch (Exception $e) {
            $this->message['content'] = "Create Error";
            $this->message['status'] = 0;
        }

        return redirect()->route('admin.yearschool.index')->with('message', $this->message);
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
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $yearSchool = YearSchool::find($id);

        return view('yearschools.edit')->with('yearschool', $yearSchool);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param YearSchoolRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(YearSchoolRequest $request, int $id): RedirectResponse
    {
        //
        $yearSchool = YearSchool::find($id);
        $yearSchool->name = $request->yearschool;
        try {
            $yearSchool->save();
            $this->message['content'] = "Update Success";
            $this->message['status'] = 1;
        } catch (Exception $e) {
            $this->message['content'] = "Update Error";
            $this->message['status'] = 0;
        }

        return redirect()->route('admin.yearschool.index')->with('message', $this->message);
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
        $yearSchool = YearSchool::find($id);

        if (count($yearSchool->grades)) {
            $this->message['content'] = "Can't delete this year school";
            $this->message['status'] = 0;
        } else {
            try {
                $yearSchool->delete();
                $this->message['content'] = "Delete Success";
                $this->message['status'] = 1;
            } catch (Exception $e) {
                $this->message['content'] = "Detele Error";
                $this->message['status'] = 0;
            }
        }

        return redirect()->route('admin.yearschool.index')->with('message', $this->message);
    }
}
