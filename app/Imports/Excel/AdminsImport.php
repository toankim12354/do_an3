<?php

namespace App\Imports\Excel;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminsImport implements 
    ToModel, 
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows
{
    use Importable, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // name
        $name = $row['ho_ten'];
        
        // dob
        $dob = transformDate($row['ngay_sinh']);

        if ($dob === null) {
            return null;
        } 

        $dob = $dob->format('Y-m-d');
        // dd($dob);

        // gender
        $gender = strtolower(trim($row['gioi_tinh']));
        switch ($gender) {
            case 'nam':
            case 'male':
                $gender = 1;
                break;

            case 'nữ':
            case 'female':
                $gender = 0;
                break;
            
            default:
               $gender = 1;
                break;
        }

        // phone
        $phone = $row['dien_thoai'];

        // address
        $address = $row['dia_chi'];

        // email
        $email = $row['email'];

        // password
        // dd($row['mat_khau']);
        $password = Hash::make($row['mat_khau']);


        // dd($name, $dob, $gender, $phone, $address, $email, $password);

        return new Admin([
            'name'     => $name,
            'dob'      => $dob,
            'gender'   => $gender,
            'phone'    => $phone,
            'address'  => $address,
            'email'    => $email,
            'password' => $password,
        ]);
    }

    public function rules() : array
    {
        return [
            '*.ho_ten'     => 'required|min:3',
            '*.ngay_sinh'  => 'required',
            '*.dien_thoai' => 'required|regex:/^[0-9]{10}$/|unique:admins,phone',
            '*.gioi_tinh'  => 'required',
            '*.dia_chi'    => 'required',
            '*.email'      => 'required|email|unique:admins,email',
            '*.mat_khau'   => 'required|regex:/^.{8,32}$/',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.ho_ten.required'     => 'Họ tên trống',
            '*.ho_ten.min'          => 'Họ tên quá ngắn',
            '*.dien_thoai.regex'    => 'Số điện thoại định dạng không đúng',
            '*.dien_thoai.required' => 'Số điện thoại trống',
            '*.dien_thoai.unique'   => 'Số điện thoại đã tồn tại',
            '*.email.required'      => 'Email trống',
            '*.email.email'         => 'Email sai định dạng',
            '*.email.unique'        => 'Email đã tồn tại',
            '*.ngay_sinh.required'  => 'Ngày sinh trống',
            '*.ngay_sinh.unique'    => 'Ngày sinh sai định dạng',
            '*.gioi_tinh.required'  => 'Giới tính trống',
            '*.dia_chi.required'    => 'Địa chỉ trống',
            '*.mat_khau.required'   => 'Mật khẩu trống',
            '*.mat_khau.regex'      => 'Mật khẩu không hợp lệ',
        ];
    }
}
