<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('artisan-migrate', function () {
    $exitCode = Artisan::call('migrate');
    echo $exitCode;
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('login', 'MobileApiController@login');
Route::get('logout', 'MobileApiController@logout');
Route::post('signup', 'MobileApiController@signup');
Route::post('send-otp', 'MobileApiController@send_otp');
Route::post('check-otp', 'MobileApiController@check_otp');
Route::post('send-forget-otp', 'MobileApiController@send_forget_otp');
Route::post('reset-password', 'MobileApiController@change_password');


Route::get('get-categories', 'MobileApiController@get_categories');
Route::get('get-subcategories/{id}', 'MobileApiController@get_subcategories');

Route::post('/get-all-products', 'MobileApiController@get_all_products');
Route::post('/get-single-product/{id}', 'MobileApiController@get_single_product');
Route::get('/get-related-product/{id}', 'MobileApiController@get_related_product');

Route::get('/get-homepage-products', 'MobileApiController@get_homepage_products');
Route::get('get-cities/{id}', 'MobileApiController@get_cities');
Route::get('get-states', 'MobileApiController@get_states');

//Route::middleware('auth:api')->group(function() {

Route::get('/get-data/{id}', 'MobileApiController@get_data');

Route::get('get-wallet-history/{id}', 'MobileApiController@get_wallet_history');
Route::post('/add-to-cart/{id}', 'MobileApiController@add_to_cart');

Route::post('/add-to-wishlist/{id}', 'MobileApiController@add_to_wishlist');

Route::post('/update-qty-to-wishlist/{id}', 'MobileApiController@update_wishlist_quantity');
Route::post('/update-qty-to-cart/{id}', 'MobileApiController@update_cart_quantity');

Route::get('/get-cart/{id}', 'MobileApiController@get_cart');
Route::get('/get-wishlist/{id}', 'MobileApiController@get_wishlist');

Route::get('remove-wishlist-item/{id}', 'MobileApiController@remove_wishlist_item');
Route::get('remove-cart-item/{id}', 'MobileApiController@remove_cart_item');

Route::get('/checkout-process/{id}', 'MobileApiController@checkout_process');

Route::post('apply-coupon-code', 'MobileApiController@apply_coupon_code');

Route::get('get-address/{id}', 'MobileApiController@get_address');
Route::post('update-address/{id}', 'MobileApiController@update_address');

Route::post('place-order', 'MobileApiController@place_order');
Route::get('get-cart-items/{id}', 'MobileApiController@get__cart_orders');
Route::get('cancel-order/{id}', 'MobileApiController@cancel_order');
Route::post('return-order', 'MobileApiController@return_order');
Route::get('get-cashback-orders/{id}', 'MobileApiController@get_cashback_orders');
Route::match(['GET', 'POST'], 'payment-success', 'MobileApiController@payment_success');

Route::get('get-orders/{id}', 'MobileApiController@get_orders');
Route::get('get-order-details/{id}', 'MobileApiController@get_order_details');
Route::get('get-user/{id}', 'MobileApiController@get_user');
Route::post('update-user', 'MobileApiController@update_user');

Route::post('add-money-to-wallet', 'MobileApiController@add_money_to_wallet');
Route::match(['GET', 'POST'],'wallet-payment-success', 'MobileApiController@wallet_payment_success');



/******************Seller apis********************/

Route::post('get-seller-orders', 'MobileApiController@get_seller_orders');
Route::get('get-dashboard-data/{id}', 'MobileApiController@get_dashboard_data');

Route::post('get-daily-total-orders/{type}', 'MobileApiController@get_daily_total_orders');
Route::post('get-daily-total-amount/{type}', 'MobileApiController@get_daily_total_amount');

Route::get('/get-purities/{id}', 'MobileApiController@get_purities');
Route::get('/get-occasion', 'MobileApiController@get_occasion');

Route::get('/get-colors', 'MobileApiController@get_colors');

Route::post('/calculate-mrp', 'MobileApiController@calculate_mrp');

Route::post('/get-all-products-seller', 'MobileApiController@get_all_products_seller');
Route::get('/get-single-product-seller/{id}', 'MobileApiController@get_single_product_seller');
Route::post('/add-product', 'MobileApiController@add_product');
Route::get('/get-single-product/{id}', 'MobileApiController@get_single_products');
Route::post('/update-product', 'MobileApiController@update_product');
Route::get('/delete-product/{id}', 'MobileApiController@delete_product');

Route::get('get-product-reviews/{id}', 'MobileApiController@get_product_reviews');

Route::get('get-product-variants/{id}', 'MobileApiController@get_product_variants');
Route::post('/add-variant', 'MobileApiController@add_variant');
Route::get('/get-single-variant/{id}', 'MobileApiController@get_single_variant');
Route::post('/calculate-variant-mrp', 'MobileApiController@calculate_variant_mrp');
Route::post('/update-variant', 'MobileApiController@update_variant');
Route::get('/delete-variant/{id}', 'MobileApiController@delete_variant');
Route::post('/upload-variant-images', 'MobileApiController@upload_variant_images');
Route::get('/delete-variant-images/{id}', 'MobileApiController@delete_variant_images');


//});