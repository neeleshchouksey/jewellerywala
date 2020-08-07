<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Commission;
use App\Models\CouponCode;
use App\Models\Shop;
use App\Models\SuperCoin;
use App\Models\Occasion;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Purity;
use App\Models\SubCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.manage-category');
    }

    public function delete_data($id, Request $request)
    {
        $type = $request->type;
        if ($type == 2) {
            $res = Category::find($id);
            unlink(storage_path('app/public/category-images/' . $res->image));
            Category::where("id", $id)->delete();
        } else if ($type == 1) {
            SubCategory::where("id", $id)->delete();
        } else if ($type == 3) {
            Purity::where("id", $id)->delete();
        } else if ($type == 4) {
            Occasion::where("id", $id)->delete();
        } else if ($type == 5) {
            Color::where("id", $id)->delete();
        } else if ($type == 6) {
            $res = Product::find($id);
            unlink(storage_path('app/public/product-images/' . $res->front_image));
            unlink(storage_path('app/public/product-images/' . $res->back_image));
            Product::where("id", $id)->delete();
        } else if ($type == 7) {
            ProductVariant::where("id", $id)->delete();
        } else if ($type == 8) {
            $res = ProductVariantImage::find($id);
            unlink(storage_path('app/public/product-images/' . $res->image));
            ProductVariantImage::where("id", $id)->delete();
        } else if ($type == 9 || $type == 10 || $type == 15) {
            User::where("id", $id)->forceDelete($id);
        } elseif ($type == 11) {
            CouponCode::where("id", $id)->delete();
        } elseif ($type == 12) {
            SuperCoin::where("id", $id)->delete();
        } elseif ($type == 13) {
            DB::table("user_search")->where("id", $id)->delete();
        } elseif ($type == 14) {
            Commission::where("id", $id)->delete();
        }
        return response()->json(["status" => "success", "message" => "Deleted Successfully"], 200);

    }

    /************Categories Related functions************/

    public function get_all_categories()
    {
        $res = Category::orderby("id", "desc")
            ->paginate(5);
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_user_categories()
    {
        $res = get_categories();
        return response()->json(["status" => "success", "res" => $res], 200);
    }
    public function get_shops(Request $request)
    {
        if(!$request->state && !$request->city){
            $res = Shop::paginate(12);
        }
        if($request->state){
            $res = Shop::where("state",$request->state)
                    ->paginate(12);
        }
        if($request->city){
            $res = Shop::where("city",$request->city)
                ->paginate(12);
        }

        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function get_categories()
    {
        $res = Category::select("id as value", "category_name as text")->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function add_category(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'category' => ['required', 'unique:categories,category_name'],
         //   'commission'=>'required',
            'image1'=>['required','mimes:jpeg,jpg,png,gif,JPEG,JPG,PNG,GIF|required|max:10000']
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            if ($request->hasfile('image1')) {
                $file1 = $request->file("image1");
                $front_image1 = time() . "_" . $file1->getClientOriginalName();
                $path1 = $file1->storeAs('public/category-images', $front_image1);
            }
            $mc = new Category();
            $mc->category_name = $request->category;
          //  $mc->commission = $request->commission;
            $mc->image = $front_image1;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function get_single_category($id)
    {
        $res = Category::find($id);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function update_category(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'category' => ['required', 'unique:categories,category_name,' . $request->id],
           // 'commission'=>'required'
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $res = Category::find($request->id);
            if ($request->hasfile('image1')) {
                $file1 = $request->file("image1");
                $front_image1 = time() . "_" . $file1->getClientOriginalName();
                $path1 = $file1->storeAs('public/category-images', $front_image1);
                if ($request->image1 != "undefined" && $request->id) {
                    unlink(storage_path('app/public/category-images/' . $res->image));
                }

            } else {
                $front_image1 = $request->old_image1;
            }

            $res->category_name = $request->category;
           // $res->commission = $request->commission;
            $res->image = $front_image1;
            $res->save();

//            $metal_price = $res->price;
//
//            $product_variants = Product::select("product_variants.*")->join("product_variants","products.id","product_variants.product_id")
//                        ->where("category_id",$request->id)
//                        ->get();
//
//            foreach($product_variants as $p){
//                $mrp = ($metal_price * $p->weight) + ($p->manufacturing_cost * $p->weight);
//                $discount = ($mrp*$p->discount)/100;
//                $variants = ProductVariant::find($p->id);
//                $variants->mrp = $mrp;
//                $variants->selling_price = $mrp - $discount;
//                $variants->save();
//            }

            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    /************Sub Categories Related functions************/

    public function get_all_sub_categories()
    {
        $res = Category::join("sub_categories", "categories.id", "sub_categories.category_id")
            ->select("sub_categories.*", "categories.category_name")
            ->orderby("id", "desc")
            ->paginate(10);
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_subcategories($id)
    {
        $res = SubCategory::select("id as value", "sub_category_name as text")->where("category_id", $id)->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function add_sub_category(Request $request)
    {

        $messages = [
            'unique' => 'Subcategory name is already taken in this category, Please try different name/category',
        ];

        $data = $request->all();
        $subcat = $request->subCategory;
        $cat = $request->category;

        $validator = Validator::make($data, [
            'category' => 'required',
            'subCategory' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat) {
                return $query->where('category_id', $cat)
                    ->where('sub_category_name', $subcat);
            })],
        ], $messages);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = new SubCategory();
            $mc->category_id = $request->category;
            $mc->sub_category_name = $request->subCategory;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function get_single_sub_category($id)
    {
        $res = SubCategory::select("sub_categories.*")
            ->join("categories", "sub_categories.category_id", "categories.id")
            ->where("sub_categories.id", $id)
            ->first();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function update_sub_category(Request $request)
    {
        $messages = [
            'unique' => 'Subcategory name is already taken in this category, Please try different name/category',
        ];

        $data = $request->all();
        $subcat = $request->subCategory;
        $cat = $request->category;
        $id = $request->id;

        $validator = Validator::make($data, [
            'category' => 'required',
            'subCategory' => ['required', Rule::unique('sub_categories', 'sub_category_name')->where(function ($query) use ($cat, $subcat, $id) {
                return $query->where('category_id', $cat)
                    ->where('sub_category_name', $subcat);
            })->ignore($id, 'id')],
        ], $messages);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $res = SubCategory::find($request->id);
            $res->category_id = $request->category;
            $res->sub_category_name = $request->subCategory;
            $res->save();
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    /************Purity Related functions************/

    public function get_all_purity()
    {
        $res = Purity::join("categories", "categories.id", "purities.category_id")
            ->select("purities.*", "categories.category_name")
            ->orderby("id", "desc")
            ->paginate(10);
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_purities($id)
    {
        $res = Purity::select("id as value", "purity_name as text")->where("category_id", $id)->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function add_purity(Request $request)
    {
        $messages = [
            'unique' => 'Purity name is already taken in this category, Please try different name/category',
        ];
        $data = $request->all();
        $purity = $request->purity;
        $cat = $request->category;

        $validator = Validator::make($data, [
            'category' => 'required',
            'purity' => ['required', Rule::unique('purities', 'purity_name')->where(function ($query) use ($cat, $purity) {
                return $query->where('category_id', $cat)
                    ->where('purity_name', $purity);
            }),
                'price' => 'required',
            'commission'=>'required'],
        ], $messages);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = new Purity();
            $mc->category_id = $request->category;
            $mc->purity_name = $request->purity;
            $mc->price = $request->price;
            $mc->commission = $request->commission;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function get_single_purity($id)
    {
        $res = Purity::select("purities.*")
            ->join("categories", "purities.category_id", "categories.id")
            ->where("purities.id", $id)
            ->first();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function update_purity(Request $request)
    {
        $messages = [
            'unique' => 'Purity name is already taken in this category, Please try different name/category',
        ];

        $data = $request->all();
        $purity = $request->purity;
        $cat = $request->category;
        $id = $request->id;

        $validator = Validator::make($data, [
            'category' => 'required',
            'purity' => ['required', Rule::unique('purities', 'purity_name')->where(function ($query) use ($cat, $purity, $id) {
                return $query->where('category_id', $cat)
                    ->where('purity_name', $purity);
            })->ignore($id, 'id'),
                'price' => 'required',
                'commission'=>'required'],
        ], $messages);

        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $res = Purity::find($request->id);
            $res->category_id = $request->category;
            $res->purity_name = $request->purity;
            $res->price = $request->price;
            $res->commission = $request->commission;
            $res->save();

            $metal_price = $res->price;

            $product_variants = Product::select("product_variants.*")->join("product_variants", "products.id", "product_variants.product_id")
                ->where("purity_id", $request->id)
                ->get();

            foreach ($product_variants as $p) {
                $mrp = ($metal_price * $p->weight) + ($p->manufacturing_cost * $p->weight);
                $discount = ($mrp * $p->discount) / 100;
                $variants = ProductVariant::find($p->id);
                $variants->mrp = $mrp;
                $variants->selling_price = $mrp - $discount;
                $variants->save();
            }


            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    /************Occasion Related functions************/

    public function get_all_occasion()
    {
        $res = Occasion::orderby("id", "desc")
            ->paginate(5);
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_occasion()
    {
        $res = Occasion::select("id as value", "occasion as text")->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function add_occasion(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'occasion' => ['required', 'unique:occasion,occasion'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = new Occasion();
            $mc->occasion = $request->occasion;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function get_single_occasion($id)
    {
        $res = Occasion::find($id);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function update_occasion(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'occasion' => ['required', 'unique:occasion,occasion,' . $request->id],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $res = Occasion::find($request->id);
            $res->occasion = $request->occasion;
            $res->save();
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }

    /************Color Related functions************/

    public function get_all_colors()
    {
        $res = Color::orderby("id", "desc")
            ->paginate(5);
        return response()->json(["status" => "success", "res" => $res], 200);

    }

    public function get_colors()
    {
        $res = Color::select("id as value", "color as text")->get();
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function add_color(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'color' => ['required', 'unique:color,color'],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $mc = new Color();
            $mc->color = $request->color;
            $mc->save();
            return response()->json(["status" => "success", "message" => "Added Successfully"], 200);
        }
    }

    public function get_single_color($id)
    {
        $res = Color::find($id);
        return response()->json(["status" => "success", "res" => $res], 200);
    }

    public function update_color(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'color' => ['required', 'unique:color,color,' . $request->id],
        ]);
        if ($validator->fails()) {
            $validation_msgs = $validator->getMessageBag()->all();
            if (isset($validation_msgs[0])) {
                return response()->json(["status" => "error", "message" => $validation_msgs[0]], 400);
            }
        } else {
            $res = Color::find($request->id);
            $res->color = $request->color;
            $res->save();
            return response()->json(["status" => "success", "message" => "Updated Successfully"], 200);
        }
    }


}
