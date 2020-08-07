<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Categories</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-cat-modal>
                                    <b> Add Category</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="allCategories.length" id="cat-datatable"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Image</th>
                                <th>Category Name</th>
<!--                                <th>Commission(in %)</th>-->
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in allCategories">
                                <td>{{index+1}}</td>
                                <td>
                                    <img height="40" width="40" :src="APP_URL+'/public/storage/category-images/'+hs.image"/> </td>
                                <td>{{hs.category_name}}</td>
<!--                                <td>{{hs.commission}}</td>-->
                                <td>
                                    <button class="btn btn-theme" v-on:click="getSingleCategory(hs.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,2)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="allCategoriesData"
                                    @pagination-change-page="getAllCategories"></pagination>
                        <p v-if="!allCategories.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="update-cat-modal" title="Update Category" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <input type="text" class="form-control col-md-8" placeholder="Enter Name"
                           v-model="singleCategory.category">
                </div>
<!--                <div class="row mb-3">-->
<!--                    <label class="col-md-4 text-left">Commission:</label>-->
<!--                    <input type="text" class="form-control  col-md-8" placeholder="Enter Commission"-->
<!--                           v-model="singleCategory.commission"-->
<!--                           @input="singleCategory.commission = singleCategory.commission.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">-->
<!--                </div>-->
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Image</label>
                    <input type="file" ref="update_cat_image" class="col-md-3 pl-0">
                    <label class="col-md-3 text-left">Old Image</label>
                    <img class="col-md-2" height="40" width="40" :src="APP_URL+'/public/storage/category-images/'+singleCategory.image"/>
                </div>
                <div class="row col-md-12 mb-3">
                    <button type="button" class="btn btn-theme text-right" :disabled="singleCategory.disabled" v-on:click="updateCategory">Update
                    </button>
                </div>
            </div>
        </b-modal>

        <b-modal id="add-cat-modal" ref="add-cat-modal" title="Add Category" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Name"
                           v-model="addCategories.category">
                </div>
<!--               <div class="row mb-3">-->
<!--                    <label class="col-md-4 text-left">Commission:</label>-->
<!--                    <input type="text" class="form-control  col-md-8" placeholder="Enter Commission"-->
<!--                           v-model="addCategories.commission"-->
<!--                           @input="addCategories.commission = addCategories.commission.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">-->
<!--                </div>-->
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Image</label>
                    <input type="file" ref="cat_image" class="col-md-8 pl-0">
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="addCategory" :disabled="addCategories.disabled">Add</button>
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
                singleCategory: {
                    "category": "",
                    // "commission":"",
                    "id": "",
                    "image":"",
                    "disabled":false
                },
                addCategories: {
                    "category": "",
                    // "commission":"",
                    "disabled":false
                },
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllCategories();
        },
        methods: {
            updateCategory: function () {
                let that = this;
                that.singleCategory.disabled = true;

                let formData = new FormData();
                var image = that.$refs.update_cat_image.files[0];
                formData.append('image1', image);
                formData.append('category', that.singleCategory.category);
              //  formData.append('commission', that.singleCategory.commission);
                formData.append('id', that.singleCategory.id);


                axios.post('update-category', formData).then((response) => {
                    that.singleCategory.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-cat-modal'].hide();
                        that.getAllCategories();
                    })

                }).catch((error) => {
                    that.singleCategory.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            addCategory: function () {
                let that = this;
                that.addCategories.disabled = true;
                let formData = new FormData();
                var image = that.$refs.cat_image.files[0];
                formData.append('image1', image);
                formData.append('category', that.addCategories.category);
              //  formData.append('commission', that.addCategories.commission);
                axios.post('add-category', formData).then((response) => {
                    that.addCategories.disabled = false;
                    response = response.data;
                    if (response.status == "success") {
                        that.$refs['add-cat-modal'].hide();
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addCategories.category = "";
                            that.getAllCategories();
                        })
                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addCategories.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSingleCategory: function (id) {
                this.$refs['update-cat-modal'].show();
                axios.get('get-single-category/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singleCategory.category = response.res.category_name;
                 //   that.singleCategory.commission = response.res.commission;
                    that.singleCategory.id = response.res.id;
                    that.singleCategory.image = response.res.image;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
        },
    }
</script>
