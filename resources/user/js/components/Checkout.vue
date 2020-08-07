<template>

    <!-- Begin Hiraola's Checkout Area -->

    <div class="checkout-area">

        <div class="container">
            <h5 class="mb-3" style="color:red">Please Don't go back, untill transaction is completed</h5>

            <div class="row">

                <div class="col-lg-6 col-12">

                    <form action="javascript:void(0)">

                        <div class="checkbox-form">

                            <h3>Billing Details</h3>

                            <div class="row">


                                <div class="col-md-6">

                                    <div class="checkout-form-list">

                                        <label>First Name <span class="required">*</span></label>

                                        <input placeholder="First Name" type="text" v-model="addressData.first_name">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="checkout-form-list">

                                        <label>Last Name <span class="required">*</span></label>

                                        <input placeholder="Last Name" type="text" v-model="addressData.last_name">

                                    </div>

                                </div>
                                <div class="col-md-6">

                                    <div class="checkout-form-list">

                                        <label>Email Address <span class="required">*</span></label>

                                        <input placeholder="Email" type="email" v-model="addressData.email">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="checkout-form-list">

                                        <label>Phone <span class="required">*</span></label>

                                        <input type="text" v-model="addressData.phone" placeholder="Phone">

                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="checkout-form-list">

                                        <label>Address <span class="required">*</span></label>

                                        <input placeholder="Street address" type="text" v-model="addressData.address1">

                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="checkout-form-list">

                                        <input placeholder="Apartment, suite, unit etc. (optional)" type="text"
                                               v-model="addressData.address2">

                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="checkout-form-list">

                                        <label>State / County <span class="required">*</span></label>
                                        <select name="billing_state" id="billing_state"
                                                class="form-control unicase-form-control state_select select2-hidden-accessible"
                                                autocomplete="address-level1"
                                                data-placeholder="Select an option…" tabindex="-1" aria-hidden="true"
                                                v-on:change="getCity('')">
                                            <option value="">Select an option…</option>
                                            <option v-for="sd in stateData" :value="sd.id" selected>{{sd.name}}</option>
                                        </select>


                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="checkout-form-list">

                                        <label>Town / City <span class="required">*</span></label>

                                        <select name="billing_city" id="billing_city"
                                                class="form-control unicase-form-control state_select select2-hidden-accessible"
                                                autocomplete="address-level1"
                                                data-placeholder="Select an option…" tabindex="-1" aria-hidden="true">
                                            <option value="">Select an option…</option>
                                            <option v-for="cd in cityData" :value="cd.id">{{cd.name}}</option>
                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-12">

                                    <div class="checkout-form-list">

                                        <label>Postcode / Zip <span class="required">*</span></label>

                                        <input placeholder="" type="text" v-model="addressData.pin_code"
                                               maxlength="6" @input="addressData.pin_code = addressData.pin_code.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>


                <div class="col-lg-6 col-12">

                    <div class="your-order">
                        <h5 class="mb-3">If you have a coupon code, please apply it below.</h5>
                        <form class="" method="post">
                            <p class="form-row form-row-first">
                                <input type="text" name="coupon_code"
                                       class="input-text form-control unicase-form-control text-input"
                                       placeholder="Coupon code" id="coupon_code" value=""
                                       v-model="addressData.coupon_code">
                            </p>
                            <p class="form-row form-row-last order-button-payment">
                                <input type="button"
                                       name="apply_coupon" value="Apply coupon" :disabled="addressData.disabled"
                                       v-on:click="applyCouponCode">
                            </p>
                        </form>
                    </div>

                    <div class="your-order">

                        <h3>Your order</h3>

                        <div class="your-order-table table-responsive">

                            <table class="table">

                                <thead>

                                <tr>

                                    <th class="cart-product-name">Product</th>

                                    <th class="cart-product-total">Total</th>

                                </tr>

                                </thead>

                                <tbody>

                                <tr v-for="cd in cartData" class="cart_item">

                                    <td class="cart-product-name"> {{cd.product_name}}<strong class="product-quantity">

                                        × {{cd.quantity}}</strong></td>

                                    <td class="cart-product-total"><span
                                            class="amount"><i class="fa fa-rupee-sign"></i> {{cd.quantity * cd.unit_price}} </span></td>

                                </tr>

                                </tbody>

                                <tfoot>

                                <tr class="cart-subtotal">

                                    <th>Cart Subtotal</th>

                                    <td><span class="amount"><i class="fa fa-rupee-sign"></i> {{totalAmount}} </span></td>

                                </tr>
                                <tr class="cart-subtotal">

                                    <th>Shipping Charges</th>

                                    <td><span class="amount"><i class="fa fa-rupee-sign"></i> {{shippingCharges}} </span></td>

                                </tr>

                                <tr class="cart-subtotal">

                                    <th>Coupon Discount</th>

                                    <td><span class="amount"><i class="fa fa-rupee-sign"></i> {{coupon_discount}} </span></td>

                                </tr>
                                <tr>
                                    <th><input type="checkbox" class="form-check-input" id="use-super-coin"
                                               name="use_super_coin" v-on:click="applySuperCoin">
                                        <label class="form-check-label" for="use-super-coin">Use Cashback Money</label></th>
                                    <td>{{addressData.used_super_coin_amount}} (Remaining - <i class="fa fa-rupee-sign"></i> {{total_super_coin_amount}}) </td>
                                </tr>
                                <tr>
                                    <th><input type="checkbox" class="form-check-input" id="use-wallet"
                                               name="use_wallet" v-on:click="applyWallet">
                                        <label class="form-check-label" for="use-wallet">Use Wallet
                                            Amount</label></th>
                                    <td> {{addressData.used_wallet_amount}} (Remaining - <i class="fa fa-rupee-sign"></i>  {{total_wallet_amount}}) </td>
                                </tr>

                                <tr class="order-total">

                                    <th>Order Total</th>

                                    <td><strong><span
                                            class="amount"><i class="fa fa-rupee-sign"></i> {{grandTotal}} </span></strong>
                                    </td>

                                </tr>

                                </tfoot>

                            </table>

                        </div>

                        <div class="payment-method">

                            <div class="payment-accordion">

                                <div class="order-button-payment">
                                    <input class="btn" value="Place order" type="button" v-on:click="updateAddress('')"
                                           :disabled="placeOrderData.disabled">
                                    <form id="pay-form" :action="APP_URL+'/payment-success'" method="POST">
                                        <input type="hidden" custom="Hidden Element" name="hidden">
                                    </form>
                                </div>
                                <!-- </a>-->

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Hiraola's Checkout Area End Here -->

</template>


<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Cart',
        data() {
            return {
                addressData: {
                    "first_name": "",
                    "last_name": "",
                    "email": "",
                    "phone": "",
                    "address1": "",
                    "address2": "",
                    "state": "",
                    "city": "",
                    "pin_code": "",
                    "coupon_code": "",
                    "super_coin_amount": 0,
                    "used_super_coin_amount":0,
                    "used_wallet_amount":0,
                    "wallet_amount":0,
                    "disabled": false,
                },
                total_super_coin_amount: 0,
                total_wallet_amount: 0,
                total_orders: 0,
                placeOrderData:{
                    "disabled":false
                }
            }
        },
        mixins: [UserMixin],
        created() {
            let that = this;
            that.cartId = document.URL.split("/")[document.URL.split("/").length - 1];
            that.getState();
            setTimeout(function () {
                that.getOrders();
                that.getAddress();
            }, 500);

        },
        methods: {
            applySuperCoin: function () {
                let that = this;
                if ($("input[name='use_super_coin']").is(":checked")) {
                    if(that.total_orders>=6) {

                        if (that.grandTotal == 0) {
                            this.$swal({
                                type: "error",
                                title: "Error",
                                text: "Grand Total amount is already 0",
                                showConfirmButton: true
                            }).then(function () {
                                $("input[name='use_super_coin']").prop("checked", false);
                            });
                        } else {
                            if (that.total_super_coin_amount > that.grandTotal) {
                                that.total_super_coin_amount = that.total_super_coin_amount - that.grandTotal;
                                that.addressData.super_coin_amount = that.grandTotal;
                                that.addressData.used_super_coin_amount = that.grandTotal;
                                that.grandTotal = 0;
                            } else if (that.total_super_coin_amount <= that.grandTotal) {
                                console.log(parseFloat(that.grandTotal) - parseFloat(that.total_super_coin_amount));
                                that.grandTotal = that.grandTotal - that.total_super_coin_amount;
                                that.addressData.used_super_coin_amount = that.total_super_coin_amount;
                                that.addressData.super_coin_amount = 0;
                                that.total_super_coin_amount = 0;
                            }
                        }
                    }else{
                        this.$swal({
                            type: "warning",
                            title: "Warning",
                            text: "You can redeem cashback money after 6 orders",
                            showConfirmButton: true
                        }).then(function(){
                            $("input[name='use_super_coin']").prop("checked",false);
                        });
                    }
                } else {
                    that.getOrders();
                }
            },

            applyWallet: function () {
                let that = this;
                if ($("input[name='use_wallet']").is(":checked")) {
                    if(that.grandTotal==0){
                        this.$swal({
                            type: "error",
                            title: "Error",
                            text: "Grand Total amount is already 0",
                            showConfirmButton: true
                        }).then(function(){
                            $("input[name='use_wallet']").prop("checked",false);
                        });
                    }else{
                        if (that.total_wallet_amount > that.grandTotal) {
                            that.total_wallet_amount = that.total_wallet_amount - that.grandTotal;
                            that.addressData.wallet_amount = that.grandTotal;
                            that.addressData.used_wallet_amount = that.grandTotal;
                            that.grandTotal = 0;
                        } else if (that.total_wallet_amount <= that.grandTotal) {
                            console.log(parseFloat(that.grandTotal) - parseFloat(that.total_wallet_amount));
                            that.grandTotal = that.grandTotal - that.total_wallet_amount;
                            that.addressData.used_wallet_amount = that.total_wallet_amount;
                            that.addressData.wallet_amount = 0;
                            that.total_wallet_amount = 0;
                        }
                    }

                } else {
                    that.getOrders();
                }
            },

            getOrders: function () {
                console.log("order calling");
                let that = this;
                axios.get(APP_URL + '/get-orders/' + that.cartId).then(response => {
                        that.cartData = response.data.res;
                        that.totalAmount = response.data.total_amount;
                        that.cart_size = response.data.res.length;
                        that.shippingCharges = response.data.shipping_charges;
                        that.total_super_coin_amount = response.data.super_coin_amount;
                        that.total_wallet_amount = response.data.wallet_amount;
                        that.total_orders = response.data.total_orders;
                        //that.orderId = response.data.order_id;
                        that.grandTotal = parseFloat(that.totalAmount) + parseFloat(that.shippingCharges) - parseFloat(that.coupon_discount);
                    }
                ).catch((error) => {

                });
            },
            applyCouponCode: function () {
                let that = this;
                if (that.addressData.coupon_code == "") {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please enter coupon code",
                        showConfirmButton: true
                    });
                } else {
                    that.addressData.disabled = true;
                    axios.post(APP_URL + '/apply-coupon-code', {"coupon_code": that.addressData.coupon_code,"cart_id":that.cartId}).then(response => {
                            that.addressData.disabled = true;
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Coupon Code Successfully Applied",
                                showConfirmButton: true
                            }).then(function () {
                                that.coupon_discount = response.data.res.discount;
                                that.grandTotal = that.grandTotal - parseFloat(that.coupon_discount);
                            });
                        }
                    ).catch((error) => {
                        that.addressData.disabled = false;
                        this.$swal({
                            type: "error",
                            title: "Error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
            placeOrder: function () {
                let that = this;
                that.addressData.cartId = that.cartId;
                that.addressData.grandTotal = that.grandTotal;
                that.addressData.shippingCharges = that.shippingCharges;
                that.addressData.totalAmount = that.totalAmount;
                that.addressData.coupon_discount = that.coupon_discount;
                that.placeOrderData.disabled = true;

                axios.post(APP_URL + '/place-order', that.addressData).then(response => {
                        //that.addressData.disabled = false;
                        that.orderId = response.data.orderId;
                     $(".loading").css("display",'none');
                        if (that.addressData.grandTotal) {
                            var options = {
                                "key": "rzp_test_49ge0fNwkPW8Zb", // Enter the Key ID generated from the Dashboard
                                "amount": that.grandTotal * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise or INR 500.
                                "currency": "INR",
                                "name": that.addressData.first_name,
                                "description": "",
                                "image": that.logo,
                                "order_id": that.order_id,//This is a sample Order ID. Create an Order using Orders API. (https://razorpay.com/docs/payment-gateway/orders/integration/#step-1-create-an-order). Refer the Checkout form table given below
                                "handler": function (response) {
                                    // alert(response);
                                    that.paymentStatus(response.razorpay_payment_id);
                                },
                                "prefill": {
                                    "name": that.addressData.first_name,
                                    "email": that.addressData.email,
                                    "contact": that.addressData.phone
                                },
                                "notes": {
                                    "address": "note value"
                                },
                                "theme": {
                                    "color": "#F37254"
                                }
                            };
                            var rzp1 = new Razorpay(options);
                            rzp1.open();
                        } else {
                            that.paymentStatus(1)
                        }
                    }
                ).catch((error) => {
                    that.placeOrderData.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });

                });
            },
            paymentStatus: function (payment_id) {
                let that = this;
                $(".loading").css("display",'block');
                axios.post(APP_URL + '/payment-success', {
                    "paymentId": payment_id,
                    "order_id": that.orderId
                }).then(response => {
                    $(".loading").css("display",'none');
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: "Payment Completed Successfully",
                            showConfirmButton: true
                        }).then(function () {
                            window.location = APP_URL + "/my-account";
                        });
                    }
                ).catch((error) => {

                });
            },
        },

    }
</script>
