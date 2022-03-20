<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Http\Requests\SubjectRequest;

class SubjectController extends Controller
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
        $subjects = Subject::paginate(8);

        return view('subjects.index')->with('subjects', $subjects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectRequest $request)
    {
        //
        $subject = new Subject();
        $subject->name = $request->subject;
        $subject->duration = $request->time;

        try {
            $subject->save();
            $this->message['content'] = "Create Success";
            $this->message['status'] = true;

        } catch (Exception $e) {
            $this->message['content'] = "Create Error";
            $this->message['status'] = false;
        }

        return redirect()->route('admin.subject.index')->with('message', $this->message);
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
    public function edit(Subject $subject)
    {
        //
        return view('subjects.edit')->with('subject', $subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectRequest $request, Subject $subject)
    {
        //
        $subject->name = $request->subject;
        $subject->duration = $request->time;
        try {
            $subject->save();
            $this->message['content'] = "Update Success";
            $this->message['status'] = true;
        } catch (Exception $e) {
            $this->message['content'] = "Update Error";
            $this->message['status'] = false;
        }

        return redirect()->route('admin.subject.index')->with('message', $this->message);
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
        $subject = Subject::find($id);
        if (count($subject->assigns)) {
            $this->message['content'] = "Can't delete this subject";
            $this->message['status'] = false;
        } else {
            try {
                Subject::destroy($id);
                $this->message['content'] = "Delete Success";
                $this->message['status'] = true;
            } catch (Exception $e) {
                $this->message['content'] = "Delete Success";
                $this->message['status'] = false;
            }

        }

        return redirect()->route('admin.subject.index')->with('message', $this->message);
    }
}
