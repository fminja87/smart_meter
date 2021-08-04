<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Bill_voucher;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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


    if(empty($values)){

    }
    else{

        $bills = Bill::query()->delete();

        foreach($values as $value){

            $new_bills = new Bill();
            $new_bills->litters = json_encode($value);
            $new_bills->save();
        }

    }

    $bills = Bill::all();

    return view('customer.bills',['bills'=>$bills,'bill'=>$bill]);
    }
}
