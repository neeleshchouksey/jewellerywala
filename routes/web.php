<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
| Define the routes for your Frontend pages here
|
*/


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
| Guest routes cannot be accessed if the user is already logged in.
| He will be redirected to '/" if he's logged in.
|
*/

Route::get('admin', function () {
    return redirect("admin/login");
});


Route::group(['prefix' => 'admin'], function () {

    Route::get('login', [
        'as' => 'login', 'uses' => 'AuthController@login'
    ]);

    Route::get('register', [
        'as' => 'register', 'uses' => 'AuthController@register'
    ]);

    Route::post('login', [
        'as' => 'login.post', 'uses' => 'AuthController@postLogin'
    ]);

    Route::get('forgot-password', [
        'as' => 'forgot-password.index', 'uses' => 'ForgotPasswordController@getEmail'
    ]);

    Route::post('/forgot-password', [
        'as' => 'send-reset-link', 'uses' => 'ForgotPasswordController@postEmail'
    ]);

    Route::get('/password/reset/{token}', [
        'as' => 'password.reset', 'uses' => 'ForgotPasswordController@GetReset'
    ]);

    Route::post('/password/reset', [
        'as' => 'reset.password.post', 'uses' => 'ForgotPasswordController@postReset'
    ]);

    Route::get('auth/{provider}', 'AuthController@redirectToProvider');

    Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Route group for Backend prefixed with "admin".
| To Enable Authentication just remove the comment from Admin Middleware
|
*/

Route::group([
    'prefix' => 'admin',
    'middleware' => 'admin'
], function () {

    // Dashboard
    //----------------------------------

    Route::get('/no-access-right', [
       'uses' => 'DashboardController@no_access_right'
    ]);
  Route::get('/dashboard', [
        'as' => 'admin.dashboard', 'uses' => 'DashboardController@index'
    ]);
    Route::get('/category', [
        'as' => 'admin.category', 'uses' => 'CategoryController@index'
    ]);

    Route::get('/homepage-cms', [
        'uses' => 'DashboardController@homepage_cms'
    ]);
    Route::get('/shipping-charges', [
        'uses' => 'DashboardController@shipping_charges'
    ]);
    Route::get('/manage-coupon', [
        'uses' => 'DashboardController@manage_coupon'
    ]);
    Route::get('/manage-super-coin', [
        'uses' => 'DashboardController@manage_super_coin'
    ]);
    Route::get('/manage-commission', [
        'uses' => 'DashboardController@manage_commission'
    ]);
     Route::get('/seller-commission', [
        'uses' => 'DashboardController@seller_commission'
    ]);
    Route::get('/seller-orders/{id}', [
        'uses' => 'DashboardController@seller_orders'
    ]);
    Route::get('/user-search-keywords', [
        'uses' => 'DashboardController@user_search_keywords'
    ]);
    Route::get('/metal-prices', [
        'uses' => 'DashboardController@metal_prices'
    ]);
    Route::get('/sub-admin', [
        'uses' => 'DashboardController@sub_admin'
    ]);

    Route::get('/products', [
        'uses' => 'ProductController@index'
    ]);
    Route::get('/add-products-to-homepage', [
        'uses' => 'ProductController@add_products_to_homepage'
    ]);
    Route::get('/orders', [
        'uses' => 'ProductController@orders'
    ]);
    Route::get('/invoice/{id}', [
        'uses' => 'ProductController@invoice'
    ]);
    Route::get('/return-requests', [
        'uses' => 'ProductController@return_requests'
    ]);
    Route::get('/users', [
        'uses' => 'DashboardController@users'
    ]);
    Route::get('/sellers', [
        'uses' => 'DashboardController@sellers'
    ]);
    Route::get('/seller-profile/{id}', [
        'uses' => 'ProfileController@index'
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


    /***************Api for categories****************/

    Route::get('/get-all-categories', 'CategoryController@get_all_categories');
    Route::post('/add-category', 'CategoryController@add_category');
    Route::get('/get-single-category/{id}', 'CategoryController@get_single_category');
    Route::post('/update-category', 'CategoryController@update_category');

    /***************Api for sub categories****************/

    Route::get('/get-categories', 'CategoryController@get_categories');
    Route::get('get-all-sub-categories', 'CategoryController@get_all_sub_categories');
    Route::post('/add-sub-category', 'CategoryController@add_sub_category');
    Route::post('/update-sub-category', 'CategoryController@update_sub_category');
    Route::get('/get-single-sub-category/{id}', 'CategoryController@get_single_sub_category');

    /***************Api for purity****************/

    Route::get('/get-purity', 'CategoryController@get_purity');
    Route::get('get-all-purity', 'CategoryController@get_all_purity');
    Route::post('/add-purity', 'CategoryController@add_purity');
    Route::post('/update-purity', 'CategoryController@update_purity');
    Route::get('/get-single-purity/{id}', 'CategoryController@get_single_purity');


    /***************Api for occasion****************/

    Route::get('/get-all-occasion', 'CategoryController@get_all_occasion');
    Route::post('/add-occasion', 'CategoryController@add_occasion');
    Route::get('/get-single-occasion/{id}', 'CategoryController@get_single_occasion');
    Route::post('/update-occasion', 'CategoryController@update_occasion');


    /***************Api for color****************/

    Route::get('/get-all-colors', 'CategoryController@get_all_colors');
    Route::post('/add-color', 'CategoryController@add_color');
    Route::get('/get-single-color/{id}', 'CategoryController@get_single_color');
    Route::post('/update-color', 'CategoryController@update_color');


    // ----------------categories required for products api routes----------------//

    Route::get('/get-categories', 'CategoryController@get_categories');
    Route::get('/get-occasion', 'CategoryController@get_occasion');
    Route::get('/get-colors', 'CategoryController@get_colors');
    Route::get('/get-subcategories/{id}', 'CategoryController@get_subcategories');
    Route::get('/get-purities/{id}', 'CategoryController@get_purities');


    //-------------------homepage-section apis----------------------------//

    Route::post('/add-section1', 'DashboardController@add_section1');
    Route::post('/add-section2', 'DashboardController@add_section2');
    Route::post('/add-section4', 'DashboardController@add_section4');
    Route::post('/add-section6', 'DashboardController@add_section6');
    Route::get('/get-section/{id}', 'DashboardController@get_section');
    Route::get('/delete-section1/{id}', 'DashboardController@delete_section1');

    //-------------------shipping charges apis---------------------//

    Route::post('/add-shipping-data', 'DashboardController@add_shipping_data');
    Route::get('/get-shipping-data', 'DashboardController@get_shipping_data');


    //---------------product apis-------------//

    Route::post('/update-product', 'ProductController@update_product_api');
    Route::post('verify-product/{id}', 'ProductController@verify_product');
    Route::get('/get-single-product/{id}', 'ProductController@get_single_products');
    Route::post('/get-all-products', 'ProductController@get_all_products');
    Route::post('/change-homepage-product-status/{id}', 'ProductController@change_homepage_product_status');
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
    Route::post('get-return-request-orders', 'ProductController@get_return_request_orders');
    Route::post('get-order-details/{id}', 'ProductController@get_order_details');
    Route::post('change-delivery-status/{id}', 'UserController@change_delivery_status');
    Route::post('change-return-status/{id}', 'UserController@change_return_status');


    //------------all users api-----------------------//

    Route::post('get-all-users/{id}', 'DashboardController@get_all_users');
    Route::post('activate-deactivate-user/{id}', 'DashboardController@activate_deactivate_user');

    //-----------coupon code apis-----------------------//

    Route::get('get-all-coupon-code', 'DashboardController@get_all_coupon_code');
    Route::get('get-single-coupon-code/{id}', 'DashboardController@get_single_coupon_code');
    Route::post('add-coupon-code', 'DashboardController@add_coupon_code');
    Route::post('update-coupon-code', 'DashboardController@update_coupon_code');

    //--------super-coin related apis-------------------//

    Route::get('get-all-super-coin', 'DashboardController@get_all_super_coin');
    Route::get('get-single-super-coin/{id}', 'DashboardController@get_single_super_coin');
    Route::post('add-super-coin', 'DashboardController@add_super_coin');
    Route::post('update-super-coin', 'DashboardController@update_super_coin');

    //---------------commission related apis---------------------//

    Route::get('get-all-commission', 'DashboardController@get_all_commission');
    Route::post('add-commission', 'DashboardController@add_commission');
    Route::get('get-single-commission/{id}', 'DashboardController@get_single_commission');
    Route::post('update-commission', 'DashboardController@update_commission');


    //-------------seller commission related apis----------------//

    Route::post('get-all-sellers-commission', 'DashboardController@get_all_sellers_commission');
    Route::post('get-seller-orders/{id}', 'DashboardController@get_seller_orders');

    Route::get('get-all-user-search-keywords', 'DashboardController@get_all_user_search_keywords');
    Route::get('get-profile/{id}', 'ProfileController@get_profile');


    //--------------dashboard chart apis--------------------------//

    Route::get('get-daily-orders/{type}', 'DashboardController@get_daily_orders');
    Route::get('get-daily-total-orders/{type}', 'DashboardController@get_daily_total_orders');


    //------------------metal -prices---------------------------//

    Route::get('get-all-metal-price', 'DashboardController@get_all_metal_price');
    Route::post('update-metal-price', 'DashboardController@update_metal_price');


    //------------------sub-admin---------------------------//

    Route::get('get-access-roles', 'DashboardController@get_access_roles');
    Route::get('get-update-access-roles/{id}', 'DashboardController@get_update_access_roles');
    Route::get('/get-all-sub-admin', 'DashboardController@get_subadmin');
    Route::post('/add-sub-admin', 'DashboardController@add_subadmin');
    Route::get('/get-single-sub-admin/{id}', 'DashboardController@get_single_subadmin');
    Route::post('/update-sub-admin', 'DashboardController@update_subadmin');

});
