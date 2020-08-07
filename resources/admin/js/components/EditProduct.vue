<template>
    <div>
        <div class="new-prodeuct" style="border: 1px solid #eee; padding: 10px;">
            <h3>Product</h3>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="productid">Product Id</label>
                    <input type="text" id="productid" placeholder="Product Id" class="form-control"
                           v-model="addProductData.productId"
                           v-on:focus="addProductDataError.productId=''">
                    <span class="color-red">{{addProductDataError.productId}}</span>
                </div>
                <div class="form-group col-md-6">
                    <label for="productname">Product Name</label>
                    <input type="text" id="productname" placeholder="Product Name" class="form-control"
                           v-model="addProductData.productName"
                           v-on:focus="addProductDataError.productName=''">
                    <span class="color-red">{{addProductDataError.productName}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label>Category</label>
                    <b-form-select id="main-category"
                                   :plain="false"
                                   :options="categories" v-model="addProductData.category"
                                   v-on:change="getSubCategories(addProductData.category);getPurities(addProductData.category);addProductDataError.category=''"
                    >
                        <template v-slot:first>
                            <option value="" selected>Select Category</option>
                        </template>
                    </b-form-select>
                    <span class="color-red">{{addProductDataError.category}}</span>

                </div>
                <div class="col-md-6 mb-4">
                    <label>Sub Category</label>
                    <b-form-select id="sub-category"
                                   :plain="false"
                                   :options="subcategories" v-model="addProductData.subCategory"
                                   v-on:change="addProductDataError.subCategory=''"
                    >
                        <template v-slot:first>
                            <option value="" selected>Select Subcategory</option>
                        </template>
                    </b-form-select>
                    <span class="color-red">{{addProductDataError.subCategory}}</span>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label>Purity</label>
                    <b-form-select id="productpurity"
                                   :plain="false"
                                   :options="purities" v-model="addProductData.purity"
                                   v-on:change="addProductDataError.purity=''"
                    >
                        <template v-slot:first>
                            <option value="" selected>Select Purity</option>
                        </template>
                    </b-form-select>
                    <span class="color-red">{{addProductDataError.purity}}</span>

                </div>
                <div class="col-md-6 mb-4">
                    <label>Occasion</label>
                    <b-form-select id="productoccasion"
                                   :plain="false"
                                   :options="occasion" v-model="addProductData.occasion"
                                   v-on:change="addProductDataError.occasion=''"
                    >
                        <template v-slot:first>
                            <option value="" selected>Select Occasion</option>
                        </template>
                    </b-form-select>
                    <span class="color-red">{{addProductDataError.occasion}}</span>

                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Product For</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="1" id="checkMale"
                                   class="form-check-input" v-model="addProductData.gender"
                                   v-on:blur="checkProductValidation"
                                   v-on:focus="addProductDataError.gender=''">
                            <label for="checkMale" class="form-check-label">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="2" id="checkFemale"
                                   class="form-check-input" v-model="addProductData.gender"
                                   v-on:focus="addProductDataError.gender=''">
                            <label for="checkFemale" class="form-check-label">Female</label>
                        </div>
                    </div>
                    <span class="color-red">{{addProductDataError.gender}}</span>

                </div>
                <div class="form-group col-md-6">
                    <label for="productname">Tax</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="tax" value="1" id="checkinclude"
                                   class="form-check-input" v-model="addProductData.tax"
                                   v-on:focus="addProductDataError.tax=''">
                            <label for="checkinclude" class="form-check-label">Include</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="tax" value="0" id="checkexclude"
                                   class="form-check-input" v-model="addProductData.tax"
                                   v-on:focus="addProductDataError.tax=''">
                            <label for="checkexclude" class="form-check-label">Exclude</label>
                        </div>
                    </div>
                    <span class="color-red">{{addProductDataError.tax}}</span>

                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Front image</label><br>
                    <!--                    <img id="f-image" class="cursor-pointer" :src="APP_URL+'/public/assets/admin/img/add-img-icon.png'" height="30px"-->
                    <!--                         width="25px" v-on:click="selectImg('front-image')">-->
                    <input ref="front_image" v-on:blur="checkProductValidation" type="file" id="front_image"
                           class="form-control-file"
                           v-on:focus="addProductDataError.frontImage=''">
                    <span class="color-red">{{addProductDataError.frontImage}}</span>

                </div>
                <div class="form-group col-md-6">
                    <label>Last Front image</label><br>
                    <img height="40" width="40" :src="APP_URL+'/public/storage/product-images/'+addProductData.frontImage"/>
                </div>
            </div>
          <div class="row">
                <div class="form-group col-md-6">
                    <label>Back image</label><br>
                    <!--                    <img id="f-image" class="cursor-pointer" :src="APP_URL+'/public/assets/admin/img/add-img-icon.png'" height="30px"-->
                    <!--                         width="25px" v-on:click="selectImg('front-image')">-->
                    <input ref="back_image" v-on:blur="checkProductValidation" type="file" id="back_image"
                           class="form-control-file"
                           v-on:focus="addProductDataError.backImage=''">
                    <span class="color-red">{{addProductDataError.backImage}}</span>

                </div>
                <div class="form-group col-md-6">
                    <label>Last Back image</label><br>
                    <img height="40" width="40" :src="APP_URL+'/public/storage/product-images/'+addProductData.backImage"/>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label>Search Tags</label><br>
                    <tags-input element-id="tags"
                                v-model="addProductData.tag"></tags-input>
                </div>
            </div>

            <button class="btn btn-theme" :disabled="addProductData.disabled" v-on:click="checkProductValidation">
                Submit
            </button>
        </div>
    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {
                addProductData: {
                    "id":"",
                    "productId": "",
                    "productName": "",
                    "category": "",
                    "subCategory": "",
                    "purity": "",
                    "occasion": "",
                    "gender": "",
                    "tax": "",
                    "frontImage": "",
                    "backImage":"",
                    "tag":[],
                    "tags":[],
                    "disabled": false
                },
                addProductDataError: {
                    "productId": "",
                    "productName": "",
                    "category": "",
                    "subCategory": "",
                    "purity": "",
                    "occasion": "",
                    "gender": "",
                    "tax": "",
                    "tag":"",
                    "frontImage": "",
                    "backImage":"",
                    "disabled": false
                },
                categories: [],
                subcategories: [],
                purities: [],
                occasion: [],
                colors: [],
            }
        },
        created() {
            let that = this;

            let id = document.URL.split('/')[document.URL.split('/').length-1];
            that.addProductData.id = id;
            that.getSingleProduct(id);
            that.getCategories();
            that.getColors();
            that.getOccasion();
        },
        mixins: [AdminSellerMixin],
        methods: {
            getSingleProduct:function(id){
                axios.get(APP_URL+'/'+USER_TYPE+'/get-single-product/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.addProductData.productId = response.res.product_id_manual;
                    that.addProductData.productName = response.res.product_name;
                    that.addProductData.category = response.res.category_id;
                    that.getSubCategories(response.res.category_id);
                    that.getPurities(response.res.category_id);
                    that.addProductData.subCategory = response.res.sub_category_id;
                    that.addProductData.purity = response.res.purity_id;
                    that.addProductData.occasion = response.res.occasion_id;
                    that.addProductData.gender = response.res.product_for;
                    that.addProductData.tax = response.res.tax;
                    that.addProductData.frontImage = response.res.front_image;
                    that.addProductData.backImage = response.res.back_image;
                    that.addProductData.tag = response.res.tag;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            calculateSellingPrice: function(){
                let that = this;
                var discount = (that.addProductData.mrp*that.addProductData.discount)/100;
                that.addProductData.sellingPrice = that.addProductData.mrp - discount;
            },
            checkProductValidation: function () {
                let that = this;
                console.log(that.addProductData.tax);
                if (that.addProductData.productId == "") {
                    that.addProductDataError.productId = "Product Id is required";
                }
                else if (that.addProductData.productName == "") {
                    that.addProductDataError.productName = "Product Name is required";
                }
                else if (that.addProductData.category == "") {
                    that.addProductDataError.category = "Category is required";
                }
                else if (that.addProductData.subCategory == "") {
                    that.addProductDataError.subCategory = "Subcategory Id is required";
                }
                else if (that.addProductData.purity == "") {
                    that.addProductDataError.purity = "Purity is required";
                }
                else if (that.addProductData.occasion == "") {
                    that.addProductDataError.occasion = "Occasion is required";
                }
                else if (that.addProductData.gender == "") {
                    that.addProductDataError.gender = "Gender is required";
                }
                else if (that.addProductData.tax === "") {
                    that.addProductDataError.tax = "Tax is required";
                }else{
                    that.updateProduct();
                }

            },
            selectImg: function (id) {
                $("#" + id).click();
            },
            updateProduct: function () {
                let that = this;

                for(var i=0;i<that.addProductData.tag.length;i++){
                    that.addProductData.tags.push(that.addProductData.tag[i].value);
                }

                let formData = new FormData();
                that.addProductData.disabled = true;
                var front_image = that.$refs.front_image.files[0];
                var back_image = that.$refs.back_image.files[0];

                formData.append('frontImage', front_image);
                formData.append('backImage', back_image);
                formData.append('id', that.addProductData.id);
                formData.append('productId', that.addProductData.productId);
                formData.append('productName', that.addProductData.productName);
                formData.append('category', that.addProductData.category);
                formData.append('subCategory', that.addProductData.subCategory);
                formData.append('purity', that.addProductData.purity);
                formData.append('occasion', that.addProductData.occasion);
                formData.append('gender', that.addProductData.gender);
                formData.append('tax', that.addProductData.tax);
                formData.append('tag', that.addProductData.tags);
                axios.post(APP_URL+'/'+USER_TYPE+'/update-product', formData).then((response) => {
                    response = response.data;
                    if (response.status == "success") {
                        that.addProductData.disabled = false;
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            window.location = APP_URL + "/" + USER_TYPE + "/products";
                        })

                    } else {
                        that.addProductData.tags = [];
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addProductData.tags = [];
                    that.addProductData.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },


        },
    }
</script>
