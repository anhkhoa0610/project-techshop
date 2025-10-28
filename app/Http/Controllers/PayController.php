<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class PayController extends Controller
{
    public function index()
    {
         $user = 1; // Lấy thông tin người dùng hiện tại
        return view('ui-thanhtoan.pay', compact('user'));
    }
 



}
