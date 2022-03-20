<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Http\Requests\ClassroomRequest;

class ClassroomController extends Controller
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
        $classrooms = ClassRoom::paginate(8);

        return view('classrooms.index')->with('classrooms', $classrooms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomRequest $request)
    {
        //
        $classroom = new ClassRoom();
        $classroom->name = $request->classroom;

        try {
            $classroom->save();
            $this->message['content'] = "Create Success";
            $this->message['status'] = true;
        } catch (Exception $e) {
            $this->message['content'] = "Create Error";
            $this->message['status'] = false;
        }

        return redirect()->route('admin.classroom.index')->with('message', $this->message);
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
    public function edit(ClassRoom $classroom)
    {
        //
        return view('classrooms.edit')->with('classroom', $classroom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClassroomRequest $request, ClassRoom $classroom)
    {
        //try
        $classroom->name = $request->classroom;
        try {
            $classroom->save();
            $this->message['content'] = "Update Success";
            $this->message['status'] = true;
        } catch (Exception $e) {
            $this->message['content'] = "Update Error";
            $this->message['status'] = false;
        }

        return redirect()->route('admin.classroom.index')->with('message', $this->message);
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
        $classroom = ClassRoom::find($id);
        if (count($classroom->schedules)) {
            $this->message['content'] = "Can't delete this class room";
            $this->message['status'] = false;
        } else {
            try {
                ClassRoom::destroy($id);
                $this->message['content'] = "Delete success";
                $this->message['status'] = true;
            } catch (Exception $e) {
                $this->message['content'] = "Delete Error";
                $this->message['status'] = false;
            }
        }

        return redirect()->route('admin.classroom.index')->with('message', $this->message);
    }
}
