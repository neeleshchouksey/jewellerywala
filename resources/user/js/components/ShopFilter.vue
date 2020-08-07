<template>

    <div class="hiraola-content_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 order-1 order-lg-2">
                    <div class="shop-toolbar">

                        <div class="product-item-selection_area">

                            <div class="product-filter-in">
                                <i class="ion-navicon solubtn21"></i>
                                <i class="ion-android-close solubtn12"></i>
                            </div>
                            <div class="product-short mr-5">
                                <select name="billing_state" id="billing_state1"
                                        class="form-control" autocomplete="address-level1"
                                        data-placeholder="Select an option…" tabindex="-1" aria-hidden="true"
                                        v-on:change="getCities">
                                    <option value="">Select an option…</option>
                                    <option v-for="sd in stateData" :value="sd.id">{{sd.name}}</option>
                                </select>
                            </div>
                            <div class="product-short mr-5">

                                <select name="billing_city" id="billing_city1"
                                        class="form-control unicase-form-control state_select select2-hidden-accessible"
                                        autocomplete="address-level1"
                                        data-placeholder="Select an option…" tabindex="-1" aria-hidden="true">
                                    <option value="">Select an option…</option>
                                    <option v-for="cd in cityData" :value="cd.id">{{cd.name}}</option>
                                </select>
                            </div>
                            <div class="product-short mr-5">
                                <button type="button" class="btn-theme mt-0" v-on:click="getShops">Filter</button>
                            </div>
                        </div>

                    </div>
                        <div v-if="shops.length" class="shop-product-wrap grid gridview-3 row">

                            <div class="col-lg-4" v-for="ap in shops">
                                <div class="slide-item">
                                    <div class="single_product">
                                        <div class="product-img">
                                            <a :href="APP_URL+'/shop?shop_id='+ap.user_id">
                                                <img height="250px" width="250px" class="primary-img"
                                                     :src="APP_URL+'/public/storage/shop-images/'+ap.logo"
                                                     alt="Hiraola's Product Image">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <pagination :data="shopsData"
                                    @pagination-change-page="getShops"></pagination>

                        <div v-if="!shops.length" class="mt-3 mb-3">
                            No Shops Found!!
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>
<style>
    .noUi-connect {
        background: #cda557 !important;
    }
</style>
<style>
    .add-actions li a {
        padding: 0;
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
        data() {
            return {
                shops:[],
                shopsData:{},
                filterData: {
                   "city":"",
                    "state":""
                },
                cityData:[]
            }
        },

        mixins: [UserMixin],
        created() {
            let that = this;
            that.getState();
            var urlParams = new URLSearchParams(window.location.search);

             setTimeout(function () {
                 $("#billing_state1").val(urlParams.get('state'));
             },2000)

            setTimeout(function(){
                that.getCities()

            },3000);
            setTimeout(function(){
                $("#billing_city1").val(urlParams.get('city'));

            },4000)

            setTimeout(function () {

                that.getShops();
            },5000)

        },
        methods: {

            getCities: function () {
                let id = $("#billing_state1").val();

                let that = this;
                axios.get(APP_URL + '/get-cities/' + id).then(response => {
                        that.cityData = response.data.res;
                    }
                ).catch((error) => {

                });
            },
            getShops: function ()
            {
                var urlParams = new URLSearchParams(window.location.search);

                let that = this;
                axios.post(APP_URL + '/get-shops',{
                    "city":$("#billing_city1").val(),
                    "state":$("#billing_state1").val()
                }).then(response => {
                    response = response.data;
                    that.shops = response.res.data;
                    that.shopsData = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },

        }
    }
</script>
