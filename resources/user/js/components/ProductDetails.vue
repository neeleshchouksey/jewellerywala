<template>
    <div>
        <div class="sp-area">
            <div class="container">
                <div class="sp-nav">
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <div class="sp-img_area">

                                <div class="card-carousel">
                                    <div class="card-img row">
                                        <div class="zoomin frame">
                                            <img height="440" width="100%" class="drift-demo-trigger"
                                                 :src="APP_URL+'/public/storage/product-images/'+currentImagePath" alt="" :data-zoom="APP_URL+'/public/storage/product-images/'+currentImagePath">
                                        </div>
                                    </div>
                                    <div class="thumbnails row">
                                        <div class="new-ovwerfoo">

                                        <div class="new-sec"
                                             v-for="(image, index) in  images"
                                             :key="image.id"
                                             :class="['thumbnail-image', (activeImage == index) ? 'active' : '']"
                                             @click="activateImage(index)"
                                        >
                                            <img height="50" width="50"
                                                 :src="APP_URL+'/public/storage/product-images/'+image.image">
                                        </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7">
                            <div class="detail sp-content">
                                <div class="sp-heading">
                                    <h5><a href="#">{{productDetails.product_name}}</a></h5>
                                </div>
                                <span class="reference">{{productDetails.short_description}}</span>
                                <star-rating :star-size=size :rating="productDetails.ratings"
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
                                    <select class="form-control" v-on:change="getSingleProduct('')"
                                            id="product-variant-id">
                                        <option v-for="pv in productDetails.variant" :value="pv.id"
                                                :selected="productDetails.product_variant_id==pv.id">{{pv.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <span>Size: {{productDetails.size}}</span>
                                    </div>
                                    <div class="col-md-12">
                                        <span>Color: {{productDetails.color_id}}</span>
                                    </div>
                                    <div class="col-md-12">
                                        <span>Weight(in gm): {{productDetails.weight}}</span>
                                    </div>
                                    <div class="col-md-12">
                                        <span>Price: <i class="fa fa-rupee-sign"></i> {{productDetails.selling_price}}</span>
                                    </div>

                                </div>
                                <div class="quantity">
                                    <label>Quantity</label>
                                    <div class="cart-plus-minus">
                                        <input class="cart-plus-minus-box" value="1" type="text" disabled
                                               v-model="addToCartData.qty">
                                        <div class="dec qtybutton" v-on:click="decrement"><i
                                                class="fa fa-angle-down"></i></div>
                                        <div class="inc qtybutton" v-on:click="increment"><i class="fa fa-angle-up"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="qty-btn_area">
                                    <ul>
                                        <li><a class="qty-cart_btn" v-on:click="addToCart('')">Add To Cart</a></li>
                                        <li v-if="AUTH_USER && productDetails.added_to_wishlist==0"><a class="qty-wishlist_btn" href="javascript:void(0)" v-on:click="addToWishlist('')"
                                               title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                        </li>
                                        <li v-if="AUTH_USER && productDetails.added_to_wishlist==1"><a class="qty-wishlist_btn" href="javascript:void(0)"
                                                                                                     title="Added To Wishlist"><i class="ion-android-favorite"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <!--                            <div class="hiraola-tag-line">-->
                                <!--                                <h6>Tags:</h6>-->
                                <!--                                <a href="javascript:void(0)">Ring</a>,-->
                                <!--                                <a href="javascript:void(0)">Necklaces</a>,-->
                                <!--                                <a href="javascript:void(0)">Braid</a>-->
                                <!--                            </div>-->
                                <!--                            <div class="hiraola-social_link">-->
                                <!--                                <ul>-->
                                <!--                                    <li class="facebook">-->
                                <!--                                        <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank"-->
                                <!--                                           title="Facebook">-->
                                <!--                                            <i class="fab fa-facebook"></i>-->
                                <!--                                        </a>-->
                                <!--                                    </li>-->
                                <!--                                    <li class="twitter">-->
                                <!--                                        <a href="https://twitter.com/" data-toggle="tooltip" target="_blank"-->
                                <!--                                           title="Twitter">-->
                                <!--                                            <i class="fab fa-twitter-square"></i>-->
                                <!--                                        </a>-->
                                <!--                                    </li>-->
                                <!--                                    <li class="youtube">-->
                                <!--                                        <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank"-->
                                <!--                                           title="Youtube">-->
                                <!--                                            <i class="fab fa-youtube"></i>-->
                                <!--                                        </a>-->
                                <!--                                    </li>-->
                                <!--                                    <li class="google-plus">-->
                                <!--                                        <a href="https://www.plus.google.com/discover" data-toggle="tooltip"-->
                                <!--                                           target="_blank" title="Google Plus">-->
                                <!--                                            <i class="fab fa-google-plus"></i>-->
                                <!--                                        </a>-->
                                <!--                                    </li>-->
                                <!--                                    <li class="instagram">-->
                                <!--                                        <a href="https://rss.com/" data-toggle="tooltip" target="_blank"-->
                                <!--                                           title="Instagram">-->
                                <!--                                            <i class="fab fa-instagram"></i>-->
                                <!--                                        </a>-->
                                <!--                                    </li>-->
                                <!--                                </ul>-->
                                <!--                            </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Begin Hiraola's Single Product Tab Area -->
        <div class="hiraola-product-tab_area-2 sp-product-tab_area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sp-product-tab_nav ">
                            <div class="product-tab">
                                <ul class="nav product-menu">
                                    <li><a class="active" data-toggle="tab" href="#description"><span>Description</span></a>
                                    </li>
                                    <li><a data-toggle="tab" href="#reviews" v-on:click="getProductReviews"><span>Reviews ({{review_count}})</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content hiraola-tab_content">
                                <div id="description" class="tab-pane active show" role="tabpanel">
                                    <div class="product-description">
                                        {{productDetails.long_description}}
                                    </div>
                                </div>
                                <div id="reviews" class="tab-pane" role="tabpanel">
                                    <div class="tab-pane active" id="tab-review">
                                        <form class="form-horizontal" id="form-review">
                                            <div id="review" v-if="reviewData.length" v-for="rd in reviewData">
                                                <table class="table table-striped table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 50%;"><strong>{{rd.first_name}}
                                                            {{rd.last_name}}</strong></td>
                                                        <td class="text-right">{{rd.created_at | formatDate}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <p>{{rd.reviews}}</p>
                                                            <star-rating :star-size=size :rating="rd.ratings"
                                                                         v-bind:increment="0.1" read-only></star-rating>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-if="!reviewData.length">No Reviews Found</div>
                                            <div id="write-review" v-if="AUTH_USER && !review_given">
                                                <h2>Write a review</h2>
                                                <div class="form-group required second-child">
                                                    <div class="col-sm-12 p-0">
                                                        <label class="control-label">Share your opinion</label>
                                                        <textarea class="review-textarea" name="con_message"
                                                                  v-model="ratingData.reviews"
                                                                  id="con_message"></textarea>
                                                        <div class="help-block"><span class="text-danger">Note:</span>
                                                            HTML is
                                                            not
                                                            translated!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group last-child required">
                                                    <div class="col-sm-12 p-0">
                                                        <div class="your-opinion">
                                                            <label>Your Rating</label>
                                                            <star-rating :star-size=size
                                                                       v-model="ratingData.ratings"></star-rating>
                                                        </div>
                                                    </div>
                                                    <div class="hiraola-btn-ps_right">
                                                        <a href="javascript:void(0)"
                                                           class="hiraola-btn hiraola-btn_dark"
                                                           v-on:click="submitRating">Submit</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Begin Hiraola's Product Area Two -->
        <div class="hiraola-product_area hiraola-product_area-2 section-space_add">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="hiraola-section_title">
                            <h4>Related Products</h4>
                        </div>

                    </div>
                    <div class="col-lg-12" v-if="relatedProducts.length">
                        <div class="hiraola-product_slider-3 hiraola-product_slider-3-aj row">

                            <!-- Begin Hiraola's Slide Item Area -->
                            <div class="slide-item aj-slide-item"  v-for="rp in relatedProducts">
                                <div class="single_product">
                                    <div class="product-img">
                                        <a  :href="APP_URL+'/product-details/'+rp.product_variant_id">
                                            <img class="primary-img new-image-re"
                                                 :src="APP_URL+'/public/storage/product-images/'+rp.front_image"
                                                 alt="Hiraola's Product Image">
                                            <img class="secondary-img new-image-re"
                                                 :src="APP_URL+'/public/storage/product-images/'+rp.back_image"
                                                 alt="Hiraola's Product Image">
                                        </a>
                                        <span class="sticker-2">Sale</span>
                                        <div class="add-actions aj-slide-itema11">
                                            <ul>
                                                <li><a class="hiraola-add_cart" href="javascript:void(0)" v-on:click="addToCart(rp)" data-toggle="tooltip"
                                                       data-placement="top" title="Add To Cart"><i class="ion-bag"></i></a>
                                                </li>
                                                <li class="hiraola-add_compare"><a href="javascript:void(0)"
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
                                        <div class="product-desc_info aj-slide-item12">
                                            <h6><a class="product-name"
                                                   :href="APP_URL+'/product-details/'+rp.product_variant_id">{{rp.product_name}}</a>
                                            </h6>
                                            <div class="price-box">
                                                <span class="new-price"><i class="fa fa-rupee-sign"></i> {{rp.selling_price}}</span>
                                            </div>
                                            <star-rating :star-size=size :rating="rp.ratings"
                                                         read-only v-bind:increment="0.1"></star-rating>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Hiraola's Slide Item Area End Here -->
                            <!-- Begin Hiraola's Slide Item Area -->

                        </div>
                    </div>
                    <div class="col-lg-12" v-if="!relatedProducts.length">
                        No Data Found
                    </div>
                </div>
            </div>
        </div>
        <!-- Hiraola's Product Area Two End Here -->

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
<style>
    .wrapper {
        margin: 0 auto;
        width: 860px;
    }
    .aj-slide-item12 {
        padding-top: 15px;
    }
    .new-image-re {
        width: 250px !important;
        height: 250px !important;
    }
    .thumbnails {
        margin-top: 10px;
    }
    .drift-demo-trigger {
        float: left;
    }
    .detail {
        height: 435px;
    }
    .aj-slide-itema11 ul li a i {
        position: relative;
        top: -7px;
    }
    .detail {
        position: relative;
        width: 55%;
        margin-left: 5%;
        float: left;
    }

    h1 {
        color: #013C4A;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    p {
        max-width: 32em;
        margin-bottom: 1em;
        color: #23637f;
        line-height: 1.6em;
    }

    p:last-of-type {
        margin-bottom: 2em;
    }

    .ix-link {
        display: block;
        margin-bottom: 3em;
    }
    .zoomin.frame img {
        padding: 10px;
        border: 1px solid #ccc;
        width:440px;
        height: 440px;
    }

    @media (max-width: 900px) {
        .new-image-re {
            width: 100% !important;
            height: 250px !important;
        }
        .detail,
        .drift-demo-trigger {
            float: none;
        }

        .drift-demo-trigger {
            max-width: 100%;
            width: auto;
            margin: 0 auto;
        }

        .detail {
            margin: 0;
            width: auto;
        }

        p {
            margin: 0 auto 1em;
        }

        .responsive-hint {
            display: none;
        }

        .drift-bounding-box {
            display: none;
        }

    }
</style>

<script>
    import StarRating from 'vue-star-rating'
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'ProductDetails',
        data() {
            return {
                ratingData: {
                    ratings: 0,
                    reviews: ""
                },
                reviewData: [],
                review_count: 0,
                review_given: 0,
                productId: "",
                size: 25,
                images: [],
                activeImage: 0,
                currentImagePath: "",
            }
        },
        mixins: [UserMixin],
        created() {
            let that = this;
            var productId = document.URL.split('/')[document.URL.split('/').length - 1];
            that.productId = productId;
            that.getSingleProduct(productId);
            that.getRelatedProduct(productId);
            that.getProductReviews();


            setTimeout(function () {
                new Drift(document.querySelector('.drift-demo-trigger'), {
                    paneContainer: document.querySelector('.detail'),
                    inlinePane: 900,
                    inlineOffsetX: -85,
                    inlineOffsetY: 0,
                    containInline: true,
                    hoverBoundingBox: true
                });

            },1000);
        },
        components: {'star-rating': StarRating},
        methods: {

            getRelatedProduct: function (id) {
                if (!id) {
                    id = $("#product-variant-id").val();
                }
                axios.get(APP_URL + '/get-related-product/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.relatedProducts = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            increment: function () {
                let that = this;
                that.addToCartData.qty = that.addToCartData.qty + 1;
            },
            decrement: function () {
                let that = this;
                if (that.addToCartData.qty > 1) {
                    that.addToCartData.qty = that.addToCartData.qty - 1;
                }
            },
            submitRating: function () {
                let that = this;
                if (that.ratingData.reviews == "") {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please enter Reviews",
                        showConfirmButton: true
                    });
                }
                else if (that.ratingData.ratings == 0) {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please enter Ratings",
                        showConfirmButton: true
                    });
                } else {
                    that.ratingData.product_id = that.productDetails.id;
                    axios.post(APP_URL + '/submit-ratings', that.ratingData).then(response => {

                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Ratings given Successfully",
                                showConfirmButton: true
                            }).then(function () {
                                that.getProductReviews();
                                that.getSingleProduct(that.productId);
                            });
                        }
                    ).catch((error) => {

                    });
                }
            },
            getProductReviews: function () {
                let that = this;
                axios.get(APP_URL + '/get-product-reviews/' + that.productId).then(response => {
                        that.reviewData = response.data.res;
                        that.review_count = response.data.review_count;
                        that.review_given = response.data.review_given;
                    }
                ).catch((error) => {

                });
            },
            getProductImages: function (id) {
                let that = this;
                axios.get(APP_URL + '/get-product-images/' +id).then(response => {
                        that.images = response.data.res;
                        that.currentImage();
                    }
                ).catch((error) => {

                });
            },
            currentImage() {
                let that = this;
                that.currentImagePath = that.images[that.activeImage].image;
                console.log(that.currentImagePath);
            },

            activateImage(imageIndex) {
                let that = this;
                that.activeImage = imageIndex;
                that.currentImage();
            },
        }
    }
</script>
