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
                    <div class="col-md-3 mt-2">
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
            <div class="card-body">
                <table v-if="allProducts.length" id="cat-datatable"
                       class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Id(manual)</th>
                        <th>Product Id</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Purity</th>
                        <th>Occasion</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(p,index) in allProducts">
                        <td><img height="40" width="40"
                                 :src="APP_URL+'/public/storage/product-images/'+p.front_image"/></td>
                        <td>{{p.product_id_manual}}</td>
                        <td>{{p.product_id}}</td>
                        <td>{{p.product_name}}</td>
                        <td>{{p.category_name}}</td>
                        <td>{{p.sub_category_name}}</td>
                        <td>{{p.purity_name}}</td>
                        <td>{{p.occasion}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Options
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="javascript:void(0);" v-if="p.new_arrival==0" v-on:click="addProductsToHomepage(p.id,1,1)">Add to New Arrival</a>
                                    <a class="dropdown-item" href="javascript:void(0);" v-if="p.new_arrival==1" v-on:click="addProductsToHomepage(p.id,1,0)">Remove from New Arrival</a>
                                    <a class="dropdown-item" href="javascript:void(0);" v-if="p.featured==0" v-on:click="addProductsToHomepage(p.id,2,1)">Add to Featured</a>
                                    <a class="dropdown-item" href="javascript:void(0);" v-if="p.featured==1" v-on:click="addProductsToHomepage(p.id,2,0)">Remove from Featured</a>
                                    <a class="dropdown-item" href="javascript:void(0);" v-if="p.trending==0" v-on:click="addProductsToHomepage(p.id,3,1)">Add to Trending</a>
                                    <a class="dropdown-item" href="javascript:void(0);" v-if="p.trending==1" v-on:click="addProductsToHomepage(p.id,3,0)">Remove from Trending</a>
                                </div>
                            </div>
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
           addProductsToHomepage: function (id,type, status) {
                let that = this;
                if(type == 1){
                    var tp = "New Arrival";
                }else if(type == 2){
                    var tp = "Featured";
                }else if(type == 3){
                    var tp = "Trending";
                }
                if (status == 1) {
                    var st = "Add this product to";
                } else {
                    var st = "Remove this product From";
                }
                that.$swal({
                    title: "Are you sure?",
                    text: "You want to " + st + " "+tp,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, " + st+" "+ tp+"!",
                }).then(function (result) {
                    if (result.value) {
                        $(".loading").css("display",'block');
                        axios.post(APP_URL + '/' + USER_TYPE + '/change-homepage-product-status/' + id, {"status": status,"type":type}).then((response) => {
                            $(".loading").css("display",'none');
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
