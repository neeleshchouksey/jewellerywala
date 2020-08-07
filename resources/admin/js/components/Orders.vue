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
                        <label for="productname">Shop Name</label>
                        <input type="text" id="shopname" placeholder="Shop Name" class="form-control"
                               v-model="orderFilterData.shopName"/>
                    </div>
                    <div class="col-md-3">
                        <label for="productname">Order Id</label>
                        <input type="text" id="productid" placeholder="Order Id" class="form-control"
                               v-model="orderFilterData.productId"/>
                    </div>
                    <div class="col-md-3">
                        <label for="productname">Date Range</label>
                    <div class="input-group input-daterange">
                        <input type="text" class="form-control ls-datepicker" id="fromDate" v-model="orderFilterData.fromDate">
                        <div class="input-group-prepend input-group-append">
                            <span class="input-group-text">to</span>
                        </div>
                        <input type="text" class="form-control ls-datepicker" id="toDate" v-model="orderFilterData.toDate"></div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-theme search-mt" v-on:click="getOrders">
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
            <div class="card-header">
                <div class="card-actions row">
                    <div class="col-md-6 text-left">
                        <h5>Orders</h5>
                    </div>
                </div>
            </div>
            <div class="card-body" v-if="USER_TYPE=='admin'" style="overflow-x: auto;">
                <div>
                <table v-if="allOrders.length" id="cat-datatable"
                       class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>User</th>
                        <th>Seller</th>
                        <th>Shop</th>
                        <th>Date</th>
                        <th>Payment Status</th>
                        <th>Total(In <i class="fa fa-inr" aria-hidden="true"></i>)</th>
                        <th>Order Status</th>
                        <th>Delivery Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(o,index) in allOrders">
                        <td>{{o.order_id}}</td>
                        <td>{{o.first_name}} {{o.last_name}}</td>
                        <td><a :href="APP_URL+'/'+USER_TYPE+'/seller-profile/'+o.seller_id">{{o.s_f_name}} {{o.s_l_name}}</a></td>
                        <td>{{o.shop_name}}</td>
                        <td>{{o.created_at | formatDate}}</td>
                        <td>{{o.payment_status}}</td>
                        <td>{{o.price}}</td>
                        <td><span v-if="o.order_status==1">Cancelled</span><span v-if="o.order_status==0">Placed</span></td>
                        <td><span v-if="o.delivered_status==0">Confirmed</span>
                            <span v-if="o.delivered_status==1">Shipped</span>
                            <span v-if="o.delivered_status==2">Delivered</span>
                            <span v-if="o.delivered_status==3 && o.return_request_status==0" >Requested For Return</span>
                            <span v-if="o.delivered_status==3 && o.return_request_status==1" >Return request accepted</span>
                            <span v-if="o.delivered_status==3 && o.return_request_status==2" >Return request declined</span>
                        </td>
                        <td>
                            <button title="View Details" class="btn btn-theme" v-on:click="getOrderDetails(o.id)">
                                <i class="fa fa-eye"></i>
                            </button>
                            <a :href="APP_URL+'/'+USER_TYPE+'/invoice/'+o.id" title="Invoice" class="btn btn-theme">
                                <i class="fa fa-files-o" aria-hidden="true"></i>
                            </a>
                            <button title="Change to Shipped" v-if="o.order_status==0 && o.delivered_status==0" class="btn btn-theme" v-on:click="changeDeliveryStatus(o.id,1)">
                                <i class="fa fa-ship"></i>
                            </button>
                            <button title="Change to Delivered"  v-if="o.order_status==0 && o.delivered_status==1" class="btn btn-theme" v-on:click="changeDeliveryStatus(o.id,2)">
                                <i class="fa fa-truck" aria-hidden="true"></i>
                            </button>

                            <button v-if="o.order_status==0 && o.delivered_status==2" class="btn btn-theme mt-2">
                                Delivered
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <pagination :data="allOrdersData"
                            @pagination-change-page="getOrders"></pagination>
                <p v-if="!allOrders.length">No Data Found</p>
            </div>

            <div class="card-body" v-if="USER_TYPE=='seller'" style="overflow-x: auto;">
                <div class="table-responsive">
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
                        <th>Order Status</th>
                        <th>Delivery Status</th>
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
                        <td><span v-if="o.order_status==1">Cancelled</span><span v-if="o.order_status==0">Placed</span></td>
                        <td><span v-if="o.delivered_status==0">Confirmed</span>
                            <span v-if="o.delivered_status==1">Shipped</span>
                            <span v-if="o.delivered_status==2">Delivered</span>
                            <span v-if="o.delivered_status==3 && o.return_request_status==0" >Requested For Return</span>
                            <span v-if="o.delivered_status==3 && o.return_request_status==1" >Return request accepted</span>
                            <span v-if="o.delivered_status==3 && o.return_request_status==2" >Return request declined</span>
                        </td>
                        <td>
                            <button class="btn btn-theme" v-on:click="getOrderDetails(o.id)">
                                View Details
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
                <pagination :data="allOrdersData"
                            @pagination-change-page="getOrders"></pagination>
                <p v-if="!allOrders.length">No Data Found</p>
            </div>

        </div>

        <b-modal size="lg" id="order-details-modal" ref="order-details-modal" title="Order Details" hide-footer>
            <div>
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
            </div>
        </b-modal>


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
                allOrders: [],
                allOrdersData: {},
                orderFilterData: {
                    'userName': "",
                    'productId': "",
                    "fromDate": "",
                    "toDate": "",
                    "shopName":""
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
            that.getOrders();
        },
        methods: {
            resetFilter:function(){
                let that = this;
                that.orderFilterData.userName = '';
                that.orderFilterData.productId = '';
                that.orderFilterData.fromDate = '';
                that.orderFilterData.toDate = '';
                that.orderFilterData.shopName = '';
                $("#fromDate").val('');
                $("#toDate").val('');
                that.getOrders();
            },
            getOrders: function (page=1) {
                let that = this;
                that.orderFilterData.fromDate = $("#fromDate").val();
                that.orderFilterData.toDate = $("#toDate").val();
                axios.post(APP_URL + '/' + USER_TYPE + '/get-orders?page='+page, that.orderFilterData).then(response => {
                        response = response.data;
                        that.allOrders = response.res.data;
                        that.allOrdersData = response.res;
                    }
                ).catch((error) => {

                });
            },
            getOrderDetails: function (id) {
                let that = this;
                axios.post(APP_URL + '/' + USER_TYPE + '/get-order-details/'+id).then(response => {
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
