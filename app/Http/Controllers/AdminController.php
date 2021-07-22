<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

   public function home(){

       $date = date('Y-m-d');

        $customers = User::select('*')->count('*');

        $transactions = Transaction::where('status','=', null)->count('*');

        $paid = Transaction::where('status','!=', null)->count('*');

        $today_transactions = DB::table('transactions')
            ->join('users','transactions.user_id','=','users.id')
            ->whereDate('transactions.created_at','=',$date)
            ->select('transactions.*','users.name')
            ->get();

       return view('admin.dashboard',['customers'=>$customers, 'transactions'=>$transactions, 'paid'=>$paid,'today_transactions'=>$today_transactions]);
   }

   public function showCustomers(){

        $customers = User::select('*')->orderBy('id','DESC')->get();
        return view('admin.customers',['customers'=>$customers]);
   }

   public function showCustomerBills(){

        $bills = DB::table('transactions')
            ->join('users','transactions.user_id','=','users.id')
            ->select('users.name','users.meter_number','transactions.*')
            ->get();

        return view('admin.bills',['bills'=>$bills]);
   }
}
