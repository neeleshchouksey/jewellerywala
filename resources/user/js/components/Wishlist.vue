<template>
    <div class="hiraola-cart-area">

        <div class="container">

            <div class="row">

                <div class="col-12" v-if="wishlistData.length">

                    <form action="javascript:void(0)">

                        <div class="table-content table-responsive">

                            <table class="table" >

                                <thead>
                                <tr>
                                    <th class="hiraola-product-remove">remove</th>

                                    <th class="hiraola-product-thumbnail">image</th>

                                    <th class="cart-product-name">Product</th>

                                    <th class="hiraola-product-price">Unit Price(In <i class="fa fa-rupee-sign"></i>)</th>

<!--                                    <th class="hiraola-product-quantity">Action</th>-->

<!--                                    <th class="hiraola-product-subtotal">Total(In <i class="fa fa-rupee-sign"></i>)</th>-->


                                </tr>

                                </thead>

                                <tbody>
                                <tr v-for="(cd,index) in wishlistData">

                                    <td class="hiraola-product-remove"><a href="javascript:void(0)" v-on:click="removeWishlistItem(cd.id)"><i
                                            class="fa fa-trash" title="Remove"></i></a></td>
                                    <td class="hiraola-product-thumbnail"><a href="javascript:void(0)"><img
                                           height="50px" width="50px" :src="APP_URL+'/public/storage/product-images/'+cd.product_image"></a></td>

                                    <td class="hiraola-product-name"><a href="javascript:void(0)">{{cd.product_name}}</a>
                                    </td>

                                    <td class="hiraola-product-price"><span class="amount">{{cd.unit_price}}</span></td>
<!--                                    <td class="hiraola-product-remove"><a href="javascript:void(0)" v-on:click="addToCart(cd)"><i-->
<!--                                            class="fas fa-shopping-cart" title="Add To Cart"></i></a></td>-->

<!--                                    <td class="quantity">-->

<!--                                        <label>Quantity</label>-->

<!--                                        <div class="cart-plus-minus">-->

<!--                                            <input class="cart-plus-minus-box" :id="'qty'+index" :value="cd.quantity" type="text" v-on:blur="calculatePrice(index,'',cd.id)">-->

<!--                                            <div class="dec qtybutton" v-on:click="decrement(index,cd.unit_price,cd.id)"><i class="fa fa-angle-down" ></i></div>-->

<!--                                            <div class="inc qtybutton" v-on:click="increment(index,cd.unit_price,cd.id)"><i class="fa fa-angle-up"></i></div>-->

<!--                                        </div>-->

<!--                                    </td>-->

<!--                                    <td class="product-subtotal"><span class="amount">{{cd.quantity*cd.unit_price}}</span></td>-->

                                </tr>

                                </tbody>

                            </table>

                        </div>


                        <div class="row" v-if="AUTH_USER">

                            <div class="col-md-5 ml-auto text-right">

                                <div class="cart-page-total">

<!--                                    <a href="javascript:void(0)" v-on:click="moveItemsToCart('')">Move All Item(s) to Cart</a>-->

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

                <div class="col-12" v-if="!wishlistData.length">
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
            that.getWishlist();

        },
        methods: {
            // increment:function(i,price,cart_id){
            //     let that =this;
            //     var val = $("#qty"+i).val();
            //     $("#qty"+i).val(parseInt(val)+1);
            //     var qty = parseInt(val)+1;
            //     that.calculatePrice(i,qty,cart_id)
            // },
            // decrement:function(i,price,cart_id){
            //     let that =this;
            //     var val = $("#qty"+i).val();
            //     if(val>1){
            //         $("#qty"+i).val(parseInt(val)-1);
            //         var qty = parseInt(val)-1;
            //         that.calculatePrice(i,qty,cart_id)
            //     }
            // },
            // calculatePrice: function (i,qty, cart_id) {
            //     let that = this;
            //     if(qty==""){
            //         var qty = $("#qty"+i).val();
            //     }
            //     axios.post(APP_URL + '/update-qty-to-wishlist/' + cart_id, {"qty": qty}).then(response => {
            //             that.getWishlist();
            //         }
            //     ).catch((error) => {
            //
            //     });
            // },
            // moveItemsToCart: function (id=null) {
            //     let that = this;
            //     that.$swal({
            //         title: "Are you sure?",
            //         text: "You want to move this item to cart",
            //         type: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#3085d6",
            //         confirmButtonText: "Yes",
            //     }).then(function (result) {
            //         if (result.value) {
            //             var url="";
            //             if(id){
            //                 //url = APP_URL + '/move-items-to-cart/'+id;
            //             }else{
            //                 url = APP_URL + '/move-all-items-to-cart';
            //             }
            //             axios.get(url).then(response => {
            //                     window.location = APP_URL + "/my-cart";
            //                 }
            //             ).catch((error) => {
            //
            //             });
            //         }
            //     })
            //
            // },
        }
    }
</script>
