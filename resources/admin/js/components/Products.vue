<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-actions row">
                    <div class="col-md-3">
                        <label>Select Category</label>
                        <b-form-select id="main-category"
                                       :plain="false"
                                       :options="categories" v-model="filterData.category"
                                       v-on:change="getSubCategories(filterData.category);getPurities(filterData.category);">
                            <template v-slot:first>
                                <option value="" selected>Select Category</option>
                            </template>
                        </b-form-select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Subcategory</label>
                        <b-form-select id="sub-category"
                                       :plain="false"
                                       :options="subcategories" v-model="filterData.subCategory"
                        >
                            <template v-slot:first>
                                <option value="" selected>Select Subcategory</option>
                            </template>
                        </b-form-select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Purity</label>
                        <b-form-select id="productpurity"
                                       :plain="false"
                                       :options="purities" v-model="filterData.purity"
                        >
                            <template v-slot:first>
                                <option value="" selected>Select Purity</option>
                            </template>
                        </b-form-select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Occasion</label>
                        <b-form-select id="productoccasion"
                                       :plain="false"
                                       :options="occasion" v-model="filterData.occasion"
                        >
                            <template v-slot:first>
                                <option value="" selected>Select Occasion</option>
                            </template>
                        </b-form-select>
                    </div>
                    <div class="col-md-3  mt-2">
                        <label for="productname">Product Name</label>
                        <input type="text" id="productname" placeholder="Product Name" class="form-control"
                               v-model="filterData.name"/>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label for="productname">Product Id</label>
                        <input type="text" id="productid" placeholder="Product Id" class="form-control"
                               v-model="filterData.productId"/>
                    </div>
                    <div class="col-md-3 mt-2" v-if="USER_TYPE=='admin'">
                        <label for="productname">Seller Name</label>
                        <input type="text" id="sellername" placeholder="Seller Name" class="form-control"
                               v-model="filterData.sellerName"/>
                    </div>
                    <div class="col-md-3 mt-2">
                        <button class="btn btn-theme search-mt" v-on:click="getAllProducts">
                            Search
                        </button>
                        <button class="btn btn-theme search-mt" v-on:click="resetFilter">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-actions row">
                    <div class="col-md-6 text-left">
                        <h5>Products</h5>
                    </div>
                    <div class="col-md-6 text-right" v-if="USER_TYPE=='seller'">
                        <a class="btn btn-theme text-right" :href="APP_URL+'/'+USER_TYPE+'/add-product'">
                            <b> Add Product</b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body ">
                <table v-if="allProducts.length" id="cat-datatable"
                       class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Id(manual)</th>
                        <th>Name</th>
                        <th v-if="USER_TYPE=='admin'">Seller Name</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Purity</th>
                        <th>Occasion</th>
                        <th>Verified By Admin</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(p,index) in allProducts">
                        <td><img height="40" width="40"
                                 :src="APP_URL+'/public/storage/product-images/'+p.front_image"/></td>
                        <td><a :href="APP_URL+'/'+USER_TYPE+'/view-product/'+p.id">{{p.product_id_manual}}</a></td>
                        <td>{{p.product_name}}</td>
                        <td v-if="USER_TYPE=='admin'">{{p.shop_name}}</td>
                        <td>{{p.category_name}}</td>
                        <td>{{p.sub_category_name}}</td>
                        <td>{{p.purity_name}}</td>
                        <td>{{p.occasion}}</td>
                        <td>
                            <span v-if="p.verified_by_admin && USER_TYPE=='seller'">Verified</span>
                            <span v-if="!p.verified_by_admin && USER_TYPE=='seller'">Not Verified</span>
                            <span v-if="!p.verified_by_admin && USER_TYPE=='admin'">
                                        <button class="btn btn-theme" v-on:click="verifyProduct(p.id,1)">
                                        Verify
                                    </button>
                                    </span>
                            <span v-if="p.verified_by_admin && USER_TYPE=='admin'">
                                        <button class="btn btn-theme" v-on:click="verifyProduct(p.id,0)">
                                        UnVerify
                                    </button>
                                    </span>
                        </td>

                        <td>
                            <span v-if="p.deleted_at==null">
                            <a class="btn btn-theme mb-1" :href="APP_URL+'/'+USER_TYPE+'/edit-product/'+p.id">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <button class="btn btn-danger mb-1" v-on:click="deleteData(p.id,6)">
                                <i class="fa fa-trash"></i>
                            </button>
                            <a class="btn btn-theme mb-1" :href="APP_URL+'/'+USER_TYPE+'/view-product/'+p.id">
                                <i class="fa fa-eye"></i>
                            </a>
                            </span>
                            <span v-if="p.deleted_at!=null" class="color-red"><b>Deactivated</b></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <pagination :data="allProductsData"
                            @pagination-change-page="getAllProducts"></pagination>
                <p v-if="!allProducts.length">No Data Found</p>
            </div>
        </div>
    </div>
</template>
<style>
    a:hover{
        color:#ffde00
    }
</style>
<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {
                categories: [],
                singleCategory: {
                    "category": "",
                    "id": "",
                    "disabled": false
                },
                addCategories: {
                    "category": "",
                    "disabled": false
                },
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            var url = document.URL.split("shop_name=");
            if(url[1]!=undefined){
                that.filterData.sellerName = decodeURIComponent(url[1]);
            }
            that.getCategories();
            that.getColors();
            that.getOccasion();
            that.getAllProducts();
        },
        methods: {
            resetFilter:function(){
                let that = this;
                that.filterData.category = '';
                that.filterData.subCategory = '';
                that.filterData.purity = '';
                that.filterData.occasion = '';
                that.filterData.name = '';
                that.filterData.productId = '';
                that.filterData.sellerName = '';
                that.getAllProducts();
            },
            verifyProduct: function (id, status) {
                let that = this;
                if (status == 1) {
                    var st = "Verify";
                } else {
                    var st = "Unverify";
                }
                that.$swal({
                    title: "Verify?",
                    text: "Are you sure? You want to " + st + " this product",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, " + st + " it!",
                }).then(function (result) {
                    if (result.value) {
                        axios.post(APP_URL + '/' + USER_TYPE + '/verify-product/' + id, {"status": status}).then((response) => {
                            response = response.data;
                            if (response.status == "success") {
                                that.$swal({
                                    type: "success",
                                    title: "Success",
                                    text: response.message,
                                    showConfirmButton: true
                                }).then(function () {
                                    that.getAllProducts();
                                });
                            }
                        });
                    }
                })
            },
        },
    }
</script>
