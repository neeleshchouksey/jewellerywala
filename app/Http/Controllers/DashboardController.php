<?php

namespace App\Http\Controllers;

use App\Models\AccessRole;
use App\Models\Category;
use App\Models\Order;
use App\Models\Commission;
use App\Models\CouponCode;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Purity;
use App\Models\Section1;
use App\Models\Section2;
use App\Models\Section4;
use App\Models\Section6;
use App\Models\ShippingCharges;
use App\Models\SubAccessRole;
use App\Models\SubAdminRole;
use App\Models\SuperCoin;
use App\Models\UsersSuperCoin;
use App\Models\UserWallet;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type == 0) {
            $total_orders = Order::where(["delivered_status" => 0, "order_status" => 0])->count();
            $total_customers = User::where(["user_type" => 2, "deleted_at" => NULL])->count();
            $total_sellers = User::where(["user_type" => 1, "deleted_at" => NULL])->count();
            $total_products = Product::where(["verified_by_admin" => 1])->count();

            $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
                ->join('users', 'users.id', 'orders.user_id')
                ->orderby("id", "desc")
                ->limit(5)->get();
            $customers = User::where(["user_type" => 2, "deleted_at" => NULL])->orderby("id", "desc")->limit(5)->get();

            return view('admin.dashboard', ["total_orders" => $total_orders, "total_customers" => $total_customers, "total_sellers" => $total_sellers, "total_products" => $total_products, "new_orders" => $orders, "new_customers" => $customers]);

        } else {
            $total_orders = count(get_seller_total_placed_orders(Auth::user()->id, 0));
            $total_return = count(get_seller_total_placed_orders(Auth::user()->id, 3));
            $total_delivered = count(get_seller_total_placed_orders(Auth::user()->id, 2));
            $total_products = Product::where(["verified_by_admin" => 1, "seller_id" => Auth::user()->id])->count();

            $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
                ->join('users', 'users.id', 'orders.user_id')
                ->orderby("id", "desc");

            $orders = $orders->join("cart", "cart.id", "orders.cart_id")
                ->join("cart_lines", "cart_lines.cart_id", "cart.id")
                ->join("products", "products.id", "cart_lines.product_id")
                ->where("products.seller_id", Auth::user()->id)
                ->groupby("orders.id")
                ->limit(5)->get();

            $returns = Order::select('orders.*', 'users.first_name', 'users.last_name')
                ->join('users', 'users.id', 'orders.user_id')
                ->join("cart", "cart.id", "orders.cart_id")
                ->join("cart_lines", "cart_lines.cart_id", "cart.id")
                ->join("products", "products.id", "cart_lines.product_id")
                ->where("products.seller_id", Auth::user()->id)
                ->where("orders.delivered_status", 3)
                ->orderby("id", "desc")
                ->groupby("orders.id")
                ->limit(5)->get();

            return view('admin.dashboard', ["total_orders" => $total_orders, "total_return" => $total_return, "total_delivered" => $total_delivered, "total_products" => $total_products, "new_orders" => $orders, "returns" => $returns]);

        }
    }

    public function no_access_right()
    {

        return view('admin.no-access-right');

    }

    public function homepage_cms()
    {
        $role = check_roles("admin/homepage-cms");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.homepage-cms');
        }
    }

    public function shipping_charges()
    {
        $role = check_roles("admin/shipping-charges");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.shipping-charges');
        }
    }

    public function manage_coupon()
    {
        $role = check_roles("admin/manage-coupon");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.coupon-code');
        }
    }

    public function manage_super_coin()
    {
        $role = check_roles("admin/manage-super-coin");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.super-coin');
        }
    }

    public function manage_commission()
    {
        $role = check_roles("admin/manage-commission");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.commission');
        }
    }

    public function user_search_keywords()
    {
        $role = check_roles("admin/user-search-keywords");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.user-search-keywords');
        }
    }

    public function users()
    {
        $role = check_roles("#admin/all-users");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.users');
        }
    }

    public function sellers()
    {
        $role = check_roles("#admin/all-users");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.sellers');
        }
    }

    public function seller_commission()
    {
        $role = check_roles("admin/seller-commission");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.seller-commission');
        }
    }

    public function seller_orders()
    {
         return view('admin.seller-all-orders');
    }

    public function metal_prices()
    {
        $role = check_roles("admin/metal-prices");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.metal-prices');
        }
    }

    public function sub_admin()
    {
        $role = check_roles("admin/sub-admin");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.sub-admin');
        }
    }

    public function get_daily_orders($type, Request $request)
    {
        $date = [];
        $amount = [];
        if (Auth::user()->user_type == 0) {
            if ($type == 1) {
                $today = Carbon::today();
                $week_start = $today->subDays(6)->format("Y-m-d");
                for ($i = 0; $i < 7; $i++) {
                    $amt = get_daily_orders($week_start);
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
                        $amt = get_weekly_orders($weeks_dates[$i], $weeks_dates[$i + 1]);
                    }
                    if ($i == 4) {
                        $date[$i] = $last_day;
                        $amt = get_weekly_orders($weeks_dates[3], $last_day);
                    }
                    if ($amt == null) {
                        $amt = 0;
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

                    $amt = get_weekly_orders($first_day[$i], $last_day[$i]);
                    if ($amt == null) {
                        $amt = 0;
                    }
                    $amount[$i] = $amt;
                }
                return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

            }
        } else {
            if ($type == 1) {
                $today = Carbon::today();
                $week_start = $today->subDays(6)->format("Y-m-d");
                for ($i = 0; $i < 7; $i++) {
                    $amt = get_daily_orders_seller($week_start);
                    if ($amt == null) {
                        $amt = 0;
                    } else {
                        $mat = $amt->amount;
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
                        $amt = get_weekly_orders_seller($weeks_dates[$i], $weeks_dates[$i + 1]);
                    }
                    if ($i == 4) {
                        $date[$i] = $last_day;
                        $amt = get_weekly_orders_seller($weeks_dates[3], $last_day);
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

                    $amt = get_weekly_orders_seller($first_day[$i], $last_day[$i]);
                    if ($amt == null) {
                        $amt = 0;
                    }
                    $amount[$i] = $amt;
                }
                return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

            }

        }

    }

    public function get_daily_total_orders($type, Request $request)
    {
        $date = [];
        $amount = [];
        if (Auth::user()->user_type == 0) {
            if ($type == 1) {
                $today = Carbon::today();
                $week_start = $today->subDays(6)->format("Y-m-d");
                for ($i = 0; $i < 7; $i++) {
                    $amt = get_daily_total_orders($week_start);
                    if ($amt == null) {
                        $amt = 0;
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
                        $amt = get_weekly_total_orders($weeks_dates[$i], $weeks_dates[$i + 1]);
                    }
                    if ($i == 4) {
                        $date[$i] = $last_day;
                        $amt = get_weekly_total_orders($weeks_dates[3], $last_day);
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

                    $amt = get_weekly_total_orders($first_day[$i], $last_day[$i]);
                    $amount[$i] = $amt;
                }
                return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

            }
        } else {
            if ($type == 1) {
                $today = Carbon::today();
                $week_start = $today->subDays(6)->format("Y-m-d");
                for ($i = 0; $i < 7; $i++) {
                    $amt = get_daily_total_orders_seller($week_start);
                    if ($amt == null) {
                        $amt = 0;
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
                        $amt = get_weekly_total_orders_seller($weeks_dates[$i], $weeks_dates[$i + 1]);
                    }
                    if ($i == 4) {
                        $date[$i] = $last_day;
                        $amt = get_weekly_total_orders_seller($weeks_dates[3], $last_day);
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

                    $amt = get_weekly_total_orders_seller($first_day[$i], $last_day[$i]);
                    $amount[$i] = $amt;
                }
                return response()->json(['status' => 'success', 'date' => $date, "amount" => $amount], 200);

            }
        }


    }

    public function get_seller_orders($id, Request $request)
    {
        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
            ->join('users', 'users.id', 'orders.user_id');

        if ($id) {
            $orders = $orders->join("cart", "cart.id", "orders.cart_id")
                ->join("cart_lines", "cart_lines.cart_id", "cart.id")
                ->join("products", "products.id", "cart_lines.product_id")
                ->where("products.seller_id", $id)
                ->where("orders.delivered_status", 2)
                ->groupby("orders.id");
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
                $orders = $orders->where('first_name', "like", "%$un[0]%")
                    ->where('last_name', 'like', "%$un[1]%")
                    ->orWhere('first_name', 'like', "%$un[1]%")
                    ->where('last_name', 'like', "%$un[0]%");
            } else {
                $name = $request->userName;
                $orders = $orders->where(function ($query) use ($name) {
                    $query->where('first_name', "like", "%$name%");
                    $query->orWhere(function ($query) use ($name) {
                        $query->where('last_name', "like", "%$name%");
                    });
                });
            }
        }

        $orders = $orders->orderby("id", "desc")
            ->paginate(10);

        foreach ($orders as $o) {
            $o->order_total = get_seller_order_total($o->id, $id);
            if (!$o->commission) {
                $o->commission = 0;
            }
        }

        return response()->json(['status' => 'success', 'res' => $orders], 200);

    }

    public function get_all_sellers_commission(Request $request)
    {
        $res = User::select('users.*', 'shops.shop_name')
            ->join("shops", "users.id", "shops.user_id")
            ->join("products", "products.seller_id", "users.id")
            ->where("user_type", 1);
        $name = $request->name;
        if ($name) {
            $un = explode(" ", $name);
            if (isset($un[0]) && isset($un[1])) {
                $res = $res->where('first_name', "like", "%$un[0]%")
                    ->where('last_name', 'like', "%$un[1]%")
                    ->orWhere('first_name', 'like', "%$un[1]%")
                    ->where('last_name', 'like', "%$un[0]%");
            } else {
                $res = $res->where(function ($query) use ($name) {
                    $query->where('first_name', "like", "%$name%");
                    $query->orWhere(function ($query) use ($name) {
                        $query->where('last_name', "like", "%$name%");
                    });
                });
            }
        } elseif ($request->email) {
            $res = $res->where("email", "like", "%$request->email%");
        } elseif ($request->phone) {
            $res = $res->where("phone", "like", "%$request->phone%");
        } elseif ($request->date) {
            $toDate = date("Y-m-d", strtotime($request->date));
            $res = $res->whereDate("users.created_at", "=", $toDate);
        } elseif ($request->shopName) {
            $res = $res->where("shop_name", "like", "%$request->shopName%");
        }

        $res = $res->groupby("users.id")->orderby("users.id", "desc")->paginate(10);

        foreach ($res as $r) {
            $r->total_orders = count(get_seller_total_orders($r->id));
            $r->total_amount = get_seller_total_amount($r->id);
            $r->commission = get_seller_order_commission($r->id);
        }

        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_all_users($type, Request $request)
    {
        $total_wallet_amt = UserWallet::sum("wallet_amount");
        $total_super_coin_amt = UsersSuperCoin::sum("super_coin_amount");
        if ($type == 2) {
            $res = User::withTrashed()->where("user_type", 2);
            $name = $request->name;
            if ($name) {

                $un = explode(" ", $name);
                if (isset($un[0]) && isset($un[1])) {
                    $orders = $res->where('first_name', "like", "%$un[0]%")
                        ->where('last_name', 'like', "%$un[1]%")
                        ->orWhere('first_name', 'like', "%$un[1]%")
                        ->where('last_name', 'like', "%$un[0]%");
                } else {

                    $res = $res->where(function ($query) use ($name) {
                        $query->where('first_name', "like", "%$name%");
                        $query->orWhere(function ($query) use ($name) {
                            $query->where('last_name', "like", "%$name%");
                        });
                    });
                }
            } elseif ($request->email) {
                $res = $res->where("email", "like", "%$request->email%");
            } elseif ($request->phone) {
                $res = $res->where("phone", "like", "%$request->phone%");
            } elseif ($request->date) {
                $toDate = date("Y-m-d", strtotime($request->date));
                $res = $res->whereDate("created_at", "=", $toDate);
            }

            $res = $res->groupby("users.id")->orderby("users.id", "desc")->paginate(10);

            foreach ($res as $r) {
                $wallet_amount = UserWallet::where("user_id", $r->id)->first();
                if ($wallet_amount) {
                    $r->wallet_amount = $wallet_amount->wallet_amount;
                } else {
                    $r->wallet_amount = 0;
                }
                $super_coin_amount = UsersSuperCoin::where("user_id", $r->id)->first();
                if ($super_coin_amount) {
                    $r->super_coin_amount = $super_coin_amount->super_coin_amount;
                } else {
                    $r->super_coin_amount = 0;
                }
            }

        } else {
            $res = User::withTrashed()->select('users.*', 'shops.shop_name',
                DB::raw("count(products.id) as total_products"))
                ->join("shops", "users.id", "shops.user_id")
                ->leftjoin("products", "products.seller_id", "users.id")
                ->where("user_type", 1);
            $res = $res->where(function ($query) {
                $query->where('users.deleted_at', NULL);
                $query->orWhere(function ($query) {
                    $query->where('users.deleted_at', "!=", NULL);
                });
            });

            $name = $request->name;
            if ($name) {
                $un = explode(" ", $name);
                if (isset($un[0]) && isset($un[1])) {
                    $res = $res->where('first_name', "like", "%$un[0]%")
                        ->where('last_name', 'like', "%$un[1]%")
                        ->orWhere('first_name', 'like', "%$un[1]%")
                        ->where('last_name', 'like', "%$un[0]%");
                } else {

                    $res = $res->where(function ($query) use ($name) {
                        $query->where('first_name', "like", "%$name%");
                        $query->orWhere(function ($query) use ($name) {
                            $query->where('last_name', "like", "%$name%");
                        });
                    });
                }
            } elseif ($request->email) {
                $res = $res->where("email", "like", "%$request->email%");
            } elseif ($request->phone) {
                $res = $res->where("phone", "like", "%$request->phone%");
            } elseif ($request->date) {
                $toDate = date("Y-m-d", strtotime($request->date));
                $res = $res->whereDate("users.created_at", "=", $toDate);
            } elseif ($request->shopName) {
                $res = $res->where("shop_name", "like", "%$request->shopName%");

            }
            $res = $res->groupby("users.id")->orderby("users.id", "desc")->paginate(10);
        }

        return response()->json(["status" => "success", "res" => $res, "total_wallet_amt" => $total_wallet_amt, "total_super_coin_amt" => $total_super_coin_amt], 200);

    }

    public function activate_deactivate_user($id, Request $request)
    {
        if ($request->status == 0) {
            $p = User::find($id);
            $p->delete();
            if ($p->user_type == 1) {
                Product::where("seller_id", $id)->delete();
            }
        } else {
            User::withTrashed()->find($id)->restore();
            $p = User::find($id);
            if ($p->user_type == 1) {
                Product::withTrashed()->where("seller_id", $id)->restore();
            }
        }

        return response()->json(["status" => "success", "message" => "Status Updated Successfully"], 200);

    }

    public function add_section1(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'text1' => ['required', 'string', 'max:255'],
            'text2' => ['required', 'string', 'max:255'],
            'text3' => ['required', 'string', 'max:255'],
            'text4' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string', 'max:255'],
            'image' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/homepage-section-images', $front_image);
            }

            $p = new Section1();
            $p->text1 = $request->text1;
            $p->text2 = $request->text2;
            $p->text3 = $request->text3;
            $p->text4 = $request->text4;
            $p->link = $request->link;
            $p->image = $front_image;
            $p->save();

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function add_section2(Request $request)
    {
        $data = $request->all();
        if ($request->id) {
            $validator = Validator::make($data, [
                'link1' => ['required', 'string', 'max:255'],
                'link2' => ['required', 'string', 'max:255'],
                'link3' => ['required', 'string', 'max:255'],
            ]);
        } else {
            $validator = Validator::make($data, [
                'link1' => ['required', 'string', 'max:255'],
                'image1' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],
                'link2' => ['required', 'string', 'max:255'],
                'image2' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],
                'link3' => ['required', 'string', 'max:255'],
                'image3' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],

            ]);
        }

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image1')) {
                $file1 = $request->file("image1");
                $front_image1 = time() . "_" . $file1->getClientOriginalName();
                $path1 = $file1->storeAs('public/homepage-section-images', $front_image1);
                if ($request->image1 != "undefined" && $request->id) {
                    unlink(storage_path('app/public/homepage-section-images/' . $request->old_image1));
                }

            } else {
                $front_image1 = $request->old_image1;
            }
            if ($request->hasfile('image2')) {
                $file2 = $request->file("image2");
                $front_image2 = time() . "_" . $file2->getClientOriginalName();
                $path2 = $file2->storeAs('public/homepage-section-images', $front_image2);
                if ($request->image2 != "undefined" && $request->id) {
                    unlink(storage_path('app/public/homepage-section-images/' . $request->old_image2));
                }

            } else {
                $front_image2 = $request->old_image2;
            }
            if ($request->hasfile('image3')) {
                $file3 = $request->file("image3");
                $front_image3 = time() . "_" . $file3->getClientOriginalName();
                $path3 = $file3->storeAs('public/homepage-section-images', $front_image3);
                if ($request->image3 != "undefined" && $request->id) {
                    unlink(storage_path('app/public/homepage-section-images/' . $request->old_image3));
                }
            } else {
                $front_image3 = $request->old_image3;
            }

            if ($request->id) {
                $p = Section2::find($request->id);
            } else {
                $p = new Section2();
            }
            $p->link1 = $request->link1;
            $p->image1 = $front_image1;
            $p->link2 = $request->link2;
            $p->image2 = $front_image2;
            $p->link3 = $request->link3;
            $p->image3 = $front_image3;
            $p->save();

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function add_section4(Request $request)
    {
        $data = $request->all();
        if ($request->id) {
            $validator = Validator::make($data, [
                'text1' => ['required', 'string', 'max:255'],
                'text2' => ['required', 'string', 'max:255'],
                'text3' => ['required', 'string', 'max:255'],
                'text4' => ['required', 'string', 'max:255'],
                'link' => ['required', 'string', 'max:255'],
            ]);
        } else {
            $validator = Validator::make($data, [
                'text1' => ['required', 'string', 'max:255'],
                'text2' => ['required', 'string', 'max:255'],
                'text3' => ['required', 'string', 'max:255'],
                'text4' => ['required', 'string', 'max:255'],
                'link' => ['required', 'string', 'max:255'],
                'image' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],
            ]);
        }

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image')) {
                $file = $request->file("image");
                $front_image = time() . "_" . $file->getClientOriginalName();
                $path = $file->storeAs('public/homepage-section-images', $front_image);
                if ($request->image != "undefined") {
                    unlink(storage_path('app/public/homepage-section-images/' . $request->old_image));
                }

            } else {
                $front_image = $request->old_image;
            }

            if ($request->id) {
                $p = Section4::find($request->id);
            } else {
                $p = new Section4();
            }
            $p->text1 = $request->text1;
            $p->text2 = $request->text2;
            $p->text3 = $request->text3;
            $p->text4 = $request->text4;
            $p->link = $request->link;
            $p->image = $front_image;
            $p->save();

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function add_section6(Request $request)
    {
        $data = $request->all();
        if ($request->id) {
            $validator = Validator::make($data, [
                'link1' => ['required', 'string', 'max:255'],
                'link2' => ['required', 'string', 'max:255'],
            ]);
        } else {
            $validator = Validator::make($data, [
                'link1' => ['required', 'string', 'max:255'],
                'image1' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],
                'link2' => ['required', 'string', 'max:255'],
                'image2' => ['mimes:jpeg,jpg,png,gif|required|max:10000'],
            ]);
        }

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->hasfile('image1')) {
                $file1 = $request->file("image1");
                $front_image1 = time() . "_" . $file1->getClientOriginalName();
                $path1 = $file1->storeAs('public/homepage-section-images', $front_image1);
                if ($request->image1 != "undefined" && $request->id) {
                    unlink(storage_path('app/public/homepage-section-images/' . $request->old_image1));
                }

            } else {
                $front_image1 = $request->old_image1;
            }
            if ($request->hasfile('image2')) {
                $file2 = $request->file("image2");
                $front_image2 = time() . "_" . $file2->getClientOriginalName();
                $path2 = $file2->storeAs('public/homepage-section-images', $front_image2);
                if ($request->image2 != "undefined" && $request->id) {
                    unlink(storage_path('app/public/homepage-section-images/' . $request->old_image2));
                }

            } else {
                $front_image2 = $request->old_image2;
            }
            if ($request->id) {
                $p = Section6::find($request->id);
            } else {
                $p = new Section6();
            }
            $p->link1 = $request->link1;
            $p->image1 = $front_image1;
            $p->link2 = $request->link2;
            $p->image2 = $front_image2;
            $p->save();

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function get_section($type)
    {
        if ($type == 1) {
            $res = Section1::all();
        } elseif ($type == 2) {
            $res = Section2::all();
        } elseif ($type == 4) {
            $res = Section4::all();
        } elseif ($type == 6) {
            $res = Section6::all();
        }
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function delete_section1($id)
    {
        $res = Section1::find($id);
        unlink(storage_path('app/public/homepage-section-images/' . $res->image));
        Section1::destroy($id);
        return response()->json(["status" => "success", "message" => "Deleted Successfully"], 200);

    }

    public function add_shipping_data(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'charges' => ['required'],
            'criteria' => ['required'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {

            if ($request->id) {
                $p = ShippingCharges::find($request->id);
            } else {
                $p = new ShippingCharges();
            }
            $p->shipping_charges = $request->charges;
            $p->shipping_criteria = $request->criteria;
            $p->save();

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);

        }
    }

    public function get_shipping_data()
    {
        $res = ShippingCharges::first();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_all_coupon_code()
    {
        $res = CouponCode::join("categories", "categories.id", "coupon_code.category_id")
            ->select("coupon_code.*", "categories.category_name")
            ->orderBy("id", "desc")
            ->paginate(5);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_single_coupon_code($id)
    {
        $res = CouponCode::find($id);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function add_coupon_code(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'code' => ['required', 'unique:coupon_code,code'],
            'qty' => ['required', 'numeric'],
            'discount' => ['required', 'numeric'],
            'validity' => ['required', 'numeric'],
            'minPrice' => ['required', 'numeric'],
            'maxPrice' => ['required', 'numeric'],
            'category' => 'required'
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = new CouponCode();
            $mc->code = $request->code;
            $mc->qty = $request->qty;
            $mc->remaining_coupon = $request->qty;
            $mc->discount = $request->discount;
            $mc->valid_till = strtotime("+$request->validity days");
            $mc->validity_days = $request->validity;
            $mc->category_id = $request->category;
            $mc->min_price = $request->minPrice;
            $mc->max_price = $request->maxPrice;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function update_coupon_code(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'code' => ['required', 'unique:coupon_code,code,' . $request->id],
            'qty' => ['required', 'numeric'],
            'remainingCoupon' => 'required',
            'discount' => ['required', 'numeric'],
            'validity' => ['required', 'numeric'],
            'minPrice' => ['required', 'numeric'],
            'maxPrice' => ['required', 'numeric'],
            'category' => 'required'

        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = CouponCode::find($request->id);
            $mc->code = $request->code;
            $mc->qty = $request->qty;
            $mc->discount = $request->discount;
            $mc->remaining_coupon = $request->remainingCoupon;
            $mc->valid_till = strtotime("+$request->validity days"); //time() . " + $request->validity days";
            $mc->validity_days = $request->validity;
            $mc->category_id = $request->category;
            $mc->min_price = $request->minPrice;
            $mc->max_price = $request->maxPrice;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    public function get_all_commission()
    {
        $res = Commission::first();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function update_commission(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'commission' => 'required',
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = Commission::find($request->id);
            $mc->commission = $request->commission;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    public function get_all_super_coin()
    {
        $res = SuperCoin::orderBy("id", "desc")->paginate(10);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_all_metal_price()
    {
        $res = Category::join("purities", "purities.category_id", "categories.id")
            ->select("categories.id as category_id", "categories.category_name", "purities.id as purity_id", "purities.purity_name", "purities.price")
            //->orderBy("id", "desc")
            ->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_all_user_search_keywords()
    {
        $res = DB::table("user_search")->orderBy("id", "desc")->paginate(10);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_single_super_coin($id)
    {
        $res = SuperCoin::find($id);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function add_super_coin(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'coinAmount' => ['required'],
            'minShoppingAmount' => 'required',
            'maxShoppingAmount' => 'required',
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = new SuperCoin();
            $mc->super_coin_amount = $request->coinAmount;
            $mc->min_shopping_amount = $request->minShoppingAmount;
            $mc->max_shopping_amount = $request->maxShoppingAmount;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function update_super_coin(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'coinAmount' => ['required'],
            'minShoppingAmount' => 'required',
            'maxShoppingAmount' => 'required'
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = SuperCoin::find($request->id);
            $mc->super_coin_amount = $request->coinAmount;
            $mc->min_shopping_amount = $request->minShoppingAmount;
            $mc->max_shopping_amount = $request->maxShoppingAmount;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    public function update_metal_price(Request $request)
    {
        $request = $request->all();
        for ($i = 0; $i < sizeof($request); $i++) {
            $res = Purity::find($request[$i]['purity_id']);
            $res->price = $request[$i]['price'] ? $request[$i]['price'] : $request[$i]['price'];
            $res->save();

            $metal_price = $res->price;

            $product_variants[$i] = Product::select("product_variants.*")->join("product_variants", "products.id", "product_variants.product_id")
                ->where("purity_id", $request[$i]['purity_id'])
                ->get();

            foreach ($product_variants[$i] as $p) {
                $mrp = ($metal_price * $p->weight) + ($p->manufacturing_cost * $p->weight);
                $discount = ($mrp * $p->discount) / 100;
                $variants = ProductVariant::find($p->id);
                $variants->mrp = $mrp;
                $variants->selling_price = $mrp - $discount;
                $variants->save();
            }
        }
        return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
    }

    public function get_access_roles()
    {
        $res = AccessRole::where("title", "!=", "Dashboard")->get();
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_update_access_roles($id)
    {
        $res = AccessRole::where("title", "!=", "Dashboard")->get();
        $roles = SubAdminRole::join("access_roles", "subadmin_roles.role_id", "access_roles.id")->where("user_id", $id)->pluck("role_id");

        foreach ($res as $r) {
            if (in_array($r->id, $roles->toArray())) {
                $r->checked = 1;
            } else {
                $r->checked = 0;
            }
        }


        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_subadmin()
    {
        $res = User::where("user_type", 0)
            ->where("id", "!=", 1)
            ->paginate(10);
        foreach ($res as $r) {
            $roles = SubAdminRole::join("access_roles", "subadmin_roles.role_id", "access_roles.id")->where("user_id", $r->id)->pluck("title as role");
            $r->roles = $roles;
        }

        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_single_subadmin($id)
    {
        $res = User::find($id);
        $roles = SubAdminRole::join("access_roles", "subadmin_roles.role_id", "access_roles.id")->where("user_id", $res->id)->pluck('role_id');
        $res->roles = $roles;

        return response()->json(["status" => "success", "res" => $res], 200);
    }


    public function add_subadmin(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'phone' => ['required'],
            'password' => ['required', 'string', 'max:255', 'min:6'],
            "accessRole" => "required|array",
            "accessRole.*" => "required",
        ]);
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
            $u->user_type = 0;
            $u->save();

            $access_role = $request->accessRole;
            foreach ($access_role as $ar) {
                $a = new SubAdminRole();
                $a->user_id = $u->id;
                $a->role_id = $ar;
                $a->save();
            }
            $a = new SubAdminRole();
            $a->user_id = $u->id;
            $a->role_id = 1;
            $a->save();

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function update_subadmin(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            "accessRole" => "required|array",
            "accessRole.*" => "required",
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $u = User::find($request->id);
            $u->first_name = $request->firstName;
            $u->last_name = $request->lastName;
            $u->save();

            $access_role = $request->accessRole;

            if (!empty($access_role)) {
                SubAdminRole::where("user_id", $request->id)->delete();
                foreach ($access_role as $ar) {
                    $a = new SubAdminRole();
                    $a->user_id = $u->id;
                    $a->role_id = $ar;
                    $a->save();
                }

                $a = new SubAdminRole();
                $a->user_id = $u->id;
                $a->role_id = 1;
                $a->save();

            }

            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }
}
