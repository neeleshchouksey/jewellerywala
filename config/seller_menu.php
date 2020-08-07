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
            'link' => 'seller/dashboard',
            'active' => 'seller/dashboard*',
            'icon' => 'icon-fa icon-fa-dashboard',
        ],
        [
            'title' => 'Profile',
            'link' => 'seller/profile',
            'active' => 'seller/profile*',
            'icon' => 'icon-fa icon-fa-user',
        ],
        [
            'title' => 'Manage Products',
            'link' => '#',
            'active' => 'seller/products*',
            'icon' => 'icon-fa icon-fa-product-hunt',
            'children' => [
                [
                    'title' => 'Add Product',
                    'link' => '/seller/add-product',
                    'active' => 'seller/add-product',
                ],
                [
                    'title' => 'View Product',
                    'link' => '/seller/products',
                    'active' => 'seller/products',
                ]
            ]
        ],
       [
            'title' => 'Orders',
            'link' => '#',
            'active' => 'seller/orders/*',
            'icon' => 'icon-fa icon-fa-cart-plus',
            'children' => [
                [
                    'title' => 'All Orders',
                    'link' => '/seller/orders',
                    'active' => 'seller/orders',
                ],
                [
                    'title' => 'Return Requests',
                    'link' => '/seller/return-requests',
                    'active' => 'seller/return-requests',
                ]
            ]
        ],
    ],
    
];
