<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Purity</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-purity-modal
                                        v-on:click="getCategories">
                                    <b> Add Purity</b>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table v-if="allPurity.length" id="users-datatable"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Category Name</th>
                                <th>Purity Name</th>
                                <th>Price(In <span class="fa fa-rupee"></span> / Gram )</th>
                                <th>Commission(in %)</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in allPurity">
                                <td>{{index+1}}</td>
                                <td>{{hs.category_name}}</td>
                                <td>{{hs.purity_name}}</td>
                                <td>{{hs.price}}</td>
                                <td>{{hs.commission}}</td>
                                <td>
                                    <button class="btn btn-theme" @click="getCategories"
                                            v-on:click="getSinglePurity(hs.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,3)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="allPurityData"
                                    @pagination-change-page="getAllPurities"></pagination>
                        <p v-if="!allPurity.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>


        <b-modal id="add-purity-modal" ref="add-purity-modal" title="Add Purity" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <b-form-select id="main-category" class="col-md-8"
                                   :plain="false"
                                   :options="categories" v-model="addPurityData.category"
                    >
                        <template v-slot:first>
                            <option value="" selected>Select Category</option>
                        </template>
                    </b-form-select>
                </div>
                <div class="row mb-3">

                    <label class="col-md-4 text-left">Purity:</label>

                    <input type="text" class="form-control col-md-8" placeholder="Enter Name"
                           v-model="addPurityData.purity">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Price(Per gram):</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Price"
                           v-model="addPurityData.price"
                           @input="addPurityData.price = addPurityData.price.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Commission:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Commission"
                           v-model="addPurityData.commission"
                           @input="addPurityData.commission = addPurityData.commission.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="addPurity" :disabled="addPurityData.disabled">Add</button>
                </div>
            </div>
        </b-modal>


        <b-modal ref="update-purity-modal" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <b-form-select id="main-category" class="col-md-8"
                                   :plain="false"
                                   :options="categories" v-model="singlePurity.category">
                    </b-form-select>
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Purity:</label>
                    <input type="text" class="form-control col-md-8" placeholder="Enter Name"
                           v-model="singlePurity.purity">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Price(Per gram):</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Price"
                           v-model="singlePurity.price"
                           @input="singlePurity.price = singlePurity.price.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Commission:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Commission"
                           v-model="singlePurity.commission"
                           @input="singlePurity.commission = singlePurity.commission.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">
                </div>
                <div class="row col-md-12 mb-3">
                    <button type="button" class="btn btn-theme text-right" :disabled="singlePurity.disabled"
                            v-on:click="updatePurity">Update
                    </button>
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
                singlePurity: {
                    "category": "",
                    "purity": "",
                    "id": "",
                    "price": "",
                    "commission":"",
                    "disabled": false
                },
                addPurityData: {
                    "category": "",
                    "purity": "",
                    "price": "",
                    "commission":"",
                    "disabled": false
                },
                categories: [],

            }
        },
        created() {
            let that = this;
            that.getAllPurities();
        },
        mixins: [AdminSellerMixin],
        methods: {

            updatePurity: function () {
                let that = this;
                that.singlePurity.disabled = true;
                axios.post('update-purity', that.singlePurity).then((response) => {
                    that.singlePurity.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-purity-modal'].hide();
                        that.getAllPurities();
                    })

                }).catch((error) => {
                    that.singlePurity.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSinglePurity: function (id) {
                this.$refs['update-purity-modal'].show();
                axios.get('get-single-purity/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singlePurity.category = response.res.category_id;
                    that.singlePurity.purity = response.res.purity_name;
                    that.singlePurity.price = response.res.price;
                    that.singlePurity.commission = response.res.commission;
                    that.singlePurity.id = response.res.id;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            addPurity: function () {
                let that = this;
                that.addPurityData.disabled = true;
                axios.post('add-purity', that.addPurityData).then((response) => {
                    that.addPurityData.disabled = false;
                    response = response.data;
                    that.$refs['add-purity-modal'].hide();
                    if (response.status == "success") {
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addPurityData.purity = "";
                            that.addPurityData.category = "";
                            that.getAllPurities();
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addPurityData.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getPurity: function (id) {
                let that = this;
                axios.get(APP_URL + "/" + USER_TYPE + '/get-purity').then(response => {
                    response = response.data;
                    that.purity = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
        },
    }
</script>
