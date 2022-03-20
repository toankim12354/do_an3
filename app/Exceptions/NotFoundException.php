<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function render($request)
    {
        $url = route('error.404');
        $request->session()->flash('message', $this->getMessage());

        if ($request->ajax()) {
            return response()->json(['url' => $url], 404);
        }

        return redirect()->guest($url);
    }
}
