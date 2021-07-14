<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

   public function home(){

       return view('admin.dashboard');
   }

   public function showCustomers(){

        $customers = User::select('*')->orderBy('id','DESC')->get();
        return view('admin.customers',['customers'=>$customers]);
   }
}
