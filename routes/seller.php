<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
| Define the routes for your Frontend pages here
|
*/


Route::get('seller', function () {
    return redirect("seller/login-register");
});


Route::group(['prefix' => 'seller'], function () {
    Route::get('login-register', [
        'as' => 'login-register', 'uses' => 'HomeController@login_register'
    ]);

});

//Route::get('logout', [
//    'as' => 'logout', 'uses' => 'AuthController@logout'
//]);


Route::group([
    'prefix' => 'seller',
    'middleware' => 'seller'
], function () {

    // ----------------view load routes----------------//

    Route::get('/dashboard', [
        'as' => 'admin.dashboard', 'uses' => 'DashboardController@index'
    ]);


    Route::get('/profile', [
        'as' => 'admin.profile', 'uses' => 'ProfileController@index'
    ]);

    Route::get('/add-product', [
        'uses' => 'ProductController@add_product'
    ]);

    Route::get('/products', [
        'uses' => 'ProductController@index'
    ]);
    Route::get('/orders', [
        'uses' => 'ProductController@orders'
    ]);
    Route::get('/return-requests', [
        'uses' => 'ProductController@return_requests'
    ]);

    Route::get('/edit-product/{id}', [
        'uses' => 'ProductController@edit_product'
    ]);

    Route::get('/view-product/{id}', [
        'uses' => 'ProductController@view_product'
    ]);

    Route::post('/delete-data/{id}', 'CategoryController@delete_data');



    /************************Notifications apis*********************/

    Route::get('get-notifications', 'NotificationController@get_notifications');
    Route::get('read-notification', 'NotificationController@read_notification');


    // ----------------categories required for products api routes----------------//

    Route::get('/get-categories', 'CategoryController@get_categories');
    Route::get('/get-colors', 'CategoryController@get_colors');
    Route::get('/get-subcategories/{id}', 'CategoryController@get_subcategories');
    Route::get('/get-purities/{id}', 'CategoryController@get_purities');
    Route::get('/get-occasion', 'CategoryController@get_occasion');

    //---------------product apis-------------//

    Route::post('/add-product', 'ProductController@add_product_api');
    Route::post('/update-product', 'ProductController@update_product_api');
    Route::get('/get-single-product/{id}', 'ProductController@get_single_products');
    Route::post('/get-all-products', 'ProductController@get_all_products');

    Route::post('/calculate-mrp', 'ProductController@calculate_mrp');
    Route::post('/calculate-variant-mrp', 'ProductController@calculate_variant_mrp');

    //-----------product variant apis---------------//

    Route::get('get-product-reviews/{id}', 'ProductController@get_product_reviews');

    Route::get('get-product-variants/{id}', 'ProductController@get_product_variants');
    Route::post('/add-variant', 'ProductController@add_variant');
    Route::get('/get-single-variant/{id}', 'ProductController@get_single_variant');
    Route::post('/update-variant', 'ProductController@update_variant');
    Route::post('/upload-variant-images', 'ProductController@upload_variant_images');


    //----------------Orders api-------------------//

    Route::post('get-orders', 'ProductController@get_orders');
    Route::post('get-order-details/{id}', 'ProductController@get_order_details');
    Route::post('get-return-request-orders', 'ProductController@get_return_request_orders');
    Route::post('change-return-status/{id}', 'UserController@change_return_status');

  //----------------Profile apis ------------------//

    Route::get('get-profile', 'ProfileController@get_profile');
    Route::post('update-profile', 'ProfileController@update_profile');
    Route::post('upload-image','ProfileController@upload_image');


    //--------------dashboard chart apis--------------------------//

    Route::get('get-daily-orders/{type}', 'DashboardController@get_daily_orders');
    Route::get('get-daily-total-orders/{type}', 'DashboardController@get_daily_total_orders');
});
