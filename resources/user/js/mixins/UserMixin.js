
export default {
    name: 'UserMixin',
    data() {
        return {
            APP_URL: APP_URL,
            AUTH_USER: AUTH_USER,
            USER_TYPE: USER_TYPE,
            stateData:[],
            cityData:[],
            UserCategories:[],
            allProducts: [],
            allProductsData: {},
            productDetails:{
                'variant_images':[]
            },
            relatedProducts:[],
            cartData:[],
            cartId: "",
            grandTotal: 0,
            shippingCharges: 0,
            coupon_discount:0,
            totalAmount:"",
            cart_size:"",
            addToCartData:{
                "qty":1,
                "sellingPrice":"",
                "productId":"",
                "variantId":""
            },
            addToWishlistData:{
                "qty":1,
                "sellingPrice":"",
                "productId":"",
                "variantId":""
            },
            wishlistData:[],
            wishlistId: "",
            size:25,
            superCoinAmount:{},
            walletAmount:{},
            walletHistory:{},
            walletHistoryData:[],

        }
    },
    created(){

    },
    methods: {

        getCategories: function () {
            let that = this;
            axios.get(APP_URL + '/get-user-categories').then(response => {
                response = response.data;
                that.UserCategories = response.res;

            }).catch((error) => {
                alert("error from 400");
            });
        },

        getState: function () {
            let that = this;
            axios.get(APP_URL + '/get-states').then(response => {
                    that.stateData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        getCity: function () {
            let id = $("#billing_state").val();

            let that = this;
            axios.get(APP_URL + '/get-cities/' + id).then(response => {
                    that.cityData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        getAllProducts: function (page = 1) {
            let that = this;
            that.filterData.price[0] = $("#price-min-value").html();
            that.filterData.price[1] = $("#price-max-value").html();
            axios.post(APP_URL + '/get-all-products?page=' + page,that.filterData).then(response => {
                response = response.data;
                that.allProducts = response.res.data;
                that.allProductsData = response.res;
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getData: function (type,page=1) {
            let that = this;
            var url = APP_URL + "/" + '/get-data/'+type;

            axios.get(url).then(response => {
                response = response.data;
                if(type==1){
                    that.subCategories = response.res;
                }else if(type==2){
                    that.purities = response.res;
                }else if(type==3){
                    that.occasion = response.res;
                }else if(type==4){
                    that.superCoinAmount = response.res;
                }else if(type==5){
                    that.walletAmount = response.res;
                }else if(type==7){
                    that.brands = response.res;
                }
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getSingleProductItem: function (id,type) {
            console.log("ASDFASDFSFDA");
            let that = this;
            that.getSingleProduct(id,type);
            this.$refs['product-quick-view'].show();
            $('.sp-img_slider-view').resize();
            $('.sp-img_slider-view').slick('refresh');
            setTimeout(function () {
                $('.sp-img_slider-view').slick({
                    infinite: true,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    prevArrow: '<button class="slick-prev"><i class="ion-ios-arrow-back"></i></button>',
                    nextArrow: '<button class="slick-next"><i class="ion-ios-arrow-forward"></i></button>',
                    responsive: [{
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 575,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });

            },2000);
        },
        getSingleProduct:function(id,type=null){
            if(!id){
                id = $("#product-variant-id").val();
            }

            axios.get(APP_URL+'/get-single-product/' + id).then(response => {

                let that = this;
                response = response.data;
                that.productDetails = response.res;
                that.productDetails.color = response.res.color_id;
                that.productDetails.qty = 1;

                that.getProductImages(id);

            }).catch((error) => {
               // alert("error from 400");
            });
        },
        increment:function(){
            let that = this;
            that.addToCartData.qty = that.addToCartData.qty+1;
        },
        decrement:function(){
            let that = this;
            if(that.addToCartData.qty>1){
                that.addToCartData.qty = that.addToCartData.qty-1;
            }
        },

        addToWishlist: function (pd=null) {
            let that = this;
            let url = "";
            if(AUTH_USER) {
                url = APP_URL + '/add-to-wishlist';

            }else{
                url = APP_URL + '/add-to-guest-wishlist';
            }
                if (!pd) {
                    that.addToWishlistData.sellingPrice = that.productDetails.selling_price;
                    that.addToWishlistData.productId = that.productDetails.id;
                    that.addToWishlistData.variantId = that.productDetails.product_variant_id;
                    pd = that.addToWishlistData;
                    console.log("calling");
                    if(!that.productDetails.qty){
                        this.$swal({
                            type: "error",
                            title: "Error",
                            text: "Please enter quantity",
                            showConfirmButton: true
                        })
                    }
                }else{
                    that.addToWishlistData.sellingPrice = pd.selling_price;
                    that.addToWishlistData.productId = pd.id;
                    that.addToWishlistData.variantId = pd.product_variant_id;
                    that.addToWishlistData.qty = 1;
                    pd = that.addToWishlistData;
                }
                axios.post(url, pd).then(response => {
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.data.message,
                            showConfirmButton: true
                        }).then(function(){
                            window.location = APP_URL+"/my-wishlist";
                        });
                    }
                ).catch((error) => {

                });
            // }else{
            //     var cur_url = document.URL;
            //     console.log(cur_url);
            //     cur_url = encodeURIComponent(cur_url);
            //     const el = document.createElement('div')
            //     el.innerHTML = "Please <a style='color: #9f8447' href='" + APP_URL + "/login-register?url=" + cur_url + "'>Login</a> or <a style='color: #9f8447' href='" + APP_URL + "/login-register?url=" + cur_url + "'>Register</a> to Continue"
            //     this.$swal({
            //         title: "Warning!",
            //         html: el,
            //         icon: "warning",
            //     }).then(function () {
            //         window.location = APP_URL + "/login-register?url=" + cur_url;
            //     });
            // }
        },
        addToCart: function (pd=null) {
            let that = this;
            let url = "";
            if(AUTH_USER){
                url = APP_URL + '/add-to-cart';
            }else{
                url = APP_URL + '/add-to-guest-cart';
            }

        //    if(AUTH_USER) {
                if (!pd) {
                    that.addToCartData.sellingPrice = that.productDetails.selling_price;
                    that.addToCartData.productId = that.productDetails.id;
                    that.addToCartData.variantId = that.productDetails.product_variant_id;
                    pd = that.addToCartData;
                    console.log("calling");
                    if(!that.productDetails.qty){
                        this.$swal({
                            type: "error",
                            title: "Error",
                            text: "Please enter quantity",
                            showConfirmButton: true
                        })
                    }
                }else{
                    that.addToCartData.sellingPrice = pd.selling_price;
                    that.addToCartData.productId = pd.id;
                    that.addToCartData.variantId = pd.product_variant_id;
                    that.addToCartData.qty = 1;
                    pd = that.addToCartData;
                }
                axios.post(url, pd).then(response => {
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.data.message,
                            showConfirmButton: true
                        }).then(function(){
                            window.location = APP_URL+"/my-cart";
                        });
                    }
                ).catch((error) => {

                });
            // }else{
            //     var cur_url = document.URL;
            //     console.log(cur_url);
            //     cur_url = encodeURIComponent(cur_url);
            //     const el = document.createElement('div')
            //     el.innerHTML = "Please <a style='color: #9f8447' href='" + APP_URL + "/login-register?url=" + cur_url + "'>Login</a> or <a style='color: #9f8447' href='" + APP_URL + "/login-register?url=" + cur_url + "'>Register</a> to Continue"
            //     this.$swal({
            //         title: "Warning!",
            //         html: el,
            //         icon: "warning",
            //     }).then(function () {
            //         window.location = APP_URL + "/login-register?url=" + cur_url;
            //     });
            // }
        },
        getCart: function () {
            let that = this;
            axios.get(APP_URL + '/get-cart').then(response => {
                    that.cartData = response.data.res;
                    that.totalAmount = response.data.total_amount;
                    that.cart_size = response.data.total_count;
                    that.cartId = response.data.cart_id;
                }
            ).catch((error) => {

            });
        },
       getWishlist: function () {
            let that = this;
            axios.get(APP_URL + '/get-wishlist').then(response => {
                    that.wishlistData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        removeWishlistItem: function (id) {
            let that = this;
            that.$swal({
                title: "Are you sure?",
                text: "You want to remove this wishlist item",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes",
            }).then(function (result) {
                if (result.value) {
                    axios.get(APP_URL + '/remove-wishlist-item/' + id).then((response) => {
                        response = response.data;
                        if (response.status == "success") {
                            that.$swal({
                                type: "success",
                                title: "Success",
                                text: "Item Removed Successfully",
                                showConfirmButton: true
                            }).then(function () {
                                that.getWishlist();
                            });
                        }
                    });
                }
            })

        },
        removeCartItem: function (id) {
            let that = this;
            that.$swal({
                title: "Are you sure?",
                text: "You want to remove this cart item",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes",
            }).then(function (result) {
                if (result.value) {
                    axios.get(APP_URL + '/remove-cart-item/' + id).then((response) => {
                        response = response.data;
                        if (response.status == "success") {
                            that.$swal({
                                type: "success",
                                title: "Success",
                                text: "Item Removed Successfully",
                                showConfirmButton: true
                            }).then(function () {
                                window.location.reload();
                            });
                        }
                    });
                }
            })

        },
        getAddress: function () {
            console.log("Calling");
            let that = this;
            axios.get(APP_URL + '/get-address').then(response => {
                    that.addressData.first_name = response.data.user.first_name;
                    that.addressData.last_name = response.data.user.last_name;
                    that.addressData.email = response.data.user.email;
                    that.addressData.phone = response.data.user.phone;
                    that.addressData.address2 = response.data.res.address2;
                    that.addressData.address1 = response.data.res.address1;
                    $("#billing_state").val(response.data.res.state);
                    that.getCity();

                    setTimeout(function () {
                        $("#billing_city").val(response.data.res.city);
                    }, 2500);

                    that.addressData.pin_code = response.data.res.pin_code;

                }
            ).catch((error) => {

            });
        },
        updateAddress: function (type = null) {
            let that = this;
            that.addressData.city = $("#billing_city").val();
            that.addressData.state = $("#billing_state").val();

            if (that.addressData.first_name == "" || that.addressData.last_name == "" || that.addressData.address1 == "" || that.addressData.address2 == "" || that.addressData.city == "" || that.addressData.state == "" || that.addressData.pin_code == "") {
                this.$swal({
                    type: "error",
                    title: "Error",
                    text: "Please fill all required fields",
                    showConfirmButton: true
                });
            } else if (isNaN(that.addressData.pin_code)) {
                this.$swal({
                    type: "error",
                    title: "Error",
                    text: "Please Enter Valid Pin Code",
                    showConfirmButton: true
                });
            } else if (that.addressData.pin_code.length != 6) {
                this.$swal({
                    type: "error",
                    title: "Error",
                    text: "Please Enter 6 digit Pin Code",
                    showConfirmButton: true
                });
            } else {
                $(".loading").css("display",'block');
                axios.post(APP_URL + '/update-address', that.addressData).then(response => {
                        if (!type) {
                            //that.getOrders(that.cartId);
                            that.placeOrder();
                        } else {
                            $(".loading").css("display",'none');
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Address Updated Successfully",
                                showConfirmButton: true
                            }).then(function () {
                                that.getAddress();
                            });
                        }

                    }
                ).catch((error) => {
                    alert("error");
                });
            }

        },

    }
}
