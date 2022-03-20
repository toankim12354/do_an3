<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;

class LessonController extends Controller
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
        $lessons = Lesson::paginate(8);

        return view('lessons.index')->with('lessons', $lessons);
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
    public function store(LessonRequest $request)
    {
        //
        $lesson = new Lesson();
        $lesson->start = $request->start;
        $lesson->end = $request->end;

        try {
            $lesson->save();
            $this->message['content'] = "Create Success";
            $this->message['status'] = true;
        } catch (Exception $e) {
            $this->message['content'] = "Create Error";
            $this->message['status'] = false;
        }

        return redirect()->route('admin.lesson.index')->with('message', $this->message);
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
    public function edit(lesson $lesson)
    {
        //
        return view('lessons.edit')->with('lesson', $lesson);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LessonRequest $request, lesson $lesson)
    {
        //
        $lesson->start = $request->start;
        $lesson->end = $request->end;

        try {
            $lesson->save();
            $this->message['content'] = "Update Success";
            $this->message['status'] = true;
        } catch (Exception $e) {
            $this->message['content'] = "Update Error";
            $this->message['status'] = false;
        }

        return redirect()->route('admin.lesson.index')->with('message', $this->message);
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
        try {
            lesson::destroy($id);
            $this->message['content'] = "Delete Success";
            $this->message['status'] = true;
        } catch (Exception $e) {
            $this->message['content'] = "Delete Error";
            $this->message['status'] = false;
        }

        return redirect()->route('admin.lesson.index')->with('message', $this->message);
    }
}
