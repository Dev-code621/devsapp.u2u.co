<?php

namespace App\Http\Controllers;

use App\Model\ActivationContent;
use App\Model\CoinList;
use App\Model\CountryCode;
use App\Model\EpgCode;
use App\Model\EpgData;
use App\Model\Faq;
use App\Model\Instruction;
use App\Model\InstructionTag;
use App\Model\MyListContent;
use App\Model\PlayList;
use App\Model\PlayListPricePackage;
use App\Model\PlayListUrl;
use App\Model\AllowDns;
use App\Model\PrivacyPageContent;
use App\Model\TermsPageContent;
use App\Model\Transaction;
use App\Model\ChannelList;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Model\News;
use App\Model\ResellerContent;
use App\Traits\SettingHelper;
use Illuminate\Support\Facades\Log;



use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Amount;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    use SettingHelper;
    public function __construct(){
        $client_id=$this->getSetting('paypal_client_id');
        $secret=$this->getSetting('paypal_secret');
        $paypal_mode=$this->getSetting('paypal_mode');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $client_id,
                $secret
            )
        );
        $this->_api_context->setConfig([
            'mode' => $paypal_mode,
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => public_path('logs/paypal.log'),
            'log.LogLevel' => 'ERROR'
        ]);

        $secret_key=$this->getSetting('stripe_secret_key');
        \Stripe\Stripe::setApiKey($secret_key);
    }

    public function showPlaylists(){
        $title=$this->getSetting('playlist_meta_title');
        $keyword=$this->getSetting('playlist_meta_keyword');
        $description=$this->getSetting('playlist_meta_content');
        $menu='playlists';
        $playlist=PlayList::find(session('playlist_id'));
        $playlist->urls=$playlist->PlayListUrls;
        return view('frontend.device_playlist',compact('title','keyword','description','menu','playlist'));
    }

    public function savePlaylist(Request $request){
        $input=$request->all();
        $playlist_id=session('playlist_id');
        if(is_null($playlist_id)){
            return ['status'=>'error','msg'=>'Sorry, session expired, please reload page'];
        }
        $current_playlist_url_id=$input['current_playlist_url_id'];
        if($current_playlist_url_id==-1)
            $playlist_url=new PlayListUrl;
        else
            $playlist_url=PlayListUrl::find($current_playlist_url_id);

        $playlist_url->playlist_id=$playlist_id;
        $playlist_url->url=$input['playlist_url'];
        $playlist_url->name=$input['playlist_name'];
        $playlist_url->is_protected=$input['protect']=='true' ? 1 : 0;
        $playlist_url->pin=$input['pin'];
        $is_allowed = false;
        $dns_list = AllowDns::all();
        
        foreach ($dns_list as $dns) {
            if (Str::contains($input['playlist_url'], $dns->dns_name)) {
                $is_allowed = true;
                break;
            }
        }
        
        // if ($is_allowed) {
            $playlist_url->save();
            return [
                'status'=>'success',
                'msg'=>'Playlist saved successfully',
                'data'=>$playlist_url
            ];
            
        // } else {
        //     return ['status'=>'error','msg'=>'Sorry, playlist domain is not allwed by administrator'];
        // }
      
    }

    public function getPlaylistUrlDetail($playlist_url_id){
        $playlist_url=PlayListUrl::find($playlist_url_id);
        return $playlist_url;
    }

    public function checkPlaylistPinCode($playlist_url_id,$pin_code){
        $playlist_url=PlayListUrl::find($playlist_url_id);
        if(!is_null($playlist_url)){
            if($playlist_url->pin==$pin_code){
                return [
                    'status'=>'success'
                ];
            }else{
                return ['status'=>'error','msg'=>'Pin code is wrong'];
            }
        }else{
            return [
                'status'=>'error',
                'msg'=>'Playlist does not exist'
            ];
        }
    }

    public function deletePlayListUrl(Request $request){
        $input=$request->all();
        $playlist_url_id=$input['playlist_url_id'];
        $pin_code=$input['pin_code'];
        $playlist_id=session('playlist_id');
        $playlist_url=PlayListUrl::find($playlist_url_id);
        if($playlist_url->playlist_id==$playlist_id){
            if($playlist_url->is_protected==1 && $playlist_url->pin!=$pin_code){
                return [
                    'status'=>'error',
                    'msg'=>'Sorry, pin code is not correct'
                ];
            }
            $playlist_url->delete();
            return [
                'status'=>'success',
                'msg'=>'Playlist deleted successfully'
            ];
        }else{
            return [
                'status'=>'error',
                'result'=>'Sorry, you are trying to delete other\'s playlist, please stop spamming'
            ];
        }
    }

    public function getStripe(){
        $secret_key=$this->getSetting('stripe_secret_key');
        $stripe=Stripe::make($secret_key);
        return $stripe;
    }

    public function index()
    {
        return view('home');
    }
    public function news(){
        $news_sections=News::where('status','publish')->orderBy('id','desc')->get();
        $title=$this->getSetting('news_meta_title');
        $keyword=$this->getSetting('news_meta_keyword');
        $description=$this->getSetting('news_meta_content');
        return view('frontend.news', compact('news_sections','title','keyword','description'));
    }
    public function faq(){
        $faqs=Faq::get();
        $title=$this->getSetting('support_meta_title');
        $keyword=$this->getSetting('support_meta_keyword');
        $description=$this->getSetting('support_meta_content');
        return view('frontend.faq', compact('faqs','title','keyword','description'));
    }

    public function instruction($tag_name=null){
        $instruction=null;
        $tag_id=null;
        if(!is_null($tag_name)){
            $temps=InstructionTag::where('tag_name',$tag_name)->get();
            if($temps->first()){
                $tag_id=$temps->first()->id;
            }
        }
        else
            $tag_id=0;
        $instruction_temp1=Instruction::where('tag_id',$tag_id)->get();
        if($instruction_temp1->count()>0)
            $instruction=$instruction_temp1->first();

        $title=$this->getSetting('instruction_meta_title');
        $keyword=$this->getSetting('instruction_meta_keyword');
        $description=$this->getSetting('instruction_meta_content');
        return view('frontend.instruction',compact('instruction','title','keyword','description'));
    }

    public function deviceLogin(){
        if(!is_null(session('playlist_id'))){
            return redirect('/device/playlists');
        }
        $mylist_content=null;
        if(MyListContent::get()->count()>0)
            $mylist_content=MyListContent::get()->first();
        $title=$this->getSetting('mylist_meta_title');
        $keyword=$this->getSetting('mylist_meta_keyword');
        $description=$this->getSetting('mylist_meta_content');
        return view('frontend.device_login', compact('mylist_content','title','keyword','description'));
    }
    public function postDeviceLogin(Request $request){
        $input=$request->all();
        $mac_address=$input['mac_address'];
        $device_key=$input['device_key'];
        $temps=PlayList::where([['mac_address','=',$mac_address],['device_key','=',$device_key]])->get();
        if($temps->first()){
            $playlist=$temps->first();
            session(['playlist_id' => $playlist->id]);
            return redirect('/device/playlists');
        }else{
            return redirect()->back()->withInput($input)->with('error','Sorry, device information is not correct');
        }
    }
    public function deviceLogout(){
        session()->forget('playlist_id');
        return redirect('/');
    }

    public function Activation(){
        $activation_content=null;
        if(ActivationContent::get()->count()>0)
            $activation_content=ActivationContent::get()->first();
        $stripe_public_key=$this->getSetting('stripe_public_key');

        $client_id=$this->getSetting('paypal_client_id');
        $title=$this->getSetting('activation_meta_title');
        $keyword=$this->getSetting('activation_meta_keyword');
        $description=$this->getSetting('activation_meta_content');
        $price_packages=PlayListPricePackage::where('price','>',0)->orderBy('price')->get();
        $coin_list=CoinList::get()->first() ? CoinList::get()->first()->data : [];
        return view('frontend.activation', compact('activation_content','price_packages','title','keyword','description','stripe_public_key','coin_list','price_packages','client_id'));
    }

    public function ActivationTest(){

        $activation_content=null;
        if(ActivationContent::get()->count()>0)
            $activation_content=ActivationContent::get()->first();
        $stripe_public_key=$this->getSetting('stripe_public_key');
        $show_paypal=$this->getSetting('show_paypal');
        $show_stripe=$this->getSetting('show_stripe');


        $client_id=$this->getSetting('paypal_client_id');
        $title=$this->getSetting('activation_meta_title');
        $keyword=$this->getSetting('activation_meta_keyword');
        $description=$this->getSetting('activation_meta_content');
        $price_packages=PlayListPricePackage::where('price','>',0)->orderBy('price')->get();
        $coin_list=CoinList::get()->first() ? CoinList::get()->first()->data : [];
        return view('frontend.activation_test', compact('activation_content','price_packages','title','keyword','description','stripe_public_key','coin_list','price_packages','client_id','show_stripe','show_paypal'));
    }

    public function terms(){
        $faqs=TermsPageContent::get();
        $title=$this->getSetting('terms_meta_title');
        $keyword=$this->getSetting('terms_meta_keyword');
        $description=$this->getSetting('terms_meta_content');
        return view('frontend.terms', compact('faqs','title','keyword','description'));
    }

    public function privacy(){
        $faqs=PrivacyPageContent::get();
        $title=$this->getSetting('privacy_meta_title');
        $keyword=$this->getSetting('privacy_meta_keyword');
        $description=$this->getSetting('privacy_meta_content');
        return view('frontend.faq', compact('faqs','title','keyword','description'));
    }

    public function deleteMyList(Request $request){
        $mac_address=$request->input('mac_address');
        if(PlayList::where('mac_address',$mac_address)->count()==0)
            return redirect()->back()->with('error',"Sorry, We could not find your mac address. <br> Please confirm if your mac address is right");
        else{
            $playlist=PlayList::where('mac_address',$mac_address)->first();
            if($playlist->lock=='on'){
                return redirect()->back()->with('error',"MAC address is locked by the user.<br> To unlock open the app and go to Settings>Account Info>Unlock Mac Address");
            }
            PlayListUrl::where('playlist_id',$playlist->id)->delete();
            return redirect()->back()->with('message',"Your playlist removed successfully");
        }
    }
    public function showEpgCode(){
        $countries=EpgCode::orderBy('country')->get();
        return view('frontend.epg_code', compact('countries'));
    }
    public function getEpgCodes(Request $request){
        $input=$request->all();
        $country=$input['country'];

        $draw = $input['draw'];
        $rowperpage = $input['length'];
        $row = $input['start'];
        $columnIndex = $input['order'][0]['column']; // Column index
        $columnName = $input['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $input['order'][0]['dir']; // asc or desc
        $searchValue = $input['search']['value']; // Search value


        $temp=ChannelList::orderBy('channel_id');
        if($country!='ALL')
        {
            $country_code=EpgCode::where('country',$country)->get()->first();
            $temp=$temp->where('epg_code_id', $country_code->id);
        }
        if(!is_null($searchValue) && $searchValue!='')
            $temp=$temp->where(function($query) use ($searchValue){
                return $query->where('channel_id','LIKE',"%$searchValue%")
                    ->orWhere('name','LIKE',"%$searchValue%");
            });

        $totalRecords=$temp->count();
        $temp=$temp->select('channel_id','name')->skip($row)->take($rowperpage);
        if($columnName=='name')
            $temp=$temp->orderBy('name',$columnSortOrder);
        if($columnName=='channel_id')
            $temp=$temp->orderBy('channel_id',$columnSortOrder);
        $temp=$temp->get();
        return ['data'=>$temp,"draw" => intval($draw),"iTotalDisplayRecords" => $totalRecords,
            "iTotalRecords" => $totalRecords];
    }


    public function getPrice(){
        $price_package=PlayListPricePackage::get()->first();
        $price=$price_package->price;
        $price=number_format((float)$price, 2, '.', '');
        return $price;
    }

    public function checkMacValid(Request $request){
        $mac_address=$request->input('mac_address');
        if(PlayList::where('mac_address',$mac_address)->get()->count()==0)
            return [
                'status'=>'error',
                'msg'=>"Your mac address does not exist. Please register your mac address first"
            ];
        $play_list=PlayList::where('mac_address',$mac_address)->get()->first();
        if($play_list->is_trial==2)
            return [
                'status'=>'error',
                'msg'=>"Sorry, You are already activated."
            ];
        return [
            'status'=>'success'
        ];
    }

    public function createOrder(Request $request){
        $price=$this->getPrice();
//        return $price;
        $client_id=$this->getSetting('paypal_client_id');
        $secret=$this->getSetting('paypal_secret');
        $paypal_mode=$this->getSetting('paypal_mode');
        $paypal_url=$paypal_mode=="sandbox" ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";
        $ch = curl_init("$paypal_url/v2/checkout/orders");
        $authorization="Basic ".base64_encode("$client_id:$secret");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $header=array(
            "Content-Type: application/json",
            "Authorization: ".$authorization,
            "Prefer: return=representation"
        );
        $post_data=[
            "intent"=>"CAPTURE",
            "purchase_units"=> [
                [
                    "amount"=>[
                        "currency_code"=>"EUR",
                        "value"=>$price
                    ]
                ]
            ]
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            json_encode($post_data)
        );
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function captureOrder(Request $request){
        $playlist_id=session('playlist_id');
        $play_list=PlayList::find($playlist_id);
        $order_id=$request->input('order_id');
        $plan_id=$request->input('plan_id');
        $price_package=PlayListPricePackage::find($plan_id);
        $client_id=$this->getSetting('paypal_client_id');
        $secret=$this->getSetting('paypal_secret');
        $paypal_mode=$this->getSetting('paypal_mode');
        $paypal_url=$paypal_mode=="sandbox" ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";
        $ch = curl_init("$paypal_url/v2/checkout/orders/$order_id/capture");
        $authorization="Basic ".base64_encode("$client_id:$secret");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $header=array(
            "Content-Type: application/json",
            "Authorization: ".$authorization,
            "Prefer: return=representation"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $expire_date=$play_list->expire_date;
        $today=new \DateTime();
        $today_date=$today->format('Y-m-d');
        $current_expire_date=$expire_date > $today_date ? new \DateTime($expire_date) : $today;


        $expire_date=$current_expire_date->modify("+".$price_package->duration.' months')->format('Y-m-d');
        $play_list->expire_date=$expire_date;
        $play_list->is_trial=2;
        $play_list->save();

        $transaction=new Transaction;
        $transaction->playlist_id=$play_list->id;
        $today=new \DateTime();
        $transaction->amount=$price_package->price;
        $transaction->pay_time=$today->format("Y-m-d H:i");
        $transaction->status="success";
        $transaction->payment_type=$paypal_mode=='sandbox' ? "Paypal Test" : "Paypal";
        $transaction->payment_id=$order_id;
        $transaction->save();
    }
    public function saveActivation(Request $request){
        $input=$request->all();
        $mac_address=$input['mac-address'];
        $payment_type=$input['payment_type'];
        if(PlayList::where('mac_address',$mac_address)->get()->count()==0)
            return redirect()->back()->with('error',"Your mac address does not exist. Please <a href='/mylist'>register</a> your mac address first");
        $play_list=PlayList::where('mac_address',$mac_address)->get()->first();
        $today=new \DateTime();
        if($play_list->is_trial==2)
            return redirect()->back()->with('error',"Sorry, You are already activated once");
        $price_package=PlayListPricePackage::get()->first();
        $price=$price_package->price[0]['price'];
        $price=number_format((float)$price, 2, '.', '');

        $current_expire_date=$today->format('Y-m-d');
        if($play_list->expire_date>$current_expire_date)
            $current_expire_date=$play_list->expire_date;
        $expire_date=((new \DateTime($current_expire_date))->modify("+".$price_package->duration.' months'))->format('Y-m-d');
        $transaction=new Transaction;
        $transaction->playlist_id=$play_list->id;
        $transaction->amount=$price;
        $transaction->pay_time=$today->format("Y-m-d H:i");
        $transaction->status="pending";
        $transaction->payment_type=$payment_type;
        $transaction->save();
        if($payment_type=='crypto'){
            $url = "https://www.coinpayments.net/api.php";
            $success_url=url("/activation/crypto/redirect?transaction_id=$transaction->id");
            $cancel_url=url("/activation/crypto/cancel?transaction_id=$transaction->id");

            $post_fields="";
            $coin_type=$input['coin_type'];
            $transaction->payment_type=$coin_type;
            $transaction->save();
            $crypto_public_key=$this->getSetting('crypto_public_key');
            $crypto_private_key=$this->getSetting('crypto_private_key');
            $key_value_arr=[
                'key'=>$crypto_public_key,
                'version'=>'1',
                'cmd'=>'create_transaction',
                'amount'=>$price,
                'currency1'=>'EUR',
                'currency2'=>$coin_type,
                'buyer_email'=>'anomous@email.com',
                'item_name'=>'Flix IPTV',
                'success_url'=>$success_url,
                'cancel_url'=>$cancel_url
            ];
            foreach ($key_value_arr as $key=>$value){
                $post_fields.="&$key=$value";
            }
            $private_key=$crypto_private_key;
            $hmac=hash_hmac('sha512', $post_fields, $private_key);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_fields,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded",
                    "HMAC: $hmac"
                ),
            ));
            $response = curl_exec($curl);
            $response=json_decode($response, true);

            if($response['error']=="ok"){
                $result=$response['result'];
                $transaction->payment_id=$result['txn_id'];
                $transaction->status_url=$result['status_url'];
                $transaction->save();
                header("Location: $result[checkout_url]");
                exit;
            }else{
                return redirect()->back()->with('error',"Sorry, error occured while trying to make crypto payment, please try again later or try other payment method");
            }
        }else if($payment_type=='mollie'){
            $mollie = new \Mollie\Api\MollieApiClient();
            $api_key=$this->getSetting('mollie_api_key');
            $mollie->setApiKey($api_key);
            $payment = $mollie->payments->create([
                "amount" => [
                    "currency" => "EUR",
                    "value" => $price
                ],
                "description" => "Flix TV lifetime license",
                "redirectUrl" => "https://flixiptv.eu/payment_status/",
                "webhookUrl"  => "https://flixiptv.eu/api/mollie/webhook",
            ]);
            $transaction->payment_id=$payment->id;
            session(['payment_id'=>$payment->id]);
            $transaction->save();
            header("Location: " . $payment->getCheckoutUrl(), true, 303);
            exit;

        }
    }

    public function StripeSuccessRedirection(Request $request, $session_id){
        $secret_key=$this->getSetting('stripe_secret_key');
        \Stripe\Stripe::setApiKey($secret_key);
        $input=$request->all();
        $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
        $payment_id=$checkout_session->payment_intent;

        $payment_type=$input['payment_type'];
        $transaction_id=$input['transaction_id'];
        $transaction=Transaction::find($transaction_id);

        if($payment_type=="activation"){
            $playlist_id=$transaction->playlist_id;
            $playlist=PlayList::find($playlist_id);
            $expire_date=$input['expire_date'];
            $playlist->expire_date=$expire_date;
            $playlist->is_trial=2;
            $playlist->save();

            $transaction->status="success";
            $transaction->payment_id=$payment_id;
            $transaction->save();
            return redirect('/activation')->with('message','Thanks for your payment. Now your mac address is activated');
        }
        return redirect('/device/activation')->with('message','Thanks for your payment');
    }

    public function PaymentCancel(Request $request){
        $input=$request->all();
        $transaction_id=$input['transaction_id'];
        $transaction=Transaction::find($transaction_id);
        $transaction->status="canceled";
        $transaction->save();
        return redirect('/device/activation')->with('error','You canceled payment. Your account would not activated or expire date would not be extended');
    }

    public function showResellerPage(){
        $faqs=ResellerContent::get();
        $title=$this->getSetting('support_meta_title');
        $keyword=$this->getSetting('support_meta_keyword');
        $description=$this->getSetting('support_meta_content');
        return view('frontend.reseller', compact('faqs','title','keyword','description'));
    }



}
