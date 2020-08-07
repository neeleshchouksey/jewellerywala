<template>
    <div>
        <section class="wallet-section-one">
            <div class="container">
                <div class="wallet-sec1">
                    <div class="row">
                        <div class="col-3 col-sm-1">
                            <div class="wallet-image">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="col-9 col-sm-2 col-bod">
                            <div class="wallet-amunt1">
                                <span><i class="fas fa-rupee-sign"></i></span>
                                <span v-if="superCoinAmount">{{superCoinAmount}}</span>
                                <span v-if="!superCoinAmount">0</span>
                            </div>
                            <div class="wallet-amunt2">
                                <span>Cashback Money</span>

                            </div>

                        </div>
                        <div class="col-3 col-sm-1">
                            <div class="wallet-image">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="col-9 col-sm-2 col-bod">
                            <div class="wallet-amunt1">
                                <span><i class="fas fa-rupee-sign"></i></span>
                                <span v-if="walletAmount.wallet_amount">{{walletAmount.wallet_amount}}</span>
                                <span v-if="!walletAmount.wallet_amount">0</span>
                            </div>
                            <div class="wallet-amunt2">
                                <span>Your Wallet Balance</span>

                            </div>

                        </div>
                        <div class="col-sm-4">

                            <div class="row ne-promo">
                                <div class="col-sm-12">
                                    <div class="form-group ne-promo1">

                                        <input type="text" class="form-control" id="amt" v-model="walletData.amount"
                                               placeholder="Enter Amount to be Added in Wallet ">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="wallet-sec4">
                                <button class="btn-theme" :disabled="walletData.disabled" v-on:click="addMoneyToWallet">
                                    Add Money to Wallet
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <section class="wallet-section-tow">
            <div class="container">
                <div class="row">
                    <div class="hiraola-product-tab_area-3 new-walit">
                        <div class="product-tab">
                            <ul class="nav product-menu">
                                <li><a class="active" data-toggle="tab"
                                       href="#necklaces-1"><span>Wallet</span></a>
                                </li>
                                <li><a data-toggle="tab" href="#earrings-1"
                                       class=""><span>Wallet Payments History</span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content hiraola-tab_content">
                            <div id="necklaces-1" class="tab-pane active show" role="tabpanel">
                                <form action="javascript:void(0)">
                                    <h3>Wallet Payments</h3>

                                    <div class="table-content table-responsive" v-if="walletHistory.length">
                                        <table class="table wallit-tab">
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Total/Remaining Amount</th>
                                                <th>Status</th>
                                                <th>Comment</th>
                                            </tr>
                                            <tr v-for="wh in walletHistory">
                                                <td>{{wh.created_at | formatDate}}</td>
                                                <td><span v-if="wh.transaction_type==1">Deposit</span><span
                                                        v-if="wh.transaction_type==2">Withdraw</span></td>
                                                <td><i class="fa fa-rupee-sign"></i> {{wh.deposit_withdraw_amount}}</td>
                                                <td><i class="fa fa-rupee-sign"></i> {{wh.remaining_wallet_amount}}</td>
                                                <td>{{wh.payment_status}}</td>
                                                <td><span v-if="wh.transaction_type==1">Payment Id {{wh.payment_id}}</span>
                                                <span v-if="wh.transaction_type==2">Order Id {{wh.order_id}}</span></td>
                                            </tr>
                                        </table>

                                        <pagination :data="walletHistoryData"
                                                    @pagination-change-page="getWalletData"></pagination>

                                    </div>
                                    <div v-if="!walletHistory.length">No Data Found</div>
                                </form>
                            </div>
<!--                            <div id="earrings-1" class="tab-pane" role="tabpanel">-->
<!--                                <form action="javascript:void(0)">-->
<!--                                    <h3>Loyalty Walle</h3>-->
<!--                                    <h5 class="new-h5-walit"><i class="fas fa-rupee-sign"></i><span>1000</span></h5>-->
<!--                                </form>-->
<!--                            </div>-->

                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

</template>


<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Cart',
        data() {
            return {
                walletData: {
                    "amount": "",
                    "disabled": false
                },
                "orderId": ""
            }
        },
        mixins: [UserMixin],
        created() {
            let that = this;
            that.getData(4);
            that.getData(5);
            that.getWalletData();

        },
        methods: {
            getWalletData: function (page = 1) {
                let that = this;
                var url = APP_URL + "/" + '/get-data/6' + '?page=' + page
                axios.get(url).then(response => {
                    response = response.data;

                    that.walletHistory = response.res.data;
                    that.walletHistoryData = response.res;

                }).catch((error) => {
                    alert("error from 400");
                });
            },
            addMoneyToWallet: function () {
                let that = this;
                if (that.walletData.amount == "") {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please Enter Wallet Amount",
                        showConfirmButton: true
                    });
                } else {
                    axios.post(APP_URL + '/add-money-to-wallet', that.walletData).then(response => {
                            that.walletData.disabled = true;
                            that.orderId = response.data.orderId;

                            var options = {
                                "key": "rzp_test_49ge0fNwkPW8Zb", // Enter the Key ID generated from the Dashboard
                                "amount": that.walletData.amount * 100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise or INR 500.
                                "currency": "INR",
                                "name": that.walletAmount.first_name,
                                "description": "",
                                "image": that.logo,
                                "order_id": that.order_id,//This is a sample Order ID. Create an Order using Orders API. (https://razorpay.com/docs/payment-gateway/orders/integration/#step-1-create-an-order). Refer the Checkout form table given below
                                "handler": function (response) {
                                    // alert(response);
                                    that.walletPaymentStatus(response.razorpay_payment_id);
                                },
                                "prefill": {
                                    "name": that.walletAmount.first_name,
                                    "email": that.walletAmount.email,
                                    "contact": that.walletAmount.phone
                                },
                                "notes": {
                                    "address": "note value"
                                },
                                "theme": {
                                    "color": "#F37254"
                                }
                            };
                            var rzp1 = new Razorpay(options);
                            rzp1.open();

                        }
                    ).catch((error) => {
                        this.$swal({
                            type: "error",
                            title: "Error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
            walletPaymentStatus: function (payment_id) {
                let that = this;
                axios.post(APP_URL + '/wallet-payment-success', {
                    "paymentId": payment_id,
                    "order_id": that.orderId
                }).then(response => {
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: "Payment Completed Successfully",
                            showConfirmButton: true
                        }).then(function () {
                            that.walletData.amount = "";
                            that.walletData.disabled = false;
                            that.getData(5);
                            that.getWalletData();

                        });
                    }
                ).catch((error) => {

                });
            },
        }
    }
</script>
