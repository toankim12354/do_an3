<?php

namespace App\Exceptions;

use Exception;

class CustomErrorException extends Exception
{
    public function render($request)
    {
        $url = route('error.custom');
        $request->session()->flash('message', $this->getMessage());

        if ($request->ajax()) {
            return response()->json(['url' => $url], 422);
        }

        return redirect($url);
    }
}
