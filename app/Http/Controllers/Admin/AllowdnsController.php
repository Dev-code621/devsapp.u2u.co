<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Instruction;
use Illuminate\Http\Request;
use App\Model\AllowDns;
use App\Traits\SummernoteOperation;

class AllowdnsController extends Controller
{
    use SummernoteOperation;
    public function index(){
        $dns=AllowDns::all();
        return view('admin.dns.list', compact('dns'));
    }
    public function addDns(Request $request){
        $dns_name=$request->input('dns_name');
        $id=$request->input('id');
        if(is_null($id) || $id=='')
            $allow_dns=new AllowDns;
        else
            $allow_dns=AllowDns::find($id);
        $allow_dns->dns_name=$dns_name;
        $allow_dns->save();
        return [
            'status'=>'success',
            'id'=>$allow_dns->id
        ];
    }
    public function deleteDns(Request $request,$id){
        AllowDns::destroy($id);
        return [
            'status'=>'success',
        ];
    }

    // public function allowDns($dns_id){
    //     $dns_content=null;
    //     $temp=AllowDns::where('dns_id',$dns_id)->get();
    //     if($temp->first())
    //         $dns_content=$temp->first();
    //     return view('admin.dns.create', compact('dns_content','dns_id'));
    // }

    

    // public function saveDnsPage(Request $request,$dns_id){
    //     $this->validate($request, [
    //         'section-content' => 'required',
    //     ]);
    //     $detail=$request->input('section-content');
    //     if(AllowDns::where('dns_id',$dns_id)->get()->count()>0)
    //         $dns_content=AllowDns::where('dns_id',$dns_id)->get()->first();
    //     else
    //         $dns_content=new AllowDns;

    //     $dns_content->dns_id=$dns_id;
    //     $detail=$this->changeDomContent($detail);
    //     $dns_content->contents= $detail;
    //     $dns_content->save();
    //     return redirect('/admin/instruction/tags');
    // }



}
