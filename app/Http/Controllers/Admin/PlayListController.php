<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PlayList;
use Illuminate\Http\Request;
use App\Model\PlayListPricePackage;
use Illuminate\Support\Facades\Auth;


class PlayListController extends Controller
{
    public function index(){
        $play_lists=[];
        $plans=PlayListPricePackage::get();
        $user=Auth::guard('admin')->user();
        $is_admin=$user->is_admin;
        $activated_count=100000000;
        if($is_admin==0)
            $activated_count=$user->max_connections-PlayList::where([['is_trial','=',2],['activated_by','=',$user->id]])->get()->count();
        return view('admin.playlist', compact('play_lists','plans','activated_count','is_admin'));
    }

    public function getPlaylists(Request $request){
        $input=$request->all();
        $show_samsung=$input['show_samsung'];
        $show_ios=$input['show_ios'];
        $show_android=$input['show_android'];
        $show_lg=$input['show_lg'];
        $show_activated=$input['show_activated'];
        $show_trial=$input['show_trial'];
        $today=(new \DateTime())->format('Y-m-d');

        $draw = $input['draw'];
        $rowperpage = $input['length'];
        $row = $input['start'];

        $columnIndex = $input['order'][0]['column']; // Column index
        $columnName = $input['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $input['order'][0]['dir']; // asc or desc
        $searchValue = $input['search']['value']; // Search value

        $playlists=PlayList::query();
        if($show_android==false || $show_android=='false'){
            $playlists=$playlists->where('app_type','!=','android');
        }

        if($show_samsung==false || $show_samsung=='false'){
            $playlists=$playlists->where('app_type','!=','samsung');
        }

        if($show_ios==false || $show_ios=='false'){
            $playlists=$playlists->where('app_type','!=','ios');
        }

        if($show_lg==false || $show_lg=='false'){
            $playlists=$playlists->where('app_type','!=','lg');
        }

        if($show_activated==false || $show_activated=='false')
            $playlists=$playlists->where('is_trial','!=',2);

        if($show_trial==false || $show_trial=='false')
            $playlists=$playlists->where('is_trial',2);

        if(!is_null($searchValue) && $searchValue!='')
            $playlists=$playlists->where(function($query) use ($searchValue){
                return $query->
                where('mac_address','LIKE',"%$searchValue%");
            });

        $user=Auth::guard('admin')->user();
        if($user->is_admin==0){
            $playlists=$playlists->where(function($query) use ($user){
                return $query->
                where([['activated_by','=',$user->id],['is_trial','=',2]])
                    ->orWhere('is_trial','!=',2);
            });
        }
        $totalRecords=$playlists->get()->count();
        $playlists=$playlists->select('id','mac_address','device_key','app_type','created_at','expire_date','is_trial')->skip($row)->take($rowperpage);
        if($columnName=='app_type')
            $playlists=$playlists->orderBy('app_type',$columnSortOrder);
        if($columnName=='created_time')
            $playlists=$playlists->orderBy('created_at',$columnSortOrder);
        if($columnName=='expire_date')
            $playlists=$playlists->orderBy('expire_date',$columnSortOrder);

        $playlists=$playlists->get();
        foreach ($playlists as $item){
            $item->created_time=$item->created_at->format('Y-m-d H:i');
        }
        $user=Auth::guard('admin')->user();
        foreach ($playlists as $item){
            if($item->is_trial!=2){
                $item->action='<button class="btn btn-sm btn-success btn-activate" data-playlist_id="'.$item->id.'">Activate</button>';
            }else{
                $item->action='<button class="btn btn-sm btn-danger btn-deactivate" data-playlist_id="'.$item->id.'">Deactivate</button>';
            }

        }
        if($user->is_admin==1){
            foreach ($playlists as $item){
                $item->action.='<a href="'.url('/admin/playlist/'.$item->id).'" target="_blank" style="margin:0 5px">'.
                            '<button class="btn btn-sm btn-primary">'.
                                '<i class="fa fa-eye"></i>'.
                            '</button>'.
                        '</a>';
            }
        }

        return ['data'=>$playlists,"draw" => intval($draw),"iTotalDisplayRecords" => $totalRecords,
            "iTotalRecords" => $totalRecords,'inputs'=>$input];
    }

    public function activate(Request $request){
        $input=$request->all();
        $playlist_id=$input['playlist_id'];
        $action=$input['action'];
        $play_list=PlayList::find($playlist_id);
        $today=new \DateTime();
        $user=Auth::guard('admin')->user();
        $activated_count=1000000000;
        if($user->is_admin==0){
            $activated_count=PlayList::where([['is_trial','=',2],['activated_by','=',$user->id]])->get()->count();
            if($activated_count>=$user->max_connections){
                return [
                    'Sorry, your max connection exceed. You can not activate anymore devices'
                ];
            }
        }
        $activated_count=$user->max_connections-$activated_count;
        if($action==1){  // i
            $price_package=PlayListPricePackage::get()->first();
            $current_expire_date=$today->format('Y-m-d');
            if($play_list->expire_date>$current_expire_date)
                $current_expire_date=$play_list->expire_date;
            $expire_date=((new \DateTime($current_expire_date))->modify("+".$price_package->duration.' months'))->format('Y-m-d');
            $play_list->expire_date=$expire_date;
            $play_list->is_trial=2;
            $play_list->activated_by=$user->id;
            $play_list->save();
            $activated_count-=1;
        }
        else{
            $expire_date=$today->modify('-1 days')->format('Y-m-d');
            $play_list->expire_date=$expire_date;
            $play_list->is_trial=1;
            $play_list->save();
            $activated_count+=1;
        }
        return [
            'status'=>'success',
            'expire_date'=>$play_list->expire_date,
            'activated_count'=>$activated_count
        ];
    }

    public function showDetail($id){
        $user=Auth::guard('admin')->user();
        if($user->is_admin==0 && $user->is_trial==2){  // can't see user detail because user activated by others
            $error=true;
            return view('admin.playlist_detail',compact('error'));
        }else{
            $play_list=PlayList::find($id);
            $play_list->urls=$play_list->PlayListUrls;
            $play_list->transactions=$play_list->Transactions;
            $error=false;
            return view('admin.playlist_detail',compact('play_list','error'));
        }

    }
}
