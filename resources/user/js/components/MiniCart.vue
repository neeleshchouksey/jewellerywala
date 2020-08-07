<template>
    <div>
        <div class="header-bottom_area header-sticky stick">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 d-lg-none d-block">
                        <div class="header-logo">
                            <a :href="APP_URL">
                                <img :src="APP_URL+'/public/assets/user/logo-login.png'"
                                >
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-10 d-none d-lg-block position-static">
                        <div class="main-menu_area">
                            <nav>
                                <ul>
                                    <li class="new-hader-li-12"></li>
                                    <li><a :href="APP_URL">Home</a></li>

                                    <li v-for="c in UserCategories"><a href="#">{{c.category_name}}</a>
                                        <ul class="hm-dropdown">
                                            <li v-for="s in c.subcategory"><a
                                                    :href="APP_URL+'/shop?sub_category='+s.id">{{s.sub_category_name}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Contact</a></li>
                                    <li><a href="javascript:void(0)" v-on:click="openModal">Shops</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-8 col-sm-8">
                        <div class="header-right_area aj-header-right_area">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" id="togelopnein"
                                       class="mobile-menu_btn toolbar-btn color--white d-lg-none d-block">
                                        <i class="ion-navicon"></i>
                                    </a>
                                </li>
                                <li>
                                    <div class="popover__wrappere offcanvas-minicart_wrapper">
                                        <a href="#" class="popover__titlee"><i class="ion-bag">{{cart_size}}</i>
                                        </a>
                                        <div class="popover__contente offcanvas-menu-inner">
                                            <!--                                            <p class="popover__message">Joseph Francis "Joey" Tribbiani, Jr.</p>-->
                                            <!--                                            <img alt="Joseph Francis Joey Tribbiani, Jr." src="https://media.giphy.com/media/11SIBu3s72Co8w/giphy.gif">-->
                                            <div v-if="cartData.length">
                                                <div class="minicart-content">
                                                    <div class="minicart-heading">
                                                        <h4>Shopping Cart</h4>
                                                    </div>
                                                    <ul class="minicart-list">
                                                        <li class="minicart-product" v-for="(cd,index) in cartData">
                                                            <a class="product-item_remove" href="javascript:void(0)"
                                                               v-on:click="removeCartItem(cd.id)"><i
                                                                    class="ion-android-close"></i></a>
                                                            <div class="product-item_img">
                                                                <img height="50px" width="50px"
                                                                     :src="APP_URL+'/public/storage/product-images/'+cd.product_image">
                                                            </div>
                                                            <div class="product-item_content">
                                                                <a class="product-item_title"
                                                                   href="shop-left-sidebar.php">{{cd.product_name}}</a>
                                                                <span class="product-item_quantity">{{cd.quantity}} x {{cd.unit_price}}</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="minicart-item_total">
                                                    <span>Subtotal</span>
                                                    <span class="ammount">{{totalAmount}}</span>
                                                </div>
                                                <div class="minicart-btn_area">
                                                    <a :href="APP_URL+'/my-cart'"
                                                       class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth">Go to
                                                        Cart</a>
                                                </div>
                                                <!--                                                <div class="minicart-btn_area">-->
                                                <!--                                                    <a href="javascript:void(0)"-->
                                                <!--                                                       class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth"-->
                                                <!--                                                       v-on:click="proceedToCheckout">Checkout</a>-->
                                                <!--                                                </div>-->
                                            </div>
                                            <div v-if="!cartData.length">
                                                No Items Found in Cart
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li v-if="AUTH_USER">
                                    <div class="popover__wrapper offcanvas-minicart_wrapper">
                                        <a href="#" class="popover__title"><i class="ion-heart"></i>
                                        </a>
                                        <div class="popover__content offcanvas-menu-inner">
                                            <!--                                            <p class="popover__message">Joseph Francis "Joey" Tribbiani, Jr.</p>-->
                                            <!--                                            <img alt="Joseph Francis Joey Tribbiani, Jr." src="https://media.giphy.com/media/11SIBu3s72Co8w/giphy.gif">-->
                                            <div v-if="wishlistData.length">
                                                <div class="minicart-content">
                                                    <div class="minicart-heading">
                                                        <h4>Shopping Wishlist</h4>
                                                    </div>
                                                    <ul class="minicart-list">
                                                        <li class="minicart-product" v-for="(cd,index) in wishlistData">
                                                            <a class="product-item_remove" href="javascript:void(0)"
                                                               v-on:click="removeWishlistItem(cd.id)"><i
                                                                    class="ion-android-close"></i></a>
                                                            <div class="product-item_img">
                                                                <img height="50px" width="50px"
                                                                     :src="APP_URL+'/public/storage/product-images/'+cd.product_image">
                                                            </div>
                                                            <div class="product-item_content">
                                                                <a class="product-item_title"
                                                                   href="shop-left-sidebar.php">{{cd.product_name}}</a>
                                                                <span class="product-item_quantity">{{cd.unit_price}}</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="minicart-btn_area">
                                                    <a :href="APP_URL+'/my-wishlist'"
                                                       class="hiraola-btn hiraola-btn_dark hiraola-btn_fullwidth">Go to
                                                        Wishlist</a>
                                                </div>
                                            </div>
                                            <div v-if="!wishlistData.length">
                                                No Items Found in Wishlist
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal id="shop-modal" ref="shop_modal" title="Search Products" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <select name="billing_state" id="billing_state"
                            class="form-control" autocomplete="address-level1"
                            data-placeholder="Select an option…" tabindex="-1" aria-hidden="true"
                            v-on:change="getCity">
                        <option value="">Select an option…</option>
                        <option v-for="sd in stateData" :value="sd.id">{{sd.name}}</option>
                    </select>
                </div>
                <div class="row mb-3">
                    <select name="billing_state" id="billing_city"
                            class="form-control unicase-form-control state_select select2-hidden-accessible"
                            autocomplete="address-level1"
                            data-placeholder="Select an option…" tabindex="-1" aria-hidden="true">
                        <option value="">Select an option…</option>
                        <option v-for="cd in cityData" :value="cd.id">{{cd.name}}</option>
                    </select>
                </div>
                <div class="row mb-3">
                    <button class="btn btn-theme" v-on:click="searchProducts">Search Products</button>
                </div>
            </div>
        </b-modal>

    </div>
</template>


<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Cart',
        data() {
            return {}
        },
        mixins: [UserMixin],
        created() {
            let that = this;
            that.getCategories();
            that.getCart();

            if (AUTH_USER) {
                that.getWishlist();
            }

        },
        methods: {

            searchProducts: function(){
               var state = $("#billing_state").val();
               var city = $("#billing_city").val();

               window.location = APP_URL+'/all-shops?state='+state+'&city='+city;
            },

            openModal: function () {
                this.$refs['shop_modal'].show();
                this.getState();
            },

            proceedToCheckout: function () {
                let that = this;
                axios.get(APP_URL + '/checkout-process').then(response => {
                        window.location = APP_URL + "/checkout/" + response.data.cart_id
                    }
                ).catch((error) => {

                });
            },
        }
    }

    $(document).ready(function () {
        $('#togelopnein').click(function () {
            $(".mobile-menu_wrapper").addClass("open");

        });
        $('.btn-close').click(function () {
            $(".mobile-menu_wrapper").removeClass("open");

        });
    });


</script>
