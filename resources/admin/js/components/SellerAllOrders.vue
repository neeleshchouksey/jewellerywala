<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-actions row">

                    <div class="col-md-3">
                        <label for="productname">User Name</label>
                        <input type="text" id="productname" placeholder="User Name" class="form-control"
                               v-model="orderFilterData.userName"/>
                    </div>
                    <div class="col-md-3">
                        <label for="productname">Order Id</label>
                        <input type="text" id="productid" placeholder="Order Id" class="form-control"
                               v-model="orderFilterData.productId"/>
                    </div>
                    <div class="col-md-4">
                        <label for="productname">Date Range</label>
                    <div class="input-group input-daterange">
                        <input type="text" class="form-control ls-datepicker" id="fromDate" v-model="orderFilterData.fromDate">
                        <div class="input-group-prepend input-group-append">
                            <span class="input-group-text">to</span>
                        </div>
                        <input type="text" class="form-control ls-datepicker" id="toDate" v-model="orderFilterData.toDate"></div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-theme mt-4" v-on:click="getOrders">
                            Search
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-actions row">
                    <div class="col-md-6 text-left">
                        <h5>Orders</h5>
                    </div>
                </div>
            </div>
        
            <div class="card-body" style="overflow-x: auto;">
                <table v-if="allOrders.length" id="cat-datatable1"
                       class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Order Total(In <i class="fa fa-inr" aria-hidden="true"></i>)</th>
                        <th>Order Commission(In <i class="fa fa-inr" aria-hidden="true"></i>)</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(o,index) in allOrders">
                        <td>{{o.order_id}}</td>
                        <td>{{o.first_name}} {{o.last_name}}</td>
                        <td>{{o.created_at | formatDate}}</td>
                        <td>{{o.payment_status}}</td>
                        <td>{{o.order_total}}</td>
                        <td>{{o.commission}}</td>
                        <td>
                            <button class="btn btn-theme" v-on:click="getOrderDetails(o.id)">
                                View Details
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <pagination :data="allOrdersData"
                            @pagination-change-page="getOrders"></pagination>
                <p v-if="!allOrders.length">No Data Found</p>
            </div>

        </div>

        <b-modal size="lg" id="order-details-modal" ref="order-details-modal" title="Order Details" hide-footer>
            <table v-if="singleOrderData.length" id="ord-datatable"
                   class="table table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(o,index) in singleOrderData">
                    <td><img height="50px" width="50px" :src="APP_URL+'/public/storage/product-images/'+o.front_image"/></td>
                    <td>{{o.product_name}}</td>
                    <td>{{o.quantity}}</td>
                    <td>{{o.unit_price}}</td>
                </tr>
                </tbody>
            </table>
        </b-modal>
    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {
                sellerId:0,
                allOrders: [],
                allOrdersData: {},
                orderFilterData: {
                    'userName': "",
                    'orderId': "",
                    "fromDate": "",
                    "toDate": ""
                },
                singleOrderData:[],
                shipStatusData:{
                    disabled:false
                },
                deliverStatusData:{
                    disabled:false
                }
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            var url = document.URL.split('/')[document.URL.split('/').length-1];
            if(!isNaN(url)){
                that.sellerId = url;
            }
            that.getOrders();
        },
        methods: {
            getOrders: function (page=1) {
                let that = this;
                that.orderFilterData.fromDate = $("#fromDate").val();
                that.orderFilterData.toDate = $("#toDate").val();
                axios.post(APP_URL + '/' + USER_TYPE + '/get-seller-orders/'+that.sellerId+'?page='+page, that.orderFilterData).then(response => {
                        response = response.data;
                        that.allOrders = response.res.data;
                        that.allOrdersData = response.res;
                    }
                ).catch((error) => {

                });
            },
            getOrderDetails: function (id) {
                let that = this;
                axios.post(APP_URL + '/' + USER_TYPE + '/get-order-details/'+id,{"sellerId":that.sellerId}).then(response => {
                        response = response.data;
                    that.$refs['order-details-modal'].show();
                        that.singleOrderData = response.res;
                    }
                ).catch((error) => {

                });
            },
            changeDeliveryStatus: function (id,status) {
                let that = this;
                if(status == 1){
                    var text = "You want to change status to Shipped"
                }else{
                    var text = "You want to change status to Delivered"
                }
                that.$swal({
                    title: "Are you sure?",
                    text: text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes",
                }).then(function (result) {
                    if(status == 1){
                        that.shipStatusData.disabled = true;
                    }else{
                        that.deliverStatusData.disabled = true;
                    }
                    if (result.value) {
                        $(".loading").css("display",'block');
                        axios.post(APP_URL+ '/' + USER_TYPE + '/change-delivery-status/' + id,{status:status}).then((response) => {
                            response = response.data;
                            $(".loading").css("display",'none');
                            if (response.status == "success") {
                                that.$swal({
                                    type: "success",
                                    title: "Success",
                                    text: "Order Status Changed Successfully",
                                    showConfirmButton: true
                                }).then(function () {
                                    if(status == 1){
                                        that.shipStatusData.disabled = false;
                                    }else{
                                        that.deliverStatusData.disabled = false;
                                    }
                                    that.getOrders();
                                });
                            }
                        });
                    }
                })
            },

        },
    }
</script>
