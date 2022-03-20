<?php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Trang chủ', route('admin.dashboard'));
});

Breadcrumbs::for('teacher.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Trang chủ', route('teacher.dashboard'));
});

// ========================== manager admin ====================================
// Admin > List Admin
Breadcrumbs::for('admin.admin-manager.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách giáo vụ', route('admin.admin-manager.index'));
});

// Admin > Create
Breadcrumbs::for('admin.admin-manager.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.admin-manager.index');
    $trail->push('Thêm giáo vụ', route('admin.admin-manager.create'));
});

// Admin > List Admin > Import
Breadcrumbs::for('admin.admin-manager.form_import', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.admin-manager.index');
    $trail->push('Import', route('admin.admin-manager.form_import'));
});

// Admin > List Admin > [Admin]
Breadcrumbs::for('admin.admin-manager.show', function (BreadcrumbTrail $trail, $admin) {
    $trail->parent('admin.admin-manager.index');
    $trail->push($admin->name, route('admin.admin-manager.show', $admin));
});

// ============================== manager teacher ==============================
// Giáo vụ > Danh sách giảng viên
Breadcrumbs::for('admin.teacher-manager.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách giảng viên', route('admin.teacher-manager.index'));
});

// Giáo vụ > Danh sách giảng viên > Thêm giảng viên
Breadcrumbs::for('admin.teacher-manager.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teacher-manager.index');
    $trail->push('Thêm giảng viên', route('admin.teacher-manager.create'));
});

// Giáo vụ > Danh sách giảng viên > Import
Breadcrumbs::for('admin.teacher-manager.form_import', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teacher-manager.index');
    $trail->push('Import', route('admin.teacher-manager.form_import'));
});

// Giáo vụ > Danh sách giảng viên > Tên giảng viên 
Breadcrumbs::for('admin.teacher-manager.show', function (BreadcrumbTrail $trail, $teacher) {
    $trail->parent('admin.teacher-manager.index');
    $trail->push($teacher->name, route('admin.teacher-manager.show', $teacher));
});

// ============================== assign  ==============================
// Giáo vụ > Danh sách phân công
Breadcrumbs::for('admin.assign.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách phân công', route('admin.assign.index'));
});

// Giáo vụ > Danh sách phân công  > Thêm phân công 
Breadcrumbs::for('admin.assign.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.assign.index');
    $trail->push('Thêm phân công', route('admin.assign.create'));
});

// ============================== schedule ==============================
// Giáo vụ > Quản lý lịch học
Breadcrumbs::for('admin.schedule.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Quản lý lịch học', route('admin.schedule.index'));
});

// Giáo vụ > Quản lý lịch học > Thêm lịch học
Breadcrumbs::for('admin.schedule.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.schedule.index');
    $trail->push('Thêm lịch học', route('admin.schedule.create'));
});

// Giáo vụ > Quản lý lịch học > Tất cả lịch học
Breadcrumbs::for('admin.schedule.indexAll', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.schedule.index');
    $trail->push('Tất cả lịch học', route('admin.schedule.indexAll'));
});

// Giáo vụ > Quản lý lịch học > Lịch học theo lớp
Breadcrumbs::for('admin.schedule.indexClass', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.schedule.index');
    $trail->push('Lịch học theo lớp', route('admin.schedule.indexClass'));
});

// Giáo vụ > Quản lý lịch học > Lịch học theo giảng viên
Breadcrumbs::for('admin.schedule.indexTeacher', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.schedule.index');
    $trail->push('Lịch học theo giảng viên', route('admin.schedule.indexTeacher'));
});

// Giáo vụ > Quản lý lịch học > Sửa lịch học
Breadcrumbs::for('admin.schedule.edit', function (BreadcrumbTrail $trail, $asign) {
    $trail->parent('admin.schedule.index');
    $trail->push('Sửa lịch học', route('admin.schedule.edit', $asign));
});

// ============================== grade ==============================
// Giáo vụ > Danh sách lớp học
Breadcrumbs::for('admin.grade.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách lớp học', route('admin.grade.index'));
});

// Giáo vụ > Danh sách lớp học > Thêm lớp học
Breadcrumbs::for('admin.grade.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.grade.index');
    $trail->push('Thêm lớp học', route('admin.grade.create'));
});

// Giáo vụ > Danh sách lớp học > Sửa lớp học 
Breadcrumbs::for('admin.grade.edit', function (BreadcrumbTrail $trail, $grade) {
    $trail->parent('admin.grade.index');
    $trail->push('Sửa lớp học', route('admin.grade.edit', $grade));
});

// ============================== subject ==============================
// Giáo vụ > Danh sách môn học
Breadcrumbs::for('admin.subject.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách môn học', route('admin.subject.index'));
});

// Giáo vụ > Danh sách môn học > Thêm môn học
Breadcrumbs::for('admin.subject.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.subject.index');
    $trail->push('Thêm môn học', route('admin.subject.create'));
});

// ============================== yearschool ==============================
// Giáo vụ > Danh sách khoá học
Breadcrumbs::for('admin.yearschool.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách khoá học', route('admin.yearschool.index'));
});

// Giáo vụ > Danh sách khoá học > Sửa khoá học 
Breadcrumbs::for('admin.yearschool.edit', function (BreadcrumbTrail $trail, $yearschool) {
    $trail->parent('admin.yearschool.index');
    $trail->push('Sửa khoá học', route('admin.yearschool.edit', $yearschool));
});

// ============================== manager student ==============================
// Giáo vụ > Danh sách sinh viên
Breadcrumbs::for('admin.student-manager.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách sinh viên', route('admin.student-manager.index'));
});

// Giáo vụ > Danh sách sinh viên > Thêm sinh viên
Breadcrumbs::for('admin.student-manager.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.student-manager.index');
    $trail->push('Thêm sinh viên', route('admin.student-manager.create'));
});

// Giáo vụ > Danh sách sinh viên > Import
Breadcrumbs::for('admin.student-manager.form_import', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.student-manager.index');
    $trail->push('Import', route('admin.student-manager.form_import'));
});

// Giáo vụ > Danh sách sinh viên > Tên sinh viên 
Breadcrumbs::for('admin.student-manager.show', function (BreadcrumbTrail $trail, $student) {
    $trail->parent('admin.student-manager.index');
    $trail->push($student->name, route('admin.student-manager.show', $student));
});

// ============================== classroom ==============================
// Giáo vụ > Danh sách phòng học
Breadcrumbs::for('admin.classroom.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Danh sách phòng học', route('admin.classroom.index'));
});

// Giáo vụ > Danh sách phòng học > Thêm phòng học
Breadcrumbs::for('admin.classroom.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.classroom.index');
    $trail->push('Thêm phòng học', route('admin.classroom.create'));
});

// Giáo vụ > Danh sách phòng học > Sửa phòng học 
Breadcrumbs::for('admin.classroom.edit', function (BreadcrumbTrail $trail, $classroom) {
    $trail->parent('admin.classroom.index');
    $trail->push('Sửa phòng học', route('admin.classroom.edit', $classroom));
});

// ============================== statistic ==============================
// Giáo vụ > Thống kê
Breadcrumbs::for('admin.statistic.attendance', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Thống kê', route('admin.statistic.attendance'));
});

// ============================== profile ==============================
// Giáo vụ > Thông tin của tôi
Breadcrumbs::for('profile.show', function (BreadcrumbTrail $trail) {
    if (\Auth::guard('admin')->check()) {
        $trail->parent('admin.dashboard');
    }

    if (\Auth::guard('teacher')->check()) {
        $trail->parent('teacher.dashboard');
    }
    $trail->push('Thông tin của tôi', route('profile.show'));
});

// ============================== attendance ==============================
// Trang chủ > Tạo điểm danh
Breadcrumbs::for('teacher.attendance.create', function (BreadcrumbTrail $trail) {
    $trail->parent('teacher.dashboard');
    $trail->push('Tạo điểm danh', route('teacher.attendance.create'));
});

// Trang chủ > Xem điểm danh 
Breadcrumbs::for('teacher.attendance.history', function (BreadcrumbTrail $trail) {
    $trail->parent('teacher.dashboard');
    $trail->push('Xem điểm danh', route('teacher.attendance.history'));
});

// ============================== work ==============================
// Trang chủ > Quản lý dạy
Breadcrumbs::for('teacher.work.assign', function (BreadcrumbTrail $trail) {
    $trail->parent('teacher.dashboard');
    $trail->push('Quản lý dạy', route('teacher.work.assign'));
});

// Trang chủ > Xem điểm danh 
Breadcrumbs::for('teacher.work.schedule', function (BreadcrumbTrail $trail) {
    $trail->parent('teacher.dashboard');
    $trail->push('Lịch dạy', route('teacher.work.schedule'));
});