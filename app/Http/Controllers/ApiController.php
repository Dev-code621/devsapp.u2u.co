<?php

namespace App\Http\Controllers;

use App\Model\ChannelList;
use App\Model\EpgData;
use App\Model\Language;
use App\Model\LanguageWord;
use App\Model\Notification;
use App\Model\Word;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Model\PlayList;
use App\Model\PlayListUrl;
use App\Traits\SettingHelper;
use DB;
use App\Model\PlayListPricePackage;
use App\Model\Transaction;



class ApiController extends Controller
{
    use SettingHelper;
    public function getPlayListInformation($mac_address,$app_type=null){
        $result=Array();
        $temps=PlayList::where('mac_address',$mac_address)->get();
        if($temps->first()){
            $play_list=$temps->first();
        }
        else{
            $six_digit_random_number = mt_rand(100000, 999999);
            $play_list=new PlayList;
            $today=new \DateTime();
            $play_list->expire_date=$today->modify('+7 days')->format('Y-m-d');
            $play_list->mac_address=$mac_address;
            $play_list->is_trial=1;
            $play_list->app_type=$app_type;
            $play_list->lock='off';
            $play_list->device_key=$six_digit_random_number;
            $play_list->save();
        }
        $result['mac_registered']=true;
        $result['is_trial']=$play_list->is_trial;
        $result['expire_date']=$play_list->expire_date;
        $result['is_trial']=$play_list->is_trial;
        $result['lock']=$play_list->lock;
        $result['device_key']=$play_list->device_key;

        foreach ($play_list->PlayListUrls as $playListUrl){
            $result['urls'][]=
                [
                    'name'=>$playListUrl->name,
                    'url'=>$playListUrl->url
                ];
        }
        if(count($play_list->PlayListUrls)==0){
            $result['urls'][]=
                [
                    'name'=>'Demo Url',
                    'url'=>$this->getSetting('demo_url')
                ];
        }
        file_put_contents(storage_path('logs/laravel.log'),'');
        return response()->json($result);
    }

    public function changeLockState(Request $request){
        $input=$request->all();
        $mac_address=$input['mac_address'];
        $state=$input['state'];
        $temps=PlayList::where('mac_address',$mac_address)->get();
        if($temps->first()){
            $playlist=$temps->first();
            $playlist->lock=$state;
            $playlist->save();
            return [
                'status'=>'success'
            ];
        }else{
            return [
                'status'=>'error',
                'msg'=>'Mac No Exist'
            ];
        }

    }

    public function getAndroidVersion(){
        $android_version_code=$this->getSetting('android_version_code');
        $apk_url=$this->getSetting('apk_url');
        return [
            'version_code'=>$android_version_code,
            'apk_url'=>$apk_url
        ];
    }
    public function saveAppPurchase(Request $request){
        $input=$request->all();
        $mac_address=$input['mac_address'];
        $amount=$input['amount'];

        if(PlayList::where('mac_address',$mac_address)->get()->count()==0)
            return [
                'status'=>'error',
                'msg'=>'Sorry, mac address does not exist'
            ];
        $price_package=PlayListPricePackage::get()->first();
        $expire_date=((new \DateTime())->modify("+".$price_package->duration.' months'))->format('Y-m-d');
        $today=new \DateTime();

        $playlist=PlayList::where('mac_address',$mac_address)->get()->first();
        $transaction=new Transaction;
        $transaction->playlist_id=$playlist->id;
        $transaction->amount=$amount;
        $transaction->pay_time=$today->format("Y-m-d H:i");
        $transaction->status="success";
        $transaction->payment_type='app_purchase';
        $transaction->save();

        $playlist->expire_date=$expire_date;
        $playlist->is_trial=2;
        $playlist->save();
        return [
            'status'=>'success',
            'expire_date'=>$expire_date
        ];
    }

}
