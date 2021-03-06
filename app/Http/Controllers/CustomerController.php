<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet_store;
use App\Models\Wallet_withdraw;
use Bryceandy\Laravel_Pesapal\OAuth\OAuthConsumer;
use Bryceandy\Laravel_Pesapal\OAuth\OAuthRequest;
use Bryceandy\Laravel_Pesapal\OAuth\OAuthSignatureMethod_HMAC_SHA1;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function initiatePayments()
    {
        $deposit = Wallet_store::where('user_id',Auth::user()->id)->sum('deposit');
        $withdraw = Wallet_withdraw::where('user_id',Auth::user()->id)->sum('withdraw');

        $balance = $deposit - $withdraw;

        return view('customer.initiate_payment',['balance'=>$balance]);
    }

    public function MakePayment(Request $request)
    {

        $this->validate($request,[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'amount'=>'required',
            'currency'=>'required',
            'gate_way'=>'required',
            'description'=>'required',
        ]);

        //get form details
        $amount = $request->amount;
        $currency = $request->currency;
        $desc = $request->description;
        $type = $request->type;
        $reference = $this->random_reference();
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $gate_way = $request->gate_way;
        $phonenumber = $request->phone;

        //pesapal params
        $token = $params = NULL;

        $consumer_key = env('PESAPAL_KEY');
        $consumer_secret = env('PESAPAL_SECRET');

        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

        $iframelink = 'https://www.pesapal.com/api/PostPesapalDirectOrderV4';

        $callback_url = 'http://localhost/water_billing/public/pesapal/callback';

        if ($request->gate_way == "Wallet"){

            $deposit = Wallet_store::where('user_id',Auth::user()->id)->sum('deposit');
            $withdraw = Wallet_withdraw::where('user_id',Auth::user()->id)->sum('withdraw');

            $balance = $deposit - $withdraw;

            if ($request->amount > $balance){

                return redirect()->back()->with('info','The wallet balance is'.' '.'('.number_format($balance).')'.' '.'less than the payment you request'.' '.'('.number_format($request->amount).')'.'.')->withInput($request->all());
            }

            Transaction::make($first_name, $last_name, $email, $amount, $currency, $desc, $reference, $phonenumber, $gate_way);

            $withdraw = new Wallet_withdraw();
            $withdraw->user_id = Auth::user()->id;
            $withdraw->withdraw = $request->input('amount');
            $withdraw->save();

            return redirect()->back()->with('success','Payment done successful from wallet');
        }

        $payment = new Payment();
        $payment->amount = $request->input('amount');
        $payment->user_id = Auth::user()->id;
        $payment->save();



        //storing into the database


        Transaction::make($first_name, $last_name, $email, $amount, $currency, $desc, $reference, $phonenumber, $gate_way);

        /*Do not touch this xml variable in any way as it is the source of errors when you try
        to be clever and add extra spaces inside it*/
        $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
				   <PesapalDirectOrderInfo
						xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
					  	xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"
					  	Currency=\"" . $currency . "\"
					  	Amount=\"" . $amount . "\"
					  	Description=\"" . $desc . "\"
					  	Type=\"" . $type . "\"
					  	Reference=\"" . $reference . "\"
					  	FirstName=\"" . $first_name . "\"
					  	LastName=\"" . $last_name . "\"
					  	Email=\"" . $email . "\"
					  	PhoneNumber=\"" . $phonenumber . "\"
					  	xmlns=\"http://www.pesapal.com\" />";
        $post_xml = htmlentities($post_xml);

        $consumer = new OAuthConsumer($consumer_key, $consumer_secret);

        //post transaction to pesapal
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);

        return view('customer.pesapal_iframe', ['iframe_src' => $iframe_src]);
    }

    public function set_parameter($name, $value, $allow_duplicates = true)
    {
        if ($allow_duplicates && isset($this->parameters[$name])) {
            // We have already added parameter(s) with this name, so add to the list
            if (is_scalar($this->parameters[$name])) {
                // This is the first duplicate, so transform scalar (string)
                // into an array so we can add the duplicates
                $this->parameters[$name] = array($this->parameters[$name]);
            }

            $this->parameters[$name][] = $value;
        } else {
            $this->parameters[$name] = $value;
        }
    }

    public function sign_request($signature_method, $consumer, $token)
    {
        $this->set_parameter(
            "oauth_signature_method",
            $signature_method->get_name(),
            false
        );
        $signature = $this->build_signature($signature_method, $consumer, $token);
        $this->set_parameter("oauth_signature", $signature, false);
    }

    public function callback()
    {
        $pesapalMerchantReference = null;
        $pesapalTrackingId = null;
        $checkStatus = new pesapalCheckStatus();

        if (isset($_GET['pesapal_merchant_reference']))
            $pesapalMerchantReference = $_GET['pesapal_merchant_reference'];

        if (isset($_GET['pesapal_transaction_tracking_id']))
            $pesapalTrackingId = $_GET['pesapal_transaction_tracking_id'];


        //obtaining the payment status after a payment
        $status = $checkStatus->checkStatusUsingTrackingIdandMerchantRef($pesapalMerchantReference, $pesapalTrackingId);

        //display the reference and payment status on the callback page
        return view('customer.callback', compact('pesapalMerchantReference', 'status'));
    }

    function checkStatusUsingTrackingIdandMerchantRef($pesapalMerchantReference, $pesapalTrackingId)
    {

        //get transaction status
        $request_status = OAuthRequest::from_consumer_and_token(
            $this->consumer,
            $this->token,
            "GET",
            $this->QueryPaymentStatus,
            $this->params
        );
        $request_status->set_parameter("pesapal_merchant_reference", $pesapalMerchantReference);
        $request_status->set_parameter("pesapal_transaction_tracking_id", $pesapalTrackingId);
        $request_status->sign_request($this->signature_method, $this->consumer, $this->token);

        $status = $this->curlRequest($request_status);

        return $status;
    }

    public function curlRequest($request_status)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_status);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if (defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True') {
            $proxy_tunnel_flag = (
                defined('CURL_PROXY_TUNNEL_FLAG')
                && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE'
            ) ? false : true;
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
        }

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $raw_header = substr($response, 0, $header_size - 4);
        $headerArray = explode("\r\n\r\n", $raw_header);
        $header = $headerArray[count($headerArray) - 1];

        //transaction status
        $elements = preg_split("/=/", substr($response, $header_size));
        $pesapal_response_data = $elements[1];

        return $pesapal_response_data;
    }


    public function random_reference($prefix = 'PESAPAL', $length = 15): string
    {
        $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $str = '';

        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $prefix . $str;
    }

    public function showTransaction(){

        $transactions = Transaction::where('user_id',Auth::user()->id)->orderBy('id','DESC')->get();

        return view('customer.payment_transaction',['transactions'=>$transactions]);

    }

    public function wallet(){

        $deposit = Wallet_store::where('user_id',Auth::user()->id)->sum('deposit');
        $withdraw = Wallet_withdraw::where('user_id',Auth::user()->id)->sum('withdraw');

        $last_deposit = Wallet_store::where('user_id',Auth::user()->id)->latest()->first();
        $last_withdraw = Wallet_withdraw::where('user_id',Auth::user()->id)->latest()->first();

        $balance = $deposit - $withdraw;

        //dd($deposit,$withdraw,$last_deposit,$last_withdraw,$balance);
        return view('customer.wallet',['balance'=>$balance,'last_deposit'=>$last_deposit,'last_withdraw'=>$last_withdraw]);
    }

    public function deposit(Request $request): \Illuminate\Http\RedirectResponse
    {

        $this->validate($request, [
            'amount' => 'required',
        ]);

        $deposit = new Wallet_store();
        $deposit->user_id = Auth::user()->id;
        $deposit->deposit = $request->input('amount');
        $deposit->save();

        return redirect()->back()->with('success','Successful deposited to wallet');

    }

    public function depositHistory(){

        $deposit_histories = Wallet_store::select('*')->orderBy('id','DESC')->get();

        return view('customer.deposit_history',['deposit_histories'=>$deposit_histories]);
    }

    public function withdrawHistory(){

        $withdraw_histories = Wallet_withdraw::select('*')->orderBy('id','DESC')->get();

        return view('customer.withdraw_history',['withdraw_histories'=>$withdraw_histories]);
    }

    public function userProfile(){

        return view('customer.profile');
    }

    public function updateProfile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request,[
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required',
            'email' => 'required|string|email|max:255',
            'region' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'street' => 'required',
            'house_number' => 'required',
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $request->input('full_name');
        $user->phone_number = $request->input('phone_number');
        $user->email = $request->input('email');
        $user->region = $request->input('region');
        $user->district = $request->input('district');
        $user->ward = $request->input('ward');
        $user->street = $request->input('street');
        $user->house_number = $request->input('house_number');
        $user->save();

        return redirect()->back()->with('success','Profile Information changed successful');
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->old_password, $hashedPassword)) {

            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Congratulations,Your password is successful changed');
        }

        return redirect()->back()->with('error', 'Ooooops!,Something went wrong(may be the old password is wrong)');
    }
}
