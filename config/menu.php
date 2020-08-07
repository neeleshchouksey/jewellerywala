<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Navigation Menu
    |--------------------------------------------------------------------------
    |
    | This array is for Navigation menus of the backend.  Just add/edit or
    | remove the elements from this array which will automatically change the
    | navigation.
    |
    */

    // SIDEBAR LAYOUT - MENU

    'sidebar' => [
        [
            'title' => 'Dashboard',
            'link' => 'admin/dashboard',
            'active' => 'admin/dashboard*',
            'icon' => 'icon-fa icon-fa-dashboard',
        ],
         [
            'title' => 'Manage Categories',
            'link' => 'admin/category',
            'active' => 'admin/category*',
            'icon' => 'icon-fa icon-fa-th-large',
        ],
        [
            'title' => 'Homepage CMS',
            'link' => 'admin/homepage-cms',
            'active' => 'admin/homepage-cms*',
            'icon' => 'icon-fa icon-fa-home',
        ],

        [
            'title' => 'Manage Products',
            'link' => '#',
            'active' => 'admin/products*',
            'icon' => 'icon-fa icon-fa-product-hunt',
            'children' => [
                [
                    'title' => 'View Products',
                    'link' => '/admin/products',
                    'active' => 'admin/products',
                ],
                [
                    'title' => 'Add Products to Homepage',
                    'link' => '/admin/add-products-to-homepage',
                    'active' => 'admin/users',
                ]
            ]
        ],
        [
            'title' => 'Orders',
            'link' => '#',
            'active' => 'admin/orders/*',
            'icon' => 'icon-fa icon-fa-cart-plus',
            'children' => [
                [
                    'title' => 'All Orders',
                    'link' => '/admin/orders',
                    'active' => 'admin/orders',
                ],
                [
                    'title' => 'Return Requests',
                    'link' => '/admin/return-requests',
                    'active' => 'admin/return-requests',
                ]
            ]
        ],
        [
            'title' => 'All Users',
            'link' => '#',
            'active' => 'admin/users*',
            'icon' => 'icon-fa icon-fa-users',
            'children' => [
                [
                    'title' => 'Sellers',
                    'link' => '/admin/sellers',
                    'active' => 'admin/sellers',
                ],
                [
                    'title' => 'Users',
                    'link' => '/admin/users',
                    'active' => 'admin/users',
                ]
            ]
        ],
        [
            'title' => 'Shipping Charges',
            'link' => 'admin/shipping-charges',
            'active' => 'admin/shipping-charges*',
            'icon' => 'icon-fa icon-fa-credit-card-alt',
        ],
        [
            'title' => 'Manage Coupon',
            'link' => 'admin/manage-coupon',
            'active' => 'admin/manage-coupon*',
            'icon' => 'icon-fa icon-fa-google-wallet',
        ],
        [
            'title' => 'Manage Cashback',
            'link' => 'admin/manage-super-coin',
            'active' => 'admin/manage-super-coin*',
            'icon' => 'icon-fa icon-fa-money',
        ],
//         [
//            'title' => 'Manage Commission',
//            'link' => 'admin/manage-commission',
//            'active' => 'admin/manage-commission*',
//            'icon' => 'icon-fa icon-fa-inr',
//        ],
        [
            'title' => 'Seller Commission',
            'link' => 'admin/seller-commission',
            'active' => 'admin/seller-commission*',
            'icon' => 'icon-fa icon-fa-inr',
        ],
        [
            'title' => 'User Search Keywords',
            'link' => 'admin/user-search-keywords',
            'active' => 'admin/user-search-keywords*',
            'icon' => 'icon-fa icon-fa-user-plus',
        ],
        [
            'title' => 'Metal Prices',
            'link' => 'admin/metal-prices',
            'active' => 'admin/metal-prices*',
            'icon' => 'icon-fa icon-fa-inr',
        ],
        [
            'title' => 'Sub Admin',
            'link' => 'admin/sub-admin',
            'active' => 'admin/sub-admin*',
            'icon' => 'icon-fa icon-fa-user',
        ],
    ],


];
