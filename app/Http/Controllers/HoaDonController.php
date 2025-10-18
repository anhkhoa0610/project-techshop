<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    public function index()
    {
        return view('ui-hoadon.hoadon');
    }

    

}
