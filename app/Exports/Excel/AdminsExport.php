<?php

namespace App\Exports\Excel;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class AdminsExport implements FromView, ShouldAutoSize
{   
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('admins.admins.export', [
            'admins' => Admin::all()
        ]);
    }
}
