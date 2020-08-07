<?php
namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Category;
use App\Models\Purity;
use App\Models\SubCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile');
    }

    public function get_profile($id=null)
    {
        if(!$id){
            $id = Auth::user()->id;
        }
        $user = User::select("users.*","shops.*","cities.name as city_name","states.name as state_name")
            ->join("shops","users.id","shops.user_id")
            ->join("states","states.id","shops.state")
            ->join("cities","cities.id","shops.city")
            ->where("users.id",$id)->first();
        return response()->json(['status' => 'success', 'res' => $user], 200);
    }
    public function update_profile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name ? $request->first_name : $user->first_name;
        $user->last_name = $request->last_name ? $request->last_name : $user->last_name;
        $user->password = $request->password ? bcrypt($request->password) : $user->password;
        $user->save();

        $shop = Shop::where("user_id",Auth::user()->id)->first();

        if ($request->hasfile('gumasta')) {
            unlink(storage_path('app/public/gumasta-images/' . $shop->gumasta));

            $file1 = $request->file("gumasta");
            $back_image = time() . "_" . $file1->getClientOriginalName();
            $path = $file1->storeAs('public/gumasta-images', $back_image);
        }else{
            $back_image = $shop->gumasta;
        }

        $shop->shop_name = $request->shop_name ? $request->shop_name : $shop->shop_name;
        $shop->address1 = $request->address1 ? $request->address1 : $shop->address1;
        $shop->address2 = $request->address2 ? $request->address2 : $shop->address2;
        $shop->city = $request->city ? $request->city : $shop->city;
        $shop->state = $request->state ? $request->state : $shop->state;
        $shop->pin_code = $request->pin_code ? $request->pin_code : $shop->pin_code;
        $shop->gst_number = $request->gst_number ? $request->gst_number : $shop->gst_number;
        $shop->pan_number = $request->pan_number ? $request->pan_number : $shop->pan_number;
        $shop->bank_branch_name = $request->bank_branch_name ? $request->bank_branch_name : $shop->bank_branch_name;
        $shop->bank_branch_address = $request->bank_branch_address ? $request->bank_branch_address : $shop->bank_branch_address;
        $shop->bank_account_number = $request->bank_account_number ? $request->bank_account_number : $shop->bank_account_number;
        $shop->bank_ifsc_code = $request->bank_ifsc_code ? $request->bank_ifsc_code : $shop->bank_ifsc_code;
        $shop->gumasta = $back_image;
        $shop->save();

        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }
    public function upload_image(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            $file = $request->file("image");
            $icon = time() . "_" . $file->getClientOriginalName();
            $path = $file->storeAs('public/shop-images', $icon);

            $u = Shop::where("user_id",Auth::user()->id)->first();
            $u->logo = $icon;
            $u->save();

            return response()->json(["status" => "success", "message" => "Uploaded Successfully", "image" => $icon], 200);
        }
    }

}
