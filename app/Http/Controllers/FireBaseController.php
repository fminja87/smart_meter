<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Bill_voucher;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FireBaseController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->middleware('auth:web');
        // $this->database = app('firebase.firestore')->database();
    }

    public function getCustomerVolume(){

        $bill = Bill_voucher::where('user_id', Auth::user()->id)->latest()->first();


        $factory = (new Factory)->withServiceAccount(__DIR__.'/firebase_credentials.json')->withDatabaseUri('https://smart-water-db-2f249-default-rtdb.firebaseio.com/');
        $database = $factory->createDatabase();
        $refence = $database->getReference('fuelsensor');
        $snapshort = $refence->getSnapshot();
        $values = $snapshort->getValue();

//  dd($values);

     $bills = DB::table('bills')->first();

    //  dd($bills);

    if($bills == null){

        foreach($values as $value){

            $new_bills = new Bill();
            $new_bills->litters = $value;
            $new_bills->units =  $new_bills->litters / 1000;
            $new_bills->bill_price = $new_bills->units * 1663;
            $new_bills->save();
        }

        $new_bills = Bill::all();
    
        return view('customer.bills',['new_bills'=>$new_bills,'bill'=>$bill]);

    }
    else{

        foreach($values as $value){

            $update_bills = Bill::find($bills->id);
            $update_bills->litters = $value;
            $update_bills->units =  $update_bills->litters / 1000;
            $update_bills->bill_price = $update_bills->units * 1663;
            $update_bills->save();
        }

        $new_bills = Bill::all();
    
        return view('customer.bills',['new_bills'=>$new_bills,'bill'=>$bill]);

    }

    }
}
