<template>
    <div class="container">

        <div class="row">

            <div class="col-lg-3">

                <ul class="nav myaccount-tab-trigger" id="account-page-tab" role="tablist">

                    <li class="nav-item">

                        <a class="nav-link active" id="account-dashboard-tab" data-toggle="tab"
                           href="#account-dashboard" role="tab" aria-controls="account-dashboard" aria-selected="true">Dashboard</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" id="account-address-tab" data-toggle="tab" href="#account-address"
                           role="tab" aria-controls="account-address" aria-selected="false">Addresses</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" id="account-details-tab" data-toggle="tab" href="#account-details"
                           role="tab" aria-controls="account-details" aria-selected="false">Account Details</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" id="cashback" data-toggle="modal" data-target="#payment"
                           aria-controls="account-details" aria-selected="false"
                           v-on:click="getCashbackOrders">Cashback</a>

                    </li>

                </ul>

            </div>

            <div class="col-lg-9">

                <div class="tab-content myaccount-tab-content" id="account-page-tab-content">

                    <div class="tab-pane fade show active" id="account-dashboard" role="tabpanel"
                         aria-labelledby="account-dashboard-tab">

                        <div class="myaccount-dashboard">

                            <p>Hello <b>{{userData.first_name}} {{userData.last_name}}</b> (not {{userData.first_name}}
                                {{userData.last_name}}? <a :href="APP_URL+'/logout'">Sign

                                    out</a>)</p>

                            <p>From your account dashboard you can view your recent orders, manage your shipping and

                                billing addresses and <a href="javascript:void(0)">edit your password and account
                                    details</a>.</p>

                        </div>

                        <div class="myaccount-orders mt-5">
                            <h4 class="small-title mt-5">MY ORDERS</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" v-if="orders.length">
                                    <tbody>
                                    <tr>
                                        <th>ORDER ID</th>
                                        <th>DATE</th>
                                        <th>STATUS</th>
                                        <th>TOTAL(In <i class="fa fa-rupee-sign"></i>)</th>
                                        <th>Order Status</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr v-for="od in orders">
                                        <td>#{{od.order_id}}</td>
                                        <td>{{od.created_at | formatDate}}</td>
                                        <td>{{od.payment_status}}</td>
                                        <td><i class="fa fa-rupee-sign"></i> {{od.final_amount}}</td>
                                        <td><span v-if="od.delivered_status==0">Confirmed</span>
                                            <span v-if="od.delivered_status==1">Shipped</span>
                                            <span v-if="od.delivered_status==2">Delivered on {{od.deliver_date | formatTime}}</span>
                                            <span v-if="od.delivered_status==3 && od.return_request_status==0">Requested for return</span>
                                            <span v-if="od.delivered_status==3 && od.return_request_status==1">Return request accepted</span>
                                            <span v-if="od.delivered_status==2 && od.return_request_status==2">Return request declined</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-theme" v-on:click="getOrderDetails(od.id)"
                                                    title="View Order Details"><i class="fa fa-eye"></i></button>
                                            <a :href="APP_URL+'/invoice/'+od.id" class="btn btn-theme"
                                               title="View Invoice"><i class="fas fa-file-invoice"></i></a>
                                            <button class="btn btn-theme"
                                                    v-if="od.order_status==0 && od.delivered_status!=2 && od.delivered_status!=3"
                                                    v-on:click="cancelOrder(od.id)" title="Cancel Order"><i
                                                    class="fa fa-times" aria-hidden="true"></i></button>
                                            <button class="btn btn-theme"
                                                    v-if="od.order_status==1 && od.delivered_status!=2">Cancelled
                                            </button>
                                            <button class="btn btn-theme"
                                                    v-if="od.order_status==0 && od.delivered_status==2"
                                                    data-toggle="modal" data-target="#returnModal"
                                                    v-on:click="openModal(od.id)" title="Return"><i class="fa fa-undo"
                                                                                                    aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-theme"
                                                    v-if="od.order_status==0 && od.delivered_status==3 && od.return_request_status==0">
                                                Requested For Return
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <pagination class="mt-3" :data="orderData"
                                                @pagination-change-page="getOrders"></pagination>

                                </table>
                                <div class="row" v-if="!orders.length"><p>No Data Found</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="account-address" role="tabpanel"
                         aria-labelledby="account-address-tab">

                        <div class="myaccount-address">

                            <p>The following addresses will be used on the checkout page by default.</p>

                            <div class="row">

                                <div class="col-lg-12 col-12">

                                    <form action="javascript:void(0)">

                                        <div class="checkbox-form">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    <div class="checkout-form-list">

                                                        <label>Address <span class="required">*</span></label>

                                                        <input placeholder="Street address" type="text"
                                                               v-model="addressData.address1">

                                                    </div>

                                                </div>

                                                <div class="col-md-12">

                                                    <div class="checkout-form-list">

                                                        <input placeholder="Apartment, suite, unit etc. (optional)"
                                                               type="text"
                                                               v-model="addressData.address2">

                                                    </div>

                                                </div>

                                                <div class="col-md-12">

                                                    <div class="checkout-form-list">

                                                        <label>State / County <span class="required">*</span></label>
                                                        <select name="billing_state" id="billing_state"
                                                                class="form-control unicase-form-control state_select select2-hidden-accessible"
                                                                autocomplete="address-level1"
                                                                data-placeholder="Select an option…" tabindex="-1"
                                                                aria-hidden="true"
                                                                v-on:change="getCity('')">
                                                            <option value="">Select an option…</option>
                                                            <option v-for="sd in stateData" :value="sd.id" selected>
                                                                {{sd.name}}
                                                            </option>
                                                        </select>


                                                    </div>

                                                </div>

                                                <div class="col-md-12">

                                                    <div class="checkout-form-list">

                                                        <label>Town / City <span class="required">*</span></label>

                                                        <select name="billing_city" id="billing_city"
                                                                class="form-control unicase-form-control state_select select2-hidden-accessible"
                                                                autocomplete="address-level1"
                                                                data-placeholder="Select an option…" tabindex="-1"
                                                                aria-hidden="true">
                                                            <option value="">Select an option…</option>
                                                            <option v-for="cd in cityData" :value="cd.id">{{cd.name}}
                                                            </option>
                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="col-md-12">

                                                    <div class="checkout-form-list">

                                                        <label>Postcode / Zip <span class="required">*</span></label>

                                                        <input placeholder="" type="text"
                                                               v-model="addressData.pin_code">

                                                    </div>

                                                </div>

                                                <div class="single-input">

                                                    <button class="hiraola-btn hiraola-btn_dark" type="button"
                                                            v-on:click="updateAddress(1)"><span>SAVE

                                                    CHANGES</span></button>

                                                </div>

                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="account-details" role="tabpanel"
                         aria-labelledby="account-details-tab">

                        <div class="myaccount-details">

                            <form action="#" class="hiraola-form">

                                <div class="hiraola-form-inner">

                                    <div class="single-input single-input-half">

                                        <label for="account-details-firstname">First Name*</label>

                                        <input type="text" id="account-details-firstname" v-model="userData.first_name">

                                    </div>

                                    <div class="single-input single-input-half">

                                        <label for="account-details-lastname">Last Name*</label>

                                        <input type="text" id="account-details-lastname" v-model="userData.last_name">

                                    </div>

                                    <div class="single-input single-input-half">

                                        <label for="account-details-email">Email*</label>

                                        <input type="email" id="account-details-email" v-model="userData.email"
                                               readonly>

                                    </div>

                                    <div class="single-input single-input-half">

                                        <label for="account-details-oldpass">Current Password(leave blank to leave

                                            unchanged)</label>

                                        <input type="password" id="account-details-oldpass" v-model="userData.password">

                                    </div>


                                    <div class="single-input">

                                        <button class="hiraola-btn hiraola-btn_dark" type="button"
                                                v-on:click="updateUser"><span>SAVE

                                                    CHANGES</span></button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <div class="modal fade" id="payment" role="dialog">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header j-modal-header">
                        <h4 class="modal-title">You have earned <i class="fa fa-rupee-sign"></i> {{cashbackAmount}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body new-modal-body">
                        <div class="new-back"></div>
                        <div class="payment-24-a">
                            <div v-if="cashbackOrders.length==6">
                                <div class="payment-24-b"
                                     v-for="(ord,index) in cashbackOrders">
                                    <div class="new-border"><i class="fa fa-check-circle"></i></div>
                                    <h6><span v-if="index==0">{{index+1}}st</span>
                                        <span v-if="index==1">{{index+1}}nd</span>
                                        <span v-if="index==2">{{index+1}}rd</span>
                                        <span v-if="index==3">{{index+1}}th</span>
                                        <span v-if="index==4">{{index+1}}th</span>
                                        <span v-if="index==5">{{index+1}}th</span>
                                        <span>Payment</span>
                                        <span><i class="fa fa-rupee-sign"> {{ord.price}}</i></span></h6>
                                    <p><span>{{ord.created_at | formatDate}}</span></p>
                                    <p>Order Id {{ord.order_id}}</p>
                                    <a href="javascript:void(0);">You have earned <i class="fa fa-rupee-sign"></i>
                                        {{ord.received_super_coin_amount}} Cashback <i class="fa fa-chevron-right"></i></a>
                                </div>
                                <div class="new-pay" v-if="cashbackAmount">
                                    <a :href="APP_URL+'/shop'">Shop Now to Redeem</a>
                                </div>
                            </div>
                            <div v-if="cashbackOrders.length==5">
                                <div class="payment-24-b"
                                     v-for="(ord,index) in cashbackOrders">
                                    <div class="new-border"><i class="fa fa-check-circle"></i></div>
                                    <h6><span v-if="index==0">{{index+1}}st</span>
                                        <span v-if="index==1">{{index+1}}nd</span>
                                        <span v-if="index==2">{{index+1}}rd</span>
                                        <span v-if="index==3">{{index+1}}th</span>
                                        <span v-if="index==4">{{index+1}}th</span>
                                        <span>Payment</span>
                                        <span><i class="fa fa-rupee-sign"> {{ord.price}}</i></span></h6>
                                    <p><span>{{ord.created_at | formatDate}}</span></p>
                                    <p>Order Id {{ord.order_id}}</p>
                                    <a href="javascript:void(0);">You have earned <i class="fa fa-rupee-sign"></i>
                                        {{ord.received_super_coin_amount}} Cashback <i class="fa fa-chevron-right"></i></a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>6th</span> <span>Payment</span></h6>
                                    <div class="new-pay">
                                        <a :href="APP_URL+'/shop'">Shop Now</a>
                                    </div>
                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                            </div>
                            <div v-if="cashbackOrders.length==4">
                                <div class="payment-24-b"
                                     v-for="(ord,index) in cashbackOrders">
                                    <div class="new-border"><i class="fa fa-check-circle"></i></div>
                                    <h6><span v-if="index==0">{{index+1}}st</span>
                                        <span v-if="index==1">{{index+1}}nd</span>
                                        <span v-if="index==2">{{index+1}}rd</span>
                                        <span v-if="index==3">{{index+1}}th</span>
                                        <span>Payment</span>
                                        <span><i class="fa fa-rupee-sign"> {{ord.price}}</i></span></h6>
                                    <p><span>{{ord.created_at | formatDate}}</span></p>
                                    <p>Order Id {{ord.order_id}}</p>
                                    <a href="javascript:void(0);">You have earned <i class="fa fa-rupee-sign"></i>
                                        {{ord.received_super_coin_amount}} Cashback <i class="fa fa-chevron-right"></i></a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>5th</span> <span>Payment</span></h6>
                                    <div class="new-pay">
                                        <a :href="APP_URL+'/shop'">Shop Now</a>
                                    </div>
                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>6th</span> <span>Payment</span></h6>
                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                            </div>
                            <div v-if="cashbackOrders.length==3">
                                <div class="payment-24-b" v-for="(ord,index) in cashbackOrders">
                                    <div class="new-border"><i class="fa fa-check-circle"></i></div>
                                    <h6><span v-if="index==0">{{index+1}}st</span>
                                        <span v-if="index==1">{{index+1}}nd</span>
                                        <span v-if="index==2">{{index+1}}rd</span>
                                        <span>Payment</span>
                                        <span><i class="fa fa-rupee-sign"> {{ord.price}}</i></span></h6>
                                    <p><span>{{ord.created_at | formatDate}}</span></p>
                                    <p>Order Id {{ord.order_id}}</p>
                                    <a href="javascript:void(0);">You have earned <i class="fa fa-rupee-sign"></i>
                                        {{ord.received_super_coin_amount}} Cashback <i class="fa fa-chevron-right"></i></a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>4th</span> <span>Payment</span></h6>
                                    <div class="new-pay">
                                        <a :href="APP_URL+'/shop'">Shop Now</a>
                                    </div>
                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>5th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>6th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                            </div>
                            <div v-if="cashbackOrders.length==2">
                                <div class="payment-24-b" v-for="(ord,index) in cashbackOrders">
                                    <div class="new-border"><i class="fa fa-check-circle"></i></div>
                                    <h6><span v-if="index==0">{{index+1}}st</span>
                                        <span v-if="index==1">{{index+1}}nd</span>
                                        <span>Payment</span>
                                        <span><i class="fa fa-rupee-sign"> {{ord.price}}</i></span></h6>
                                    <p><span>{{ord.created_at | formatDate}}</span></p>
                                    <p>Order Id {{ord.order_id}}</p>
                                    <a href="javascript:void(0);">You have earned <i class="fa fa-rupee-sign"></i>
                                        {{ord.received_super_coin_amount}} Cashback <i class="fa fa-chevron-right"></i></a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>3rd</span> <span>Payment</span></h6>
                                    <div class="new-pay">
                                        <a :href="APP_URL+'/shop'">Shop Now</a>
                                    </div>
                                    <a href="javascript:void(0);" class="active">Get <i class="fa fa-inr"></i> Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>4th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get <i class="fa fa-inr"></i> 150 Bus
                                        Voucher</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>5th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get <i class="fa fa-inr"></i> 150 Bus
                                        Voucher</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>6th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get <i class="fa fa-inr"></i> 150 Bus
                                        Voucher</a>
                                </div>
                            </div>
                            <div v-if="cashbackOrders.length==1">
                                <div class="payment-24-b" v-for="(ord,index) in cashbackOrders">
                                    <div class="new-border"><i class="fa fa-check-circle"></i></div>
                                    <h6><span v-if="index==0">{{index+1}}st</span>
                                        <span>Payment</span></h6>
                                    <span><i class="fa fa-rupee-sign"> {{ord.price}}</i></span>
                                    <p><span>{{ord.created_at | formatDate}}</span></p>
                                    <p>Order Id {{ord.order_id}}</p>
                                    <a href="javascript:void(0);">You have earned <i class="fa fa-rupee-sign"></i>
                                        {{ord.received_super_coin_amount}} Cashback <i class="fa fa-chevron-right"></i></a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>2nd</span> <span>Payment</span></h6>
                                    <div class="new-pay">
                                        <a :href="APP_URL+'/shop'">Shop Now</a>
                                    </div>
                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>

                                    <h6><span>4th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>

                                    <h6><span>4th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>5th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>6th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get
                                        Cashback</a>
                                </div>
                            </div>
                            <div v-if="!cashbackOrders.length">
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>1st</span> <span>Payment</span></h6>
                                    <div class="new-pay">
                                        <a :href="APP_URL+'/shop'">Shop Now</a>
                                    </div>
                                    <a href="javascript:void(0);" class="active">Get Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>2nd</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get Cashback</a>
                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>3rd</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get Cashback</a>

                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>4th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get Cashback</a>

                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>5th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get Cashback</a>

                                </div>
                                <div class="payment-24-b">
                                    <div class="new-border"></div>
                                    <h6><span>6th</span> <span>Payment</span></h6>

                                    <a href="javascript:void(0);" class="active">Get Cashback</a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="returnModal" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Request for Return Product</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Note: You can request for return within 7 days of your delivery date</h5>

                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <textarea class="form-control" v-model="returnProduct.reason"
                                          placeholder="Write a reason"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <button class="btn btn-theme" :disabled="returnProduct.disabled" v-on:click="returnOrder">
                                Request
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <b-modal size="lg" id="order-details-modal" ref="order-details-modal" title="Order Details" hide-footer>
            <div class="table-responsive">
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
                        <td><img height="50px" width="50px"
                                 :src="APP_URL+'/public/storage/product-images/'+o.front_image"/></td>
                        <td>{{o.product_name}}</td>
                        <td>{{o.quantity}}</td>
                        <td><i class="fa fa-rupee-sign"></i>{{o.unit_price}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'MyAccount',
        data() {
            return {
                userData: {
                    "first_name": "",
                    "last_name": "",
                    "email": "",
                    "phone": "",
                    "password": "",
                },
                addressData: {
                    "address1": "",
                    "address2": "",
                    "state": "",
                    "city": "",
                    "pin_code": "",
                },
                cityData: [],
                stateData: [],
                orderData: {},
                orders: [],
                cashbackOrders: [],
                cashbackAmount: 0,
                returnProduct: {
                    reason: "",
                    order_id: "",
                    disabled: false

                },
                singleOrderData: [],
                cancelOrderData: {
                    disabled: false
                }

            }
        },
        created() {
            let that = this;
            that.getUser();
            that.getState();
            setTimeout(function () {
                that.getOrders();
                that.getAddress();
            }, 500);

        },

        mixins: [UserMixin],
        methods: {
            getOrderDetails: function (id) {
                let that = this;
                axios.post(APP_URL + '/get-order-details/' + id).then(response => {
                        response = response.data;
                        that.$refs['order-details-modal'].show();
                        that.singleOrderData = response.res;
                    }
                ).catch((error) => {

                });
            },
            openModal: function (id) {
                console.log("ASFASDFGADSF");
                let that = this;
                that.returnProduct.order_id = id;
            },
            getUser: function () {
                let that = this;
                axios.get(APP_URL + '/get-user').then(response => {
                        that.userData = response.data.res;
                    }
                ).catch((error) => {

                });
            },
            updateUser: function () {
                let that = this;
                axios.post(APP_URL + '/update-user', that.userData).then(response => {
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: "Details Updated Successfully",
                            showConfirmButton: true
                        });
                    }
                ).catch((error) => {

                });
            },

            cancelOrder: function (id) {
                let that = this;
                that.$swal({
                    title: "Are you sure?",
                    text: "You want to cancel this order",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes",
                }).then(function (result) {
                    if (result.value) {
                        $(".loading").css("display", 'block');
                        that.cancelOrderData.disabled = true;
                        axios.get(APP_URL + '/cancel-order/' + id).then((response) => {
                            response = response.data;
                            if (response.status == "success") {
                                $(".loading").css("display", 'none');
                                that.$swal({
                                    type: "success",
                                    title: "Success",
                                    text: "Order Cancelled Successfully",
                                    showConfirmButton: true
                                }).then(function () {
                                    that.cancelOrderData.disabled = false;
                                    that.getOrders();
                                });
                            }
                        });
                    }
                })
            },
            returnOrder: function () {
                let that = this;
                if (that.returnProduct.reason == "") {
                    this.$swal({
                        type: "error",
                        title: "Error",
                        text: "Please enter reason",
                        showConfirmButton: true
                    })
                } else {
                    $(".loading").css("display", 'block');
                    that.returnProduct.disabled = true;
                    axios.post(APP_URL + '/return-order', that.returnProduct).then(response => {
                            $(".loading").css("display", 'none');
                            that.returnProduct.disabled = false;
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Successfully Requested for Return",
                                showConfirmButton: true
                            }).then(function () {
                                that.returnProduct.reason == ""
                                $("#returnModal").modal("hide");
                                that.getOrders();
                            });

                        }
                    ).catch((error) => {
                        that.returnProduct.disabled = false;
                    });
                }
            },

            getOrders: function (page = 1) {
                let that = this;
                axios.get(APP_URL + '/get-orders?page=' + page).then(response => {
                        response = response.data;
                        that.orders = response.res.data;
                        that.orderData = response.res;
                    }
                ).catch((error) => {

                });
            },
            getCashbackOrders: function () {
                let that = this;
                axios.get(APP_URL + '/get-cashback-orders').then(response => {
                        response = response.data;
                        that.cashbackOrders = response.res;
                        that.cashbackAmount = response.cashback;
                    }
                ).catch((error) => {

                });
            },

        }

    }
</script>
