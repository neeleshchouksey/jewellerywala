<template>

    <div class="hiraola-content_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 order-2 order-lg-1 sectione-2">
                    <div class="hiraola-sidebar-catagories_area">
                        <div class="hiraola-sidebar_categories">
                            <div class="hiraola-categories_title">
                                <h5>Category</h5>
                            </div>

                            <ul class="accordion new-ul">
                                <li class="new-li-aco" v-for="(cd,index) in subCategories">
                                    <a href="JavaScript:Void(0);" class="toggle">{{cd.category_name}}</a>
                                    <div class="inner">
                                        <div v-for="(cm,index) in cd.subcategory">
                                            <input type="checkbox" :id="cd.category_name+index" name="subcategory"
                                                   v-on:change="getAllProducts" :value="cm.id"
                                                   v-model="filterData.subCategory">
                                            <label :for="cd.category_name+index">{{cm.sub_category_name}}</label>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                        </div>

                        <div class="hiraola-sidebar_categories">
                            <div class="hiraola-categories_title">
                                <h5>Purity</h5>
                            </div>
                            <ul class="accordion new-ul">
                                <li class="new-li-aco" v-for="(pd,index) in purities">
                                    <a href="JavaScript:Void(0);" class="toggle">{{pd.category_name}}</a>
                                    <div class="inner">
                                        <div v-for="(pm,index) in pd.purity">
                                            <input type="checkbox" :id="pm.purity_name+index"
                                                   v-on:change="getAllProducts" name="purity" :value="pm.id"
                                                   v-model="filterData.purity">
                                            <label :for="pm.purity_name+index">{{pm.purity_name}}</label>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>

                        <div class="hiraola-sidebar_categories">
                            <div class="hiraola-categories_title">
                                <h5>Occasion</h5>
                            </div>
                            <ul class="sidebar-checkbox_list">
                                <li>
                                    <div v-for="(o,index) in occasion">
                                        <input type="checkbox" :id="o.occasion+index" name="occasion"
                                               v-model="filterData.occasion"
                                               :value="o.id" v-on:change="getAllProducts">
                                        <label :for="o.occasion+index">{{o.occasion}}</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                      <div class="hiraola-sidebar_categories">
                            <div class="hiraola-categories_title">
                                <h5>Brands</h5>
                            </div>
                            <ul class="sidebar-checkbox_list">
                                <li>
                                    <div v-for="(b,index) in brands">
                                        <input type="checkbox" :id="'brand'+index" name="brand"
                                               v-model="filterData.brand"
                                               :value="b.user_id" v-on:change="getAllProducts">
                                        <label :for="'brand'+index">{{b.shop_name}}</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="hiraola-sidebar_categories">
                            <div class="hiraola-categories_title">
                                <h5>Price Range</h5>
                            </div>
                            <div id="price-range"></div>
                            <div class="row">
                                <div id="price-min-value" class="m-t-10 col-md-6"></div>
                                <div id="price-max-value" class="m-t-10 col-md-6 text-right"></div>
                            </div>
                        </div>

                        <div class="hiraola-sidebar_categories">
                            <div class="hiraola-categories_title">
                                <h5>Search Keyword</h5>
                            </div>
                            <ul class="sidebar-checkbox_list">
                                <li><input placeholder="Search Keyword" class="form-control" type="text"
                                           v-model="filterData.name" v-on:change="getAllProducts"></li>
                            </ul>
                        </div>


                    </div>
                    <div class="sidebar-banner_area">
                        <div class="banner-item img-hover_effect">
                            <a href="javascript:void(0)">
                                <img :src="APP_URL+'/public/assets/user/images/banner/1_1.jpg'"
                                     alt="Hiraola's Shop Banner Image">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="shop-toolbar">

                        <div class="product-item-selection_area">

                            <div class="product-filter-in">
                                <i class="ion-navicon solubtn21"></i>
                                <i class="ion-android-close solubtn12" ></i>
                            </div>
                            <div class="product-short">
                                <label class="select-label">Sort By:</label>
                                <select class="form-control" v-on:change="getAllProducts" v-model="filterData.sortBy">
                                    <option value="">Sort By</option>
                                    <option value="1">Name, A to Z</option>
                                    <option value="2">Name, Z to A</option>
                                    <option value="3">Price, low to high</option>
                                    <option value="4">Price, high to low</option>
                                    <option value="5">Rating, low to high</option>
                                    <option value="6">Rating, high to low</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div v-if="allProducts.length" class="shop-product-wrap grid gridview-3 row">

                        <div class="col-lg-4" v-for="ap in allProducts">
                            <div class="slide-item">
                                <div class="single_product">
                                    <div class="product-img">
                                        <a :href="APP_URL+'/product-details/'+ap.product_variant_id">
                                            <img height="250px" width="250px" class="primary-img"
                                                 :src="APP_URL+'/public/storage/product-images/'+ap.front_image"
                                                 alt="Hiraola's Product Image">
                                            <img height="250px" width="250px" class="secondary-img"
                                                 :src="APP_URL+'/public/storage/product-images/'+ap.back_image"
                                                 alt="Hiraola's Product Image">
                                        </a>
                                        <div class="add-actions">
                                            <ul>
                                                <li><a class="hiraola-add_cart" href="javascript:void(0)"
                                                       data-toggle="tooltip"
                                                       data-placement="top" title="Add To Cart"
                                                       v-on:click="addToCart(ap)"><i class="ion-bag"></i></a>
                                                </li>
                                                <li class="quick-view-btn" v-if="AUTH_USER && ap.added_to_wishlist==0"><a href="javascript:void(0)"
                                                                              data-toggle="tooltip"
                                                                              data-placement="top"
                                                                              title="Add To Wishlist"
                                                                              v-on:click="addToWishlist(ap)"><i
                                                        class="ion-android-favorite-outline"></i></a>
                                                </li>
                                                <li class="quick-view-btn" v-if="AUTH_USER && ap.added_to_wishlist==1"><a href="javascript:void(0)"
                                                                                                                          data-toggle="tooltip"
                                                                                                                          data-placement="top"
                                                                                                                          title="Added To Wishlist"
                                                                                                                          ><i
                                                        class="ion-android-favorite"></i></a>
                                                </li>
                                                <li class="quick-view-btn"><a href="javascript:void(0)"
                                                                              data-toggle="tooltip"
                                                                              data-placement="top"
                                                                              title="Quick View"
                                                                              v-on:click="getSingleProductItem(ap.product_variant_id,'quick')"><i
                                                        class="ion-eye"></i></a></li>

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="hiraola-product_content">
                                        <div class="product-desc_info">
                                            <h6><a class="product-name"
                                                   :href="APP_URL+'/product-details/'+ap.product_variant_id">{{ap.product_name}}</a>
                                            </h6>
                                            <div class="price-box">
                                                <span class="new-price"><i class="fa fa-rupee-sign"></i> {{ap.selling_price}}  </span>
                                            </div>
<!--                                            <div class="additional-add_action">-->
<!--                                                <ul>-->
<!--                                                    <li><a class="hiraola-add_compare" href="wishlist.php"-->
<!--                                                           data-toggle="tooltip" data-placement="top"-->
<!--                                                           title="Add To Wishlist"><i-->
<!--                                                            class="ion-android-favorite-outline"></i></a>-->
<!--                                                    </li>-->
<!--                                                </ul>-->
<!--                                            </div>-->
                                            <star-rating :star-size=size :rating=ap.ratings
                                                         read-only v-bind:increment="0.1"></star-rating>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <pagination :data="allProductsData"
                                @pagination-change-page="getAllProducts"></pagination>

                    <div v-if="!allProducts.length">
                        No Products Found!!
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
                                        <img height="413px" width="413px" class="zoompro"
                                             :src="APP_URL+'/public/storage/product-images/'+productDetails.variant_images[0].image"
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
                                        <select class="form-control" v-on:change="getSingleProduct('','')"
                                                id="product-variant-id">
                                            <option v-for="pv in productDetails.variant" :value="pv.id"
                                                    :selected="productDetails.product_variant_id==pv.id">{{pv.name}}
                                            </option>
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
                                            <span>Price: {{productDetails.selling_price}}<i
                                                    class="fa fa-rupee-sign"></i></span>
                                        </div>

                                    </div>
                                    <div class="quantity">
                                        <label>Quantity</label>
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" value="1" type="text" disabled
                                                   v-model="addToCartData.qty">
                                            <div class="dec qtybutton" v-on:click="decrement"><i
                                                    class="fa fa-angle-down"></i></div>
                                            <div class="inc qtybutton" v-on:click="increment"><i
                                                    class="fa fa-angle-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="qty-btn_area">
                                        <ul>
                                            <li><a class="qty-cart_btn" v-on:click="addToCart('')">Add To Cart</a></li>
                                            <li><a class="qty-wishlist_btn" href="wishlist.html" data-toggle="tooltip"
                                                   title="Add To Wishlist"><i class="ion-android-favorite-outline"></i></a>
                                            </li>
                                            <li><a class="qty-compare_btn" href="compare.html" data-toggle="tooltip"
                                                   title="Compare This Product"><i
                                                    class="ion-ios-shuffle-strong"></i></a></li>
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
    .noUi-connect {
        background: #cda557 !important;
    }
</style>
<style>
    .add-actions li a {
        padding:0;
    }
    .aj-header-right_area ul li a i {
        top: 0 !important;
    }

</style>
<script>
    import UserMixin from "../mixins/UserMixin";
    import ProductDetails from "./ProductDetails";
    import StarRating from 'vue-star-rating'

    export default {
        name: 'ProductFilter',
        components: {ProductDetails},
        data() {
            return {
                filterData: {
                    "sortBy": "",
                    "subCategory": [],
                    "purity": [],
                    "occasion": [],
                    "name": "",
                    "price": [0, 1000000],
                    "ratings":0,
                    "brand":[]
                },
                subCategories: [],
                purities: [],
                occasion: [],
                brands: [],
                ratings:[
                    {"rating":"0 & More"},
                    {"rating":"1 & More"},
                    {"rating":"2 & More"},
                    {"rating":"3 & More"},
                    {"rating":"4 & More"},
                    ]
            }
        },
        components: {'star-rating': StarRating},

        mixins: [UserMixin],
        created() {
            let that = this;
            that.getAllProducts();
            that.getData(1);
            that.getData(2);
            that.getData(3);
            that.getData(7);
            var keyword = $("#key").val();
            that.filterData.name = keyword;
            $("#keyword").val(keyword);
            var sub_cat = $("#sub-cat").val();
            if (sub_cat) {
                that.filterData.subCategory.push(sub_cat);
                $("#sub_category").val(sub_cat);
            }
            var shop_id = $("#shop_id").val();
            if (shop_id) {
                that.filterData.brand.push(shop_id);
                //$("#sub_category").val(sub_cat);
            }
            setTimeout(function () {
                $('.toggle').click(function (e) {
                    e.preventDefault();

                    var $this = $(this);

                    if ($this.next().hasClass('show')) {
                        $this.next().removeClass('show');
                        $this.next().slideUp(350);
                    } else {
                        $this.parent().parent().find('.new-li-aco .inner').slideUp(350);
                        $this.parent().parent().find('.new-li-aco .inner').removeClass('show');

                        $this.next().slideToggle(350);
                    }
                });
            }, 1000);


            setTimeout(function () {


                var nonLinearSlider = document.getElementById('price-range');

                noUiSlider.create(nonLinearSlider, {
                    connect: true,
                    behaviour: 'tap',
                    start: [0, 1000000],
                    range: {
                        // Starting at 500, step the value by 500,
                        // until 4000 is reached. From there, step by 1000.
                        'min': [0],
                        '10%': [500, 500],
                        '50%': [4000, 1000],
                        'max': [1000000]
                    }
                });

// Display the slider value and how far the handle moved
// from the left edge of the slider.
                $("#price-range-value").empty();
                nonLinearSlider.noUiSlider.on('update', function (values, handle) {
                    $("#price-min-value").html(values[0]);
                    $("#price-max-value").html(values[1]);
                    that.getAllProducts();
                });
            }, 2000)

        },
        methods: {}
    }
    window.onload = function() {

        var y = window.matchMedia("(max-width: 768px)")
        if (y.matches) {


        }
    }
    window.onload = function() {

        var y = window.matchMedia("(max-width: 768px)")
        if (y.matches) {

            $('.solubtn21').click(function() {

                $('.solubtn21').hide();
                $('.solubtn12').show();
                $('.sectione-2').fadeIn("slow");

            });
            $('.solubtn12').click(function() {

                $('.solubtn21').show();
                $('.solubtn12').hide();
                $('.sectione-2').fadeOut("slow");

            });
        }
    }
</script>
