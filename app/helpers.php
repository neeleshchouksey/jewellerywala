<?php

use App\Mail\SendEmail;
use App\Models\AccessRole;
use App\Models\Category;
use App\Models\Commission;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariantImage;
use App\Models\ProductVariant;
use App\Models\Purity;
use App\Models\Rating;
use App\Models\Shop;
use App\Models\SubAdminRole;
use Hassansin\DBCart\Models\Cart;
use Hassansin\DBCart\Models\CartLine;
use Illuminate\Support\Facades\Auth;


function send_msg($msisdn,$msg)
{

    $sid = "VDNSIN";
    $msg = urlencode($msg);
    $type = "txt";
    $url12 = "http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=Chetan%20Prajapat&password=Chetan@0640&msisdn=$msisdn&sid=VDNSIN&msg=$msg&fl=0&gwid=2";
//step1
    $cSession = curl_init();
//step2
    curl_setopt($cSession,CURLOPT_URL,$url12);
    curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($cSession,CURLOPT_HEADER, false);
//step3
    $result=curl_exec($cSession);
//step4
    curl_close($cSession);
//step5
    return $result;

}




if (!function_exists('check_roles')) {
    function check_roles($link)
    {
        $admin_id = Auth::user()->id;
        if($admin_id == 1){
            return true;
        }else{
            $role = AccessRole::where("link",$link)->first();
            $admin_role = SubAdminRole::where("user_id",$admin_id)
                ->where("role_id",$role->id)
                ->first();
            if($admin_role){
                return true;
            }else{
                return false;
            }
        }
    }
}

if (!function_exists('get_admin_roles')) {
    function get_admin_roles()
    {
        $admin_id = Auth::user()->id;
        if($admin_id == 1){
            return AccessRole::with("children")->get();
        }else{
            $roles = SubAdminRole::where("user_id",$admin_id)->pluck("role_id");
            return AccessRole::with("children")->whereIn("id",$roles)->get();
        }
    }
}

if (!function_exists('send_email')) {
    function send_email($email, $sub, $type, $msg)
    {
        return Mail::to($email)
            ->send(new SendEmail($type, $msg, $sub));
    }
}
if (!function_exists('calculate_commission')) {
    function calculate_commission($cat,$price)
    {
       $commission = Purity::find($cat)->commission;
       return $commission = $price * $commission/100;

    }
}
if (!function_exists('get_categories')) {
    function get_categories()
    {
        return $res = Category::with('subcategory')->get();
    }
}
if (!function_exists('get_cart_item_count')) {
    function get_cart_item_count()
    {
        $cart = Cart::current();
        return $total = count($cart->items()->get());
    }
}
if (!function_exists('get_cart')) {
    function get_cart()
    {
        if (isset($id)) {
            $cart = Cart::find($id);
            $items = CartLine::where("cart_id", $id)->get();
        } else {
            $cart = Cart::current();
            $items = $cart->items;
        }

        $total_price = $cart->total_price;

        foreach ($items as $a) {
            $a->product_name = Product::where("id", $a->product_id)->pluck("product_name")[0];
            $a->product_image = Product::where("id", $a->product_id)->pluck("front_image")[0];
        }


        return $items;
    }
}
if (!function_exists('get_cart_total')) {
    function get_cart_total()
    {
        if (isset($id)) {
            $cart = Cart::find($id);
            $items = CartLine::where("cart_id", $id)->get();
        } else {
            $cart = Cart::current();
            $items = $cart->items;
        }

        $total_price = $cart->total_price;

        return $total_price;
    }
}

if (!function_exists('get_ratings')) {
    function get_ratings($product_id)
    {
        return floatval(Rating::where("product_id", $product_id)->avg("ratings"));
    }
}

if (!function_exists('get_all_metal_price')) {
    function get_all_metal_price()
    {
        $res = Category::join("purities","categories.id","purities.category_id")
                        ->get();
//        foreach ($res as $r) {
//            if ($r->category_name == "Gold") {
//                $r->price = $r->price * 10;
//            } elseif ($r->category_name == "Silver") {
//                $r->price = $r->price * 1000;
//
//            }
//
//        }
        return $res;
    }
}

if (!function_exists('get_ratings_html')) {
    function get_ratings_html($product_id)
    {
        $data = Rating::where("product_id", $product_id)->avg("ratings");

        if ($data == null) {
            $data = 0;
        }

        if ($data == 0) {
            $html = '<span class="fa fa-star rat rat1" val="1"></span>
                                                        <span class="fa fa-star rat rat2" val="2"></span>
                                                        <span class="fa fa-star rat rat3" val="3"></span>
                                                        <span class="fa fa-star rat rat4" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';


        } elseif ($data > 0 && $data < 1) {
            $html = '<span class="fa fa-star-half-o rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2" val="2"></span>
                                                        <span class="fa fa-star rat rat3" val="3"></span>
                                                        <span class="fa fa-star rat rat4" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data == 1) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2" val="2"></span>
                                                        <span class="fa fa-star rat rat3" val="3"></span>
                                                        <span class="fa fa-star rat rat4" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data > 1 && $data < 2) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star-half-o rat rat2 checked" val="2"></span>
                                                        <span class="fa fa-star rat rat3" val="3"></span>
                                                        <span class="fa fa-star rat rat4" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data == 2) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2 checked" val="2"></span>
                                                        <span class="fa fa-star rat rat3" val="3"></span>
                                                        <span class="fa fa-star rat rat4" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data > 2 && $data < 3) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2 checked" val="2"></span>
                                                        <span class="fa fa-star-half-o rat rat3 checked" val="3"></span>
                                                        <span class="fa fa-star rat rat4" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data == 3) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2 checked" val="2"></span>
                                                        <span class="fa fa-star rat rat3 checked" val="3"></span>
                                                        <span class="fa fa-star rat rat4" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data > 3 && $data < 4) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2 checked" val="2"></span>
                                                        <span class="fa fa-star rat rat3 checked" val="3"></span>
                                                        <span class="fa fa-star-half-o rat rat4 checked" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data == 4) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2 checked " val="2"></span>
                                                        <span class="fa fa-star rat rat3 checked" val="3"></span>
                                                        <span class="fa fa-star rat rat4 checked" val="4"></span>
                                                        <span class="fa fa-star rat rat5" val="5"></span>';
        } elseif ($data && $data < 5) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2 checked" val="2"></span>
                                                        <span class="fa fa-star rat rat3 checked" val="3"></span>
                                                        <span class="fa fa-star rat rat4 checked" val="4"></span>
                                                        <span class="fa fa-star-half-o rat rat5 checked" val="5"></span>';
        } elseif ($data == 5) {
            $html = '<span class="fa fa-star rat rat1 checked" val="1"></span>
                                                        <span class="fa fa-star rat rat2 checked" val="2"></span>
                                                        <span class="fa fa-star rat rat3 checked" val="3"></span>
                                                        <span class="fa fa-star rat rat4 checked" val="4"></span>
                                                        <span class="fa fa-star rat rat5 checked" val="5"></span>';
        }

        return $html;

    }
}

if (!function_exists('get_total_review_count')) {
    function get_total_review_count($product_id)
    {
        return count(Rating::where("product_id", $product_id)->get());
    }
}

if (!function_exists('get_product_images')) {
    function get_product_images($id)
    {
        return ProductVariantImage::where("product_size_id", $id)
            ->get();
    }
}
if (!function_exists('get_related_products')) {
    function get_related_products($id)
    {
        $pi = ProductVariant::find($id);
        $p = $pi->product_id;
        $sub = Product::find($p);
        $company = $sub->company_id;
        return Product::select("product.*", "product_variants.price", "product_variants.name")
            ->join("product_variants", "product_variants.product_id", "product.id")
            ->where(["company_id" => $company])->where("product.id", "!=", $p)->get();
    }
}
if (!function_exists('send__user_otp')) {
    function send__user_otp($phone, $msg, $otp)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://control.msg91.com/api/sendotp.php?email=deepikabarve26@gmail.com&template=&otp=$otp&otp_length=6&otp_expiry=&sender=JWLRYWALA&message=$msg&mobile=$phone&authkey=281145A66wkWU75d09fab3",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            return $response;
        }
    }
}

if (!function_exists('get_seller_total_orders')) {
    function get_seller_total_orders($seller_id)
    {
        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
            ->join('users', 'users.id', 'orders.user_id');
        $orders = $orders->join("cart", "cart.id", "orders.cart_id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $seller_id)
            ->where("orders.delivered_status", 2)
            ->groupby("orders.id")->get();
        return $orders;
    }
}
if (!function_exists('get_seller_total_placed_orders')) {
    function get_seller_total_placed_orders($seller_id, $status)
    {
        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
            ->join('users', 'users.id', 'orders.user_id');
        $orders = $orders->join("cart", "cart.id", "orders.cart_id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $seller_id)
            ->where("orders.delivered_status", $status)
            ->groupby("orders.id")->get();
        return $orders;
    }
}
if (!function_exists('get_seller_total_amount')) {
    function get_seller_total_amount($seller_id)
    {
        $total = 0;
        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
            ->join('users', 'users.id', 'orders.user_id');
        $orders = $orders->join("cart", "cart.id", "orders.cart_id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $seller_id)
            ->where("orders.delivered_status", 2)
            ->groupby("orders.id")->get();
        foreach ($orders as $r) {
            $total = $total + get_seller_order_total($r->id, $seller_id);
        }
        return $total;
    }
}
if (!function_exists('get_seller_order_commission')) {
    function get_seller_order_commission($seller_id)
   // function get_seller_order_commission($total_amount)
    {
//        $com = Commission::first()->commission;
//        return $total_amount * $com / 100;

        $total = 0;
        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
            ->join('users', 'users.id', 'orders.user_id');
        $orders = $orders->join("cart", "cart.id", "orders.cart_id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $seller_id)
            ->where("orders.delivered_status", 2)
            ->groupby("orders.id")->get();
        foreach ($orders as $r) {
            $total = $total + $r->commission;
        }
        return $total;

    }
}

if (!function_exists('get_seller_order_total')) {
    function get_seller_order_total($order_id, $seller_id)
    {
        return $res = Order::select("cart_lines.quantity", "cart_lines.unit_price", "products.product_name", "products.front_image")
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $seller_id)
            ->where("orders.id", $order_id)
            ->sum('cart_lines.unit_price');
    }
}

if (!function_exists('get_daily_orders')) {
    function get_daily_orders($date)
    {
        return $res = Order::whereDate("created_at", $date)->select(DB::raw("sum(final_amount) as amount"))->first();
    }
}

if (!function_exists('get_daily_orders_seller')) {
    function get_daily_orders_seller($date)
    {
        return $res = Order::select(DB::raw("sum(final_amount) as amount"))
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", Auth::user()->id)
            ->whereDate("orders.created_at", $date)
            ->groupBy("orders.id")
            ->first();
    }
}


if (!function_exists('get_daily_total_orders_seller')) {
    function get_daily_total_orders_seller($date)
    {
        return $res = Order::join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", Auth::user()->id)
            ->whereDate("orders.created_at", $date)
            ->count();
    }
}




if (!function_exists('get_daily_total_orders')) {
    function get_daily_total_orders($date)
    {
        return $res = Order::whereDate("created_at", $date)->count();

    }
}

if (!function_exists('get_weekly_total_orders')) {
    function get_weekly_total_orders($date1, $date2)
    {
        return $res = Order::whereDate("created_at", ">=", $date1)
            ->whereDate("created_at", "<=", $date2)
            ->count();

    }
}
if (!function_exists('get_weekly_total_orders_seller')) {
    function get_weekly_total_orders_seller($date1, $date2)
    {
        return $res = Order::join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", Auth::user()->id)
            ->whereDate("orders.created_at", ">=", $date1)
            ->whereDate("orders.created_at", "<=", $date2)
            ->count();

    }
}
if (!function_exists('get_weekly_orders')) {
    function get_weekly_orders($date1, $date2)
    {
        return $res = Order::whereDate("created_at", ">=", $date1)
            ->whereDate("created_at", "<=", $date2)
            ->select(DB::raw("sum(final_amount) as amount"))->first()->amount;

    }
}
if (!function_exists('get_weekly_orders_seller')) {
    function get_weekly_orders_seller($date1, $date2)
    {
        return $res = Order::select(DB::raw("sum(final_amount) as amount"))
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", Auth::user()->id)
            ->whereDate("orders.created_at", ">=", $date1)
            ->whereDate("orders.created_at", "<=", $date2)
            ->groupBy("orders.id")
            ->first();

    }
}






if (!function_exists('get_weekly_orders_seller_api')) {
    function get_weekly_orders_seller_api($date1, $date2,$id)
    {
        return $res = Order::select(DB::raw("sum(final_amount) as amount"))
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $id)
            ->whereDate("orders.created_at", ">=", $date1)
            ->whereDate("orders.created_at", "<=", $date2)
            ->groupBy("orders.id")
            ->first();

    }
}

if (!function_exists('get_daily_orders_seller_api')) {
    function get_daily_orders_seller_api($date,$id)
    {
        return $res = Order::select(DB::raw("sum(final_amount) as amount"))
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $id)
            ->whereDate("orders.created_at", $date)
            ->groupBy("orders.id")
            ->first();
    }
}
if (!function_exists('get_daily_total_orders_seller_api')) {
    function get_daily_total_orders_seller_api($date,$id)
    {
        return $res = Order::join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $id)
            ->whereDate("orders.created_at", $date)
            ->count();
    }
}


if (!function_exists('get_weekly_total_orders_seller_api')) {
    function get_weekly_total_orders_seller_api($date1, $date2,$id)
    {
        return $res = Order::join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("products.seller_id", $id)
            ->whereDate("orders.created_at", ">=", $date1)
            ->whereDate("orders.created_at", "<=", $date2)
            ->count();

    }
}
