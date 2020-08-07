import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import moment from 'moment'
import VoerroTagsInput from '@voerro/vue-tagsinput';

Vue.config.productionTip = false
Vue.component('tags-input', VoerroTagsInput);
/****************Admin Component import************************/
import Echo from "laravel-echo"
import Pusher from "pusher-js"

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'fdd7e26e22655e70303b', //Add your pusher key here
    cluster: 'ap2',
    encrypted: true
});
import BarChart from './components/BarChart.vue'
import LineChart from './components/LineChart.vue'
import PieChart from './components/PieChart.vue'
import SalesChart from './components/SalesChart.vue'

import ManageCategory from './components/ManageCategory.vue'
import SubAdmin from './components/SubAdmin.vue'
import Users from './components/Users.vue'
import Sellers from './components/Sellers.vue'

import HomepageProducts from "./components/HomepageProducts";
import HomepageCMS from "./components/HomepageCMS";
import ShippingCharges from "./components/ShippingCharges";
import CouponCode from "./components/CouponCode";
import SuperCoin from "./components/SuperCoin";
import Commission from "./components/Commission";
import MetalPrices from "./components/MetalPrices";
import SellerCommission from "./components/SellerCommission";
import SellerAllOrders from "./components/SellerAllOrders";
import UserSearchKeyword from "./components/UserSearchKeyword";
import ReturnRequests from "./components/ReturnRequests";

/****************End Admin Component import************************/

import Notification from "./components/Notification";

/****************Seller Component import************************/

import AddProduct from './components/AddProduct.vue'
import EditProduct from './components/EditProduct.vue'
import ViewProduct from './components/ViewProduct.vue'
import Products from "./components/Products";
import Orders from "./components/Orders";
import Profile from "./components/Profile";

/****************End Seller Component import************************/


import VueSweetalert2 from 'vue-sweetalert2';

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);
Vue.use(BootstrapVue);
Vue.component('pagination', require('laravel-vue-pagination'));

/********************User Components import**************/

import LoginRegister from '../../user/js/components/LoginRegister'
import ProductFilter from '../../user/js/components/ProductFilter'
import ShopFilter from '../../user/js/components/ShopFilter'
import ProductDetails from "../../user/js/components/ProductDetails";
import Cart from "../../user/js/components/Cart";
import Wishlist from "../../user/js/components/Wishlist";
import MiniCart from "../../user/js/components/MiniCart";
import Checkout from "../../user/js/components/Checkout";
import MyAccount from "../../user/js/components/MyAccount";
import Wallet from "../../user/js/components/Wallet";
import ResetPassword from "../../user/js/components/ResetPassword";
import NewArrivalSlider from "../../user/js/components/NewArrivalSlider";
import FeaturedSlider from "../../user/js/components/FeaturedSlider";
import TrendingSlider from "../../user/js/components/TrendingSlider";


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = Vue

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


/****************Admin Components************************/

window.Vue.component('bar-chart', BarChart)
window.Vue.component('line-chart', LineChart)
window.Vue.component('pie-chart', PieChart)
window.Vue.component('sales-chart', SalesChart)

window.Vue.component('manage-category', ManageCategory)
window.Vue.component('sub-admin', SubAdmin)
window.Vue.component('sellers', Sellers)
window.Vue.component('users', Users)

window.Vue.component('homepage-products', HomepageProducts)
window.Vue.component('homepage-cms', HomepageCMS)
window.Vue.component('shipping-charges', ShippingCharges)
window.Vue.component('coupon-code', CouponCode)
window.Vue.component('super-coin', SuperCoin)
window.Vue.component('commission', Commission)
window.Vue.component('metal-prices', MetalPrices)
window.Vue.component('seller-commission', SellerCommission)
window.Vue.component('seller-all-orders', SellerAllOrders)
window.Vue.component('user-search-keywords', UserSearchKeyword)
window.Vue.component('return-requests', ReturnRequests)
window.Vue.component('notification', Notification)

/*****************Seller Components**************************/

window.Vue.component('add-product', AddProduct)
window.Vue.component('edit-product', EditProduct)
window.Vue.component('view-product', ViewProduct)
window.Vue.component('products', Products)
window.Vue.component('orders', Orders)
window.Vue.component('profile', Profile)


/***********USER COMPONENTS***********************/

window.Vue.component('login-register', LoginRegister)
window.Vue.component('my-account', MyAccount)
window.Vue.component('product-filter', ProductFilter)
window.Vue.component('shop-filter', ShopFilter)
window.Vue.component('product-details', ProductDetails)
window.Vue.component('cart', Cart)
window.Vue.component('wishlist', Wishlist)
window.Vue.component('mini-cart', MiniCart)
window.Vue.component('checkout', Checkout)
window.Vue.component('wallet', Wallet)
window.Vue.component('reset-password', ResetPassword)
window.Vue.component('new-arrival-slider', NewArrivalSlider)
window.Vue.component('featured-slider', FeaturedSlider)
window.Vue.component('trending-slider', TrendingSlider)

let app = new window.Vue({
    el: '#app',
    created() {
        console.log(AUTH_USER_ID);
        if(AUTH_USER_ID){
            window.Echo.channel('order-placed'+AUTH_USER_ID)
                .listen('OrderPlaced', (e) => {
                    console.log("Event calling"+'order-placed'+AUTH_USER_ID);
                    this.getNotifications();
                });
            window.Echo.channel('order-placed-admin' + AUTH_USER_ID)
                .listen('OrderPlacedAdmin', (e) => {
                    console.log("Event calling"+'order-placed'+AUTH_USER_ID);
                    this.getNotifications();
                });
        }

    },
    data: {
        notifications: [],
        readNot: ""
    },
    methods: {
        getNotifications: function () {
            console.log("calling notifications");
            axios.get(APP_URL + '/' + USER_TYPE + '/get-notifications',).then(response => {
                response = response.data;
                if (response.status == "success") {
                    this.notifications = response.res;
                    this.readNot = response.read;
                } else {
                }

            }).catch((error) => {
                alert("error from 400");
            });
        },
        readNotification: function () {
            axios.get(APP_URL + '/' + USER_TYPE + '/read-notification',).then(response => {
                this.getNotifications();
            }).catch((error) => {
                alert("error from 400");
            });
        },
    }
});

let user_app = new window.Vue({
    el: '#user-app'
});
Vue.filter('formatDate', function (value) {
    if (value) {

        return moment(String(value)).format('DD/MM/YYYY hh:mm')

    }

});
Vue.filter('formatTime', function (value) {

    if (value) {

        return moment.unix(value).format("DD/MM/YYYY hh:mm");

    }

});
