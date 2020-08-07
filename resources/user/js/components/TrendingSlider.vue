<template>
    <div class="hiraola-product_area new-ahiraola-product_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hiraola-section_title">
                        <h4>Trending Products</h4>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="hiraola-product-tab_slider-2">
                        <!-- Begin Hiraola's Slide Item Area -->

                        <div class="slide-item" v-for="rp in trendingSliderData">
                            <div class="single_product">
                                <div class="product-img">
                                    <a  :href="APP_URL+'/product-details/'+rp.product_variant_id">
                                        <img height="252" width="252" class="primary-img"
                                             :src="APP_URL+'/public/storage/product-images/'+rp.front_image"
                                             alt="Hiraola's Product Image">
                                        <img height="195" width="195" class="secondary-img"
                                             :src="APP_URL+'/public/storage/product-images/'+rp.back_image"
                                             alt="Hiraola's Product Image">
                                    </a>
                                    <span class="sticker-2">Sale</span>
                                    <div class="add-actions">
                                        <ul>
                                            <li><a class="hiraola-add_cart" href="javascript:void(0)" v-on:click="addToCart(rp)" data-toggle="tooltip"
                                                   data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                            </li>
                                            <li class="quick-view-btn"><a href="javascript:void(0)"
                                                                          data-toggle="tooltip"
                                                                          data-placement="top"
                                                                          title="Add To Wishlist"
                                                                          v-on:click="addToWishlist(rp)"><i
                                                    class="ion-android-favorite-outline"></i></a></li>
                                            <li class="quick-view-btn"><a href="javascript:void(0)"
                                                                          data-toggle="tooltip"
                                                                          data-placement="top"
                                                                          title="Quick View"
                                                                          v-on:click="getSingleProductItem(rp.product_variant_id,'quick')"><i
                                                    class="ion-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="hiraola-product_content">
                                    <div class="product-desc_info">
                                        <h6><a class="product-name"
                                               :href="APP_URL+'/product-details/'+rp.product_variant_id">{{rp.product_name}}</a>
                                        </h6>
                                        <div class="price-box">
                                            <span class="new-price">₹ {{rp.selling_price}}</span>
                                        </div>
                                        <star-rating :star-size=size :rating="rp.ratings"
                                                     read-only v-bind:increment="0.1"></star-rating>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Hiraola's Slide Item Area End Here -->
                    </div>
                </div>
            </div>
        </div>

        <b-modal id="product-quick-view" size="lg" ref="product-quick-view" title="Product Quick View" hide-footer>
            <div class="sp-area pt-0">
                <div class="container">
                    <div class="sp-nav">
                        <div class="row">
                            <div class="col-lg-5 col-md-5">
                                <div class="sp-img_area">
                                    <div class="zoompro-border" v-if="productDetails.variant_images.length">
                                        <img height="413px" width="413px" class="zoompro" :src="APP_URL+'/public/storage/product-images/'+productDetails.variant_images[0].image"
                                             :data-zoom-image="APP_URL+'/public/storage/product-images/'+productDetails.variant_images[0].image"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7">
                                <div class="sp-content">
                                    <div class="sp-heading">
                                        <h5><a href="#">{{productDetails.product_name}}</a></h5>
                                    </div>
                                    <span class="reference">{{productDetails.short_description}}</span>
                                    <star-rating :star-size=size :rating=productDetails.ratings
                                                 read-only v-bind:increment="0.1"></star-rating>
                                    <div class="sp-essential_stuff">
                                        <ul>
                                            <!--                                    <li>EX Tax: <a href="javascript:void(0)"><span>£453.35</span></a></li>-->
                                            <!--                                    <li>Brands <a href="javascript:void(0)">Buxton</a></li>-->
                                            <li>Product Id: {{productDetails.product_id_manual}}</li>
                                            <!--                                    <li>Reward Points: <a href="javascript:void(0)">600</a></li>-->
                                            <li>Availability: <a href="javascript:void(0)">In Stock</a></li>
                                        </ul>
                                    </div>
                                    <div class="product-size_box">
                                        <span>Variants</span>
                                        <select class="form-control" v-on:change="getSingleProduct('','')" id="product-variant-id">
                                            <option v-for="pv in productDetails.variant" :value="pv.id" :selected="productDetails.product_variant_id==pv.id">{{pv.name}}</option>
                                        </select>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-3">
                                            <span>Size: {{productDetails.size}}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>Color: {{productDetails.color_id}}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>Weight(in gm): {{productDetails.weight}}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>Price: {{productDetails.selling_price}}<i class="fa fa-rupee-sign"></i></span>
                                        </div>

                                    </div>
                                    <div class="quantity">
                                        <label>Quantity</label>
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" value="1" type="text" disabled v-model="addToCartData.qty">
                                            <div class="dec qtybutton" v-on:click="decrement"><i class="fa fa-angle-down"></i></div>
                                            <div class="inc qtybutton"  v-on:click="increment"><i class="fa fa-angle-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="qty-btn_area">
                                        <ul>
                                            <li><a class="qty-cart_btn" v-on:click="addToCart('')">Add To Cart</a></li>
                                            <li><a class="qty-wishlist_btn" href="javascript:void(0)" v-on:click="addToWishlist('')"
                                                   title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--                                    <div class="hiraola-tag-line">-->
                                    <!--                                        <h6>Tags:</h6>-->
                                    <!--                                        <a href="javascript:void(0)">Ring</a>,-->
                                    <!--                                        <a href="javascript:void(0)">Necklaces</a>,-->
                                    <!--                                        <a href="javascript:void(0)">Braid</a>-->
                                    <!--                                    </div>-->
                                    <!--                                    <div class="hiraola-social_link">-->
                                    <!--                                        <ul>-->
                                    <!--                                            <li class="facebook">-->
                                    <!--                                                <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank"-->
                                    <!--                                                   title="Facebook">-->
                                    <!--                                                    <i class="fab fa-facebook"></i>-->
                                    <!--                                                </a>-->
                                    <!--                                            </li>-->
                                    <!--                                            <li class="twitter">-->
                                    <!--                                                <a href="https://twitter.com/" data-toggle="tooltip" target="_blank"-->
                                    <!--                                                   title="Twitter">-->
                                    <!--                                                    <i class="fab fa-twitter-square"></i>-->
                                    <!--                                                </a>-->
                                    <!--                                            </li>-->
                                    <!--                                            <li class="youtube">-->
                                    <!--                                                <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank"-->
                                    <!--                                                   title="Youtube">-->
                                    <!--                                                    <i class="fab fa-youtube"></i>-->
                                    <!--                                                </a>-->
                                    <!--                                            </li>-->
                                    <!--                                            <li class="google-plus">-->
                                    <!--                                                <a href="https://www.plus.google.com/discover" data-toggle="tooltip"-->
                                    <!--                                                   target="_blank" title="Google Plus">-->
                                    <!--                                                    <i class="fab fa-google-plus"></i>-->
                                    <!--                                                </a>-->
                                    <!--                                            </li>-->
                                    <!--                                            <li class="instagram">-->
                                    <!--                                                <a href="https://rss.com/" data-toggle="tooltip" target="_blank"-->
                                    <!--                                                   title="Instagram">-->
                                    <!--                                                    <i class="fab fa-instagram"></i>-->
                                    <!--                                                </a>-->
                                    <!--                                            </li>-->
                                    <!--                                        </ul>-->
                                    <!--                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>


    </div>

</template>


<script>
    import UserMixin from "../mixins/UserMixin";
    import StarRating from 'vue-star-rating'
    export default {
        name: 'Cart',
        data() {
            return {
                trendingSliderData: []
            }
        },
        components: {'star-rating': StarRating},
        mixins: [UserMixin],
        created() {
            let that = this;
            that.getSliders(3);
            setTimeout(function(){

                $('.hiraola-product-tab_slider-2').slick({
                    infinite: true,
                    arrows: true,
                    dots: false,
                    speed: 2000,
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    prevArrow: '<button class="slick-prev"><i class="ion-ios-arrow-back"></i></button>',
                    nextArrow: '<button class="slick-next"><i class="ion-ios-arrow-forward"></i></button>',
                    responsive: [{
                        breakpoint: 1501,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1
                            }
                        },
                        {
                            breakpoint: 575,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });


            },2000);
        },
        methods: {
            getSliders: function (type) {
                let that = this;
                var url = APP_URL + '/get-sliders/'+type;
                axios.get(url).then(response => {
                    response = response.data;
                    that.trendingSliderData = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
        }
    }
</script>
