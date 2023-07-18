<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\CountryCode;
use App\Model\EpgCode;
use Illuminate\Http\Request;

class EpgController extends Controller
{
    public function showEpgCode(){
        $epg_codes=EpgCode::get();
        $countries=CountryCode::get();
        return view('admin.epg_code',compact('epg_codes','countries'));
    }
    public function create(Request $request){
        $input=$request->all();
        $id=$input['id'];
        $url=$input['url'];
        $country=$input['country'];
        if(is_null($id) || $id==''){
            $epg_code=new EpgCode;
        }
        else
            $epg_code=EpgCode::find($id);
        $epg_code->url=$url;
        $epg_code->country=$country;
        $epg_code->save();
        return [
            'status'=>'success',
            'epg_code'=>$epg_code
        ];
    }

    public function delete(Request $request, $id){
        EpgCode::destroy($id);
        return [
            'status'=>'success'
        ];
    }
}
