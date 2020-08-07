<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Coupon Code</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-cat-modal v-on:click="getCategories">
                                    <b> Add Coupon Code</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="couponCodes.length" id="cat-datatable"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Code</th>
                                <th>Category</th>
                                <th>Discount</th>
                                <th>Quantity</th>
                                <th>Remaining Coupon</th>
                                <th>Valid Till</th>
                                <th>Min Price</th>
                                <th>Max Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in couponCodes">
                                <td>{{index+1}}</td>
                                <td>{{hs.code}}</td>
                                <td>{{hs.category_name}}</td>
                                <td>{{hs.discount}}</td>
                                <td>{{hs.qty}}</td>
                                <td>{{hs.remaining_coupon}}</td>
                                <td>{{hs.valid_till | formatTime}}</td>
                                <td>{{hs.min_price}}</td>
                                <td>{{hs.max_price}}</td>
                                <td>
                                    <button class="btn btn-theme" @click="getCategories" v-on:click="getSingleCouponCode(hs.id);">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,11)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="couponCodesData"
                                    @pagination-change-page="getAllCouponCode"></pagination>
                        <p v-if="!couponCodes.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="update-cat-modal" title="Update Coupon Code" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Code:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Code"
                           v-model="singleCouponCode.code">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Quantity:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Quantity"
                           v-model="singleCouponCode.qty">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Remaining Coupon:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Quantity"
                           v-model="singleCouponCode.remainingCoupon">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Discount:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Discount"
                           v-model="singleCouponCode.discount">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Validity(In Days):</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Validity"
                           v-model="singleCouponCode.validity">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Min Price:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Min Price"
                           v-model="singleCouponCode.minPrice">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Max Price:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Max Price"
                           v-model="singleCouponCode.maxPrice">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <b-form-select id="main-category" class="col-md-8"
                                   :plain="false"
                                   :options="categories" v-model="singleCouponCode.category">
                    </b-form-select>
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="updateCouponCode" :disabled="singleCouponCode.disabled">Update</button>
                </div>
            </div>
        </b-modal>

        <b-modal id="add-cat-modal" ref="add-cat-modal" title="Add Coupon Code" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Code:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Code"
                           v-model="addCoupon.code">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Quantity:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Quantity"
                           v-model="addCoupon.qty">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Discount:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Discount"
                           v-model="addCoupon.discount">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Validity(In Days):</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Validity"
                           v-model="addCoupon.validity">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Min Price:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Min Price"
                           v-model="addCoupon.minPrice">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Max Price:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Max Price"
                           v-model="addCoupon.maxPrice">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <b-form-select id="main-category" class="col-md-8"
                                   :plain="false"
                                   :options="categories" v-model="addCoupon.category">
                        <template v-slot:first>
                            <option value="" selected>Select Category</option>
                        </template>
                    </b-form-select>
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="addCouponCode" :disabled="addCoupon.disabled">Add</button>
                </div>
            </div>
        </b-modal>


    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {
                categories: [],
                couponCodes: [],
                couponCodesData:{},
                singleCouponCode: {
                    "code": "",
                    "discount":"",
                    "qty":"",
                    "remainingCoupon":"",
                    "validity":"",
                    "id": "",
                    "category":"",
                    "minPrice":"",
                    "maxPrice":"",
                    "disabled":false
                },
                addCoupon: {
                    "category":"",
                    "code": "",
                    "discount":"",
                    "qty":"",
                    "validity":"",
                    "minPrice":"",
                    "maxPrice":"",
                    "disabled":false
                },
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllCouponCode();
        },
        methods: {
            updateCouponCode: function () {
                let that = this;
                that.singleCouponCode.disabled = true;
                axios.post('update-coupon-code', that.singleCouponCode).then((response) => {
                    that.singleCouponCode.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-cat-modal'].hide();
                        that.getAllCouponCode();
                    })

                }).catch((error) => {
                    that.singleCouponCode.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            addCouponCode: function () {
                let that = this;
                that.addCoupon.disabled = true;
                axios.post('add-coupon-code', that.addCoupon).then((response) => {
                    that.addCoupon.disabled = false;
                    response = response.data;
                    if (response.status == "success") {
                        that.$refs['add-cat-modal'].hide();
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addCoupon.code = "";
                            that.addCoupon.discount = "";
                            that.addCoupon.qty = "";
                            that.addCoupon.validity = "";
                            that.getAllCouponCode();
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addCoupon.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSingleCouponCode: function (id) {
                this.$refs['update-cat-modal'].show();
                axios.get('get-single-coupon-code/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singleCouponCode.code = response.res.code;
                    that.singleCouponCode.id = response.res.id;
                    that.singleCouponCode.discount = response.res.discount;
                    that.singleCouponCode.qty = response.res.qty;
                    that.singleCouponCode.remainingCoupon = response.res.remaining_coupon;
                    that.singleCouponCode.id = response.res.id;
                    that.singleCouponCode.validity = response.res.validity_days;
                    that.singleCouponCode.category = response.res.category_id;
                    that.singleCouponCode.minPrice = response.res.min_price;
                    that.singleCouponCode.maxPrice = response.res.max_price;

                }).catch((error) => {
                    alert("error from 400");
                });
            },
            getAllCouponCode: function (page=1) {
                axios.get('get-all-coupon-code?page='+page).then(response => {
                    let that = this;
                    response = response.data;
                    that.couponCodes = response.res.data;
                    that.couponCodesData = response.res;
                    console.log(that.couponCodes);
                    console.log(that.couponCodesData);
                }).catch((error) => {
                    alert("error from 400");
                });
            },

        },
    }
</script>
