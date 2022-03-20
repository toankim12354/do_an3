<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $counts = [
            "students" => Student::count(),
            "teachers" => Teacher::count(),
            "classrooms" => ClassRoom::count(),
            "grades" => Grade::count(),
        ];

        return view('admins.dashboard', ["counts" => $counts]);
    }
}
