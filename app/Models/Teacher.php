<?php

namespace App\Models;

use App\Models\Assign;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $guard = 'teacher';

    protected $fillable = [
        'name',
        'dob',
        'gender',
        'phone',
        'address',
        'email',
        'password',
        'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    // cast geder to string
    public function getGenderAttribute($value)
    {
        return $value ? "Nam" : "Nữ";
    }

    // convert Y-m-d to d-m-Y when get dob Attribute
    public function getDobAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)
                      ->format('d-m-Y');
    }

    // convert d-m-Y to Y-m-d when set dob Attribute
    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * list assign (type: object) of teacher
     * @return [type] [description]
     */
    public function assigns()
    {
        return $this->hasMany(Assign::class, 'id_teacher');
    }

    /**
     * check if teacher has assign by status if staus exist else check all
     * @return boolean [description]
     */
    public function hasAssign($status = null) : bool
    {
        if ($status === null) {
            return $this->countAssign() > 0 ? true : false;
        }

        return $this->countAssign($status) > 0 ? true : false;
    }

    /**
     * get list of assign active
     * @return [type] [description]
     */
    public function assignsActive()
    {
        return $this->getAssign(1);
    }

    /**
     * get list of assign Inactive
     * @return [type] [description]
     */
    public function assignsInactive()
    {
        return $this->getAssign(0);
    }

    /**
     * get list of assign moved for other teacher
     * @return [type] [description]
     */
    public function assignsMoved()
    {
        return $this->getAssign(2);
    }

    /**
     * get list of assign done
     * @return [type] [description]
     */
    public function assignsDone()
    {
        return $this->getAssign(3);
    }

    /**
     * count number of assign by status if status exist else count all
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    public function countAssign($status = null) : int
    {
        // count all
        if ($status === null) {
            return count($this->assigns);
        }

        // count by status
        return count($this->assigns->where('status', $status));
    }

    /**
     * get assign of teacher by status if status exist else get all
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    public function getAssign($status = null)
    {
        // get all
        if ($status === null) {
            return $this->assigns;
        }

        // get by status
        return $this->assigns->where('status', $status);
    }


    /**
     * lay danh sach cac lop ma giao vien duoc phan cong theo status cua phan cong neu co. neu khong co status thi lay het cac lop cua giao vien do
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    public function getMyGrades($status = null)
    {
        // lay het cac lop cua giao vien
        if ($status === null) {
            return Grade::whereIn('id', function($query) {
                $query->selectRaw('id_grade')
                      ->from('assigns')
                      ->whereRaw('id_teacher = ?', [$this->id]);
            })->distinct()->get();
        }

        // lay cac lop cua giao vien theo status phan cong
        return Grade::whereIn('id', function($query) use ($status) {
            $query->selectRaw('id_grade')
                  ->from('assigns')
                  ->whereRaw('status = ? and id_teacher = ?', [
                    $status, $this->id
                ]);
        })->distinct()->get();
    }

    /**
     * lay cac mon cua giao vien duoc phan cong theo status cua phan cong neu co. neu khong co status thi lay het
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    public function getMySubjects($status = null, $idGrade = null)
    {
        $where = "id_teacher = ?";
        $bind = [$this->id];

        // tao cau dieu kien va du lieu dien vao tuong ung
        if ($status === null && $idGrade === null) {
            // lay tat ca mon cua giao vien trong phan cong
            $where = $where;
            $bind = $bind;
        } elseif ($status === null) {
            // lay cac mon theo lop trong phan cong
            $where .= " and id_grade = ?";
            $bind[] = $idGrade;
        } elseif ($idGrade === null) {
            // lay cac mon theo trang thai phan cong
            $where .= " and status = ?";
            $bind[] = $status;
        } else {
            // lay cac mon theo trang thai phan cong va lop
            $where .= " and status = ? and id_grade = ?";
            $bind = [...$bind, $status, $idGrade];
        }

        // lay cac mon cua giao vien theo dieu kien
        return Subject::whereIn('id', function($query) use ($where, $bind) {
                $query->selectRaw('id_subject')
                      ->from('assigns')
                      ->whereRaw($where, $bind);
        })->distinct()->get();
    }
}
