<template>
    <div class="tabs tabs-default">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#product-details" role="tab">Product
                    Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#product-variants" role="tab">Product
                    Variants</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#product-reviews" role="tab">Product
                    Reviews</a>
            </li>

        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="product-details" role="tabpanel">
                <div class="singel-prodect-had">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="singel-product-imaeg">
                                <div class="s-image1">
                                    <img :src="APP_URL+'/public/storage/product-images/'+singleProductData.front_image">
                                    <div class="p-image-content">
                                        <h6>Front Image</h6>
                                    </div>
                                </div>
                                <div class="s-image2">
                                    <img :src="APP_URL+'/public/storage/product-images/'+singleProductData.back_image">
                                    <div class="p-image-content2">
                                        <h6>Back Image</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="singel-prodect-content">
                                <div class="s-p-contant0">
                                    <div class="s-p-id0">Id</div>
                                    <div class="s-p-id1">{{singleProductData.product_id_manual}}</div>
                                </div>
                                <div class="s-p-contant1">
                                    <div class="s-p-id0">Name</div>
                                    <div class="s-p-id1">{{singleProductData.product_name}}</div>
                                </div>

                            </div>
                            <div class="singel-prodect-content">
                                <div class="s-p-contant0">
                                    <div class="s-p-id0">Category</div>
                                    <div class="s-p-id1">{{singleProductData.category_name}}</div>
                                </div>
                                <div class="s-p-contant1">
                                    <div class="s-p-id0">Occasion</div>
                                    <div class="s-p-id1">{{singleProductData.occasion}}</div>
                                </div>

                            </div>
                            <div class="singel-prodect-content">
                                <div class="s-p-contant0">
                                    <div class="s-p-id0">Sub Category</div>
                                    <div class="s-p-id1">{{singleProductData.sub_category_name}}</div>
                                </div>
                                <div class="s-p-contant1">
                                    <div class="s-p-id0">Purity</div>
                                    <div class="s-p-id1">{{singleProductData.purity_name}}</div>
                                </div>

                            </div>
                            <div class="singel-prodect-content">
                                <div class="s-p-contant0">
                                    <div class="s-p-id0">Product For</div>
                                    <div class="s-p-id1"><span v-if="singleProductData.product_for==1">Male</span>
                                        <span v-if="singleProductData.product_for==2">Female</span></div>
                                </div>
                                <div class="s-p-contant1">
                                    <div class="s-p-id0">Tax</div>
                                    <div class="s-p-id1"><span v-if="singleProductData.tax==0">Exclude</span>
                                        <span v-if="singleProductData.tax==1">Include</span></div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="product-variants" role="tabpanel">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions row">
                                    <div class="col-md-6 text-left">
                                        <h5>Variants</h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button class="btn btn-theme text-right" v-b-modal.add-variant-modal
                                                v-on:click="getColors">
                                            <b> Add Variant</b>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table v-if="variantData.length" id="cat-datatable"
                                       class="table table-striped table-bordered" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Size</th>
                                        <th>Weight(In grams)</th>
                                        <th>Color</th>
                                        <th>MRP</th>
                                        <th>Discount</th>
                                        <th>Selling Price</th>
                                        <th>Short Description</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(hs,index) in variantData">
                                        <td>{{index+1}}</td>
                                        <td>{{hs.name}}</td>
                                        <td>{{hs.size}}</td>
                                        <td>{{hs.weight}}</td>
                                        <td>{{hs.color}}</td>
                                        <td>{{hs.mrp}} <i class="fa fa-rupee"></i></td>
                                        <td>{{hs.discount}}%</td>
                                        <td>{{hs.selling_price}}<i class="fa fa-rupee-sign"></i></td>
                                        <td>{{hs.short_description}}</td>
                                        <td>
                                            <button class="btn btn-theme mr-1"
                                                    v-on:click="getColors();getSingleVariant(hs.id,'update')">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button class="btn btn-danger mr-1" v-on:click="deleteData(hs.id,7)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button class="btn btn-theme mr-1 mt-2"
                                                    v-on:click="getSingleVariant(hs.id,'view')">
                                                <i class="fa fa-eye" title="View Variant Images"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p v-if="!variantData.length">No Data Found</p>
                            </div>
                        </div>
                    </div>
                </div>


</div>
            <div class="tab-pane" id="product-reviews" role="tabpanel">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions row">
                                    <div class="col-md-6 text-left">
                                        <h5>Reviews & Ratings</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table v-if="reviews.length" id="cat-datatable3"
                                       class="table table-striped table-bordered" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>User Name</th>
                                        <th>Review</th>
                                        <th>Ratings</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(hs,index) in reviews">
                                        <td>{{index+1}}</td>
                                        <td>{{hs.first_name}} {{hs.last_name}}</td>
                                        <td>{{hs.reviews}}</td>
                                        <td> <star-rating :star-size=size :rating=hs.ratings
                                                          read-only v-bind:increment="0.1"></star-rating></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <pagination :data="reviewData"
                                            @pagination-change-page="getProductReviews"></pagination>
                                <p v-if="!reviews.length">No Data Found</p>
                            </div>
                        </div>
                    </div>
                </div>


</div>
        </div>


        <b-modal size="lg" id="add-variant-modal" ref="add-variant-modal" title="Add Variant" hide-footer>
            <div class="new-sec" style="border: 1px solid #eee; padding: 10px; margin-top: 10px;">

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="producsize">Name</label>
                        <input type="text" id="productname" placeholder="Product Name" class="form-control"
                               v-on:blur="checkVariantValidation"
                               v-on:focus="addVariantDataError.name=''"
                               v-model="addVariantData.name">
                        <span class="color-red">{{addVariantDataError.name}}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label>Color</label>
                        <b-form-select id="productcolor"
                                       :plain="false"
                                       :options="colors" v-model="addVariantData.color"
                                       v-on:change="checkVariantValidation(); addVariantDataError.color=''"
                        >
                            <template v-slot:first>
                                <option value="" selected>Select Color</option>
                            </template>
                        </b-form-select>
                        <span class="color-red">{{addVariantDataError.color}}</span>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="producsize">Size</label>
                        <input type="text" id="productsize" placeholder="Product Size" class="form-control"
                               v-on:blur="checkVariantValidation"
                               v-on:focus="addVariantDataError.size=''"
                               v-model="addVariantData.size">
                        <span class="color-red">{{addVariantDataError.size}}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="producweight">Weight(in grams)</label>
                        <input type="text" id="productweight" placeholder="Product Weight" class="form-control"
                               v-on:blur="checkVariantValidation"
                               v-on:focus="addVariantDataError.weight=''"
                               v-model="addVariantData.weight">
                        <span class="color-red">{{addVariantDataError.weight}}</span>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="producweight">Manufacturing Cost(in grams)</label>
                        <input type="text" id="mc" placeholder="Manufacturing Cost(in grams)" class="form-control"
                               v-on:blur="checkVariantValidation();calculateMRPAddVariant();"
                               v-on:focus="addVariantDataError.mc=''"
                               v-model="addVariantData.mc">
                        <span class="color-red">{{addVariantDataError.mc}}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mrp">MRP</label>
                        <input type="text" id="mrp" placeholder="MRP" class="form-control"
                               v-on:blur="checkVariantValidation"
                               v-on:focus="addVariantDataError.mrp=''"
                               v-model="addVariantData.mrp" disabled>
                        <span class="color-red">{{addVariantDataError.mrp}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="discount">Discount(In %)</label>
                        <input type="text" id="discount" placeholder="Discount" class="form-control"
                               v-on:blur="checkVariantValidation();calculateSellingPrice()"
                               v-on:focus="addVariantDataError.discount=''"
                               v-model="addVariantData.discount">
                        <span class="color-red">{{addVariantDataError.discount}}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sellingprice">Selling price</label>
                        <input readonly type="text" id="sellingprice" placeholder="selling price" class="form-control"
                               v-on:blur="checkVariantValidation"
                               v-on:focus="addVariantDataError.sellingPrice=''"
                               v-model="addVariantData.sellingPrice">
                        <span class="color-red">{{addVariantDataError.sellingPrice}}</span>

                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="discount">Short Description</label>
                        <textarea type="text" id="short--description" placeholder="Short Description"
                                  class="form-control"
                                  v-on:blur="checkVariantValidation();"
                                  v-on:focus="addVariantDataError.shortDescription=''"
                                  v-model="addVariantData.shortDescription"></textarea>
                        <span class="color-red">{{addVariantDataError.shortDescription}}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sellingprice">Long Description</label>
                        <textarea type="text" id="long-description" placeholder="Long Description" class="form-control"
                                  v-on:blur="checkVariantValidation"
                                  v-on:focus="addVariantDataError.longDescription=''"
                                  v-model="addVariantData.longDescription"></textarea>
                        <span class="color-red">{{addVariantDataError.longDescription}}</span>

                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="multipleimage">Multiple image</label>
                        <input type="file" ref="variant_images" id="multipleimage" class="form-control-file" multiple
                               v-on:blur="checkVariantValidation"
                               v-on:focus="addVariantDataError.variantImages=''">
                        <span class="color-red">{{addVariantDataError.variantImages}}</span>
                    </div>
                </div>
                <button class="btn btn-theme" :disabled="addVariantData.disabled" v-on:click="addVariant">
                    Submit
                </button>
            </div>

        </b-modal>
        <b-modal size="lg" id="update-variant-modal" ref="update-variant-modal" title="Update Variant" hide-footer>
            <div class="new-sec" style="border: 1px solid #eee; padding: 10px; margin-top: 10px;">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="productname">Name</label>
                        <input type="text" id="productname1" placeholder="Product Name" class="form-control"
                               v-on:blur="checkVariantValidationUpdate"
                               v-on:focus="updateVariantDataError.name=''"
                               v-model="updateVariantData.name">
                        <span class="color-red">{{updateVariantDataError.name}}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label>Color</label>
                        <b-form-select id="productcolor"
                                       :plain="false"
                                       :options="colors" v-model="updateVariantData.color"
                                       v-on:change="checkVariantValidationUpdate();updateVariantDataError.color=''"
                        >
                            <template v-slot:first>
                                <option value="" selected>Select Color</option>
                            </template>
                        </b-form-select>
                        <span class="color-red">{{updateVariantDataError.color}}</span>

                    </div>
                </div>



              <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="productsize">Size</label>
                        <input type="text" id="productsize1" placeholder="Product Size" class="form-control"
                               v-on:blur="checkVariantValidationUpdate"
                               v-on:focus="updateVariantDataError.size=''"
                               v-model="updateVariantData.size">
                        <span class="color-red">{{updateVariantDataError.size}}</span>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="producweight">Weight(in grams)</label>
                        <input type="text" id="productweight1" placeholder="Product Weight" class="form-control"
                               v-on:blur="checkVariantValidationUpdate();calculateMRP();"
                               v-on:focus="updateVariantDataError.weight=''"
                               v-model="updateVariantData.weight">
                        <span class="color-red">{{updateVariantDataError.weight}}</span>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="producweight">Manufacturing Cost(in grams)</label>
                        <input type="text" id="mc1" placeholder="Manufacturing Cost(in grams)" class="form-control"
                               v-on:blur="checkVariantValidationUpdate();calculateMRP();"
                               v-on:focus="updateVariantDataError.mc=''"
                               v-model="updateVariantData.mc">
                        <span class="color-red">{{updateVariantDataError.mc}}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mrp">MRP</label>
                        <input type="text" id="mrp1" placeholder="MRP" class="form-control"
                               v-on:blur="checkVariantValidationUpdate"
                               v-on:focus="updateVariantDataError.mrp=''"
                               v-model="updateVariantData.mrp" disabled>
                        <span class="color-red">{{updateVariantDataError.mrp}}</span>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="discount">Discount(In %)</label>
                        <input type="text" id="discount1" placeholder="Discount" class="form-control"
                               v-on:blur="checkVariantValidationUpdate();calculateSellingPriceUpdate()"
                               v-on:focus="updateVariantDataError.discount=''"
                               v-model="updateVariantData.discount">
                        <span class="color-red">{{updateVariantDataError.discount}}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sellingprice">Selling price</label>
                        <input readonly type="text" id="sellingprice1" placeholder="selling price" class="form-control"
                               v-on:blur="checkVariantValidationUpdate"
                               v-on:focus="updateVariantDataError.sellingPrice=''"
                               v-model="updateVariantData.sellingPrice">
                        <span class="color-red">{{updateVariantDataError.sellingPrice}}</span>

                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="discount">Short Description</label>
                        <textarea type="text" id="short--description1" placeholder="Short Description"
                                  class="form-control"
                                  v-on:blur="checkVariantValidationUpdate();"
                                  v-on:focus="updateVariantDataError.shortDescription=''"
                                  v-model="updateVariantData.shortDescription"></textarea>
                        <span class="color-red">{{updateVariantDataError.shortDescription}}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sellingprice">Long Description</label>
                        <textarea type="text" id="long-description1" placeholder="Long Description" class="form-control"
                                  v-on:blur="checkVariantValidationUpdate"
                                  v-on:focus="updateVariantDataError.longDescription=''"
                                  v-model="updateVariantData.longDescription"></textarea>
                        <span class="color-red">{{updateVariantDataError.longDescription}}</span>

                    </div>

                </div>
                <!--                <div class="row">-->
                <!--                    <div class="form-group col-md-6">-->
                <!--                        <label for="multipleimage">Multiple image</label>-->
                <!--                        <input type="file" ref="variant_images" id="multipleimage" class="form-control-file" multiple-->
                <!--                               v-on:blur="checkVariantValidation"-->
                <!--                               v-on:focus="updateVariantDataError.variantImages=''">-->
                <!--                        <span class="color-red">{{updateVariantDataError.variantImages}}</span>-->
                <!--                    </div>-->
                <!--                </div>-->
                <button class="btn btn-theme" :disabled="updateVariantData.disabled" v-on:click="updateVariant">
                    Submit
                </button>
            </div>

        </b-modal>
        <b-modal id="view-variant-modal-1" ref="view-variant-modal" title="View Variant Images" hide-footer>
            <div class="new-sec" style="border: 1px solid #eee; padding: 10px; margin-top: 10px;">
                <div class="row">
                    <div class="col-md-3 mb-4" v-for="(vi,index) in variantImages">
                        <i v-if="index!=0" class="fa fa-remove color-red cursor-pointer" title="Delete"
                           v-on:click="deleteData(vi.id,8,vi.product_variant_id)"></i>
                        <i v-if="index==0" class="fa fa-remove color-red cursor-pointer" title="You can't delete all variant images"></i>

                        <img height="100" width="100"
                             :src="APP_URL+'/public/storage/product-images/'+vi.image"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label for="multipleimage">Upload Multiple image</label>
                    <input type="file" ref="upload_variant_images" id="upload_multipleimage" class="form-control-file"
                           multiple>
                </div>
            </div>
            <button class="btn btn-theme" :disabled="uploadVariantImageData.disabled"
                    v-on:click="uploadVariantImage(variantImages[0].product_variant_id)">
                Upload
            </button>
        </b-modal>
    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";
    import StarRating from 'vue-star-rating'

    export default {

        data() {
            return {
                size:25,
                variantData: [],
                reviewData: [],
                reviews: [],
                singleProductData:{},
                uploadVariantImageData: {
                    "disabled": false,
                    "variantImages": "",
                    "variantId": ""
                },
                addVariantData: {
                    "productId": "",
                    "shortDescription": "",
                    "longDescription": "",
                    "name":"",
                    "size": "",
                    "weight": "",
                    "color": "",
                    "mrp": "",
                    "mc":"",
                    "sellingPrice": "",
                    "discount": "",
                    "variantImages": "",
                    "disabled": false,
                },
                addVariantDataError: {
                    "shortDescription": "",
                    "longDescription": "",
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
                updateVariantData: {
                    "productId": "",
                    "shortDescription": "",
                    "longDescription": "",
                    "name":"",
                    "size": "",
                    "weight": "",
                    "color": "",
                    "mrp": "",
                    "mc":"",
                    "sellingPrice": "",
                    "discount": "",
                    "variantImages": "",
                    "disabled": false,
                },
                updateVariantDataError: {
                    "shortDescription": "",
                    "longDescription": "",
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

                categories: [],
                subcategories: [],
                purities: [],
                occasion: [],
                colors: [],
                variantImages: []
            }
        },
        created() {
            let that = this;
            let id = document.URL.split('/')[document.URL.split('/').length - 1];
            that.addVariantData.id = id;
            that.getProductVariants(id);
            that.getProductReviews(id);
            that.addVariantData.productId = id;
            that.updateVariantData.productId = id;
            that.getSingleProduct(id);
        },
        components: {'star-rating': StarRating},

        mixins: [AdminSellerMixin],
        methods: {

            uploadVariantImage: function (id) {

                let that = this;
                that.uploadVariantImageData.disabled = true;
                let formData = new FormData();
                var variant_image = that.$refs.upload_variant_images.files;
                for (var i = 0; i < variant_image.length; i++) {
                    let variant_image1 = variant_image[i];
                    formData.append('variantImages[' + i + ']', variant_image1);
                }
                formData.append("variantId", id)
                axios.post(APP_URL + '/' + USER_TYPE + '/upload-variant-images', formData).then((response) => {
                    response = response.data;
                    if (response.status == "success") {
                        that.uploadVariantImageData.disabled = false;
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            const input = that.$refs.upload_variant_images
                            input.type = 'text'
                            input.type = 'file'
                            that.getSingleVariant(id);
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addVariantData.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            addVariant: function () {
                let that = this;
                let formData = new FormData();
                that.addVariantData.disabled = true;
                var variant_image = that.$refs.variant_images.files;
                for (var i = 0; i < variant_image.length; i++) {
                    let variant_image1 = variant_image[i];
                    formData.append('variantImages[' + i + ']', variant_image1);
                }
                formData.append('productId', that.addVariantData.productId);
                formData.append('weight', that.addVariantData.weight);
                formData.append('name', that.addVariantData.name);
                formData.append('size', that.addVariantData.size);
                formData.append('discount', that.addVariantData.discount);
                formData.append('sellingPrice', that.addVariantData.sellingPrice);
                formData.append('mrp', that.addVariantData.mrp);
                formData.append('mc', that.addVariantData.mc);
                formData.append('color', that.addVariantData.color);
                formData.append('shortDescription', that.addVariantData.shortDescription);
                formData.append('longDescription', that.addVariantData.longDescription);
                axios.post(APP_URL + '/' + USER_TYPE + '/add-variant', formData).then((response) => {
                    response = response.data;
                    if (response.status == "success") {
                        that.addVariantData.disabled = false;
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.getProductVariants(that.addVariantData.productId);
                            that.$refs['add-variant-modal'].hide();
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addVariantData.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getProductVariants: function (id) {
                axios.get(APP_URL + '/' + USER_TYPE + '/get-product-variants/' + id).then(response => {
                    let that = this;
                    that.variantData = response.data.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            getProductReviews: function (id,page=1) {
                axios.get(APP_URL + '/' + USER_TYPE + '/get-product-reviews/' + id).then(response => {
                    let that = this;
                    that.reviews = response.data.res.data;
                    that.reviewData = response.data.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            getSingleVariant: function (id, type) {
                axios.get(APP_URL + '/' + USER_TYPE + '/get-single-variant/' + id).then(response => {
                    let that = this;
                    if (type == "update") {
                        that.$refs['update-variant-modal'].show();
                        response = response.data;
                        that.updateVariantData.id = response.res.id;
                        that.updateVariantData.name = response.res.name;
                        that.updateVariantData.productId = response.res.product_id;
                        that.updateVariantData.size = response.res.size;
                        that.updateVariantData.weight = response.res.weight;
                        that.updateVariantData.color = response.res.color_id;
                        that.updateVariantData.mrp = response.res.mrp;
                        that.updateVariantData.mc = response.res.manufacturing_cost;
                        that.updateVariantData.discount = response.res.discount;
                        that.updateVariantData.sellingPrice = response.res.selling_price;
                        that.updateVariantData.shortDescription = response.res.short_description;
                        that.updateVariantData.longDescription = response.res.long_description;
                    } else {
                        response = response.data;
                        that.$refs['view-variant-modal'].show();
                        that.variantImages = response.res.variant_images;
                    }

                }).catch((error) => {
                    alert("error from 400");
                });
            },
            getSingleProduct: function (id) {
                axios.get(APP_URL + '/' + USER_TYPE + '/get-single-product/' + id).then(response => {
                    let that = this;
                    that.singleProductData = response.data.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            calculateMRP: function(){
                let that = this;
                axios.post(APP_URL+'/'+USER_TYPE+'/calculate-variant-mrp', {"mc":that.updateVariantData.mc,"weight":that.updateVariantData.weight,"product":that.updateVariantData.productId}).then(response => {
                    response =response.data;
                    that.updateVariantData.mrp = response.mrp;
                }).catch((error) => {
                    // alert("error from 400");
                });
            },
             calculateMRPAddVariant: function(){
                let that = this;
                axios.post(APP_URL+'/'+USER_TYPE+'/calculate-variant-mrp', {"mc":that.addVariantData.mc,"weight":that.addVariantData.weight,"product":that.addVariantData.productId}).then(response => {
                    response =response.data;
                    that.addVariantData.mrp = response.mrp;
                }).catch((error) => {
                    // alert("error from 400");
                });
            },
            calculateSellingPrice: function () {
                let that = this;
                var discount = (that.addVariantData.mrp * that.addVariantData.discount) / 100;
                that.addVariantData.sellingPrice = that.addVariantData.mrp - discount;
            },
            calculateSellingPriceUpdate: function () {
                let that = this;
                var discount = (that.updateVariantData.mrp * that.updateVariantData.discount) / 100;
                that.updateVariantData.sellingPrice = that.updateVariantData.mrp - discount;
            },
            checkVariantValidation: function () {
                let that = this;
                if (that.addVariantData.name == "") {
                    that.addVariantDataError.name = "Name is required";
                }
               if (that.addVariantData.size == "") {
                    that.addVariantDataError.size = "Size is required";
                }
                if (that.addVariantData.weight == "") {
                    that.addVariantDataError.weight = "Weight is required";
                }
                if(!$.isNumeric($("#productweight").val())){
                    that.addVariantDataError.weight = "Please Enter numeric value";
                }
                if (that.addVariantData.color == "") {
                    that.addVariantDataError.color = "Color is required";
                }
                if (that.addVariantData.mc == "") {
                    that.addVariantDataError.mc = "Manufacturing Cost is required";
                }
                if(!$.isNumeric($("#mc").val())){
                    that.addVariantDataError.mc = "Please Enter numeric value";
                }
                // if (that.addVariantData.sellingPrice == "") {
                //     that.addVariantDataError.sellingPrice = "Selling Price is required";
                // }
                if (that.addVariantData.discount == "") {
                    that.addVariantDataError.discount = "Discount is required";
                }
                if(!$.isNumeric($("#discount").val())){
                    that.addVariantDataError.discount = "Please Enter numeric value";
                }
                if (that.addVariantData.shortDescription == "") {
                    that.addVariantDataError.shortDescription = "Short Description is required";
                }
                if (that.addVariantData.longDescription == "") {
                    that.addVariantDataError.longDescription = "Long Description is required";
                }
                // if (!that.$refs.variant_images.files.length) {
                //     that.addVariantDataError.variantImages = "Variant Images Price Image is required";
                // }
            },
            checkVariantValidationUpdate: function () {
                let that = this;
                if (that.updateVariantData.name == "") {
                    that.updateVariantDataError.name = "Name is required";
                }
               if (that.updateVariantData.size == "") {
                    that.updateVariantDataError.size = "Size is required";
                }
                if (that.updateVariantData.weight == "") {
                    that.updateVariantDataError.weight = "Weight is required";
                }
                if(!$.isNumeric($("#productweight1").val())){
                    that.updateVariantDataError.weight = "Please Enter numeric value";
                }
                if (that.updateVariantData.color == "") {
                    that.updateVariantDataError.color = "Color is required";
                }
                if (that.updateVariantData.mc == "") {
                    that.updateVariantDataError.mc = "Manufacturing Cost is required";
                }
                if(!$.isNumeric($("#mc1").val())){
                    that.updateVariantDataError.mc = "Please Enter numeric value";
                }
                // if (that.addVariantData.sellingPrice == "") {
                //     that.addVariantDataError.sellingPrice = "Selling Price is required";
                // }
                if (that.updateVariantData.discount == "") {
                    that.updateVariantDataError.discount = "Discount is required";
                }
                if(!$.isNumeric($("#discount1").val())){
                    that.updateVariantDataError.discount = "Please Enter numeric value";
                }
                if (that.updateVariantData.shortDescription == "") {
                    that.updateVariantDataError.shortDescription = "Short Description is required";
                }
                if (that.updateVariantData.longDescription == "") {
                    that.updateVariantDataError.longDescription = "Long Description is required";
                }
                // if (!that.$refs.variant_images.files.length) {
                //     that.addVariantDataError.variantImages = "Variant Images Price Image is required";
                // }
            },
            updateVariant: function () {
                let that = this;
                let formData = new FormData();
                that.updateVariantData.disabled = true;
                axios.post(APP_URL + '/' + USER_TYPE + '/update-variant', that.updateVariantData).then((response) => {
                    response = response.data;
                    if (response.status == "success") {
                        that.updateVariantData.disabled = false;
                        that.$refs['update-variant-modal'].hide();

                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.getProductVariants(that.updateVariantData.productId);
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.updateVariantData.disabled = false;
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
