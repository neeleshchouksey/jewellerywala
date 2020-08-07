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
                    <input ref="front_image" type="file" id="front_image"
                           class="form-control-file"
                           v-on:focus="addProductDataError.frontImage=''">
                    <span class="color-red">{{addProductDataError.frontImage}}</span>
                </div>
                <div class="form-group col-md-6">
                    <label>Back image</label><br>
                    <!--                    <img id="f-image" class="cursor-pointer" :src="APP_URL+'/public/assets/admin/img/add-img-icon.png'" height="30px"-->
                    <!--                         width="25px" v-on:click="selectImg('front-image')">-->
                    <input ref="back_image" type="file" id="back_image"
                           class="form-control-file"
                           v-on:focus="addProductDataError.backImage=''">
                    <span class="color-red">{{addProductDataError.backImage}}</span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Search Tags</label><br>
                <tags-input element-id="tags"
                            v-model="addProductData.tag"></tags-input>
                </div>
            </div>
        </div>
        <div class="new-sec" style="border: 1px solid #eee; padding: 10px; margin-top: 10px;">
            <h3>Variant</h3>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="productvname">Name</label>
                    <input type="text" id="productvname" placeholder="Product Name" class="form-control"

                           v-on:focus="addProductDataError.name=''"
                            v-model="addProductData.name">
                    <span class="color-red">{{addProductDataError.name}}</span>
                </div>
                <div class="col-md-6 mb-4">
                    <label>Color</label>
                    <b-form-select id="productcolor"
                                   :plain="false"
                                   :options="colors" v-model="addProductData.color"
                                   v-on:change="addProductDataError.color=''"
                    >
                        <template v-slot:first>
                            <option value="" selected>Select Color</option>
                        </template>
                    </b-form-select>
                    <span class="color-red">{{addProductDataError.color}}</span>

                </div>

            </div>
            <div class="row">
              <div class="col-md-6 mb-4">
                    <label for="producsize">Size</label>
                    <input type="text" id="producsize" placeholder="Product Size" class="form-control"
                           v-on:focus="addProductDataError.size=''"
                            v-model="addProductData.size">
                    <span class="color-red">{{addProductDataError.size}}</span>
                </div>
                <div class="form-group col-md-6">
                    <label for="mrp">Manufacturing Cost(Per gram)</label>
                    <input type="text" id="mc" placeholder="Manufacturing Cost(Per gram)" class="form-control"
                           v-on:focus="addProductDataError.mc=''"
                           v-model="addProductData.mc">
                    <span class="color-red">{{addProductDataError.mc}}</span>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="producweight">Weight(in grams)</label>
                    <input type="text" id="producweight" placeholder="Product Weight" class="form-control"
                           v-on:focus="addProductDataError.weight=''"
                           v-model="addProductData.weight" v-on:blur="calculateMRP">
                    <span class="color-red">{{addProductDataError.weight}}</span>
                </div>

                <div class="form-group col-md-6">
                    <label for="mrp">MRP</label>
                    <input type="text" id="mrp" placeholder="MRP" class="form-control"
                           v-on:focus="addProductDataError.mrp=''" disabled
                            v-model="addProductData.mrp">
                    <span class="color-red">{{addProductDataError.mrp}}</span>

                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="discount">Discount(In %)</label>
                    <input type="text" id="discount" placeholder="Discount" class="form-control"
                           v-on:blur="calculateSellingPrice()"
                           v-on:focus="addProductDataError.discount=''"
                    v-model="addProductData.discount">
                    <span class="color-red">{{addProductDataError.discount}}</span>
                </div>
                <div class="form-group col-md-6">
                    <label for="sellingprice">Selling price</label>
                    <input readonly type="text" id="sellingprice" placeholder="selling price" class="form-control"
                           v-on:focus="addProductDataError.sellingPrice=''"
                           v-model="addProductData.sellingPrice">
                    <span class="color-red">{{addProductDataError.sellingPrice}}</span>

                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="discount">Short Description</label>
                    <textarea type="text" id="short--description" placeholder="Short Description" class="form-control"
                           v-on:focus="addProductDataError.shortDescription=''"
                              v-model="addProductData.shortDescription"></textarea>
                    <span class="color-red">{{addProductDataError.shortDescription}}</span>
                </div>
                <div class="form-group col-md-6">
                    <label for="sellingprice">Long Description</label>
                    <textarea type="text" id="long-description" placeholder="Long Description" class="form-control"
                           v-on:focus="addProductDataError.longDescription=''"
                              v-model="addProductData.longDescription"></textarea>
                    <span class="color-red">{{addProductDataError.longDescription}}</span>

                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="multipleimage">Multiple image</label>
                    <input type="file" ref="variant_images" id="multipleimage" class="form-control-file" multiple
                           v-on:focus="addProductDataError.variantImages=''">
                    <span class="color-red">{{addProductDataError.variantImages}}</span>
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
                    "productId": "",
                    "productName": "",
                    "category": "",
                    "subCategory": "",
                    "purity": "",
                    "occasion": "",
                    "gender": "",
                    "tax": "",
                    "frontImage": "",
                    "backImage": "",
                    "shortDescription":"",
                    "longDescription":"",
                    "name":"",
                    "size": "",
                    "weight": "",
                    "color": "",
                    "mrp": "",
                    "mc":"",
                    "sellingPrice": "",
                    "discount": "",
                    "variantImages": "",
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
                    "frontImage": "",
                    "backImage": "",
                    "tag":"",
                    "shortDescription":"",
                    "longDescription":"",
                    "name":"",
                    "size": "",
                    "weight": "",
                    "color": "",
                    "mrp": "",
                    "mc":"",
                    "sellingPrice": "",
                    "discount": "",
                    "variantImages": "",
                    "disabled": false
                },

            }
        },
        created() {
            let that = this;
            that.getCategories();
            that.getColors();
            that.getOccasion();

        },
        mixins: [AdminSellerMixin],
        methods: {
            calculateMRP: function(){
                let that = this;
                axios.post(APP_URL+'/'+USER_TYPE+'/calculate-mrp', {"mc":that.addProductData.mc,"weight":that.addProductData.weight,"purity":that.addProductData.purity}).then(response => {
                    response =response.data;
                    that.addProductData.mrp = response.mrp;
                }).catch((error) => {
                   // alert("error from 400");
                });
            },
            calculateSellingPrice: function(){
                let that = this;
                var discount = (that.addProductData.mrp*that.addProductData.discount)/100;
                that.addProductData.sellingPrice = that.addProductData.mrp - discount;
            },
            checkProductValidation: function () {
                let that = this;
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
                else if (that.addProductData.tax == "") {
                    that.addProductDataError.tax = "Tax is required";
                }
                else if (!that.$refs.front_image.files.length) {
                    that.addProductDataError.frontImage = "Front Image is required";
                }
                else if (!that.$refs.back_image.files.length) {
                    that.addProductDataError.backImage = "Back Image is required";
                }
                else if (that.addProductData.tag == "") {
                    that.addProductDataError.tag = "Tag is required";
                }
                else if (that.addProductData.name == "") {
                    that.addProductDataError.name = "Name is required";
                }
                else if (!that.$refs.variant_images.files.length) {
                    that.addProductDataError.variantImages = "Variant Images is required";
                }
                else if (that.addProductData.size == "") {
                    that.addProductDataError.size = "Size is required";
                }
                else if (that.addProductData.weight == "") {
                    that.addProductDataError.weight = "Weight is required";
                }
                else if (that.addProductData.color == "") {
                    that.addProductDataError.color = "Color is required";
                }
                else if (that.addProductData.mc == "") {
                    that.addProductDataError.mc = "Manufacturing Cost is required";
                }
                // if (that.addProductData.sellingPrice == "") {
                //     that.addProductDataError.sellingPrice = "Selling Price is required";
                // }
                else if (that.addProductData.discount == "") {
                    that.addProductDataError.discount = "Discount is required";
                }
                else if (that.addProductData.shortDescription == "") {
                    that.addProductDataError.shortDescription = "Short Description is required";
                }
                else if (that.addProductData.longDescription == "") {
                    that.addProductDataError.longDescription = "Long Description is required";
                }
                else{
                    that.addProduct();
               }
            },

            addProduct: function () {
                let that = this;
                for(var i=0;i<that.addProductData.tag.length;i++){
                    that.addProductData.tags.push(that.addProductData.tag[i].value);
                }
                let formData = new FormData();
                that.addProductData.disabled = true;
                var front_image = that.$refs.front_image.files[0];
                var back_image = that.$refs.back_image.files[0];
                var variant_image = that.$refs.variant_images.files;
                for( var i = 0; i < variant_image.length; i++ ){
                    let variant_image1 = variant_image[i];
                    formData.append('variantImages[' + i + ']', variant_image1);
                }
                formData.append('frontImage', front_image);
                formData.append('backImage', back_image);
                formData.append('productId', that.addProductData.productId);
                formData.append('productName', that.addProductData.productName);
                formData.append('category', that.addProductData.category);
                formData.append('subCategory', that.addProductData.subCategory);
                formData.append('purity', that.addProductData.purity);
                formData.append('occasion', that.addProductData.occasion);
                formData.append('gender', that.addProductData.gender);
                formData.append('tax', that.addProductData.tax);
                formData.append('weight', that.addProductData.weight);
                formData.append('name', that.addProductData.name);
                formData.append('size', that.addProductData.size);
                formData.append('discount', that.addProductData.discount);
                formData.append('sellingPrice', that.addProductData.sellingPrice);
                formData.append('mrp', that.addProductData.mrp);
                formData.append('mc', that.addProductData.mc);
                formData.append('color', that.addProductData.color);
                formData.append('shortDescription', that.addProductData.shortDescription);
                formData.append('longDescription', that.addProductData.longDescription);
                formData.append('tag', that.addProductData.tags);

                axios.post(APP_URL+'/'+USER_TYPE+'/add-product', formData).then((response) => {
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
