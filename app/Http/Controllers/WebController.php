<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Illuminate\Http\Request|null $request
     * @return void
     */
    public function frontend(?Request $request)
    {
        return view('frontend');
    }

    public function backend(?Request $request)
    {
        return view('backend');
    }
}
