<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Purity;
use App\Models\Rating;
use App\Models\SubCategory;
use Hassansin\DBCart\Models\CartLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    /****************Product related functioans*********************/

    public function index()
    {
        if(Auth::user()->user_type==0) {
            $role = check_roles("#admin/manage-products");
            if (!$role) {
                return redirect("admin/no-access-right");
            } else {
                return view('admin.products');
            }
        }else{
            return view('admin.products');
        }
    }

    public function add_products_to_homepage()
    {
        $role = check_roles("#admin/manage-products");
        if (!$role) {
            return redirect("admin/no-access-right");
        } else {
            return view('admin.homepage-products');
        }
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

    public function add_product()
    {
        return view('admin.add-product');
    }

    public function add_product_api(Request $request)
    {
        $data = $request->all();
        $seller_id = Auth::user()->id;
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

    public function edit_product($id)
    {
        $p_name = Product::find($id)->product_name;
        return view('admin.edit-product', ["product_name" => $p_name]);
    }

    public function view_product($id)
    {
        $p_name = Product::find($id)->product_name;
        return view('admin.view-product', ["product_name" => $p_name]);
    }

    public function get_single_products($id)
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

    public function verify_product($id, Request $request)
    {
        $p = Product::find($id);
        $p->verified_by_admin = $request->status;
        $p->save();
        return response()->json(["status" => "success", "message" => "Status Updated Successfully"], 200);

    }

    public function change_homepage_product_status($id, Request $request)
    {
        $p = Product::find($id);
        if ($request->type == 1) {
            $p->new_arrival = $request->status;
        }
        if ($request->type == 2) {
            $p->featured = $request->status;
        }
        if ($request->type == 3) {
            $p->trending = $request->status;
        }
        $p->save();
        return response()->json(["status" => "success", "message" => "Status Updated Successfully"], 200);

    }

    public function update_product_api(Request $request)
    {
        $data = $request->all();
        $seller_id = Auth::user()->id;
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


    /*****************Product variants related functions**********************/

    public function get_product_variants($id)
    {
        $res = ProductVariant::join('color', 'product_variants.color_id', 'color.id')
            ->select('product_variants.*', 'color.color')
            ->with('variantImages')->where("product_id", $id)->get();
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_product_reviews($id)
    {
        $res = Rating::join('users', 'ratings.user_id', 'users.id')
            ->select('ratings.*', 'users.first_name', 'users.last_name')
            ->where("product_id", $id)->paginate(5);
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function add_variant(Request $request)
    {
        $data = $request->all();
        $seller_id = Auth::user()->id;
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

    public function upload_variant_images(Request $request)
    {
        $data = $request->all();
        $seller_id = Auth::user()->id;
        $product_id = $request->productId;
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

    public function update_variant(Request $request)
    {
        $data = $request->all();
        $seller_id = Auth::user()->id;
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

    public function get_single_variant($id)
    {
        $res = ProductVariant::with('variantImages')->find($id);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    /**********************ORders related function********************/

    public function orders()
    {
        if(Auth::user()->user_type==0) {
            $role = check_roles("#admin/orders");
            if (!$role) {
                return redirect("admin/no-access-right");
            } else {
                return view('admin.orders');
            }
        }else{
            return view('admin.orders');
        }
    }

    public function invoice($id)
    {
        $orders = Order::join("users", "orders.user_id", "users.id")
            ->join("address", "address.id", "orders.address_id")
            ->select("orders.*", "address.*", "users.first_name", "users.last_name", "users.phone", "users.email")
            ->where("orders.id", $id)
            ->first();

        $order_item = Order::select("cart.total_price", "cart_lines.quantity", "cart_lines.unit_price", "products.product_name", "products.front_image", "orders.shipping_charge", "orders.final_amount", "orders.wallet_amount", "orders.super_coin_amount", "orders.discount", "orders.price")
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->where("orders.id", $id)
            ->get();

        return view('admin.invoice', ['orders' => $orders, "order_item" => $order_item]);
    }

    public function return_requests()
    {
        if(Auth::user()->user_type==0) {
            $role = check_roles("#admin/orders");
            if (!$role) {
                return redirect("admin/no-access-right");
            } else {
                return view('admin.return-requests');
            }
        }else{
            return view('admin.return-requests');
        }
    }


    public function get_orders(Request $request)
    {
        $auth_user_type = Auth::user()->user_type;

        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name',"s.first_name as s_f_name","s.last_name as s_l_name","s.id as seller_id","shops.shop_name")
            ->join('users', 'users.id', 'orders.user_id')
            ->join("cart", "cart.id", "orders.cart_id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->join("users as s","products.seller_id","s.id")
            ->join("shops","shops.user_id","s.id")
            ->groupby("orders.id");
        if ($auth_user_type == 1) {
            $orders = $orders->where("products.seller_id", Auth::user()->id);

        }
        if ($request->productId) {
            $orders = $orders->where("orders.order_id", "like", "%$request->productId%");
        }
        if ($request->shopName) {
            $orders = $orders->where("shops.shop_name", "like", "%$request->shopName%");
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

        if (Auth::user()->user_type == 1) {
            foreach ($orders as $o) {
                $o->order_total = get_seller_order_total($o->id, Auth::user()->id);
            }

        }
        return response()->json(['status' => 'success', 'res' => $orders], 200);

    }

//    public function get_orders(Request $request)
//    {
//        $auth_user_type = Auth::user()->user_type;
//
//        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name')
//            ->join('users', 'users.id', 'orders.user_id');
//
//        if ($auth_user_type == 1) {
//            $orders = $orders->join("cart", "cart.id", "orders.cart_id")
//                ->join("cart_lines", "cart_lines.cart_id", "cart.id")
//                ->join("products", "products.id", "cart_lines.product_id")
//                ->where("products.seller_id", Auth::user()->id)
//                ->groupby("orders.id");
//        }
//        if ($request->productId) {
//            $orders = $orders->where("orders.order_id", "like", "%$request->productId%");
//        }
//        if ($request->fromDate) {
//            $fromDate = date("Y-m-d", strtotime($request->fromDate));
//            $orders = $orders->whereDate("orders.created_at", ">=", $fromDate);
//        }
//        if ($request->toDate) {
//            $toDate = date("Y-m-d", strtotime($request->toDate));
//            $orders = $orders->whereDate("orders.created_at", "<=", $toDate);
//        }
//        if ($request->userName) {
//            $un = explode(" ", $request->userName);
//            if (isset($un[0]) && isset($un[1])) {
//                $orders = $orders->where('first_name', "like", "%$un[0]%")
//                    ->where('last_name', 'like', "%$un[1]%")
//                    ->orWhere('first_name', 'like', "%$un[1]%")
//                    ->where('last_name', 'like', "%$un[0]%");
//            } else {
//                $name = $request->userName;
//                $orders = $orders->where(function ($query) use ($name) {
//                    $query->where('first_name', "like", "%$name%");
//                    $query->orWhere(function ($query) use ($name) {
//                        $query->where('last_name', "like", "%$name%");
//                    });
//                });
//            }
//        }
//        $orders = $orders->orderby("id", "desc")
//            ->paginate(10);
//
//        if (Auth::user()->user_type == 1) {
//            foreach ($orders as $o) {
//                $o->order_total = get_seller_order_total($o->id, Auth::user()->id);
//            }
//
//        }
//        return response()->json(['status' => 'success', 'res' => $orders], 200);
//
//    }

    public function get_return_request_orders(Request $request)
    {
        $auth_user_type = Auth::user()->user_type;

        $orders = Order::select('orders.*', 'users.first_name', 'users.last_name',"s.first_name as s_f_name","s.last_name as s_l_name","s.id as seller_id","shops.shop_name")
            ->join('users', 'users.id', 'orders.user_id')
            ->join("cart", "cart.id", "orders.cart_id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id")
            ->join("users as s","products.seller_id","s.id")
            ->join("shops","shops.user_id","s.id")
            ->groupby("orders.id");

        if ($auth_user_type == 1) {
            $orders = $orders->where("products.seller_id", Auth::user()->id);

        }
        if ($request->shopName) {
            $orders = $orders->where("shops.shop_name", "like", "%$request->shopName%");
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
            $name = $request->userName;
            $orders = $orders->where(function ($query) use ($name) {
                $query->where('first_name', "like", "%$name%");
                $query->orWhere(function ($query) use ($name) {
                    $query->where('last_name', "like", "%$name%");
                });
            });
        }
        $orders = $orders->where("orders.delivered_status", 3)->orderby("id", "desc")
            ->paginate(10);
        return response()->json(['status' => 'success', 'res' => $orders], 200);
    }

    public function get_order_details($id, Request $request)
    {
        $res = Order::select("cart_lines.quantity", "cart_lines.unit_price", "products.product_name", "products.front_image")
            ->join("cart", "orders.cart_id", "cart.id")
            ->join("cart_lines", "cart_lines.cart_id", "cart.id")
            ->join("products", "products.id", "cart_lines.product_id");

        if (Auth::user()->user_type == 1) {
            $res = $res->where("products.seller_id", Auth::user()->id);
        } else {
            if ($request->sellerId) {
                $res = $res->where("products.seller_id", $request->sellerId);
            }
        }
        $res = $res->where("orders.id", $id)
            ->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

}
