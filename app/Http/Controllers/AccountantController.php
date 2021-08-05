<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\Models\Bill_voucher;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:accountant');
    }

    public function home(){

        $customers = User::select('*')->count('*');

        $transactions = Transaction::where('status','=', null)->count('*');

        $paid = Transaction::where('status','!=', null)->count('*');

        return view('accountant.home',['customers'=>$customers, 'transactions'=>$transactions, 'paid'=>$paid]);
    }

    public function showCustomers(){

        $customers = User::select('*')->orderBy('id','DESC')->get();
        return view('accountant.customers',['customers'=>$customers]);
   }

   public function generateBills(Request $request): \Illuminate\Http\RedirectResponse
   {

        $this->validate($request,[
            'start_date'=>'required',
            'end_date'=>'required'
        ]);

        $start = Carbon::createFromFormat('d/m/Y',$request->input('start_date'))->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y',$request->input('end_date'))->format('Y-m-d');

        $users = User::all();

        if (count($users) == 0){

            return redirect()->back()->with('error','No user in the system');
        }

        $user_bill = DB::table('bills')->first();

        foreach ($users as $user){

            $bill = new Bill_voucher();
            $bill->user_id = $user->id;
            $bill->starting_date = $request->input('start_date');
            $bill->end_date = $request->input('end_date');
            $bill->total_litters = $user_bill->litters;
            $bill->total_units = $user_bill->units;
            $bill->total_bill = $user_bill->bill_price;
            $bill->vourcher_number = rand(10000000000, 9999999999999);
            $bill->save();
        }

        $bill_vourcher = Bill_voucher::latest()->first();

        $bill_update = DB::table('bills')
            ->whereBetween('created_at', [$start, $end])
            ->update(['vourcher'=>$bill_vourcher->vourcher_number]);

        return redirect()->back()->with('success','Bills generated successful');
   }

   public function showCustomerBills(){

        $bills = DB::table('bill_vouchers')
        ->join('users','bill_vouchers.user_id','=','users.id')
        ->select('bill_vouchers.*','users.name')
        ->get();

        return view('accountant.bills',['bills'=>$bills]);
   }

   public function profile(){

        return view('accountant.profile');
   }

    /**
     * @throws ValidationException
     */
    public function updateProfile(Request $request){
        $this->validate($request,[
            'full_name'=>'required',
            'email'=>'required|email'
        ]);

        $admin = Account::find(Auth::user()->id)->update(['name'=>$request->full_name,'email'=>$request->email]);

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

            $user = Account::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Congratulations,Your password is successful changed');
        }

        return redirect()->back()->with('error', 'Ooooops!,Something went wrong(may be the old password is wrong)');
    }
}
