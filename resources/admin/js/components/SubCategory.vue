<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Sub Categories</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-sub-modal v-on:click="getCategories">
                                    <b> Add Sub Category</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="allSubCategories.length" id="users-datatable"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Category Name</th>
                                <th>Sub Category Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="(hs,index) in allSubCategories">
                                <td>{{index+1}}</td>
                                <td>{{hs.category_name}}</td>
                                <td>{{hs.sub_category_name}}</td>
                                <td>
                                    <button class="btn btn-theme" @click="getCategories" v-on:click="getSingleSubCategory(hs.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,1)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="allSubData"
                                    @pagination-change-page="getAllSubCategories"></pagination>
                        <p v-if="!allSubCategories.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>

        <b-modal ref="update-sub-modal" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <b-form-select id="main-category" class="col-md-5"
                                   :plain="false"
                                   :options="categories" v-model="singleSubCategory.category">
                    </b-form-select>
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Sub Category:</label>
                    <input type="text" class="form-control col-md-8" placeholder="Enter Name"
                           v-model="singleSubCategory.subCategory">
                </div>
                <div class="row col-md-12 mb-3">
                    <button type="button" class="btn btn-theme text-right" :disabled="singleSubCategory.disabled" v-on:click="updateSubCategory">Update
                    </button>
                </div>
            </div>
        </b-modal>


        <b-modal id="add-sub-modal" ref="add-sub-modal" title="Add Category" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>

                    <b-form-select id="main-category" class="col-md-8"
                                   :plain="false"
                                   :options="categories" v-model="addSubCategories.category">
                        <template v-slot:first>
                            <option value="" selected>Select Category</option>
                        </template>
                    </b-form-select>
                </div>
                <div class="row mb-3">

                <label class="col-md-4 text-left">Sub Category:</label>

                    <input type="text" class="form-control col-md-8" placeholder="Enter Name"
                           v-model="addSubCategories.subCategory">
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="addSubCategory" :disabled="addSubCategories.disabled">Add</button>
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
                singleSubCategory: {
                    "category": "",
                    "subCategory": "",
                    "id": "",
                    "disabled":false
                },
                addSubCategories: {
                    "category": "",
                    "subCategory": "",
                    "disabled":false
                },
                categories: [],

            }
        },
        created() {
            let that = this;
           // that.getCategories();
            that.getAllSubCategories();
        },
        mixins: [AdminSellerMixin],
        methods: {
            updateSubCategory: function () {
                let that = this;
                that.singleSubCategory.disabled = true;
                axios.post('update-sub-category', that.singleSubCategory).then((response) => {
                    that.singleSubCategory.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-sub-modal'].hide();
                        that.getAllSubCategories();
                    })

                }).catch((error) => {
                    that.singleSubCategory.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSingleSubCategory: function (id) {
                this.$refs['update-sub-modal'].show();
                axios.get('get-single-sub-category/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singleSubCategory.category = response.res.category_id;
                    that.singleSubCategory.subCategory = response.res.sub_category_name;
                    that.singleSubCategory.id = response.res.id;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            addSubCategory: function () {
                let that = this;
                that.addSubCategories.disabled = true;
                axios.post('add-sub-category', that.addSubCategories).then((response) => {
                    response = response.data;
                    if (response.status == "success") {
                        that.addSubCategories.disabled = false;
                        that.$refs['add-sub-modal'].hide();

                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addSubCategories.subCategory = "";
                            that.addSubCategories.category = "";
                            that.getAllSubCategories();
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addSubCategories.disabled = false;
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
