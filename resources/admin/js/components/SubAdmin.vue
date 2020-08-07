<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5> Sub Admin</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-cat-modal>
                                    <b> Add Sub Admin</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="subAdmin.length" id="cat-datatable"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in subAdmin">
                                <td>{{index+1}}</td>
                                <td>{{hs.first_name}} {{hs.last_name}}</td>
                                <td>{{hs.email}}</td>
                                <td>{{hs.phone}}</td>
                                <td><ul><li v-for="r in hs.roles">{{r}}</li></ul></td>
                                <td>
                                    <button class="btn btn-theme" v-on:click="getUpdateAccessRoles(hs.id);getSingleSubadmin(hs.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,15)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="subAdminData"
                                    @pagination-change-page="getSubAdmin"></pagination>
                        <p v-if="!subAdmin.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>
        <b-modal  size="lg" ref="update-cat-modal" title="Update Sub Admin" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-3 text-left">First Name:</label>
                    <input type="text" class="form-control  col-md-9" placeholder="Enter First Name"
                           v-model="singleSubadmin.firstName">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Last Name:</label>
                    <input type="text" class="form-control  col-md-9" placeholder="Enter Last Name"
                           v-model="singleSubadmin.lastName">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Email:</label>
                    <input readonly type="text" class="form-control  col-md-9" placeholder="Enter Email"
                           v-model="singleSubadmin.email">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Phone</label>
                    <input readonly type="text" class="form-control  col-md-9" placeholder="Enter Phone"
                           v-model="singleSubadmin.phone"
                           @input="singleSubadmin.phone = singleSubadmin.phone.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 text-left">Access Roles:</label>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6" v-for="(ar,index) in updateAccessRoles">
                                <div class="row">
                                    <input type="checkbox" class="form-control col-md-1" name="update_access_role"
                                           :value="ar.id" :key="index" :checked="ar.checked">
                                    <label class="col-md-10">{{ar.title}}</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
<!--                <div class="row mb-3">-->
<!--                    <label class="col-md-4 text-left">Password:</label>-->
<!--                    <input type="password" class="form-control  col-md-8" placeholder="Enter Password"-->
<!--                           v-model="singleSubadmin.password">-->
<!--                </div>-->

                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="updateSubadmin" :disabled="singleSubadmin.disabled">Add
                    </button>
                </div>
            </div>
        </b-modal>

        <b-modal size="lg" id="add-cat-modal" ref="add-cat-modal" title="Add Sub Admin" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-3 text-left">First Name:</label>
                    <input type="text" class="form-control  col-md-9" placeholder="Enter First Name"
                           v-model="addSubadmins.firstName">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Last Name:</label>
                    <input type="text" class="form-control  col-md-9" placeholder="Enter Last Name"
                           v-model="addSubadmins.lastName">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Email:</label>
                    <input type="text" class="form-control  col-md-9" placeholder="Enter Email"
                           v-model="addSubadmins.email">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Phone</label>
                    <input type="text" class="form-control  col-md-9" placeholder="Enter Phone"
                           v-model="addSubadmins.phone"
                           @input="addSubadmins.phone = addSubadmins.phone.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1')">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Password:</label>
                    <input type="password" class="form-control  col-md-9" placeholder="Enter Password"
                           v-model="addSubadmins.password">
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 text-left">Access Roles:</label>
                    <div class="col-md-9">
                        <div class="row">
                        <div class="col-md-6" v-for="(ar,index) in accessRoles">
                            <div class="row">
                            <input type="checkbox" class="form-control col-md-1" name="access_role"
                                  :value="ar.id" :key="index">
                            <label class="col-md-10">{{ar.title}}</label>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="addSubadmin" :disabled="addSubadmins.disabled">Add
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
                subAdmin: [],
                subAdminData: [],
                accessRoles: [],
                updateAccessRoles: [],
                categories: [],
                singleSubadmin: {
                    "firstName": "",
                    "lastName": "",
                    "email": "",
                    "phone": "",
                    "password": "",
                    "accessRole": [],
                    "disabled": false
                },
                addSubadmins: {
                    "firstName": "",
                    "lastName": "",
                    "email": "",
                    "phone": "",
                    "password": "",
                    "accessRole": [],
                    "disabled": false
                },
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAccessRoles();
            that.getSubAdmin();
        },
        methods: {
            getAccessRoles: function () {
                axios.get('get-access-roles').then(response => {
                    let that = this;
                    response = response.data;
                    that.accessRoles = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
             getUpdateAccessRoles: function (id) {
                axios.get('get-update-access-roles/'+id).then(response => {
                    let that = this;
                    response = response.data;
                    that.updateAccessRoles = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            getSubAdmin: function () {
                axios.get('get-all-sub-admin').then(response => {
                    let that = this;
                    response = response.data;
                    that.subAdminData = response.res;
                    that.subAdmin = response.res.data;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            updateSubadmin: function () {
                let that = this;
                that.singleSubadmin.accessRole = $("input[name='update_access_role']:checked") .map(function () {
                    return $(this).val();
                }).get();
                that.singleSubadmin.disabled = true;
                axios.post('update-sub-admin', that.singleSubadmin).then((response) => {
                    that.singleSubadmin.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-cat-modal'].hide();
                        that.getSubAdmin();
                    })

                }).catch((error) => {
                    that.singleSubadmin.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            addSubadmin: function () {
                let that = this;

                that.addSubadmins.accessRole = $("input[name='access_role']:checked") .map(function () {
                    return $(this).val();
                }).get();
                //that.addSubadmins.accessRole = access_role;
                that.addSubadmins.disabled = true;
                axios.post('add-sub-admin', that.addSubadmins).then((response) => {
                    that.addSubadmins.disabled = false;
                    response = response.data;
                    if (response.status == "success") {
                        that.$refs['add-cat-modal'].hide();
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addSubadmins.firstName = "";
                            that.addSubadmins.lastName = "";
                            that.addSubadmins.email = "";
                            that.addSubadmins.phone = "";
                            that.addSubadmins.password = "";
                            that.getSubAdmin();
                        })
                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addSubadmins.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSingleSubadmin: function (id) {
                this.$refs['update-cat-modal'].show();
                axios.get('get-single-sub-admin/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singleSubadmin.firstName = response.res.first_name;
                    that.singleSubadmin.lastName = response.res.last_name;
                    that.singleSubadmin.email = response.res.email;
                    that.singleSubadmin.phone = response.res.phone;
                    that.singleSubadmin.password = response.res.password;
                    that.singleSubadmin.id = response.res.id;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
        },
    }
</script>
