<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\Otp;
use App\Models\Product;
use App\Models\Section1;
use App\Models\Section2;
use App\Models\Section4;
use App\Models\Section6;
use App\Models\Shop;
use App\User;
use Hassansin\DBCart\Models\Cart;
use Hassansin\DBCart\Models\CartLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {

        $section1 = Section1::all();
        $section2 = Section2::first();
        $section4 = Section4::first();
        $section6 = Section6::first();

        $shop_logo = Shop::all();


        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.index', ["section1" => $section1, "section2" => $section2, "section4" => $section4, "section6" => $section6,"shop_logo"=>$shop_logo]);
            }
        } else {
            return view('user.index', ["section1" => $section1, "section2" => $section2, "section4" => $section4, "section6" => $section6,"shop_logo"=>$shop_logo]);
        }
    }

    public function get_sliders($type)
    {
        if($type==1){   //new
            $res = Product::
            join("categories", "categories.id", "products.category_id")
                ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
                ->join("purities", "purities.id", "products.purity_id")
                ->join("occasion", "occasion.id", "products.occasion_id")
                ->join("users", "users.id", "products.seller_id")
                ->join("product_variants", "products.id", "product_variants.product_id")
                ->select("products.*", "product_variants.id as product_variant_id", "product_variants.selling_price", "categories.category_name", "sub_categories.sub_category_name", "purities.purity_name", "occasion.occasion")
                ->where("products.verified_by_admin", 1)
                ->where("new_arrival", 1)
                ->groupby("product_variants.product_id")
                ->orderby("id", "desc")
                ->get();
        }else if($type == 2){ //featured
            $res = Product::
            join("categories", "categories.id", "products.category_id")
                ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
                ->join("purities", "purities.id", "products.purity_id")
                ->join("occasion", "occasion.id", "products.occasion_id")
                ->join("users", "users.id", "products.seller_id")
                ->join("product_variants", "products.id", "product_variants.product_id")
                ->select("products.*", "product_variants.id as product_variant_id", "product_variants.selling_price", "categories.category_name", "sub_categories.sub_category_name", "purities.purity_name", "occasion.occasion")
                ->where("products.verified_by_admin", 1)
                ->where("featured", 1)
                ->groupby("product_variants.product_id")
                ->orderby("id", "desc")
                ->get();
        }else if($type==3){ //trdending
            $res = Product::
            join("categories", "categories.id", "products.category_id")
                ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
                ->join("purities", "purities.id", "products.purity_id")
                ->join("occasion", "occasion.id", "products.occasion_id")
                ->join("users", "users.id", "products.seller_id")
                ->join("product_variants", "products.id", "product_variants.product_id")
                ->select("products.*", "product_variants.id as product_variant_id", "product_variants.selling_price", "categories.category_name", "sub_categories.sub_category_name", "purities.purity_name", "occasion.occasion")
                ->where("products.verified_by_admin", 1)
                ->where("trending", 1)
                ->groupby("product_variants.product_id")
                ->orderby("id", "desc")
                ->get();
        }
        foreach ($res as $r) {
            $r->ratings = get_ratings($r->id);
        }
        return response()->json(['status' => 'success', 'res' => $res], 200);

    }


    public function login_register()
    {
        return view('user.login-register');
    }

    public function logout()
    {
        $user_type = Auth::user()->user_type;
        Auth::logout();
        if ($user_type == 0) {
            return redirect('/admin/login');
        } elseif ($user_type == 1) {
            return redirect("/seller/login-register");
        } else {
            return redirect("/login-register");
        }

    }

    public function send_email(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255']
        ]);
        $u = User::where("email", $request->email)->first();

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } elseif (!$u) {
            return response()->json(["status" => "error", "message" => "This email is not registered"], 400);
        } else {
            $msg = "Please use below link to reset your password";
            $token = md5(uniqid(1000, 9999));
            $expiry = time() + (60 * 10);

            DB::table("reset_password")->insert(["email" => $request->email, "token" => $token, "time" => time(), "expiry" => $expiry]);
            Mail::to($request->email)
                ->send(new ForgotPassword($token));
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function reset_password($id)
    {
        return view("user.reset-password", ["token" => $id]);
    }

    public function change_password(Request $request)
    {
        $res = DB::table("reset_password")->where(["token" => $request->token])->where("expiry", ">", time())->first();
        if ($res) {
            $u = User::where("email", $res->email)->first();
            $u->password = bcrypt($request->npassword);
            $u->save();

            DB::table("reset_password")->where("token", $request->token)->delete();

            Auth::login($u);
            return response()->json(['status' => 'success', 'message' => 'Success'], 200);
        } else {
            return response()->json(['status' => 'success', 'message' => 'Link is invalid or expired'], 400);
        }
    }

    public function check_email(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            return response()->json(["status" => "success", "message" => "email not registered"], 200);
        }
    }

    public function signup(Request $request)
    {
        $data = $request->all();

        if ($request->userType == 2) {
            $validator = Validator::make($data, [
                    'firstName' => ['required', 'string', 'max:255'],
                    'lastName' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'email' => ['required', 'string', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'max:255'],
                    'rpassword' => ['required', 'string', 'max:255']]
            );
        } else {
            $validator = Validator::make($data, [
                    'firstName' => ['required', 'string', 'max:255'],
                    'lastName' => ['required', 'string', 'max:255'],
                    'phone' => ['required'],
                    'email' => ['required', 'string', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'max:255'],
                    'rpassword' => ['required', 'string', 'max:255'],
                    'shopName' => ['required', 'string', 'max:255'],
                    'address1' => ['required', 'string', 'max:255'],
                    'address2' => ['required', 'string', 'max:255'],
                    'state' => ['required'],
                    'city' => ['required'],
                    'pinCode' => ['required'],
                    'gumasta' => 'required'
                ]
            );
        }

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $u = new User();
            $u->first_name = $request->firstName;
            $u->last_name = $request->lastName;
            $u->phone = $request->phone;
            $u->email = $request->email;
            $u->password = bcrypt($request->password);
            $u->phone = "+91" . $request->phone;
            $u->user_type = $request->userType;
            $u->save();

            if ($request->userType == 1) {

                if ($request->hasfile('gumasta')) {
                    $file1 = $request->file("gumasta");
                    $back_image = time() . "_" . $file1->getClientOriginalName();
                    $path = $file1->storeAs('public/gumasta-images', $back_image);
                }

                $s = new Shop();
                $s->shop_name = $request->shopName;
                $s->user_id = $u->id;
                $s->address1 = $request->address1;
                $s->address2 = $request->address2;
                $s->state = $request->state;
                $s->city = $request->city;
                $s->pin_code = $request->pinCode;
                $s->gumasta = $back_image;
                $s->save();
            }
            Auth::login($u);
            Otp::where("phone", "+91" . $request->phone)->delete();

            $otps = rand(100000, 999999);
            $email = $u->email;
            $sub = "Jewellerywala | User Registration";
            $type = 1; //registration
            $msg = "You have registered successfully on jewellerywala.co";
            send_email($email, $sub, $type, $msg);
            send__user_otp("$u->phone", "You have successfully registered on jewellerywala.co", $otps);
            return response()->json(["status" => "success", "message" => "User registered successfully"], 200);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $remember = $request->remember;
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        } else if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => $request->userType])) {
            $user = Auth::user();
            return response()->json(['status' => 'success', 'message' => 'Success', 'res' => Auth::user()], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Sorry, you entered an incorrect email address or password.'], 400);
        }
    }

    public function check_otp(Request $request)
    {
        $otp = $request->otp;

        $phone = "+91" . $request->phone;
        $o = Otp::where(["phone" => $phone, "otp" => $otp])->where("expiry", '>', time())->first();
        if ($o) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'You have entered wrong otp or Otp is expired'], 400);
        }
    }

    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
        } else {


            $otp = rand(100000, 999999);
            $phone = "+91" . $request->phone;

            $u = User::where("phone", $phone)->first();
            if ($u) {
                return response()->json([
                    'status' => 'error', "message" => "Phone Number already registered"
                ], 400);
            } else {
                $msg = "Verification code is " . $otp;

                $res = send__user_otp($phone, $msg, $otp);
                if ($res->type == "success") {
                    $o = new Otp();
                    $o->phone = $phone;
                    $o->otp = $otp;
                    $o->expiry = time() + (60 * 5);
                    $o->save();

                    return response()->json([
                        'status' => 'success', "otp" => $otp
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error', "message" => $res->message
                    ], 400);
                }
            }
        }
    }

    public function get_cities($state_id)
    {

        $city = DB::table("cities")->where("state_id", $state_id)->get();

        return response()->json(['status' => 'success', 'res' => $city], 200);
    }

    public function get_states()
    {
        $state = "";
        $city = DB::table("states")->where("country_id", 101)->get();
        return response()->json(['status' => 'success', 'res' => $city], 200);
    }

}
