<div class="sidebar" data-active-color="green" data-background-color="black" data-image="{{ asset('assets/img/faces/crad-2.jpeg') }}">
            <!--
        Tip 1: You can change the color of active element of the sidebar using: data-active-color="purple | blue | green | orange | red | rose"
        Tip 2: you can also add an image using data-image tag
        Tip 3: you can change the color of the sidebar with data-background-color="white | black"
    -->
    <div class="sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="{{ asset('assets/img/faces/logo2.jpeg') }}" />
            </div>
            <div class="info">
                <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                    <span>
                        @if (Auth::check())
                            {{ Auth::user()->name }}
                        @endif
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="clearfix"></div>
                <div class="collapse" id="collapseExample">
                    <ul class="nav">
                        <li>
                            <a href="{{ route('profile.show') }}">
                                <span class="sidebar-mini"> MP </span>
                                <span class="sidebar-normal"> Tài khoản của tôi </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}">
                                <span class="sidebar-mini">
                                    <i class="fas fa-sign-out-alt"></i>
                                </span>
                                <span class="sidebar-normal"> Đăng xuất </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav">
            {{-- function of admin --}}
            @auth('admin')
                {{-- dashboard --}}
                <li class="{{ request()->is('admin') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="material-icons">dashboard</i>
                        <p> Dashboard </p>
                    </a>
                </li>

                {{-- admin --}}
                @can('admin-manager')
                    <li class="{{  request()->is('admin/admin-manager')
                        || request()->is('admin/admin-manager/*')
                        ? 'active' : ''  }}">
                        <a href="{{ route('admin.admin-manager.index') }}">
                            <i class="fas fa-tasks"></i>
                            <p>
                                Quản lý Giáo vụ
                            </p>
                        </a>
                    </li>
                @endcan

                {{-- teacher --}}
                <li class="{{
                    request()->is('admin/teacher-manager')
                    || request()->is('admin/teacher-manager/*')
                    ? 'active' : ''
                }}">
                    <a href="{{ route('admin.teacher-manager.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>
                            Quản lý Giảng viên
                        </p>
                    </a>
                </li>

                {{-- assign --}}
                <li class="{{ request()->is('admin/assign') || request()->is('admin/assign/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.assign.index') }}">
                        <i class="far fa-address-book"></i>
                        <p>
                            Phân công giảng dạy
                        </p>
                    </a>
                </li>

                {{-- schedule --}}
                <li class="{{ request()->is('admin/schedule') || request()->is('admin/schedule*') ? 'active' : '' }}">
                    <a href="{{ route('admin.schedule.index') }}">
                        <i class="fas fa-school"></i>
                        <p>
                            Quản lý Lịch học
                        </p>
                    </a>
                </li>

                {{-- lesson --}}
                <li class="{{ request()->is('admin/lesson') || request()->is('admin/lesson/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.lesson.index') }}">
                        <i class="fas fa-clock"></i>
                        <p>
                            Quản lý ca học
                        </p>
                    </a>
                </li>

                {{-- year schools --}}
                <li class="{{ request()->is('admin/yearschool') || request()->is('admin/yearschool/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.yearschool.index') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <p>
                            Quản lý Khóa học
                        </p>
                    </a>
                </li>

                {{-- grade --}}
                <li class="{{ request()->is('admin/grade') || request()->is('admin/grade/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.grade.index') }}">
                        <i class="fas fa-book-reader"></i>
                        <p>
                            Quản lý Lớp học
                        </p>
                    </a>
                </li>

                {{-- subject --}}
                <li class="{{ request()->is('admin/subject') || request()->is('admin/subject/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subject.index') }}">
                        <i class="fas fa-book"></i>
                        <p>
                            Quản lý môn học
                        </p>
                    </a>
                </li>

                {{-- student --}}
                <li class="{{  request()->is('admin/student-manager')
                    || request()->is('admin/student-manager/*')
                    ? 'active' : ''  }}">
                    <a href="{{ route('admin.student-manager.index') }}">
                        <i class="fas fa-user-graduate"></i>
                        <p>
                            Quản lý Sinh viên
                        </p>
                    </a>
                </li>

                {{-- class room --}}
                <li class="{{ request()->is('admin/classroom') || request()->is('admin/classroom/*') ? 'active' : '' }}">
                    <a href="{{ route('admin.classroom.index') }}">
                        <i class="material-icons">house</i>
                        <p>
                            Quản lý phòng học
                        </p>
                    </a>
                </li>

                {{-- statistical --}}
                <li class="{{ request()->is('admin/statistic/attendance') ? 'active' : '' }}">
                    <a href="{{ route('admin.statistic.attendance') }}">
                        <i class="fas fa-chart-line"></i>
                        <p>
                            Thống kê
                        </p>
                    </a>
                </li>
            @endauth

            {{-- function of teacher --}}
            @auth('teacher')
            {{-- dashboard --}}
            <li class="{{ request()->is('teacher') ? 'active' : '' }}">
                <a href="{{ route('teacher.dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <p> Dashboard </p>
                </a>
            </li>

            {{-- attendance --}}
           <li>
                <a data-toggle="collapse" href="#attendance">
                    <i class="fas fa-tasks"></i>
                    <p> Điểm danh
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="attendance">
                    <ul class="nav">
                        <li class="{{ request()->is('teacher/attendance/create') ? 'active' : '' }}">
                            <a href="{{ route('teacher.attendance.create') }}">
                                <i class="far fa-calendar-plus"></i>
                                <span class="sidebar-normal"> Tạo điểm danh </span>
                            </a>
                        </li>
                        <li class="{{ request()->is('teacher/attendance/history') ? 'active' : '' }}">
                            <a href="{{ route('teacher.attendance.history') }}">
                                <i class="far fa-list-alt"></i>
                                <span class="sidebar-normal"> Xem điểm danh </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Work --}}
            <li>
                <a data-toggle="collapse" href="#assignMenu">
                    <i class="fas fa-briefcase"></i>
                    <p> Công việc
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="assignMenu">
                    <ul class="nav">
                        <li class="{{ request()->is('teacher/work/assign') ? 'active' : '' }}">
                            <a href="{{ route('teacher.work.assign') }}">
                                <i class="far fa-chart-bar"></i>
                                <span class="sidebar-normal">
                                  Quản lý dạy
                                </span>
                            </a>
                        </li>

                        <li class="{{ request()->is('teacher/work/schedule') ? 'active' : '' }}">
                            <a href="{{ route('teacher.work.schedule') }}">
                                <i class="far fa-calendar-alt"></i>
                                <span class="sidebar-normal">
                                  Lịch dạy
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @endauth

        </ul>
    </div>
</div>
