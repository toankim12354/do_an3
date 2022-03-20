<?php

namespace App\Imports\Excel;

use App\Models\Grade;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class StudentsImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows
{
    use Importable, SkipsFailures;

    private $grades;

    public function __construct()
    {
       $this->grades = Grade::all();
    }

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

        //code
        $code = $row['ma_sinh_vien'];

        // id_grade
        $nameGrade = $row['lop'];
        $idGrade = $this->grades->where('name', $nameGrade)->first()->id ?? null;

        if ($idGrade === null) {
            return null;
        }

        return new Student([
            'name'     => $name,
            'code'     => $code,
            'dob'      => $dob,
            'phone'    => $phone,
            'gender'   => $gender,
            'address'  => $address,
            'email'    => $email,
            'id_grade' => $idGrade
        ]);
    }

    public function rules() : array
    {
        return [
            '*.ma_sinh_vien' => 'required|unique:students,code',
            '*.ho_ten'       => 'required|min:3',
            '*.ngay_sinh'    => 'required',
            '*.dien_thoai'   => 'required|regex:/^[0-9]{10}$/|unique:students,phone',
            '*.gioi_tinh'    => 'required',
            '*.dia_chi'      => 'required',
            '*.email'        => 'required|email|unique:students,email',
            '*.lop'          => 'required'
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.ma_sinh_vien.required' => 'Mã sinh viên trống',
            '*.ma_sinh_vien.unique'   => 'Mã sinh viên đã tồn tại',
            '*.ho_ten.required'       => 'Họ tên trống',
            '*.ho_ten.min'            => 'Họ tên quá ngắn',
            '*.dien_thoai.regex'      => 'Số điện thoại định dạng không đúng',
            '*.dien_thoai.required'   => 'Số điện thoại trống',
            '*.dien_thoai.unique'     => 'Số điện thoại đã tồn tại',
            '*.email.required'        => 'Email trống',
            '*.email.email'           => 'Email sai định dạng',
            '*.email.unique'          => 'Email đã tồn tại',
            '*.ngay_sinh.required'    => 'Ngày sinh trống',
            '*.ngay_sinh.unique'      => 'Ngày sinh sai định dạng',
            '*.gioi_tinh.required'    => 'Giới tính trống',
            '*.dia_chi.required'      => 'Địa chỉ trống',
            '*.lop.required'          => 'Lớp trống',
        ];
    }
}
