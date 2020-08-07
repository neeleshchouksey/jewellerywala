<?php

namespace App\Http\Controllers;

use App;
use App\Events\OrderPlaced;
use App\Events\OrderPlacedAdmin;
use App\Models\Address;
use App\Models\AdminNotification;
use App\Models\Notification;
use App\Models\Category;
use App\Models\CouponCode;
use App\Models\Occasion;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Rating;
use App\Models\ShippingCharges;
use App\Models\Shop;
use App\Models\SuperCoin;
use App\Models\UsersSuperCoin;
use App\Models\UserWallet;
use App\Models\UserWalletHistory;
use App\Models\Wishlist;
use App\User;
use Hassansin\DBCart\Models\Cart;
use Hassansin\DBCart\Models\CartLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.shop');
            }
        } else {
            return view('user.shop');
        }
    }
    public function all_shops()
    {
        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.all-shops');
            }
        } else {
            return view('user.all-shops');
        }
    }

    public function offers()
    {
        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.offers');
            }
        } else {
            return view('user.offers');
        }
    }

    public function product_details()
    {
        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.product-details');
            }
        } else {
            return view('user.product-details');
        }
    }

    public function my_cart()
    {
        // if (Auth::user()) {
//            if (Auth::user()->user_type == 0) {
//                return redirect("admin/dashboard");
//            } elseif (Auth::user()->user_type == 1) {
//                return redirect("seller/dashboard");
//            } else {
        return view('user.cart');
        // }
//        } else {
//            return redirect("/");
//        }
    }

    public function my_wishlist()
    {
//        if (Auth::user()) {
//            if (Auth::user()->user_type == 0) {
//                return redirect("admin/dashboard");
//            } elseif (Auth::user()->user_type == 1) {
//                return redirect("seller/dashboard");
//            } else {
        return view('user.wishlist');
//            }
//        } else {
//            return redirect("/");
//        }
    }

    public function wallet()
    {
        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.wallet');
            }
        } else {
            return redirect("/");
        }
    }

    public function checkout()
    {
        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.checkout');
            }
        } else {
            return redirect("/");
        }
    }

    public function my_account()
    {
        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.my-account');
            }
        } else {
            return redirect("/");
        }
    }

    public function order_details($id)
    {
        $order_id = Order::find($id);

        $res = Order::select("cart_lines.quantity", "cart_lines.unit_price", "products.product_name", "products.front_image")
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("orders.id", $id)
            ->get();
        return view('user.order-details', ["orders" => $res, "order_id" => $order_id->order_id]);
    }

    public function get_user()
    {
        $user = User::find(Auth::user()->id);
        return response()->json(['status' => 'success', 'res' => $user], 200);

    }

    public function update_user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name ? $request->first_name : $user->first_name;
        $user->last_name = $request->last_name ? $request->last_name : $user->last_name;
        $user->password = $request->password ? bcrypt($request->password) : $user->password;
        $user->save();
        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }

    public function get_orders()
    {
        $orders = DB::table("orders")->where('user_id', Auth::user()->id)->orderby("id", "desc")->paginate(5);
        foreach ($orders as $o) {
            if (strtotime('+7 day', $o->deliver_date) > time()) {
                $o->show = true;
            } else {
                $o->show = false;
            }
        }
        return response()->json(['status' => 'success', 'res' => $orders], 200);
    }

    public function get_cashback_orders()
    {
        $orders = DB::table("orders")
            ->where('user_id', Auth::user()->id)
            ->where(["order_status" => 0, "delivered_status" => 2])
            ->get();
        $cashback = DB::table("orders")
            ->where('user_id', Auth::user()->id)
            ->where(["order_status" => 0, "delivered_status" => 2])
            ->sum('received_super_coin_amount');


        // $cashback = DB::table("users_super_coin")->where("user_id", Auth::user()->id)->first();
        return response()->json(['status' => 'success', 'res' => $orders, 'cashback' => $cashback], 200);
    }

    public function get_data($type)
    {
        if ($type == 1) //get subcategories
        {
            $res = Category::with('subcategory')->get();
        } else if ($type == 2) { //get purity {
            $res = Category::with('purity')->get();
        } else if ($type == 3) { //get purity {
            $res = Occasion::get();
        } else if ($type == 4) { //get purity {
            $res = DB::table("orders")
                ->where('user_id', Auth::user()->id)
                ->where(["order_status" => 0, "delivered_status" => 2])
                ->sum('received_super_coin_amount');
            //$res = UsersSuperCoin::where("user_id", Auth::user()->id)->first();
        } else if ($type == 5) { //get purity {
            $res = User::leftjoin("user_wallet", "users.id", "user_wallet.user_id")->where("users.id", Auth::user()->id)->first();
        } else if ($type == 6) { //get purity {
            $res = UserWalletHistory::where("user_id", Auth::user()->id)->orderBy("id", "desc")->paginate(10);
        } else if ($type == 7) { //get purity {
            $res = Shop::all();
        }

        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_all_products(Request $request)
    {
        $auth_user = Auth::user();

        $res = Product::
        join("categories", "categories.id", "products.category_id")
            ->join("sub_categories", "sub_categories.id", "products.sub_category_id")
            ->join("purities", "purities.id", "products.purity_id")
            ->join("occasion", "occasion.id", "products.occasion_id")
            ->join("users", "users.id", "products.seller_id")
            ->join("product_variants", "products.id", "product_variants.product_id")
            ->leftjoin("ratings", "ratings.product_id", "products.id")
            ->select("products.*", "product_variants.id as product_variant_id", "product_variants.selling_price", "categories.category_name", "sub_categories.sub_category_name", "purities.purity_name", "occasion.occasion",
                DB::raw("avg(ratings) as ratings"))
            ->where("products.verified_by_admin", 1);

        if ($request->subCategory) {
            $res = $res->whereIn("products.sub_category_id", $request->subCategory);
        }

        if ($request->purity) {
            $res = $res->whereIn("products.purity_id", $request->purity);
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

//        if ($request->name) {
//            $res = $res->where("products.product_name", "like", "%" . $request->name . "%");
//        }

        if ($request->sellerName) {
            $res = $res->where("products.product_name", "like", "%" . $request->name . "%");
        }
        if ($request->productId) {
            $res = $res->where("products.product_id_manual", "like", "%" . $request->productId . "%");
        }

        if ($request->price[0] && $request->price[1]) {
            $res = $res->whereBetween("product_variants.selling_price", array($request->price[0], $request->price[1]));
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
        $res = $res->groupby("products.id")->paginate(12);

        if ($auth_user) {
            foreach ($res as $r) {
                if ($r->ratings == null) {
                    $r->ratings = 0;
                }
                $w = Wishlist::where(["user_id" => $auth_user->id, "product_id" => $r->id])->first();
                if ($w) {
                    $r->added_to_wishlist = 1;
                } else {
                    $r->added_to_wishlist = 0;
                }
            }
        } else {
            foreach ($res as $r) {
                if ($r->ratings == null) {
                    $r->ratings = 0;
                }
                $r->added_to_wishlist = 0;
            }
        }

        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_single_product($id)
    {
        $auth_user = Auth::user();

        $res = Product::join("product_variants", "products.id", "product_variants.product_id")
            ->join("color", "color.id", "product_variants.color_id")
            ->select("products.*", "product_variants.id as product_variant_id", "product_variants.selling_price", "product_variants.short_description", "product_variants.long_description", "product_variants.size", "product_variants.weight", "color.color as color_id")
            ->where("product_variants.id", $id)
            ->first();

        $product_id = ProductVariant::find($id)->product_id;
        $sub_category_id = Product::find($product_id)->sub_category_id;

        $res->variant = ProductVariant::select("product_variants.*", "color.color")
            ->join("color", "color.id", "product_variants.color_id")
            ->where("product_variants.product_id", $product_id)
            ->get();

        $res->variant_images = ProductVariantImage::where("product_variant_id", $id)->get();

        $res->ratings = get_ratings($res->id);
        if ($auth_user) {
            $w = Wishlist::where(["user_id" => $auth_user->id, "product_id" => $res->id])->first();
            if ($w) {
                $res->added_to_wishlist = 1;
            } else {
                $res->added_to_wishlist = 0;
            }
        } else {

            $res->added_to_wishlist = 0;

        }
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public
    function get_related_product($id)
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

    public
    function add_to_cart(Request $request)
    {
        $product_id = $request->productId;
        $item_id = $request->variantId;
        $pi = ProductVariant::find($item_id);
        $product_price = $request->sellingPrice;
        $product_qty = $request->qty;

        $cart = Cart::where(["user_id" => Auth::user()->id, "name" => "default", "status" => "active"])->first();

        if ($cart) {
//            $cart->addItem([
//                'product_id' => $product_id,
//                'unit_price' => $product_price,
//                'quantity' => $product_qty
//            ]);

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
        $cart->user_id = Auth::user()->id;
        $cart->save();
        return response()->json(['status' => 'success', 'message' => "Item Successfully added to cart"], 200);
    }

    public
    function remove_cart_item($id)
    {
        if (Auth::user()) {
            CartLine::find($id)->delete();
        } else {
            $cart = Cart::current("guest_cart");
            $cart->removeItem([
                'id' => $id
            ]);
        }
        return response()->json(['status' => 'success', 'message' => "Item removed successfully"], 200);
    }

    public
    function update_cart_quantity($item_id, Request $request)
    {
        if (Auth::user()) {
            CartLine::where("id", $item_id)->update(["quantity" => $request->qty]);
        } else {
            $cart = Cart::current("guest_cart");
            $cart->updateItem([
                'id' => $item_id
            ], [
                'quantity' => $request->qty
            ]);
        }


        return response()->json(['status' => 'success', 'message' => "updated successfully"], 200);
    }

    public
    function get_cart()
    {
//        dd($guest);
        if (Auth::user()) {
            $cart = Cart::where(["user_id" => Auth::user()->id, "name" => "default", "status" => "active"])->first();
            if ($cart) {
                $items = CartLine::where("cart_id", $cart->id)->get();
                $total_count = count(CartLine::where("cart_id", $cart->id)->get());
                $total_price = $cart->total_price;
                $guest = app('cart', ['name' => 'guest_cart']);
                //dd($guest);
                if ($guest) {
                    $guest->moveItemsTo($cart);
                }
            } else {
                $cart = Cart::current();
                if ($cart) {
                    $items = $cart->items;
                    $total_count = count($cart->items()->get());
                    $guest = app('cart', ['name' => 'guest_cart']);

                    if ($guest) {
                        $guest->moveItemsTo($cart);
                    }
                    $total_price = $cart->total_price;
                    $cart->user_id = Auth::user()->id;
                    $cart->save();


                } else {
                    $cart = array();
                    $total_price = 0;
                    $items = array();
                    $total_count = 0;
                }
            }
        } else {
            $cart = Cart::current("guest_cart");
            $items = $cart->items;
            $total_count = count($cart->items()->get());
            $total_price = $cart->total_price;

        }


        if($cart){
            $cart_id = $cart->id;
        }else{
            $cart_id = 0;
        }

        foreach ($items as $a) {
            $a->product_name = Product::where("id", $a->product_id)->pluck("product_name")[0];
            $a->product_image = Product::where("id", $a->product_id)->pluck("front_image")[0];
        }


        return response()->json(['status' => 'success', 'res' => $items, 'total_amount' => $total_price, 'total_count' => $total_count,"cart_id"=>$cart_id], 200);
    }

    public function add_to_wishlist(Request $request)
    {
        $product_id = $request->productId;
        $product_price = $request->sellingPrice;
        $wishlist = new Wishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $product_id;
        $wishlist->unit_price = $product_price;
        $wishlist->save();

        return response()->json(['status' => 'success', 'message' => "Item Successfully added to wishlist"], 200);
    }

    public
    function remove_wishlist_item($id)
    {
        if (Auth::user()) {
            Wishlist::find($id)->delete();
        }

        return response()->json(['status' => 'success', 'message' => "Item removed successfully"], 200);
    }

    public
    function get_wishlist()
    {
        if (Auth::user()) {
            $wishlist = array();
            if (Auth::user()) {
                $wishlist = Wishlist::where("user_id", Auth::user()->id)->get();
                foreach ($wishlist as $a) {
                    $a->product_name = Product::where("id", $a->product_id)->pluck("product_name")[0];
                    $a->product_image = Product::where("id", $a->product_id)->pluck("front_image")[0];
                }
            }

        }


        return response()->json(['status' => 'success', 'res' => $wishlist], 200);
    }

    public
    function add_to_guest_cart(Request $request)
    {
        $product_id = $request->productId;
        $item_id = $request->variantId;
        $pi = ProductVariant::find($item_id);
        $product_price = $request->sellingPrice;
        $product_qty = $request->qty;

        $guest = Cart::current('guest_cart');
        if ($guest) {
            $guest->addItem([
                'product_id' => $product_id,
                'unit_price' => $product_price,
                'quantity' => $product_qty
            ]);
        } else {
            $guest = app('cart', ['name' => 'guest_cart']);
            $guest->addItem([
                'product_id' => $product_id,
                'unit_price' => $product_price,
                'quantity' => $product_qty
            ]);
        }
        return response()->json(['status' => 'success', 'message' => "Item Successfully added to cart"], 200);
    }

    public
    function get__cart_orders($id = null, Request $request)
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
        if (Auth::user()) {
            $super_coin_amount = UsersSuperCoin::where("user_id", Auth::user()->id)->first();
            if ($super_coin_amount) {
                $super_coin_amount = $super_coin_amount->super_coin_amount;
            } else {
                $super_coin_amount = 0;
            }
            $wallet_amount = UserWallet::where("user_id", Auth::user()->id)->first();
            if ($wallet_amount) {
                $wallet_amount = $wallet_amount->wallet_amount;
            } else {
                $wallet_amount = 0;
            }

            $total_orders = Order::where("user_id", Auth::user()->id)->count();
        }
        return response()->json(['status' => 'success', 'res' => $items, 'total_orders' => $total_orders, 'total_amount' => $total_price, "shipping_charges" => $shipping_charges, "super_coin_amount" => $super_coin_amount, "wallet_amount" => $wallet_amount], 200);
    }

    public
    function checkout_process($id)
    {
        $cart = Cart::find($id);
        $cart->checkout();
        return response()->json(['status' => 'success', 'message' => "Checkout Successfully Completed", 'cart_id' => $cart->id], 200);
    }

    public
    function get_address()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $address = DB::table("address")->where("user_id", $user_id)->first();
        return response()->json(['status' => 'success', 'res' => $address, 'user' => $user], 200);
    }

    public
    function update_address(Request $request)
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

        $add = Address::where("user_id", Auth::user()->id)->first();
        if (!$add) {
            $add = new Address();
        }

        $add->user_id = Auth::user()->id;
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

    public
    function apply_coupon_code(Request $request)
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

    public
    function place_order(Request $request)
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

        if ($coupon_code != null) {
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

        $add = Address::where("user_id", Auth::user()->id)->first();
        if (!$add) {
            $add = new Address();
        }

        $add->user_id = Auth::user()->id;
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
        $ord->user_id = Auth::user()->id;
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


        $spa = UsersSuperCoin::where("user_id", Auth::user()->id)->first();
        if ($spa) {
            $spa->super_coin_amount = $spa->super_coin_amount - $request->used_super_coin_amount;
            $spa->save();
        }

        $spa = UserWallet::where("user_id", Auth::user()->id)->first();
        if ($spa) {
            $spa->wallet_amount = $spa->wallet_amount - $request->used_wallet_amount;
            $spa->save();
        }


        if ($request->used_wallet_amount) {
            $wallet = UserWallet::where("user_id", Auth::user()->id)
                ->where("wallet_amount", ">", 0)->first();
            $rem_wallet_amount = 0;
            if ($wallet) {
                $rem_wallet_amount = $wallet->wallet_amount;
                $wallet->wallet_amount = $wallet->wallet_amount - $request->used_wallet_amount;
                $wallet->save();

            }
            $ord = new UserWalletHistory();
            $ord->user_id = Auth::user()->id;
            $ord->order_id = $order_id;
            $ord->remaining_wallet_amount = $rem_wallet_amount;
            $ord->deposit_withdraw_amount = $request->used_wallet_amount;
            $ord->transaction_type = 2;
            $ord->payment_status = "SUCCESS";
            $ord->save();
        }


        return response()->json(['status' => 'success', 'message' => "Order Placed Successfully", 'orderId' => $order_id], 200);
    }

    public
    function payment_success(Request $request)
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
        $cart->complete();


        $super_coin = SuperCoin::where("min_shopping_amount", "<=", $o->final_amount)
            ->where("max_shopping_amount", ">=", $o->final_amount)
            ->first();

        if ($super_coin) {
            $o->received_super_coin_amount = $super_coin->super_coin_amount;
            $o->save();
            $usc = UsersSuperCoin::where("user_id", Auth::user()->id)->first();

            if (!$usc) {
                $usc = new UsersSuperCoin();
                $usc->user_id = Auth::user()->id;
                $usc->order_id = $o->id;
                $usc->super_coin_amount = $super_coin->super_coin_amount;
                $usc->save();
            } else {
                $usc->order_id = $o->id;
                $usc->super_coin_amount = $usc->super_coin_amount + $super_coin->super_coin_amount;
                $usc->save();
            }

        }

        $email = User::find($o->user_id)->email;
        $sub = "Jewellerywala | Place Order";
        $type = 2; //place order
        $msg = "You have successfully placed order on jewellerywala.co, Your order id is " . $o->order_id;
        send_email($email, $sub, $type, $msg);

        $phone = User::find($o->user_id)->phone;
        send_msg($phone, $msg);

        $p_id = CartLine::where("cart_id", $o->cart_id)->pluck("product_id");

        $seller_id = Product::whereIn("id", $p_id)->pluck("seller_id");
        $admin_id = User::where("user_type", 0)->first()->id;


        $an = new Notification();
        $an->sender_id = Auth::user()->id;
        $an->rec_id = $admin_id;
        $an->notification = "New Order Placed by " . Auth::user()->first_name . " " . Auth::user()->last_name;
        $an->date = time();
        $an->url = "orders";
        $an->save();

        event(new OrderPlacedAdmin($an));


        foreach ($seller_id as $sid) {
            $n = new Notification();
            $n->sender_id = Auth::user()->id;
            $n->rec_id = $sid;
            $n->notification = "New Order Placed by " . Auth::user()->first_name . " " . Auth::user()->last_name;
            $n->date = time();
            $n->url = "orders";
            $n->save();
            event(new OrderPlaced($n));
        }

        return response()->json(['status' => 'success', 'message' => "Order Placed Successfully"], 200);
    }

    public
    function add_money_to_wallet(Request $request)
    {
        $amount = $request->input("amount");
        $order_id = "JWORD" . rand(10000, 99999);

        $wallet = UserWallet::where("user_id", Auth::user()->id)->first();

        if ($wallet) {
            $rem_wallet_amount = $wallet->wallet_amount;
            $wallet->wallet_amount = $wallet->wallet_amount + $amount;
            $wallet->save();

        } else {
            $rem_wallet_amount = $amount;
            $wallet = new UserWallet();
            $wallet->wallet_Amount = $amount;
            $wallet->user_id = Auth::user()->id;
            $wallet->save();
        }

        $ord = new UserWalletHistory();
        $ord->user_id = Auth::user()->id;
        $ord->order_id = $order_id;
        $ord->remaining_wallet_amount = $rem_wallet_amount;
        $ord->deposit_withdraw_amount = $amount;
        $ord->transaction_type = 1;
        $ord->payment_status = "PENDING";
        $ord->save();
        return response()->json(['status' => 'success', 'message' => "Order Placed Successfully", 'orderId' => $order_id], 200);
    }

    public
    function wallet_payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $payment_id = $request->paymentId;
        $o = UserWalletHistory::where("order_id", $order_id)->first();
        $o->payment_status = "SUCCESS";
        $o->payment_id = $payment_id;
        $o->save();

        return response()->json(['status' => 'success', 'message' => "Order Placed Successfully"], 200);

    }

    public
    function submit_ratings(Request $request)
    {
        $product_id = $request->product_id;
        $r = new Rating();
        $r->user_id = Auth::user()->id;
        $r->product_id = $product_id;
        $r->ratings = $request->ratings;
        $r->reviews = $request->reviews;
        $r->save();
        return response()->json(['status' => 'success', 'message' => "You have successfully given rating to this product"], 200);
    }

    public
    function get_product_reviews($id)
    {
        $p = ProductVariant::find($id);
        $p_id = $p->product_id;
        $res = Rating::select("ratings.*", "users.first_name", "users.last_name")
            ->join("users", "users.id", "ratings.user_id")->where("product_id", $p_id)
            ->get();
        $review_given = 0;
        if (Auth::user()) {
            $rg = Rating::select("ratings.*", "users.first_name", "users.last_name")
                ->join("users", "users.id", "ratings.user_id")
                ->where("product_id", $p_id)
                ->where("user_id", Auth::user()->id)
                ->first();
            if ($rg) {
                $review_given = 1;
            }
        }
        return response()->json(['status' => 'success', 'res' => $res, 'review_count' => count($res), 'review_given' => $review_given], 200);
    }

    public
    function cancel_order($id)
    {
        $o = Order::find($id);
        $o->order_status = 1;
        $o->save();

        $usc = UsersSuperCoin::where("user_id", Auth::user()->id)->first();
        $usc->super_coin_amount = $usc->super_coin_amount - $o->received_super_coin_amount;
        $usc->save();

        $email = User::find($o->user_id)->email;
        $sub = "Jewellerywala | Order Cancelled";
        $msg = "Your order(order id " . $o->order_id . ") is successfully cancelled on jewellerywala.co";
        $type = 8; //cancel order
        send_email($email, $sub, $type, $msg);
        $phone = User::find($o->user_id)->phone;
        send_msg($phone, $msg);

        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }

    public
    function get_product_images($id)
    {
        $p_item = ProductVariant::find($id);
        $p_id = $p_item->product_id;
        $res = ProductVariantImage::where(["product_variant_id" => $id])->get()->toArray();

        $front_image = Product::find($p_id)->front_image;
        $back_image = Product::find($p_id)->back_image;

        $new_img_arr1 = array(["image" => $front_image, "product_variant_id" => $id]);
        $new_img_arr2 = array(["image" => $back_image, "product_variant_id" => $id]);

        array_push($res, $new_img_arr1[0]);
        array_push($res, $new_img_arr2[0]);

        return response()->json(['status' => 'success', 'res' => array_reverse($res)], 200);
    }

    public
    function change_delivery_status($id, Request $request)
    {
        $o = Order::find($id);
        $o->delivered_status = $request->status;
        $o->deliver_date = time();
        $o->save();

        $email = User::find($o->user_id)->email;
        if ($request->status == 1) {
            $sub = "Jewellerywala | Order Shipped";
            $msg = "Your order(order id " . $o->order_id . ") is successfully shipped by jewellerywala.co";
            $type = 3; //order shipped

        } else if ($request->status == 2) {
            $sub = "Jewellerywala | Order Delivered";
            $msg = "Your order(order id " . $o->order_id . ") is successfully delivered by jewellerywala.co";
            $type = 4; //order delivered
        }
        send_email($email, $sub, $type, $msg);
        $phone = User::find($o->user_id)->phone;
        send_msg($phone, $msg);
        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }

    public
    function change_return_status($id, Request $request)
    {
        $o = Order::find($id);
        $o->return_request_status = $request->status;
        $o->save();

        $email = User::find($o->user_id)->email;
        if ($request->status == 1) {
            $sub = "Jewellerywala | Order Return Request Accepted";
            $msg = "Your order(order id " . $o->order_id . ") is accepted by jewellerywala.co";
            $type = 6; //order return accepted

        } else if ($request->status == 2) {
            $o = Order::find($id);
            $o->delivered_status = 2;
            $o->save();

            $sub = "Jewellerywala | Order Return Request Declined";
            $msg = "Your order(order id " . $o->order_id . ") is declined by jewellerywala.co";
            $type = 7; //order return declined
        }
        send_email($email, $sub, $type, $msg);
        $phone = User::find($o->user_id)->phone;
        send_msg($phone, $msg);
        return response()->json(['status' => 'success', 'message' => "success"], 200);
    }

    public
    function return_order(Request $request)
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

    public
    function get_order_details($id)
    {

        $res = Order::select("cart_lines.quantity", "cart_lines.unit_price", "products.product_name", "products.front_image")
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("orders.id", $id)
            ->get();
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public
    function invoice($id)
    {

        $orders = Order::join("users", "orders.user_id", "users.id")
            ->join("address", "address.id", "orders.address_id")
            ->join("states", "states.id", "address.state")
            ->join("cities", "cities.id", "address.city")
            ->select("orders.*", "address.address1", "address.address2", "address.pin_code", "states.name as state", "cities.name as city", "users.first_name", "users.last_name", "users.phone", "users.email")
            ->where("orders.id", $id)
            ->first();

        $order_item = Order::select("cart.total_price", "cart_lines.quantity", "cart_lines.unit_price", "products.product_name", "products.front_image", "orders.shipping_charge", "orders.final_amount", "orders.wallet_amount", "orders.super_coin_amount", "orders.discount", "orders.price")
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("orders.id", $id)
            ->get();

        if (Auth::user()) {
            if (Auth::user()->user_type == 0) {
                return redirect("admin/dashboard");
            } elseif (Auth::user()->user_type == 1) {
                return redirect("seller/dashboard");
            } else {
                return view('user.invoice', ["order" => $orders, 'order_item' => $order_item]);
            }
        } else {
            return view('user.invoice', ["order" => $orders, 'order_item' => $order_item]);
        }


        //  return view('admin.invoice', ['orders' => $orders, "order_item" => $order_item]);
    }
}

