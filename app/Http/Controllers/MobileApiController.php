<?php

namespace App\Http\Controllers;

use App;
use App\Events\OrderPlaced;
use App\Events\OrderPlacedAdmin;
use App\Mail\ForgotPassword;
use App\Models\Address;
use App\Models\Category;
use App\Models\Color;
use App\Models\CouponCode;
use App\Models\Notification;
use App\Models\Occasion;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Purity;
use App\Models\Rating;
use App\Models\Section1;
use App\Models\Section2;
use App\Models\Section4;
use App\Models\Section6;
use App\Models\ShippingCharges;
use App\Models\Shop;
use App\Models\SubCategory;
use App\Models\SuperCoin;
use App\Models\UsersSuperCoin;
use App\Models\UserWallet;
use App\Models\UserWalletHistory;
use App\Models\Wishlist;
use App\User;
use Hassansin\DBCart\Models\Cart;
use Hassansin\DBCart\Models\CartLine;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MobileApiController extends Controller
{

    public function signup(Request $request)
    {
        $data = $request->all();

        if ($request->userType == 2) {
            $validator = Validator::make($data, [
                    'firstName' => ['required', 'string', 'max:255'],
                    'lastName' => ['required', 'string', 'max:255'],
                    'phone' => ['required', 'unique:users'],
                    'email' => ['required', 'string', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'max:255']]
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
            $u->email = $request->email;
            $u->password = bcrypt($request->password);
            $u->phone = "+91" . $request->phone;
            $u->user_type = $request->userType;
            $u->save();


            Auth::login($u);
            $user = Auth::login($u);
            $tokenResult = $u->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();

            Otp::where("phone", "+91" . $request->phone)->delete();

            $otps = rand(100000, 999999);
            $email = $u->email;
            $sub = "Jewellerywala | User Registration";
            $type = 1; //registration
            $msg = "You have registered successfully on jewellerywala.co";
            send_email($email, $sub, $type, $msg);
            send__user_otp("$u->phone", "You have successfully registered on jewellerywala.co", $otps);
            return response()->json([
                "status" => "success",
                "message" => "User login successfully",
                'access_token' => $tokenResult->accessToken,
                'user' => $user,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ], 200);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => 'success', "message" => "Logout Successfully"], 200);

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
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();
            return response()->json([
                "status" => "success",
                "message" => "User login successfully",
                'access_token' => $tokenResult->accessToken,
                'user' => $user,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Sorry, you entered an incorrect email address or password.'], 400);
        }
    }

    public function send_forget_otp(Request $request)
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
            if (!$u) {
                return response()->json([
                    'status' => 'error', "message" => "Phone is not registered"
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
                        'status' => 'success', "otp" => $otp, "message" => "OTP Sent successfully"
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error', "message" => $res->message
                    ], 400);
                }
            }
        }
    }

    public function change_password(Request $request)
    {
        $u = User::where("phone", $request->phone)->first();
        $u->password = bcrypt($request->password);
        $u->save();
        Otp::where("phone", "+91" . $request->phone)->delete();
        Auth::login($u);
        return response()->json(['status' => 'success', 'message' => 'Password Changed successfully'], 200);

    }

    public function check_otp(Request $request)
    {
        $otp = $request->otp;

        $phone = "+91" . $request->phone;
        $o = Otp::where(["phone" => $phone, "otp" => $otp])->where("expiry", '>', time())->first();
        if ($o) {
            return response()->json(['status' => 'success', "message" => "OTP is correct"], 200);
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
                        'status' => 'success', "otp" => $otp, "message" => "OTP Sent successfully"
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error', "message" => $res->message
                    ], 400);
                }
            }
        }
    }

    public function get_categories()
    {
        $res = Category::all();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_subcategories($id)
    {
        $res = SubCategory::where("category_id", $id)->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_all_products(Request $request)
    {
        $res = Product::
        join("categories", "categories.id", "products.category_id")
            ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
            ->join("purities", "purities.id", "products.purity_id")
            ->join("occasion", "occasion.id", "products.occasion_id")
            ->join("shops", "shops.user_id", "products.seller_id")
            ->join("product_variants", "products.id", "product_variants.product_id")
            ->leftjoin("ratings", "ratings.product_id", "products.id")
            ->select("products.*", "product_variants.id as product_variant_id",
                "product_variants.selling_price", "product_variants.mrp",
                "categories.category_name", "sub_categories.sub_category_name",
                "purities.purity_name", "occasion.occasion",
                "shops.shop_name",
                DB::raw("avg(ratings) as ratings"))
            ->where("products.verified_by_admin", 1);

        if ($request->subCategory) {
            $res = $res->where("products.sub_category_id", $request->subCategory);
        }
        if ($request->purity) {
            $res = $res->where("products.purity_id", $request->purity);
        }
        if ($request->occasion) {
            $res = $res->whereIn("products.occasion_id", $request->occasion);
        }
        if ($request->brand) {
            $res = $res->whereIn("products.seller_id", $request->brand);
        }

        $tag = $request->name;

        if (!empty($tag)) {
            $res = $res->join("product_tags as pt", "products.id", "pt.product_id")
                ->where("pt.tag", "like", "%$tag%");

            $key = DB::table("user_search")->where("keyword", $tag)->first();
            if (!$key) {
                DB::table("user_search")->insert(["keyword" => urldecode($tag)]);
            }
        }

        if ($request->sellerName) {
            $res = $res->where("products.product_name", "like", "%" . $request->name . "%");
        }
        if ($request->productId) {
            $res = $res->where("products.product_id_manual", "like", "%" . $request->productId . "%");
        }
        if ($request->minPrice && $request->maxPrice) {
            $res = $res->whereBetween("product_variants.selling_price", array($request->minPrice, $request->maxPrice));
        }

        $res = $res->groupby("product_variants.product_id");

        if ($request->sortBy == 1) {
            $res = $res->orderby("product_name", "asc");
        } else if ($request->sortBy == 2) {
            $res = $res->orderby("product_name", "desc");
        } else if ($request->sortBy == 3) {
            $res = $res->orderby("product_variants.selling_price", "asc");
        } else if ($request->sortBy == 4) {
            $res = $res->orderby("product_variants.selling_price", "desc");
        } else if ($request->sortBy == 5) {
            $res = $res->orderby("ratings", "asc");
        } else if ($request->sortBy == 6) {
            $res = $res->orderby("ratings", "desc");
        } else {
            $res = $res->orderby("id", "desc");
        }
        $res = $res->groupby("products.id")->paginate(10);


        foreach ($res as $r) {
            if ($r->ratings == null) {
                $r->ratings = 0;
            }
        }

        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_single_product($id, Request $request)
    {
        $user_id = $request->user_id;
        $res = Product::join("product_variants", "products.id", "product_variants.product_id")
            ->join("color", "color.id", "product_variants.color_id")
            ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
            ->join("purities", "purities.id", "products.purity_id")
            ->join("shops", "shops.user_id", "products.seller_id")
            ->select("products.*", "product_variants.id as product_variant_id",
                "product_variants.selling_price",
                "product_variants.mrp",
                "product_variants.short_description",
                "product_variants.long_description",
                "product_variants.size", "product_variants.weight",
                "color.color as color_id", "sub_categories.sub_category_name",
                "purities.purity_name", "shops.shop_name")
            ->where("product_variants.id", $id)
            ->first();

        $product_id = "";
        $sub_category_id = "";
        $product = ProductVariant::find($id);
        if ($product) {
            $product_id = $product->product_id;
            $sub_category_id = $product->sub_category_id;
            $res->variant = ProductVariant::select("product_variants.*", "color.color")
                ->join("color", "color.id", "product_variants.color_id")
                ->where("product_variants.product_id", $product_id)
                ->get();

            $res->variant_images = ProductVariantImage::where("product_variant_id", $id)->get();

            $res->ratings = get_ratings($res->id);
            $res->reviews = Rating::join('users', 'ratings.user_id', 'users.id')
                ->select('ratings.*', 'users.first_name', 'users.last_name')
                ->where("product_id", $product_id)->get();
            $fav = Cart::join("cart_lines", "cart.id", "cart_lines.cart_id")
                ->where(["cart_lines.product_id" => $product_id, "user_id" => $user_id])
                ->first();

            if ($fav) {
                $res->fav = true;
            } else {
                $res->fav = false;
            }

        }

        if ($res) {
            return response()->json(["status" => "success", "res" => $res], 200);

        } else {
            return response()->json(["status" => "error", "message" => "No Data Found"], 200);

        }


    }

    public function get_related_product($id)
    {
        $product_id = ProductVariant::find($id)->product_id;
        $sub_category_id = Product::find($product_id)->sub_category_id;

        $related_prodcuts = Product::join("product_variants", "products.id", "product_variants.product_id")
            ->select("products.*", "product_variants.id as product_variant_id", "product_variants.selling_price")
            ->where(["products.verified_by_admin" => 1, "sub_category_id" => $sub_category_id])
            ->where("products.id", "!=", $product_id)
            ->groupBy("products.id")
            ->get();

        foreach ($related_prodcuts as $r) {
            $r->ratings = get_ratings($r->id);
        }
        return response()->json(["status" => "success", "res" => $related_prodcuts], 200);

    }

    public function get_homepage_products()
    {
        $section1 = Section1::all();
        $section2 = Section2::first();

        $new = Product::join("categories", "categories.id", "products.category_id")
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
            ->limit(6)
            ->get();
        foreach ($new as $r) {
            $r->ratings = get_ratings($r->id);
        }

        $featured = Product::join("categories", "categories.id", "products.category_id")
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
            ->limit(6)
            ->get();
        foreach ($featured as $r) {
            $r->ratings = get_ratings($r->id);
        }

        $trending = Product::join("categories", "categories.id", "products.category_id")
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
            ->limit(6)
            ->get();
        foreach ($trending as $r) {
            $r->ratings = get_ratings($r->id);
        }
        $brands = Shop::orderby("id", "desc")->limit(6)->get();


        return response()->json(['status' => 'success',
            'main_slider' => $section1,
            "offers" => $section2,
            "new" => $new,
            "featured" => $featured,
            "trending" => $trending,
            "brands" => $brands
        ], 200);

    }

    public function add_to_cart($id, Request $request)
    {
        $product_id = $request->productId;
        $item_id = $request->variantId;
        $pi = ProductVariant::find($item_id);
        $product_price = $request->sellingPrice;
        $product_qty = $request->qty;

        $cart = Cart::where(["user_id" => $id, "name" => "default", "status" => "active"])->first();

        if ($cart) {
            $cl = new CartLine();
            $cl->cart_id = $cart->id;
            $cl->product_id = $product_id;
            $cl->quantity = $product_qty;
            $cl->unit_price = $product_price;
            $cl->save();

        } else {
            $cart = App::make('cart');
            $cart->addItem([
                'product_id' => $product_id,
                'unit_price' => $product_price,
                'quantity' => $product_qty
            ]);
        }
        $cart->user_id = $id;
        $cart->save();
        return response()->json(['status' => 'success', 'message' => "Item Successfully added to cart"], 200);
    }

    public function get_cart($id)
    {
        $cart = Cart::where(["user_id" => $id, "name" => "default", "status" => "active"])->first();
        if ($cart) {
            $items = CartLine::join("products", "cart_lines.product_id", "products.id")
                ->join("categories", "categories.id", "products.category_id")
                ->join("shops", "shops.user_id", "products.seller_id")
                ->join("product_variants","product_variants.product_id","products.id")
                ->select("product_variants.id as product_variant_id","cart_lines.*", "products.product_name", "products.front_image", "categories.category_name", "shops.shop_name")
                ->where("cart_id", $cart->id)->get();
            $total_count = count(CartLine::where("cart_id", $cart->id)->get());
            $total_price = $cart->total_price;
        } else {
            $items = array();
            $total_price = 0;
            $total_count = 0;
        }
        return response()->json(['status' => 'success', 'res' => $items, 'total_amount' => $total_price, 'total_count' => $total_count], 200);
    }

//    public function update_cart_quantity($item_id, Request $request)
//    {
//        if (Auth::user()) {
//            CartLine::where("id", $item_id)->update(["quantity" => $request->qty]);
//        } else {
//            $cart = Cart::current("guest_cart");
//            $cart->updateItem([
//                'id' => $item_id
//            ], [
//                'quantity' => $request->qty
//            ]);
//        }
//        return response()->json(['status' => 'success', 'message' => "updated successfully"], 200);
//    }

    public function remove_cart_item($id)
    {
        CartLine::find($id)->delete();
        return response()->json(['status' => 'success', 'message' => "Item removed successfully"], 200);
    }

    public function add_to_wishlist($id, Request $request)
    {
//        $product_id = $request->productId;
//        $item_id = $request->variantId;
//        $pi = ProductVariant::find($item_id);
//        $product_price = $request->sellingPrice;
//        $product_qty = $request->qty;
//
//        $wishlist = Cart::where(["user_id" => $id, "name" => "wishlist", "status" => "active"])->first();
//        if ($wishlist) {
//            $cl = new CartLine();
//            $cl->cart_id = $wishlist->id;
//            $cl->product_id = $product_id;
//            $cl->quantity = $product_qty;
//            $cl->unit_price = $product_price;
//            $cl->save();
//        } else {
//            $wishlist = app('cart', ['name' => 'wishlist']);
//            $wishlist->addItem([
//                'product_id' => $product_id,
//                'unit_price' => $product_price,
//                'quantity' => $product_qty
//            ]);
//        }
//        $wishlist->user_id = $id;
//        $wishlist->save();

        $product_id = $request->productId;
        $product_price = $request->sellingPrice;
        $wishlist = new Wishlist();
        $wishlist->user_id = $id;
        $wishlist->product_id = $product_id;
        $wishlist->unit_price = $product_price;
        $wishlist->save();


        return response()->json(['status' => 'success', 'message' => "Item Successfully added to wishlist"], 200);
    }

    public function remove_wishlist_item($id)
    {
        Wishlist::find($id)->delete();

        return response()->json(['status' => 'success', 'message' => "Item removed successfully"], 200);
    }

    public function get_wishlist($id)
    {
//        $cart = Cart::where(["user_id" => $id, "name" => "wishlist", "status" => "active"])->first();
//
//        $items = CartLine::join("products", "cart_lines.product_id", "products.id")
//            ->join("categories", "categories.id", "products.category_id")
//            ->join("shops", "shops.user_id", "products.seller_id")
//            ->select("cart_lines.*", "products.product_name", "products.front_image", "categories.category_name", "shops.shop_name")
//            ->where("cart_id", $cart->id)->get();
        $wishlist = Wishlist::select("wishlist.*","product_variants.id as product_variant_id")
            ->join("product_variants","product_variants.product_id","wishlist.product_id")
            ->where("user_id", $id)->get();
        foreach ($wishlist as $a) {
            $a->product_name = Product::where("id", $a->product_id)->pluck("product_name")[0];
            $a->product_image = Product::where("id", $a->product_id)->pluck("front_image")[0];
        }
        return response()->json(['status' => 'success', 'res' => $wishlist], 200);
    }

//    public function update_wishlist_quantity($item_id, Request $request)
//    {
//        CartLine::where("id", $item_id)->update(["quantity" => $request->qty]);
//
//        return response()->json(['status' => 'success', 'message' => "updated successfully"], 200);
//    }

//    public function move_All_items_to_cart(Request $request)
//    {
//        if (Auth::user()) {
//            $cart = Cart::current();
//        } else {
//            $cart = Cart::current("guest_cart");
//        }
//
////        $cart = Cart::current();
////        if ($cart) {
////            $cart = App::make('cart');
////        }
//        $wishlist = app('cart', ['name' => 'wishlist']);
//        $wishlist->moveItemsTo($cart);
//
//        return response()->json(['status' => 'success', 'message' => "updated successfully"], 200);
//    }

    public function checkout_process($id)
    {
        $cart = Cart::find($id);
        $cart->status = "pending";
        $cart->placed_at = date("Y-m-d h:i:s", time());
        $cart->save();
        return response()->json(['status' => 'success', 'message' => "Checkout Successfully Completed", 'cart_id' => $cart->id], 200);
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

    public function get_address($user_id)
    {
        $user = User::find($user_id);
        $address = DB::table("address")->where("user_id", $user_id)->first();
        return response()->json(['status' => 'success', 'res' => $address, 'user' => $user], 200);
    }

    public function update_address($user_id, Request $request)
    {
        $address1 = $request->input("address1");
        $address2 = $request->input("address2");
        $state = $request->input("state");
        $city = $request->input("city");
        $pin_code = $request->input("pin_code");
        $first_name = $request->input("first_name");
        $last_name = $request->input("last_name");
        $phone = $request->input("phone");
        $email = $request->input("email");

        $add = Address::where("user_id", $user_id)->first();
        if (!$add) {
            $add = new Address();
        }

        $add->user_id = $user_id;
        $add->full_name = $first_name . " " . $last_name;
        $add->address1 = $address1;
        $add->address2 = $address2;
        $add->state = $state;
        $add->city = $city;
        $add->pin_code = $pin_code;
        $add->mobile = $phone;
        $add->email = $email;
        $add->save();

        return response()->json(['status' => 'success', 'message' => "Address Updated Successfully"], 200);
    }

    public function apply_coupon_code(Request $request)
    {
        $cart_id = $request->cart_id;
        $cart = Cart::find($cart_id);
        $product_id = CartLine::where("cart_id", $cart_id)->pluck("product_id");
        $total_price = $cart->total_price;
        $category_ids = Product::whereIn("id", $product_id)->pluck("category_id")->toArray();
        // dd($category_ids);
        DB::enableQueryLog();
        $res = DB::table("coupon_code")
            ->where("code", $request->coupon_code)
            ->where("valid_till", ">=", time())
            ->where("remaining_coupon", ">", 0)
            ->whereIn("category_id", $category_ids)
            ->where("min_price", "<=", $total_price)
            ->where("max_price", ">=", $total_price)
            ->first();
        if ($res) {
            return response()->json(['status' => 'success', 'res' => $res], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => "Coupon code is wrong or expired or does not belongs to this product category or not applicable for this price amount"], 400);
        }
    }

    public function get__cart_orders($id = null, Request $request)
    {

        $cart = Cart::find($id);
        $items = CartLine::where("cart_id", $id)->get();

        $total_price = $cart->total_price;

        foreach ($items as $a) {
            $a->product_name = Product::where("id", $a->product_id)->pluck("product_name")[0];
            $a->product_image = Product::where("id", $a->product_id)->pluck("front_image")[0];
        }


        $shipping_charges = ShippingCharges::first();
        if ($total_price < $shipping_charges->shipping_criteria) {
            $shipping_charges = $shipping_charges->shipping_charges;
        } else {
            $shipping_charges = 0;
        }

        $super_coin_amount = 0;
        $wallet_amount = 0;
        $total_orders = 0;

        $super_coin_amount = UsersSuperCoin::where("user_id", $cart->user_id)->first();
        if ($super_coin_amount) {
            $super_coin_amount = $super_coin_amount->super_coin_amount;
        } else {
            $super_coin_amount = 0;
        }
        $wallet_amount = UserWallet::where("user_id", $cart->user_id)->first();
        if ($wallet_amount) {
            $wallet_amount = $wallet_amount->wallet_amount;
        } else {
            $wallet_amount = 0;
        }

        $total_orders = Order::where("user_id", $cart->user_id)->count();

        return response()->json(['status' => 'success', 'res' => $items, 'total_orders' => $total_orders, 'total_amount' => $total_price, "shipping_charges" => $shipping_charges, "super_coin_amount" => $super_coin_amount, "wallet_amount" => $wallet_amount], 200);
    }

    public function place_order(Request $request)
    {
        $fname = $request->input("fname");
        $lname = $request->input("lname");
        $email = $request->input("email");
        $mobile = $request->input("phone");
        $address1 = $request->input("address1");
        $address2 = $request->input("address2");
        $state = $request->input("state");
        $city = $request->input("city");
        $pin_code = $request->input("pin_code");
        $cart_id = $request->input("cartId");
        $total_amount = $request->input("totalAmount");
        $grand_total = $request->input("grandTotal");
        $coupon_code = $request->input("coupon_code");
        $coupon_discount = $request->input("coupon_discount");
        $shipping_charge = $request->input("shippingCharges");
        $user_id = $request->input("user_id");

        if ($coupon_code) {
            $res = CouponCode::where("code", $request->coupon_code)
                ->where("valid_till", ">=", time())
                ->where("remaining_coupon", ">", 0)
                ->first();
            if ($res) {
                $res->remaining_coupon = $res->remaining_coupon - 1;
                $res->save();
            } else {
                return response()->json(['status' => 'error', 'message' => "Coupon code is wrong or expired"], 400);
            }
        }

        $add = Address::where("user_id", $user_id)->first();
        if (!$add) {
            $add = new Address();
        }

        $add->user_id = $user_id;
        $add->full_name = $fname . " " . $lname;
        $add->email = $email;
        $add->mobile = $mobile;
        $add->address1 = $address1;
        $add->address2 = $address2;
        $add->state = $state;
        $add->city = $city;
        $add->pin_code = $pin_code;
        $add->save();


        $order_id = "JWORD" . rand(10000, 99999);
        $ord = new Order();
        $ord->user_id = $user_id;
        $ord->address_id = $add->id;
        $ord->cart_id = $cart_id;
        $ord->order_id = $order_id;
        $ord->price = $total_amount;
        $ord->final_amount = $grand_total;
        $ord->coupon_code = $coupon_code;
        $ord->discount = $coupon_discount;
        $ord->payment_mode = "COD";
        $ord->super_coin_amount = $request->used_super_coin_amount;
        $ord->wallet_amount = $request->used_wallet_amount;
        $ord->payment_status = "PENDING";
        $ord->order_status = 0;
        $ord->deliver_date = time();
        $ord->delivered_status = 0;
        $ord->shipping_charge = $shipping_charge;
        $ord->save();


        $spa = UsersSuperCoin::where("user_id", $user_id)->first();
        if ($spa) {
            $spa->super_coin_amount = $spa->super_coin_amount - $request->used_super_coin_amount;
            $spa->save();
        }

        $spa = UserWallet::where("user_id", $user_id)->first();
        if ($spa) {
            $spa->wallet_amount = $spa->wallet_amount - $request->used_wallet_amount;
            $spa->save();
        }


        if ($request->used_wallet_amount) {
            $wallet = UserWallet::where("user_id", $user_id)
                ->where("wallet_amount", ">", 0)->first();
            $rem_wallet_amount = 0;
            if ($wallet) {
                $rem_wallet_amount = $wallet->wallet_amount;
                $wallet->wallet_amount = $wallet->wallet_amount - $request->used_wallet_amount;
                $wallet->save();

            }
            $ord = new UserWalletHistory();
            $ord->user_id = $user_id;
            $ord->order_id = $order_id;
            $ord->remaining_wallet_amount = $rem_wallet_amount;
            $ord->deposit_withdraw_amount = $request->used_wallet_amount;
            $ord->transaction_type = 2;
            $ord->payment_status = "SUCCESS";
            $ord->save();
        }


        return response()->json(['status' => 'success', 'message' => "Order Placed Successfully", 'orderId' => $order_id], 200);
    }

    public function payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $payment_id = $request->paymentId;
        $payment_mode = "Online(razorpay)";
        if ($payment_id == 1) {
            $payment_id = "super-coin";
            $payment_mode = "Offline(Cashback Money)";
        }
        $o = Order::where("order_id", $order_id)->first();

        $products = CartLine::join("products", "cart_lines.product_id", "products.id")
            ->where("cart_lines.cart_id", $o->cart_id)->get();

        $total_commission = 0;

        foreach ($products as $p) {
            $total_commission += calculate_commission($p->purity_id, $p->unit_price);
        }


        $o->payment_status = "SUCCESS";
        $o->payment_mode = $payment_mode;
        $o->payment_id = $payment_id;
        $o->commission = $total_commission;
        $o->save();

        $cart = Cart::find($o->cart_id);
        $cart->status = "complete";
        $cart->completed_at = date("Y-m-d h:i:s", time());
        $cart->save();

        $super_coin = SuperCoin::where("min_shopping_amount", "<=", $o->final_amount)
            ->where("max_shopping_amount", ">=", $o->final_amount)
            ->first();

        if ($super_coin) {
            $o->received_super_coin_amount = $super_coin->super_coin_amount;
            $o->save();
            $usc = UsersSuperCoin::where("user_id", $o->user_id)->first();

            if (!$usc) {
                $usc = new UsersSuperCoin();
                $usc->user_id = $o->user_id;
                $usc->order_id = $o->id;
                $usc->super_coin_amount = $super_coin->super_coin_amount;
                $usc->save();
            } else {
                $usc->order_id = $o->id;
                $usc->super_coin_amount = $usc->super_coin_amount + $super_coin->super_coin_amount;
                $usc->save();
            }

        }
        $user = User::find($o->user_id);
        $email = $user->email;
        $sub = "Jewellerywala | Place Order";
        $type = 2; //place order
        $msg = "You have successfully placed order on jewellerywala.co, Your order id is " . $o->order_id;
        send_email($email, $sub, $type, $msg);

        $phone = $user->phone;
        send_msg($phone, $msg);

        $p_id = CartLine::where("cart_id", $o->cart_id)->pluck("product_id");

        $seller_id = Product::whereIn("id", $p_id)->pluck("seller_id");
        $admin_id = User::where("user_type", 0)->first()->id;


        $first_name = $user->first_name;
        $last_name = $user->last_name;

        $an = new Notification();
        $an->sender_id = $o->user_id;
        $an->rec_id = $admin_id;
        $an->notification = "New Order Placed by " . $first_name . " " . $last_name;
        $an->date = time();
        $an->url = "orders";
        $an->save();

        event(new OrderPlacedAdmin($an));


        foreach ($seller_id as $sid) {
            $n = new Notification();
            $n->sender_id = $o->user_id;
            $n->rec_id = $sid;
            $n->notification = "New Order Placed by " . $first_name . " " . $last_name;
            $n->date = time();
            $n->url = "orders";
            $n->save();
            event(new OrderPlaced($n));
        }

        return response()->json(['status' => 'success', 'message' => "Order Placed Successfully"], 200);
    }

    public function get_orders($id)
    {
        $orders = DB::table("orders")->where('user_id', $id)->orderby("id", "desc")->paginate(10);
        foreach ($orders as $o) {
            if (strtotime('+7 day', $o->deliver_date) > time()) {
                $o->show = true;
            } else {
                $o->show = false;
            }
        }
        return response()->json(['status' => 'success', 'res' => $orders], 200);
    }

    public function get_order_details($id)
    {
        $res = Order::select("cart_lines.quantity", "cart_lines.unit_price", "products.product_name", "products.front_image", "categories.category_name", "shops.shop_name")
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->join("categories", "categories.id", "products.category_id")
            ->join("shops", "shops.user_id", "products.seller_id")
            ->where("orders.id", $id)
            ->get();
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function cancel_order($id)
    {
        $o = Order::find($id);
        $o->order_status = 1;
        $o->save();

        $usc = UsersSuperCoin::where("user_id", $o->user_id)->first();
        if ($usc) {
            $usc->super_coin_amount = $usc->super_coin_amount - $o->received_super_coin_amount;
            $usc->save();
        }
        $email = User::find($o->user_id)->email;
        $sub = "Jewellerywala | Order Cancelled";
        $msg = "Your order(order id " . $o->order_id . ") is successfully cancelled on jewellerywala.co";
        $type = 8; //cancel order
        send_email($email, $sub, $type, $msg);
        $phone = User::find($o->user_id)->phone;
        send_msg($phone, $msg);

        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }

    public function return_order(Request $request)
    {
        $res = Order::find($request->order_id);
        $res->reason = $request->reason;
        $res->delivered_status = 3;
        $res->save();

        $email = User::find($res->user_id)->email;

        $sub = "Jewellerywala | Order Return Request";
        $msg = "Your order(order id " . $res->order_id . ") is successfully requested for return on jewellerywala.co";
        $type = 5; //return request
        send_email($email, $sub, $type, $msg);
        $phone = User::find($res->user_id)->phone;
        $msg = send_msg($phone, $msg);
        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }

    public function get_cashback_orders($user_id)
    {
        $orders = DB::table("orders")
            ->where('user_id', $user_id)
            ->where(["order_status" => 0, "delivered_status" => 2])
            ->get();
        $cashback = DB::table("orders")
            ->where('user_id', $user_id)
            ->where(["order_status" => 0, "delivered_status" => 2])
            ->sum('received_super_coin_amount');


        // $cashback = DB::table("users_super_coin")->where("user_id", Auth::user()->id)->first();
        return response()->json(['status' => 'success', 'res' => $orders, 'cashback' => $cashback], 200);
    }

    public function get_user($id)
    {
        $user = User::find($id);
        return response()->json(['status' => 'success', 'res' => $user], 200);

    }

    public function update_user(Request $request)
    {
        $user = User::find($request->id);
        $user->first_name = $request->first_name ? $request->first_name : $user->first_name;
        $user->last_name = $request->last_name ? $request->last_name : $user->last_name;
        $user->password = $request->password ? bcrypt($request->password) : $user->password;
        $user->save();
        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }


    public function get_wallet_history($user_id)
    {
        $res = UserWalletHistory::where("user_id", $user_id)->orderBy("id", "desc")->paginate(10);
        $total = User::leftjoin("user_wallet", "users.id", "user_wallet.user_id")->where("users.id", $user_id)->first();

        $cashback = DB::table("orders")
            ->where('user_id', $user_id)
            ->where(["order_status" => 0, "delivered_status" => 2])
            ->sum('received_super_coin_amount');

        return response()->json(["status" => "success", "res" => $res, "total" => $total, "cashback"=>$cashback], 200);
    }

    public function get_data($type)
    {
        if ($type == 1) {
            $res = Category::with('subcategory')->get();
        } else if ($type == 2) {
            $res = Category::with('purity')->get();
        } else if ($type == 3) {
            $res = Occasion::get();
        } else if ($type == 4) {
            $res = Shop::select("id", "shop_name")->get();
        }
        return response()->json(["status" => "success", "res" => $res], 200);

    }


    #####################################################################
    ######################SELLER APIS#############################
    ################################################################

    public function get_seller_orders(Request $request)
    {
        $auth_user_type = 1;

        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name', "s.first_name as s_f_name", "s.last_name as s_l_name", "s.id as seller_id")
            ->join('users', 'users.id', 'orders.user_id')
            ->join("cart", "cart.id", "orders.cart_id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->join("users as s", "products.seller_id", "s.id")
            ->groupby("orders.id");
        if ($auth_user_type == 1) {
            $orders = $orders->where("products.seller_id", $request->user_id);

        }
        if ($request->productId) {
            $orders = $orders->where("orders.order_id", "like", "%$request->productId%");
        }
        if ($request->fromDate) {
            $fromDate = date("Y-m-d", strtotime($request->fromDate));
            $orders = $orders->whereDate("orders.created_at", ">=", $fromDate);
        }
        if ($request->toDate) {
            $toDate = date("Y-m-d", strtotime($request->toDate));
            $orders = $orders->whereDate("orders.created_at", "<=", $toDate);
        }
        if ($request->userName) {
            $un = explode(" ", $request->userName);
            if (isset($un[0]) && isset($un[1])) {
                $orders = $orders->where('users.first_name', "like", "%$un[0]%")
                    ->where('users.last_name', 'like', "%$un[1]%")
                    ->orWhere('users.first_name', 'like', "%$un[1]%")
                    ->where('users.last_name', 'like', "%$un[0]%");
            } else {
                $name = $request->userName;
                $orders = $orders->where(function ($query) use ($name) {
                    $query->where('users.first_name', "like", "%$name%");
                    $query->orWhere(function ($query) use ($name) {
                        $query->where('users.last_name', "like", "%$name%");
                    });
                });
            }
        }
        $orders = $orders->orderby("orders.id", "desc")
            ->paginate(10);

        if ($auth_user_type == 1) {
            foreach ($orders as $o) {
                $o->order_total = get_seller_order_total($o->id, $request->user_id);
            }

        }
        return response()->json(['status' => 'success', 'res' => $orders], 200);

    }

    public function get_dashboard_data($user_id)
    {
        $total_orders = count(get_seller_total_placed_orders($user_id, 0));
        $total_return = count(get_seller_total_placed_orders($user_id, 3));
        $total_delivered = count(get_seller_total_placed_orders($user_id, 2));
        $total_products = Product::where(["verified_by_admin" => 1, "seller_id" => $user_id])->count();

        return response()->json(['status' => 'success', 'total_orders' => $total_orders,
            "total_return" => $total_return,
            "total_delivered" => $total_delivered,
            "total_products" => $total_products], 200);
    }

    public function get_daily_total_orders($type, Request $request)
    {
        $date = [];
        $amount = [];
        $id = $request->user_id;
        if ($type == 1) {
            $today = Carbon::today();
            $week_start = $today->subDays(6)->format("Y-m-d");
            for ($i = 0; $i < 7; $i++) {
                $amt = get_daily_total_orders_seller_api($week_start,$id);

                $dt = strtotime($week_start);
                $date[$i] = $week_start . "(" . date("D", $dt) . ")";
                $amount[$i] = $amt;
                $week_start = date("Y-m-d", strtotime("$week_start +1 day"));
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        } else if ($type == 2) {
            $cur_date = Carbon::now();
            $last_month_num = $cur_date->subMonth()->format('m');
            $last_day = new Carbon('last day of last month');
            $last_day = $last_day->format("Y-m-d");
            $month_arr_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            for ($i = 0; $i < 5; $i++) {
                $carbon = new Carbon('first day of last month');
                $weeks_dates[$i] = $carbon->addWeeks($i + 1)->format('Y-m-d');
            }

            for ($i = 0; $i < sizeof($weeks_dates); $i++) {

                /************ get visits **************/

                $date[$i] = $weeks_dates[$i];
                if ($i < 3) {
                    $amt = get_weekly_total_orders_seller_api($weeks_dates[$i], $weeks_dates[$i + 1],$id);
                }
                if ($i == 4) {
                    $date[$i] = $last_day;
                    $amt = get_weekly_total_orders_seller_api($weeks_dates[3], $last_day,$id);
                }


                $amount[$i] = $amt;

                $date[$i] = $i + 1 . " Week";

                $last_month = $month_arr_en[$last_month_num - 1];

            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount, "last_month" => $last_month], 200);
        } else if ($type == 3) {
            $now = Carbon::now();
            $year = $now->year;
            $monthName1 = [];
            for ($i = 0; $i <= 11; $i++) {
                $monthName1[$i] = date('F', mktime(0, 0, 0, $i + 1, 10));
            }
            $monthName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            for ($i = 0; $i < sizeof($monthName); $i++) {

                $f = new Carbon("first day of $monthName1[$i] $year");
                $first_day[$i] = $f->format("Y-m-d");

                $l = new Carbon("last day of $monthName1[$i] $year");
                $last_day[$i] = $l->format("Y-m-d");

                /************ get visits **************/

                $date[$i] = $monthName[$i];

                $amt = get_weekly_total_orders_seller_api($first_day[$i], $last_day[$i],$id);

                $amount[$i] = $amt;
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        }


    }

    public function get_daily_total_amount($type, Request $request)
    {
        $id = $request->user_id;
        $date = [];
        $amount = [];

        if ($type == 1) {
            $today = Carbon::today();
            $week_start = $today->subDays(6)->format("Y-m-d");
            for ($i = 0; $i < 7; $i++) {
                $amt = get_daily_orders_seller_api($week_start,$id);
                if ($amt == null) {
                    $amt = 0;
                } else {
                    $amt = $amt->amount;
                }
                $dt = strtotime($week_start);
                $date[$i] = $week_start . "(" . date("D", $dt) . ")";
                $amount[$i] = $amt;
                $week_start = date("Y-m-d", strtotime("$week_start +1 day"));
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        } else if ($type == 2) {
            $cur_date = Carbon::now();
            $last_month_num = $cur_date->subMonth()->format('m');
            $last_day = new Carbon('last day of last month');
            $last_day = $last_day->format("Y-m-d");
            $month_arr_en = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            for ($i = 0; $i < 5; $i++) {
                $carbon = new Carbon('first day of last month');
                $weeks_dates[$i] = $carbon->addWeeks($i + 1)->format('Y-m-d');
            }

            for ($i = 0; $i < sizeof($weeks_dates); $i++) {

                /************ get visits **************/

                $date[$i] = $weeks_dates[$i];
                if ($i < 3) {
                    $amt = get_weekly_orders_seller_api($weeks_dates[$i], $weeks_dates[$i + 1],$id);
                }
                if ($i == 4) {
                    $date[$i] = $last_day;
                    $amt = get_weekly_orders_seller_api($weeks_dates[3], $last_day,$id);
                }

                if ($amt) {
                    $amount[$i] = $amt->amount;
                } else {
                    $amount[$i] = 0;
                }

                $date[$i] = $i + 1 . " Week";

                $last_month = $month_arr_en[$last_month_num - 1];

            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount, "last_month" => $last_month], 200);
        } else if ($type == 3) {
            $now = Carbon::now();
            $year = $now->year;
            $monthName1 = [];
            for ($i = 0; $i <= 11; $i++) {
                $monthName1[$i] = date('F', mktime(0, 0, 0, $i + 1, 10));
            }
            $monthName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            for ($i = 0; $i < sizeof($monthName); $i++) {

                $f = new Carbon("first day of $monthName1[$i] $year");
                $first_day[$i] = $f->format("Y-m-d");
                $l = new Carbon("last day of $monthName1[$i] $year");
                $last_day[$i] = $l->format("Y-m-d");

                /************ get visits **************/

                $date[$i] = $monthName[$i];

                $amt = get_weekly_orders_seller_api($first_day[$i], $last_day[$i],$id);
                if ($amt == null) {
                    $amt = 0;
                }else{
                    $amt = $amt->amount;
                }
                $amount[$i] = $amt;
            }
            return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

        }


    }

    public function get_all_products_seller(Request $request)
    {
        $auth_user = User::find($request->sellerId);
        $res = Product::
        join("categories", "categories.id", "products.category_id")
            ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
            ->join("purities", "purities.id", "products.purity_id")
            ->join("occasion", "occasion.id", "products.occasion_id")
            ->join("users", "users.id", "products.seller_id")
            ->join("shops", "shops.user_id", "users.id")
            ->select("products.*", "shops.shop_name", "categories.category_name", "sub_categories.sub_category_name", "purities.purity_name", "occasion.occasion");

        if ($auth_user->user_type == 1) {
            $res = $res->where("products.seller_id", $auth_user->id);
        }
        if ($auth_user->user_type == 0) {
            $res = $res->where("shops.shop_name", "like", "%" . $request->sellerName . "%");
        }

        if ($request->category) {
            $res = $res->where("products.category_id", $request->category);
        }

        if ($request->subCategory) {
            $res = $res->where("products.sub_category_id", $request->subCategory);
        }

        if ($request->purity) {
            $res = $res->where("products.purity_id", $request->purity);
        }

        if ($request->occasion) {
            $res = $res->where("products.occasion_id", $request->occasion);
        }

        if ($request->name) {
            $res = $res->where("products.product_name", "like", "%" . $request->name . "%");
        }

        if ($request->productId) {
            $res = $res->where("products.product_id_manual", "like", "%" . $request->productId . "%");
        }

        $res = $res->orderby("id", "desc")
            ->paginate(10);

        //dd(DB::getQueryLog());

        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function calculate_mrp(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'purity' => ['required'],
            'mc' => ['required'],
            'weight' => ['required']
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $metal_price = Purity::find($request->purity)->price;
            $mrp = $metal_price * $request->weight + $request->mc * $request->weight;
            return response()->json(["status" => "success", "mrp" => $mrp], 200);

        }
    }

    public function add_product(Request $request)
    {
        $data = $request->all();
        $seller_id = $request->sellerId;
        $product_id = "JWPR" . rand(10000, 99999);
        $product_id_manual = $request->productId;
        $validator = Validator::make($data, [
            'productId' => ['required', 'string', 'max:255', Rule::unique('products', 'product_id_manual')->where(function ($query) use ($product_id_manual, $seller_id) {
                return $query->where('seller_id', $seller_id)
                    ->where('product_id_manual', $product_id_manual);
            })],
            'productName' => ['required', 'string', 'max:255'],
            'category' => ['required'],
            'subCategory' => ['required'],
            'purity' => ['required'],
            'occasion' => ['required'],
            'gender' => ['required'],
            'tax' => ['required'],
            'frontImage' => ['mimes:jpeg,jpg,png,gif,JPG,JPEG,PNG,GIF|required|max:10000'],
            'backImage' => ['mimes:jpeg,jpg,png,gif,JPG,JPEG,PNG,GIF|required|max:10000'],
            'tag' => 'required',
            'name' => ['required'],
            'size' => ['required'],
            'mc' => ['required'],
            'weight' => ['required'],
            'color' => ['required'],
            'mrp' => ['required'],
            'discount' => ['required'],
            'sellingPrice' => ['required'],
            'shortDescription' => ['required'],
            'longDescription' => ['required'],
            'variantImages' => ['required'],
            'variantImages.*' => ['mimes:jpeg,jpg,png,gif,JPG,JPEG,PNG,GIF|max:20000'],

        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('frontImage')) {
                $file = $request->file("frontImage");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/product-images', $front_image);
            }
            if ($request->hasfile('backImage')) {
                $file1 = $request->file("backImage");
                $back_image = time() . "_" . $file1->getClientOriginalName();
                $path = $file1->storeAs('public/product-images', $back_image);
            }
            $p = new Product();
            $p->product_id = $product_id;
            $p->seller_id = $seller_id;
            $p->product_id_manual = $product_id_manual;
            $p->product_name = $request->productName;
            $p->category_id = $request->category;
            $p->sub_category_id = $request->subCategory;
            $p->purity_id = $request->purity;
            $p->occasion_id = $request->occasion;
            $p->product_for = $request->gender;
            $p->tax = $request->tax;
            $p->front_image = $front_image;
            $p->back_image = $back_image;
            $p->tags = $request->tag;
            $p->save();

            $pv = new ProductVariant();
            $pv->product_id = $p->id;
            $pv->color_id = $request->color;
            $pv->short_description = $request->shortDescription;
            $pv->long_description = $request->longDescription;
            $pv->name = $request->name;
            $pv->size = $request->size;
            $pv->weight = $request->weight;
            $pv->mrp = $request->mrp;
            $pv->manufacturing_cost = $request->mc;
            $pv->discount = $request->discount;
            $pv->selling_price = $request->sellingPrice;
            $pv->save();


            $tags = explode(',', $request->tag);
            for ($i = 0; $i < sizeof($tags); $i++) {
                $pt = new ProductTag();
                $pt->product_id = $p->id;
                $pt->tag = $tags[$i];
                $pt->save();
            }

            if ($request->hasfile('variantImages')) {
                $file = $request->file("variantImages");

                foreach ($file as $key => $image) {
                    $name = time() . "_" . $file[$key]->getClientOriginalName();
                    $path = $file[$key]->storeAs('public/product-images', $name);
                    $pvi = new ProductVariantImage();
                    $pvi->product_variant_id = $pv->id;
                    $pvi->image = $name;
                    $pvi->save();
                }
            }

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function update_product(Request $request)
    {
        $data = $request->all();
        $seller_id = $request->sellerId;
        $product_id_manual = $request->productId;
        $id = $request->id;
        $validator = Validator::make($data, [
            'productId' => ['required', 'string', 'max:255', Rule::unique('products', 'product_id_manual')->where(function ($query) use ($product_id_manual, $seller_id, $id) {
                return $query->where('seller_id', $seller_id)
                    ->where('product_id_manual', $product_id_manual);
            })->ignore($id, 'id')],
            'productName' => ['required', 'string', 'max:255'],
            'category' => ['required'],
            'subCategory' => ['required'],
            'purity' => ['required'],
            'occasion' => ['required'],
            'gender' => ['required'],
            'tax' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $pro = Product::find($id);
            $product_image = $pro->front_image;
            $product_image_back = $pro->back_image;
            if ($request->hasfile('frontImage')) {

                unlink(storage_path('app/public/product-images/' . $product_image));

                $file = $request->file("frontImage");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/product-images', $front_image);
            } else {
                $front_image = $product_image;
            }
            if ($request->hasfile('backImage')) {

                unlink(storage_path('app/public/product-images/' . $product_image_back));

                $file1 = $request->file("backImage");
                $back_image = time() . "_" . $file1->getClientOriginalName();
                $path = $file1->storeAs('public/product-images', $back_image);
            } else {
                $back_image = $product_image_back;
            }

            $tags = $request->tag;

            $p = Product::find($request->id);
            $p->product_id_manual = $product_id_manual;
            $p->product_name = $request->productName;
            $p->category_id = $request->category;
            $p->sub_category_id = $request->subCategory;
            $p->purity_id = $request->purity;
            $p->occasion_id = $request->occasion;
            $p->product_for = $request->gender;
            $p->tax = $request->tax;
            $p->front_image = $front_image;
            $p->back_image = $back_image;
            $p->tags = $tags;
            $p->save();

            $t = explode(",", $tags);

            ProductTag::where("product_id", $p->id)->delete();
            for ($i = 0; $i < sizeof($t); $i++) {
                $pt = new ProductTag();
                $pt->product_id = $p->id;
                $pt->tag = $t[$i];
                $pt->save();
            }
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);

        }
    }

    public function get_single_product_seller($id)
    {
        $res = Product::
        join("categories", "categories.id", "products.category_id")
            ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
            ->join("purities", "purities.id", "products.purity_id")
            ->join("occasion", "occasion.id", "products.occasion_id")
            ->join("users", "users.id", "products.seller_id")
            ->select("products.*", "categories.category_name", "sub_categories.sub_category_name", "purities.purity_name", "occasion.occasion")
            ->where("products.id", $id)
            ->first();

        $res->tag = ProductTag::select("tag as key", "tag as value")->where("product_id", $id)->get();
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function delete_product($id){
        $res = Product::find($id);
        $variant = ProductVariant::where("product_id",$id)->pluck('id');
        $vi = ProductVariantImage::whereIn("product_variant_id",$variant)->get();
        foreach ($vi as $v){
            unlink(storage_path('app/public/product-images/' . $v->image));
        }
        unlink(storage_path('app/public/product-images/' . $res->front_image));
        unlink(storage_path('app/public/product-images/' . $res->back_image));
        Product::where("id", $id)->delete();
        return response()->json(["status" => "success", "message" => "Deleted Successfully"], 200);

    }

    public function get_purities($id)
    {
        $res = Purity::select("id as value", "purity_name as text")->where("category_id", $id)->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_occasion()
    {
        $res = Occasion::select("id as value", "occasion as text")->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_colors()
    {
        $res = Color::select("id as value", "color as text")->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }


    public function get_product_reviews($id)
    {
        $res = Rating::join('users', 'ratings.user_id', 'users.id')
            ->select('ratings.*', 'users.first_name', 'users.last_name')
            ->where("product_id", $id)->paginate(5);
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_product_variants($id)
    {
        $res = ProductVariant::join('color', 'product_variants.color_id', 'color.id')
            ->select('product_variants.*', 'color.color')
            ->with('variantImages')->where("product_id", $id)->get();
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function add_variant(Request $request)
    {
        $data = $request->all();
        $product_id = $request->productId;
        $validator = Validator::make($data, [
            'name' => ['required', 'unique:product_variants,name'],
            'size' => ['required'],
            'weight' => ['required'],
            'color' => ['required'],
            'mrp' => ['required'],
            'mc' => ['required'],
            'discount' => ['required'],
            'sellingPrice' => ['required'],
            'shortDescription' => ['required'],
            'longDescription' => ['required'],
            'variantImages' => ['required'],
            'variantImages.*' => ['mimes:jpeg,jpg,png,gif|max:10000'],

        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $pv = new ProductVariant();
            $pv->product_id = $product_id;
            $pv->name = $request->name;
            $pv->color_id = $request->color;
            $pv->short_description = $request->shortDescription;
            $pv->long_description = $request->longDescription;
            $pv->size = $request->size;
            $pv->weight = $request->weight;
            $pv->mrp = $request->mrp;
            $pv->manufacturing_cost = $request->mc;
            $pv->discount = $request->discount;
            $pv->selling_price = $request->sellingPrice;
            $pv->save();

            if ($request->hasfile('variantImages')) {
                $file = $request->file("variantImages");

                foreach ($file as $key => $image) {
                    $name = time() . "_" . $file[$key]->getClientOriginalName();
                    $path = $file[$key]->storeAs('public/product-images', $name);
                    $pvi = new ProductVariantImage();
                    $pvi->product_variant_id = $pv->id;
                    $pvi->image = $name;
                    $pvi->save();
                }
            }

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function get_single_variant($id)
    {
        $res = ProductVariant::with('variantImages')->find($id);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function calculate_variant_mrp(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'product' => ['required'],
            'mc' => ['required'],
            'weight' => ['required']
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $product = Product::find($request->product);
            $metal_price = Purity::find($product->purity_id)->price;
            $mrp = $metal_price * $request->weight + $request->mc * $request->weight;
            return response()->json(["status" => "success", "mrp" => $mrp], 200);

        }
    }

    public function update_variant(Request $request)
    {
        $data = $request->all();
        $variant_id = $request->id;
        $validator = Validator::make($data, [
            'name' => ['required', 'unique:product_variants,name,' . $variant_id],
            'size' => ['required'],
            'weight' => ['required'],
            'color' => ['required'],
            'mrp' => ['required'],
            'mc'=>['required'],
            'discount' => ['required'],
            'sellingPrice' => ['required'],
            'shortDescription' => ['required'],
            'longDescription' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $pv = ProductVariant::find($variant_id);
            $pv->color_id = $request->color;
            $pv->short_description = $request->shortDescription;
            $pv->long_description = $request->longDescription;
            $pv->name = $request->name;
            $pv->size = $request->size;
            $pv->weight = $request->weight;
            $pv->mrp = $request->mrp;
            $pv->manufacturing_cost = $request->mc;
            $pv->discount = $request->discount;
            $pv->selling_price = $request->sellingPrice;
            $pv->save();
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    public function delete_variant($id){
        $variant = ProductVariant::find($id);
        $res = ProductVariantImage::where("product_variant_id")->get();
        foreach ($res as $vi){
            unlink(storage_path('app/public/product-images/' . $vi->image));
        }
        $variant->delete();

        return response()->json(["status" => "success", "message" => "Deleted Successfully"], 200);

    }

    public function upload_variant_images(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'variantImages' => ['required'],
            'variantImages.*' => ['mimes:jpeg,jpg,png,gif|max:10000'],

        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('variantImages')) {
                $file = $request->file("variantImages");

                foreach ($file as $key => $image) {
                    $name = time() . "_" . $file[$key]->getClientOriginalName();
                    $path = $file[$key]->storeAs('public/product-images', $name);
                    $pvi = new ProductVariantImage();
                    $pvi->product_variant_id = $request->variantId;
                    $pvi->image = $name;
                    $pvi->save();
                }
            }

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function delete_variant_image($id){
        $res = ProductVariantImage::find($id);
        unlink(storage_path('app/public/product-images/' . $res->image));
        ProductVariantImage::where("id", $id)->delete();

        return response()->json(["status" => "success", "message" => "Deleted Successfully"], 200);

    }

    public function add_money_to_wallet(Request $request)
    {
        $amount = $request->input("amount");
        $user_id = $request->user_id;
        $order_id = "JWORD" . rand(10000, 99999);

        $wallet = UserWallet::where("user_id", $user_id)->first();

        if ($wallet) {
            $rem_wallet_amount = $wallet->wallet_amount;
            $wallet->wallet_amount = $wallet->wallet_amount + $amount;
            $wallet->save();

        } else {
            $rem_wallet_amount = $amount;
            $wallet = new UserWallet();
            $wallet->wallet_Amount = $amount;
            $wallet->user_id = $user_id;
            $wallet->save();
        }

        $ord = new UserWalletHistory();
        $ord->user_id = $user_id;
        $ord->order_id = $order_id;
        $ord->remaining_wallet_amount = $rem_wallet_amount;
        $ord->deposit_withdraw_amount = $amount;
        $ord->transaction_type = 1;
        $ord->payment_status = "PENDING";
        $ord->save();
        return response()->json(['status' => 'success', 'message' => "Amount Successfully added to your wallet", 'orderId' => $order_id], 200);
    }

    public function wallet_payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $payment_id = $request->paymentId;
        $o = UserWalletHistory::where("order_id", $order_id)->first();
        $o->payment_status = "SUCCESS";
        $o->payment_id = $payment_id;
        $o->save();

        return response()->json(['status' => 'success', 'message' => "Payment Completed Successfully"], 200);
    }
}
