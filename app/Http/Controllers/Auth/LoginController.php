<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        // chua dang nhap ? tiep tuc : dashboard
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:teacher')->except('logout');
    }

    // hien thi form dang nhap cua admin
    public function showAdminLoginForm()
    {
        return view('auth.admin.login');
    }

    // hien thi trang dang nhap cua co giao
    public function showTeacherLoginForm()
    {
        return view('auth.login');
    }

    /**
     * [xu ly dang nhap cua admin]
     * @param  LoginRequest $request [form request cua form dang nhap]
     * @return [] [
     * xac thuc thanh cong: 
     *     + luu thong tin dang nhap neu duoc chon
     *     + redirect -> trang chu
     * xac thuc khong thanh cong redirect ve form dang nhap cua admin + thong 
     * bao loi
     * ]
     */
    public function adminLogin(LoginRequest $request)
    {
        $validated = $request->validated();
        $remember = $request->has('remember');
        
        if (Auth::guard('admin')->attempt($validated, $remember)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()
            ->route('login.admin')
            ->with('msg', 'Tên đăng nhập hoặc mật khẩu không chính xác');
    }


    /**
     * [xu ly dang nhap cua teacher]
     * [tuong tu admin]
     */
    public function teacherLogin(LoginRequest $request)
    {
        $validated = $request->validated();
        $remember = $request->has('remember');
        
        if (Auth::guard('teacher')->attempt($validated, $remember)) {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()
            ->route('login.teacher')
            ->with('msg', 'Tên đăng nhập hoặc mật khẩu không chính xác');
    }

    /**
     * [xu ly dang xuat]
     * [
     * kiem tra loai nguoi dung dang dang nhap -> dang xuat -> redirect ve 
     * trang dang nhap tuong ung. 
     * neu khong co tim thay loai nguoi dung trong danh sach guard:
     * redirect -> welcome
     * ]
     * @return [type] [description]
     */
    public function logout()
    {
        $guards = ['admin', 'teacher'];

        foreach ($guards as $key => $guard) {
            if (Auth::guard($guard)->check()) {
               Auth::guard($guard)->logout();
               Session::flush();
               return $this->redirectAfterLoggedOut($guard);
            }
        }

        return redirect()->route('home');
    }

    // redirect den trang dang nhap tuong ung sau khi dang xuat
    public function redirectAfterLoggedOut($guard)
    {
        switch ($guard) {
            case 'admin':
                return redirect()->route('login.admin');
                break;
            
            default:
                return redirect()->route('login.teacher');
                break;
        }
    }
}
