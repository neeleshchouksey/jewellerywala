<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Shop;
use App\Models\Category;
use App\Models\Purity;
use App\Models\SubCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function get_notifications()
    {
        $res = Notification::where("rec_id",Auth::user()->id)->orderby("id","desc")->get();
        if(!empty($res->toArray())){
            if($res[0]->read == 0){
                $read = 0;
            }else{
                $read = 1;
            }
        }else{
            $read = 1;
        }

        return response()->json(["status" => "success", "res" => $res,"read"=>$read], 200);
    }
    public function read_notification()
    {
        $res = Notification::where("rec_id",Auth::user()->id)->update(["read"=>1]);

        return response()->json(["status" => "success"], 200);
    }


}
