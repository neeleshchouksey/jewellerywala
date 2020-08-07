<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Cashback Money</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-cat-modal>
                                    <b> Add Cashback Money</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="superCoin.length" id="cat-datatable"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Cashback Money</th>
                                <th>Min Shopping Amount</th>
                                <th>Max Shopping Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in superCoin">
                                <td>{{index+1}}</td>
                                <td>{{hs.super_coin_amount}}</td>
                                <td>{{hs.min_shopping_amount}}</td>
                                <td>{{hs.max_shopping_amount}}</td>
                                <td>
                                    <button class="btn btn-theme" v-on:click="getSingleSuperCoin(hs.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,12)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="superCoinData"
                                    @pagination-change-page="getAllSuperCoin"></pagination>
                        <p v-if="!superCoin.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="update-cat-modal" title="Update Cashback Money" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Cashback Money:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Coin Amount"
                           v-model="singleSuperCoin.coinAmount">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Minimum Shopping Amount:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Minimum Shopping Amount"
                           v-model="singleSuperCoin.minShoppingAmount">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Maximum Shopping Amount:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Maximum Shopping Amount"
                           v-model="singleSuperCoin.maxShoppingAmount">
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="updateSuperCoin" :disabled="singleSuperCoin.disabled">Update</button>
                </div>
            </div>
        </b-modal>

        <b-modal size="lg" id="add-cat-modal" ref="add-cat-modal" title="Add Cashback Money" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Cashback Money:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Coin Amount"
                           v-model="addSuperCoin.coinAmount">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Minimum Shopping Amount:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Minimum Shopping Amount"
                           v-model="addSuperCoin.minShoppingAmount">
                </div>
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Maximum Shopping Amount:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Maximum Shopping Amount"
                           v-model="addSuperCoin.maxShoppingAmount">
                </div>

                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="addSuperCoinFun" :disabled="addSuperCoin.disabled">Add</button>
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
                superCoin: [],
                superCoinData:{},
                singleSuperCoin: {
                    "coinAmount": "",
                    "minShoppingAmount":"",
                    "maxShoppingAmount":"",
                    "id": "",
                    "disabled":false
                },
                addSuperCoin: {
                    "coinAmount": "",
                    "minShoppingAmount":"",
                    "maxShoppingAmount":"",
                    "disabled":false
                },
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllSuperCoin();
        },
        methods: {
            updateSuperCoin: function () {
                let that = this;
                that.singleSuperCoin.disabled = true;
                axios.post('update-super-coin', that.singleSuperCoin).then((response) => {
                    that.singleSuperCoin.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-cat-modal'].hide();
                        that.getAllSuperCoin();
                    })

                }).catch((error) => {
                    that.singleSuperCoin.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            addSuperCoinFun: function () {
                let that = this;
                that.addSuperCoin.disabled = true;
                axios.post('add-super-coin', that.addSuperCoin).then((response) => {
                    that.addSuperCoin.disabled = false;
                    response = response.data;
                    if (response.status == "success") {
                        that.$refs['add-cat-modal'].hide();
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addSuperCoin.coinAmount = "";
                            that.addSuperCoin.minShoppingAmount = "";
                            that.addSuperCoin.maxShoppingAmount = "";
                            that.addSuperCoin.disabled = false
                            that.getAllSuperCoin();
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addSuperCoin.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSingleSuperCoin: function (id) {
                this.$refs['update-cat-modal'].show();
                axios.get('get-single-super-coin/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singleSuperCoin.coinAmount = response.res.super_coin_amount;
                    that.singleSuperCoin.minShoppingAmount = response.res.min_shopping_amount;
                    that.singleSuperCoin.maxShoppingAmount = response.res.max_shopping_amount;
                    that.singleSuperCoin.id = response.res.id;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            getAllSuperCoin: function (page=1) {
                axios.get('get-all-super-coin?page='+page).then(response => {
                    let that = this;
                    response = response.data;
                    that.superCoin = response.res.data;
                    that.superCoinData = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },

        },
    }
</script>
