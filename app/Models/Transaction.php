<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'currency', 'description', 'reference', 'phone', 'status', 'pesapal_transaction_tracking_id', 'pesapal_merchant_reference', 'pesapal_notification_type', 'payment_method'
    ];

    protected $table = 'transactions';

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class);
    }

    //adds a user first if they are not in the users table then adds transaction details
    public static function make($first_name, $last_name, $email, $amount, $currency, $desc, $reference, $phonenumber)
    {
        $userExists = User::where('email',$email)->first();
        if($userExists)
        {
            $user_id = $userExists->id;
            Transaction::create([
                'user_id' => $user_id, 'phone' => $phonenumber, 'amount' => $amount, 'currency' => $currency, 'description' => $desc, 'reference' => $reference
            ]);
        }
        else
        {
            //make sure your Users table has at least the three columns for this to work, or modify per your needs
            $user = User::create([
                'first_name' =>$first_name, 'last_name' =>$last_name, 'email' =>$email
            ]);

            $user_id = $user->id;
            Transaction::create([
                'user_id' => $user_id, 'phone' => $phonenumber, 'amount' => $amount, 'currency' => $currency, 'description' => $desc, 'reference' => $reference
            ]);
        }

    }

    public static function modify($transaction)
    {
        $status 					= $transaction['status'];
        $payment_method 			= $transaction['payment_method'];
        $pesapalMerchantReference	= $transaction['pesapal_merchant_reference'];
        $pesapalTrackingId 			= $transaction['pesapal_transaction_tracking_id'];

        //payment status will always detect a change and send you notifications by IPN
        $update = Transaction::whereReference($pesapalMerchantReference)->first();
        $update->update([
            'status' => $status,
            'payment_method' => $payment_method,
            'tracking_id' => $pesapalTrackingId
        ]);
    }
}
