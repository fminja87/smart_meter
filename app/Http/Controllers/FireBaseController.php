<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Illuminate\Http\Request;


class FireBaseController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->middleware('auth:web');
        // $this->database = app('firebase.firestore')->database();
    }

    public function getCustomerVolume(){

        $factory = (new Factory)->withServiceAccount(__DIR__.'/firebase_credentials.json')->withDatabaseUri('https://smart-water-db-2f249-default-rtdb.firebaseio.com/');
        $database = $factory->createDatabase();
        $refence = $database->getReference('sensor');
        $snapshort = $refence->getSnapshot();
        $values = $snapshort->getValue();

    if(empty($values)){

    }
    else{

        $bills = Bill::query()->delete();

        foreach($values as $value){

            $new_bills = new Bill();
            $new_bills->litters = $value;
            $new_bills->units = $new_bills->litters / 1000;
            $new_bills->bill_price = $new_bills->units * 1663;
            $new_bills->save();
        }

    }
      
    $bills = Bill::all();

    return view('customer.bills',['bills'=>$bills]);
    }
}