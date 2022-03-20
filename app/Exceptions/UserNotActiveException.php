<?php

namespace App\Exceptions;

use App\Exceptions\ForbiddenException;

class UserNotActiveException extends ForbiddenException
{
    // public function render($request)
    // {
    //     $url = route('error.403');
    //     $request->session()->flash('message', $this->getMessage());

    //     if ($request->ajax()) {
    //         return response()->json(['url' => $url], 403);
    //     }

    //     return redirect()->guest($url);
    // }
}
