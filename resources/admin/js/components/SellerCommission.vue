<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-actions row">
                    <div class="col-md-3">
                        <label for="productname">Name</label>
                        <input type="text" id="productname" placeholder="Name" class="form-control"
                               v-model="filterSellerData.name"/>
                    </div>
                    <div class="col-md-3">
                        <label for="shopname">Shop Name</label>
                        <input type="text" id="shopname" placeholder="Shop Name" class="form-control"
                               v-model="filterSellerData.shopName"/>
                    </div>
                    <div class="col-md-3">
                        <label for="productname">Email</label>
                        <input type="text" id="productid" placeholder="Email" class="form-control"
                               v-model="filterSellerData.email"/>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" placeholder="Phone" class="form-control"
                               v-model="filterSellerData.phone"/>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label for="date">Registered Date</label>
                        <input type="text" id="date" placeholder="Registered Date" class="form-control ls-datepicker"
                        />
                    </div>

                    <div class="col-md-3 mt-2">
                        <button class="btn btn-theme search-mt" v-on:click="getAllUsers">
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
            <div class="card-body">
                <table v-if="allUsers.length" id="cat-datatable"
                       class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Shop Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Total Orders</th>
                        <th>Total Amount(In <span class="fa fa-rupee"></span>)</th>
                        <th>Total Commission(In <span class="fa fa-rupee"></span>)</th>
                        <th>Registered Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(p,index) in allUsers">
                        <td>{{p.first_name}}</td>
                        <td>{{p.last_name}}</td>
                        <td><a :href="APP_URL+'/'+USER_TYPE+'/seller-profile/'+p.id">{{p.shop_name}}</a></td>
                        <td>{{p.email}}</td>
                        <td>{{p.phone}}</td>
                        <td><a :href="APP_URL+'/'+USER_TYPE+'/seller-orders/'+p.id">{{p.total_orders}}</a></td>
                        <td>{{p.total_amount}}</td>
                        <th>{{p.commission}}</th>
                        <td>{{p.created_at | formatDate}}</td>
                    </tr>
                    </tbody>
                </table>
                <pagination :data="allUsersData"
                            @pagination-change-page="getAllUsers"></pagination>
                <p v-if="!allUsers.length">No Data Found</p>
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
                filterSellerData: {
                    "name":"",
                    "email":"",
                    "phone":"",
                    "date":"",
                    "shopName":""
                },
                allUsers: [],
                allUsersData: {},
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
                that.filterSellerData.name = '';
                that.filterSellerData.email = '';
                that.filterSellerData.phone = '';
                that.filterSellerData.shopName = '';
                that.filterSellerData.date = '';
                $("#date").val('');
                that.getAllUsers();
            },
            getAllUsers: function (page = 1) {
                let that = this;
                that.filterSellerData.date = $("#date").val();
                axios.post(APP_URL + "/" + USER_TYPE + '/get-all-sellers-commission' + '?page=' + page, that.filterSellerData).then(response => {
                    response = response.data;
                    that.allUsers = response.res.data;
                    that.allUsersData = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
        },
    }
</script>
