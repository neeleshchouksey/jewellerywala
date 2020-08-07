<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-actions row">
                    <div class="col-md-3">
                        <label for="productname">Name</label>
                        <input type="text" id="productname" placeholder="Name" class="form-control"
                               v-model="filterUserData.name"/>
                    </div>
                    <div class="col-md-3">
                        <label for="productname">Email</label>
                        <input type="text" id="productid" placeholder="Email" class="form-control"
                               v-model="filterUserData.email"/>
                    </div>
                    <div class="col-md-3">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" placeholder="Phone" class="form-control"
                               v-model="filterUserData.phone"/>
                    </div>
                    <div class="col-md-3">
                        <label for="date">Registered Date</label>
                        <input type="text" id="date" placeholder="Registered Date" class="form-control ls-datepicker"
                               />
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-theme search-mt" v-on:click="getAllUsers">
                            Search
                        </button>
                        <button class="btn btn-theme search-mt" v-on:click="resetFilter">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <div class="card-actions row">
                    <div class="col-md-6 p-2">
                        <b>Total Wallet Amount : <i class="fa fa-rupee w-0"></i>{{totalWalletAmount}}</b>
                    </div>
                    <div class="col-md-6 p-2">
                        <b>Total Cashback Money : <i class="fa fa-rupee w-0"></i>{{totalSuperCoinAmount}}</b>
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="card">-->
<!--            <div class="card-body">-->
                <table v-if="allUsers.length" id="cat-datatable"
                       class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Registered Date</th>
                        <th>Wallet Amount</th>
                        <th>Cashback Money</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(p,index) in allUsers">
                        <td>{{p.first_name}}</td>
                        <td>{{p.last_name}}</td>
                        <td>{{p.email}}</td>
                        <td>{{p.phone}}</td>
                        <td>{{p.created_at | formatDate}}</td>
                        <td>{{p.wallet_amount}}</td>
                        <td>{{p.super_coin_amount}}</td>
                        <td><span v-if="!p.deleted_at">
                                        <button class="btn btn-theme" v-on:click="deactivateActivateUser(p.id,0)">
                                        Deactivate
                                    </button>
                                    </span>
                            <span v-if="p.deleted_at">
                                        <button class="btn btn-theme" v-on:click="deactivateActivateUser(p.id,1)">
                                        Activate
                                    </button>
                                    </span>

                            <button class="btn btn-danger mt-2" v-on:click="deleteData(p.id,9)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <pagination :data="allUsersData"
                            @pagination-change-page="getAllUsers"></pagination>
                <p v-if="!allUsers.length">No Data Found</p>
<!--            </div>-->
<!--        </div>-->
    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {
                filterUserData: {
                    "name":"",
                    "email":"",
                    "phone":"",
                    "date":""
                },
                allUsers: [],
                allUsersData: {},
                totalSuperCoinAmount:0,
                totalWalletAmount:0,
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;

            that.getAllUsers();
        },
        methods: {
            resetFilter:function(){
                let that = this;
                that.filterUserData.name = '';
                that.filterUserData.email = '';
                that.filterUserData.phone = '';
                that.filterUserData.date = '';
                $("#date").val('');
                that.getAllUsers();
            },
            getAllUsers: function ( page = 1) {
                let that = this;
                var type = 2;
                that.filterUserData.date = $("#date").val();
                axios.post(APP_URL + "/" + USER_TYPE + '/get-all-users/' + type + '?page=' + page, that.filterUserData).then(response => {
                    response = response.data;
                    that.allUsers = response.res.data;
                    that.allUsersData = response.res;
                    that.totalWalletAmount = response.total_wallet_amt;
                    that.totalSuperCoinAmount = response.total_super_coin_amt;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            deactivateActivateUser: function (id, status) {
                let that = this;
                if (status == 1) {
                    var st = "Activate";
                } else {
                    var st = "Deactivate";
                }
                that.$swal({
                    title: "Verify?",
                    text: "Are you sure? You want to " + st + " this user",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, " + st + " it!",
                }).then(function (result) {
                    if (result.value) {
                        axios.post(APP_URL + '/' + USER_TYPE + '/activate-deactivate-user/' + id, {"status": status}).then((response) => {
                            response = response.data;
                            if (response.status == "success") {
                                that.$swal({
                                    type: "success",
                                    title: "Success",
                                    text: response.message,
                                    showConfirmButton: true
                                }).then(function () {
                                    that.getAllUsers();
                                });
                            }
                        });
                    }
                })
            },
        },
    }
</script>
