<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
| Define the routes for your Frontend pages here
|
*/

Route::get('/call-artisan',function(){
    $exitCode = Artisan::call('storage:link', [] );
    echo $exitCode;
});

Route::get('/', [
    'as' => 'home', 'uses' => 'HomeController@index'
]);

Route::get('/terms-and-conditions-buyer',function (){
    return view("user.terms-and-condition-buyer");
});

Route::get('/terms-and-conditions-seller',function (){
    return view("user.terms-and-condition-seller");
});

Route::get('/login-register', [
    'as' => 'login-register', 'uses' => 'HomeController@login_register'
]);
Route::get('/all-shops', 'UserController@all_shops');
Route::get('/shop', [
    'as' => 'shop', 'uses' => 'UserController@index'
]);
Route::get('/product-details/{id}', [
    'as' => 'product-details', 'uses' => 'UserController@product_details'
]);
Route::get('/my-account', [
    'as' => 'my-account', 'uses' => 'UserController@my_account'
]);
Route::get('/wallet', [
    'as' => 'wallet', 'uses' => 'UserController@wallet'
]);
Route::get('/offers', [
    'as' => 'offers', 'uses' => 'UserController@offers'
]);
Route::get('/invoice/{id}', [
    'uses' => 'UserController@invoice'
]);

Route::get('/get-user-categories', 'CategoryController@get_user_categories');
Route::post('/get-shops', 'CategoryController@get_shops');

Route::get('order-details/{id}', 'UserController@order_details');

Route::post('login', 'HomeController@login');
Route::get('logout', 'HomeController@logout');
Route::post('signup', 'HomeController@signup');
Route::get('get-cities/{id}', 'HomeController@get_cities');
Route::get('get-states', 'HomeController@get_states');
Route::post('send-otp', 'HomeController@send_otp');
Route::post('check-otp', 'HomeController@check_otp');
Route::post('check-email', 'HomeController@check_email');
Route::post('send-email', 'HomeController@send_email');
Route::post('reset-password', 'HomeController@change_password');
Route::get('reset-password/{id}', 'HomeController@reset_password');
/**************Product filter page apis *******************/

Route::post('/get-all-products', 'UserController@get_all_products');

Route::get('/get-data/{id}', 'UserController@get_data');

Route::get('/get-single-product/{id}', 'UserController@get_single_product');
Route::get('/get-related-product/{id}', 'UserController@get_related_product');
Route::get('/my-cart', 'UserController@my_cart');
Route::get('/my-wishlist', 'UserController@my_wishlist');

/******************Checkout related api**********************/
Route::get('remove-wishlist-item/{id}', 'UserController@remove_wishlist_item');
Route::get('remove-cart-item/{id}', 'UserController@remove_cart_item');
//Route::get('move-items-to-cart/{id}', 'UserController@move_items_to_cart');
Route::get('move-all-items-to-cart', 'UserController@move_all_items_to_cart');
Route::post('/add-to-cart', 'UserController@add_to_cart');
Route::post('/add-to-guest-cart', 'UserController@add_to_guest_cart');
Route::post('/update-qty-to-cart/{id}', 'UserController@update_cart_quantity');
Route::get('/get-cart', 'UserController@get_cart');
Route::post('/add-to-wishlist', 'UserController@add_to_wishlist');
Route::post('/add-to-guest-wishlist', 'UserController@add_to_guest_wishlist');
Route::post('/update-qty-to-wishlist/{id}', 'UserController@update_wishlist_quantity');
Route::get('/get-wishlist', 'UserController@get_wishlist');
Route::get('/checkout-process/{id}', 'UserController@checkout_process');
Route::get('/checkout/{id}','UserController@checkout');
Route::get('get-address', 'UserController@get_address');
Route::post('update-address', 'UserController@update_address');
Route::post('place-order', 'UserController@place_order');
Route::get('get-orders/{id}', 'UserController@get__cart_orders');

Route::match(['GET', 'POST'],'payment-success', 'UserController@payment_success');

Route::match(['GET', 'POST'],'wallet-payment-success', 'UserController@wallet_payment_success');

Route::post('add-money-to-wallet', 'UserController@add_money_to_wallet');


Route::post('apply-coupon-code','UserController@apply_coupon_code');

/************My account page apis*******************/

Route::get('get-user', 'UserController@get_user');
Route::get('get-orders', 'UserController@get_orders');
Route::get('get-cashback-orders', 'UserController@get_cashback_orders');
Route::get('cancel-order/{id}', 'UserController@cancel_order');
Route::post('change-delivery-status/{id}', 'UserController@change_delivery_status');
Route::post('return-order', 'UserController@return_order');
Route::post('get-order-details/{id}', 'UserController@get_order_details');

Route::post('update-user', 'UserController@update_user');
Route::post('submit-ratings', 'UserController@submit_ratings');
Route::get('get-product-reviews/{id}', 'UserController@get_product_reviews');
Route::get('get-product-images/{id}', 'UserController@get_product_images');


/************************Home page sliders*******************/

Route::get('get-sliders/{id}', 'HomeController@get_sliders');


