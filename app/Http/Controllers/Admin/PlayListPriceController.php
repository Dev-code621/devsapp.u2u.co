<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\PlayListPricePackage;

class PlayListPriceController extends Controller
{
    public function index(){
        $price_packages=PlayListPricePackage::get();
        return view('admin.playlist_package.index', compact('price_packages'));
    }
    public function createPackage($id=null){
        $package=null;
        if(!is_null($id)){
            $package=PlayListPricePackage::find($id);
        }
        return view('admin.playlist_package.create', compact('package','id'));
    }
    public function savePackage(Request $request){
        $input=$request->all();
        $package_name=$input['package_name'];
        $duration=$input['duration'];
        $price=$input['price'];
        $id=$input['id'];
        $package=null;
        if(!is_null($id)){
            $package=PlayListPricePackage::find($id);
        }
        else
            $package=new PlayListPricePackage;
        $package->name=$package_name;
        $package->duration=$duration;
        $package->price=$price;
        $package->save();
        return redirect('/admin/playlist_package');
    }

    public function deletePackage($id){
        PlayListPricePackage::destroy($id);
        return [
            'status'=>'successs'
        ];
    }
}
