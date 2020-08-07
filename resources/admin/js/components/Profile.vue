<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        <div class="sec-in-pro1">
                            <h4>Personal Details</h4>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="avatar-container">
                                        <i v-if="USER_TYPE=='seller'"
                                           class="fa fa-pencil cursor-pointer edit-profile-img"
                                           v-on:click="selectFile"></i>
                                        <input type="file" id="profile-img" ref="profile_img" class="d-none"
                                               v-on:change="uploadImage">
                                        <img height="152px" width="265px" id="shop-img"
                                             :src="APP_URL+'/public/storage/shop-images/'+userData.logo"
                                             alt="Admin Avatar" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="detail-row"><span>First Name</span><span>{{userData.first_name}}</span>
                                    </div>
                                    <div class="detail-row"><span>Last Name</span><span>{{userData.last_name}}</span>
                                    </div>
                                    <div class="detail-row"><span>E-mail</span><span>{{userData.email}}</span></div>
                                    <div class="detail-row"><span>Mobile</span><span>{{userData.phone}}</span></div>
                                    <a v-if="USER_TYPE=='seller'" href="javascript:void(0);"
                                       class="btn btn-theme new-mod-btn-pr"
                                       data-toggle="modal" data-target=".ls-example-modal-lg">Update Profile</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="sec-in-pro2">
                                    <h4>Business Details</h4>
                                    <div class="detail-row"><span>Shop Name</span><span>{{userData.shop_name}}</span>
                                    </div>
                                    <div class="detail-row"><span>Shop Address</span><span>{{userData.address1}} {{userData.address2}} {{userData.city_name}} {{userData.state_name}} {{userData.pin_code}}</span>
                                    </div>
                                    <div class="detail-row"><span>GSTN</span><span>{{userData.gst_number}}</span></div>
                                    <div class="detail-row"><span>PAN</span><span>{{userData.pan_number}}</span></div>
                                    <div class="detail-row"><span>Gumasta</span><a target="_blank" :href="APP_URL+'/public/storage/gumasta-images/'+userData.gumasta">{{userData.gumasta}}</a></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="sec-in-pro2">
                                    <h4>Bank Details</h4>
                                    <div class="detail-row">
                                        <span>Bank Name</span><span>{{userData.bank_branch_name}}</span></div>
                                    <div class="detail-row"><span>AC / Number </span><span>{{userData.bank_account_number}}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>IFSC Code</span><span>{{userData.bank_ifsc_code}}</span></div>
                                    <div class="detail-row"><span>Bank Address</span><span>{{userData.bank_branch_address}}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="profile-update" class="modal fade ls-example-modal-lg" tabindex="-1" role="dialog"
             aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Personal Details</h5>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="f_name" placeholder="First Name"
                                       v-model="userData.first_name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="l_name" placeholder="Last Name"
                                       v-model="userData.last_name" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="email">E-mail</label>
                                <input readonly v-model="userData.email" type="text" id="email" name="email"
                                       placeholder="E-mail" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mobail">Phone</label>
                                <input readonly v-model="userData.phone" type="text" id="mobail" name="mobail"
                                       placeholder="Phone" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <h5>Business Details</h5>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="shop-name">Shop Name</label>
                                <input type="text" v-model="userData.shop_name" id="shop-name" name="shop_name"
                                       placeholder="Shop Name" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Gst">Gst</label>
                                <input type="text" id="Gst" v-model="userData.gst_number" name="gst"
                                       placeholder="Gst Number" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pan">Pan Number</label>
                                <input type="text" id="pan" name="pan" v-model="userData.pan_number"
                                       placeholder="Pan Number" class="form-control">
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="shop-address1">Shop Address Line-1</label>
                                <input type="text" id="shop-address1" v-model="userData.address1" name="shop_address1"
                                       placeholder="Shop Address Line-1"
                                       class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="shop-address2">Shop Address Line-2</label>
                                <input type="text" id="shop-address2" v-model="userData.address2" name="shop_address2"
                                       placeholder="Shop Address Line-2"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="billing_state">State</label>
                                <select name="billing_state" id="billing_state"
                                        class="form-control"
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
                            <div class="form-group col-md-4">
                                <label for="billing_city">City</label>
                                <select name="billing_city" id="billing_city"
                                        class="form-control"
                                        autocomplete="address-level1"
                                        data-placeholder="Select an option…" tabindex="-1"
                                        aria-hidden="true">
                                    <option value="">Select an option…</option>
                                    <option v-for="cd in cityData" :value="cd.id">{{cd.name}}
                                    </option>
                                </select>

                            </div>
                            <div class="form-group col-md-4">
                                <label for="pin">PIN</label>
                                <input type="text" id="pin" v-model="userData.pin_code" name="pin"
                                       placeholder="PIN"
                                       class="form-control"
                                       maxlength="6" @input="userData.pin_code = userData.pin_code.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Gumasta</label>
                                <input type="file" ref="gumasta" name="gumasta">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pin">Old Gumasta</label>
                                <div class="detail-row"><a target="_blank" :href="APP_URL+'/public/storage/gumasta-images/'+userData.gumasta">{{userData.gumasta}}</a></div>

                            </div>
                        </div>
                        <hr>
                        <h5>Bank Details</h5>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" id="bank_name" v-model="userData.bank_branch_name" name="bank_name"
                                       placeholder="Bank Name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="account_number">Account Number</label>
                                <input type="text" id="account_number" v-model="userData.bank_account_number"
                                       name="account_number" placeholder="Account Number" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="ifsc">IFSC Code</label>
                                <input type="text" id="ifsc" name="ifsc" v-model="userData.bank_ifsc_code"
                                       placeholder="IFSC Code" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="branch-address">Branch Address</label>
                                <input type="text" id="branch-address" v-model="userData.bank_branch_address"
                                       name="branch_address" placeholder="Branch Address" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-theme" v-on:click="updateUser"
                                :disabled="userData.disabled">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {
                userData: {
                    "first_name": "",
                    "last_name": "",
                    "email": "",
                    "phone": "",
                    "password": "",
                    "shop_name": "",
                    "address1": "",
                    "address2": "",
                    "state": "",
                    "city": "",
                    "pin_code": "",
                    "gst_number": "",
                    "pan_number": "",
                    "bank_branch_name": "",
                    "bank_branch_address": "",
                    "bank_account_number": "",
                    "bank_ifsc_code": "",
                    "disabled": false
                },
                user_id: ""
            }
        },
        created() {
            let that = this;
            that.getState();
            var url = document.URL.split('/')[document.URL.split('/').length - 1]
            if (!isNaN(url)) {
                that.user_id = url;
            } else {
                that.user_id = ""
            }
            that.getUser();

        },
        mixins: [AdminSellerMixin],
        methods: {
            uploadImage: function () {
                let that = this;
                var file = that.$refs.profile_img.files[0];
                let formData = new FormData();
                formData.append('image', file);
                axios.post(APP_URL + "/" + USER_TYPE + '/upload-image', formData).then((response) => {
                    response = response.data;
                    $("#shop-img").attr("src", APP_URL + '/public/storage/shop-images/' + response.image);
                    that.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.getUser();
                    })

                }).catch((error) => {
                    that.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            selectFile: function () {
                $("#profile-img").click();
            },
            getUser: function () {
                let that = this;
                let url1 = "";
                console.log(that.user_id);
                if (USER_TYPE == "admin") {
                    url1 = APP_URL + '/' + USER_TYPE + '/get-profile/' + that.user_id
                } else {
                    url1 = APP_URL + '/' + USER_TYPE + '/get-profile'
                }

                axios.get(url1).then(response => {
                        that.userData = response.data.res;
                        $("#billing_state").val(that.userData.state);
                        that.getCity();
                        setTimeout(function () {
                            $("#billing_city").val(that.userData.city);
                        }, 1000)

                    }
                ).catch((error) => {

                });
            },
            updateUser: function () {
                let that = this;
                var gstVal = that.userData.gst_number;
                var reggst = /^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$/;

                var panVal = that.userData.pan_number;
                var regpan = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;

                var ifscVal = that.userData.bank_ifsc_code;
                var regifsc = /^[A-Za-z]{4}\d{7}$/;


                if (that.userData.gst_number != "" && !reggst.test(gstVal)) {
                    that.$swal({
                        type: "error",
                        title: "error",
                        text: "Please enter valid gst number",
                        showConfirmButton: true
                    });
                } else if (that.userData.pan_number != "" && !regpan.test(panVal)) {
                    that.$swal({
                        type: "error",
                        title: "error",
                        text: "Please enter valid pan number",
                        showConfirmButton: true
                    });

                }else if (that.userData.bank_ifsc_code != "" && !regifsc.test(ifscVal)) {
                    that.$swal({
                        type: "error",
                        title: "error",
                        text: "Please enter valid ifsc code",
                        showConfirmButton: true
                    });

                } else {
                    that.userData.disabled = true;
                    that.userData.state = $("#billing_state").val();
                    that.userData.city = $("#billing_city").val();
                    let formData = new FormData();
                    var front_image = that.$refs.gumasta.files[0];
                    formData.append('gumasta', front_image);
                    formData.append('first_name', that.userData.first_name);
                    formData.append('last_name', that.userData.last_name);
                    formData.append('shop_name', that.userData.shop_name);
                    formData.append('address1', that.userData.address1);
                    formData.append('address2', that.userData.address2);
                    formData.append('city', that.userData.city);
                    formData.append('state', that.userData.state);
                    formData.append('gst_number', that.userData.gst_number);
                    formData.append('pan_number', that.userData.pan_number);
                    formData.append('bank_branch_name', that.userData.bank_branch_name);
                    formData.append('bank_branch_address', that.userData.bank_branch_address);
                    formData.append('bank_account_number', that.userData.bank_account_number);
                    formData.append('bank_ifsc_code', that.userData.bank_ifsc_code);

                    axios.post(APP_URL + '/' + USER_TYPE + '/update-profile', formData).then(response => {
                            that.userData.disabled = false;
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Details Updated Successfully",
                                showConfirmButton: true
                            }).then(function () {
                                $("#profile-update").modal("hide");
                                that.getUser();
                            });
                        }
                    ).catch((error) => {

                    });
                }
            },
        },
    }
</script>
