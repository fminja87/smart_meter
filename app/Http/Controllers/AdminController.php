<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

   public function profile(){

        return view('admin.profile');
   }

    /**
     * @throws ValidationException
     */
    public function updateProfile(Request $request){
        $this->validate($request,[
            'full_name'=>'required',
            'email'=>'required|email'
        ]);

        $admin = Admin::find(Auth::user()->id)->update(['name'=>$request->full_name,'email'=>$request->email]);

        return redirect()->back()->with('success','Profile info update successful');
   }

    /**
     * @throws ValidationException
     */
    public function updatePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'admin_old_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->admin_old_password, $hashedPassword)) {

            $user = Admin::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Congratulations,Your password is successful changed');
        }

        return redirect()->back()->with('error', 'Ooooops!,Something went wrong(may be the old password is wrong)');
    }
}
