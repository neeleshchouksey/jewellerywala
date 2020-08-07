<template>
    <div class="hiraola-cart-area">

        <div class="container">

            <div class="row">

                <div class="col-12" v-if="cartData.length">

                    <form action="javascript:void(0)">

                        <div class="table-content table-responsive">

                            <table class="table" >

                                <thead>

                                <tr>

                                    <th class="hiraola-product-remove">remove</th>

                                    <th class="hiraola-product-thumbnail">image</th>

                                    <th class="cart-product-name">Product</th>

                                    <th class="hiraola-product-price">Unit Price(In <i class="fa fa-rupee-sign"></i>)</th>

                                    <th class="hiraola-product-quantity">Quantity</th>

                                    <th class="hiraola-product-subtotal">Total(In <i class="fa fa-rupee-sign"></i>)</th>

                                </tr>

                                </thead>

                                <tbody>
                                <tr v-for="(cd,index) in cartData">

                                    <td class="hiraola-product-remove"><a href="javascript:void(0)" v-on:click="removeCartItem(cd.id)"><i
                                            class="fa fa-trash" title="Remove"></i></a></td>
                                    <td class="hiraola-product-thumbnail"><a href="javascript:void(0)"><img
                                           height="50px" width="50px" :src="APP_URL+'/public/storage/product-images/'+cd.product_image"></a></td>

                                    <td class="hiraola-product-name"><a href="javascript:void(0)">{{cd.product_name}}</a>
                                    </td>

                                    <td class="hiraola-product-price"><span class="amount"><i class="fa fa-rupee-sign"></i> {{cd.unit_price}}</span></td>

                                    <td class="quantity">

                                        <label>Quantity</label>

                                        <div class="cart-plus-minus">

                                            <input class="cart-plus-minus-box" :id="'qty'+index" :value="cd.quantity" type="text" v-on:blur="calculatePrice(index,'',cd.id)">

                                            <div class="dec qtybutton" v-on:click="decrement(index,cd.unit_price,cd.id)"><i class="fa fa-angle-down" ></i></div>

                                            <div class="inc qtybutton" v-on:click="increment(index,cd.unit_price,cd.id)"><i class="fa fa-angle-up"></i></div>

                                        </div>

                                    </td>

                                    <td class="product-subtotal"><span class="amount"><i class="fa fa-rupee-sign"></i> {{cd.quantity*cd.unit_price}}</span></td>

                                </tr>

                                </tbody>

                            </table>

                        </div>


                        <div class="row">

                            <div class="col-md-5 ml-auto">

                                <div class="cart-page-total">

                                    <h2>Cart totals</h2>

                                    <ul>

                                        <li>Subtotal <span><i class="fa fa-rupee-sign"></i> {{totalAmount}}</span></li>

                                        <li>Total <span><i class="fa fa-rupee-sign"></i> {{totalAmount}}</span></li>

                                    </ul>

                                    <a href="javascript:void(0)" v-on:click="proceedToCheckout(cartId)">Proceed to checkout</a>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

                <div class="col-12" v-if="!cartData.length">
                    <h3 class="text-center">No Data Found</h3>
                </div>
            </div>

        </div>

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
            that.getCart();

        },
        methods: {
            increment:function(i,price,cart_id){
                let that =this;
                var val = $("#qty"+i).val();
                $("#qty"+i).val(parseInt(val)+1);
                var qty = parseInt(val)+1;
                that.calculatePrice(i,qty,cart_id)
            },
            decrement:function(i,price,cart_id){
                let that =this;
                var val = $("#qty"+i).val();
                if(val>1){
                    $("#qty"+i).val(parseInt(val)-1);
                    var qty = parseInt(val)-1;
                    that.calculatePrice(i,qty,cart_id)
                }
            },
            calculatePrice: function (i,qty, cart_id) {
                let that = this;
                if(qty==""){
                    var qty = $("#qty"+i).val();
                }
                axios.post(APP_URL + '/update-qty-to-cart/' + cart_id, {"qty": qty}).then(response => {
                        that.getCart();
                    }
                ).catch((error) => {

                });
            },
            proceedToCheckout:function(id){
                let that = this;
                    if(AUTH_USER) {
                        $(".loading").css("display", 'block');
                        axios.get(APP_URL + '/checkout-process/'+id).then(response => {
                                $(".loading").css("display", 'none');
                                window.location = APP_URL + "/checkout/" + response.data.cart_id
                            }
                        ).catch((error) => {

                        });
                     }else{
                    var cur_url = document.URL;
                    console.log(cur_url);
                    cur_url = encodeURIComponent(cur_url);
                    const el = document.createElement('div')
                    el.innerHTML = "Please <a style='color: #9f8447' href='" + APP_URL + "/login-register?url=" + cur_url + "'>Login</a> or <a style='color: #9f8447' href='" + APP_URL + "/login-register?url=" + cur_url + "'>Register</a> to Continue"
                    this.$swal({
                        title: "Warning!",
                        html: el,
                        icon: "warning",
                    }).then(function () {
                        window.location = APP_URL + "/login-register?url=" + cur_url;
                    });
                }
            },
        }
    }
</script>
