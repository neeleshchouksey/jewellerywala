<template>
    <div class="row">

        <div class="col-sm-12 col-md-12 col-xs-12 col-lg-6">

            <!-- Login Form s-->

            <div class="login-form">

                <h4 class="login-title">Login</h4>

                <div class="row">

                    <div class="col-md-12 col-12">

                        <label>Email Address*</label>

                        <input type="email" placeholder="Email Address" v-model="loginUser.email">

                    </div>

                    <div class="col-12 mb--20">

                        <label>Password</label>

                        <input type="password" placeholder="Password" v-model="loginUser.password" v-on:keyup.enter="login">

                    </div>

                    <div class="col-md-8">

                        <div class="check-box">

                            <input type="checkbox" id="remember_me">

                            <label for="remember_me">Remember me</label>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="forgotton-password_info">

                            <a href="#" data-toggle="modal" data-target="#forgetten_pass"> Forgot pasward?</a>

                        </div>

                    </div>

                    <div class="col-md-12">

                        <button class="hiraola-login_btn" v-on:click="login">Login</button>

                    </div>

                </div>

            </div>


        </div>

        <div class="col-sm-12 col-md-12 col-lg-6 col-xs-12">

            <form action="#">

                <div class="login-form">

                    <h4 class="login-title">Register</h4>

                    <div class="row">

                        <div class="col-md-6 col-12 mb--20">

                            <label>First Name</label>

                            <input type="text" placeholder="First Name" v-model="signupUser.firstName"
                                    v-on:focus="signupError.firstName=''">
                            <span class="color-red">{{signupError.firstName}}</span>
                        </div>

                        <div class="col-md-6 col-12 mb--20">

                            <label>Last Name</label>

                            <input type="text" placeholder="Last Name" v-model="signupUser.lastName"
                                   v-on:focus="signupError.lastName=''">
                            <span class="color-red">{{signupError.lastName}}</span>
                        </div>

                        <div class="col-md-12 col-12 mb--20">

                            <label>Phone</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Phone"
                                       aria-label="Recipient's username" aria-describedby="basic-addon2"
                                       maxlength="10"
                                       @input="signupUser.phone = signupUser.phone.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')"
                                       v-model="signupUser.phone"
                                       v-on:focus="signupError.phone=''"/>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" v-on:click="sendOtp">Send
                                        OTP
                                    </button>
                                </div>
                            </div>
                            <span class="color-red">{{signupError.phone}}</span>
                        </div>

                        <div class="col-md-6 col-12 mb--20">

                            <label>OTP</label>

                            <input type="text" placeholder="OTP" v-model="signupUser.otp"
                                   maxlength="6"
                                   @input="signupUser.phone = signupUser.phone.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')"
                                   v-on:focus="signupError.otp=''">
                            <span class="color-red">{{signupError.otp}}</span>
                        </div>


                        <div class="col-md-6">

                            <label>Email Address*</label>

                            <input type="email" placeholder="Email Address" v-model="signupUser.email"
                                   v-on:focus="signupError.email=''">
                            <span class="color-red">{{signupError.email}}</span>
                        </div>

                        <div class="col-md-6">

                            <label>Password</label>

                            <input type="password" placeholder="Password" v-model="signupUser.password"
                                   v-on:focus="signupError.password=''">
                            <span class="color-red">{{signupError.password}}</span>
                        </div>

                        <div class="col-md-6">

                            <label>Confirm Password</label>

                            <input type="password" placeholder="Confirm Password" v-model="signupUser.rpassword"
                                   v-on:focus="signupError.rpassword=''">
                            <span class="color-red">{{signupError.rpassword}}</span>
                        </div>

                        <div class="col-md-12" v-if="signupUser.userType==1">

                            <label>Shop Name</label>

                            <input type="text" placeholder="Shop Name" v-model="signupUser.shopName"
                                   v-on:focus="signupError.shopName=''">
                            <span class="color-red">{{signupError.shopName}}</span>
                        </div>

                        <div class="col-md-6" v-if="signupUser.userType==1">

                            <label>Address Line 1</label>

                            <textarea placeholder="Address Line 1" v-model="signupUser.address1"
                                    v-on:focus="signupError.address1=''"></textarea>
                            <span class="color-red">{{signupError.address1}}</span>
                        </div>

                        <div class="col-md-6" v-if="signupUser.userType==1">

                            <label>Address Line 2</label>

                            <textarea placeholder="Address Line 2" v-model="signupUser.address2"
                                     v-on:focus="signupError.address2=''"></textarea>
                            <span class="color-red">{{signupError.address2}}</span>
                        </div>

                        <div class="col-md-6" v-if="signupUser.userType==1">

                            <label>State</label>

                            <select name="billing_state" id="billing_state" v-on:focus="signupError.state=''"
                                    class="form-control" autocomplete="address-level1"
                                    data-placeholder="Select an option…" tabindex="-1" aria-hidden="true"
                                    v-on:change="getCity">
                                <option value="">Select an option…</option>
                                <option v-for="sd in stateData" :value="sd.id">{{sd.name}}</option>
                            </select>
                            <span class="color-red">{{signupError.state}}</span>
                        </div>
                        <div class="col-md-6" v-if="signupUser.userType==1">

                            <label>City</label>

                            <select name="billing_state" id="billing_city" v-on:focus="signupError.city=''"
                                    class="form-control unicase-form-control state_select select2-hidden-accessible"
                                    autocomplete="address-level1"
                                    data-placeholder="Select an option…" tabindex="-1" aria-hidden="true">
                                <option value="">Select an option…</option>
                                <option v-for="cd in cityData" :value="cd.id">{{cd.name}}</option>
                            </select>
                            <span class="color-red">{{signupError.city}}</span>
                        </div>

                        <div class="col-md-6" v-if="signupUser.userType==1">

                            <label>Pin Code</label>

                            <input type="text" placeholder="Pin Code" v-model="signupUser.pinCode"
                                   maxlength="6"
                                   @input="signupUser.pinCode = signupUser.pinCode.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1')"
                                   v-on:focus="signupError.pinCode=''">
                            <span class="color-red">{{signupError.pinCode}}</span>
                        </div>

                        <div class="col-md-6" v-if="signupUser.userType==1">

                            <label>Upload Gumasta</label>

                            <input type="file" ref="gumasta" class="p-1">
                            <span class="color-red">{{signupError.gumasta}}</span>
                        </div>


                        <div class="col-md-12">

                            <div class="check-box">

                                <input type="checkbox" id="terms">

                                <label for="terms" v-if="signupUser.userType==1"><a
                                        :href="APP_URL+'/terms-and-conditions-seller'">Terms and Conditions</a></label>
                                <label for="terms" v-if="signupUser.userType==2"><a
                                        :href="APP_URL+'/terms-and-conditions-buyer'">Terms and Conditions</a></label>

                            </div>
                            <span class="color-red">{{signupError.terms}}</span>

                        </div>


                        <div class="col-12">

                            <button class="hiraola-register_btn" type="button" v-on:click="validateForm()">Register
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

        <div id="forgetten_pass" class="modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Forgot Password</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 col-12">
                            <div class="form-group">
                                <input type="email" v-model="forgetPasswordData.email" class="form-control"
                                       placeholder="Enter Your E-mail Id">
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <button class="btn btn-theme" v-on:click="sendEmail"
                                    :disabled="forgetPasswordData.disabled">Submit
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>
</template>

<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Login',
        data() {
            return {
                loginUser: {
                    "email": "",
                    "password": "",
                    "userType": "",
                    "remember":false,
                    "disabled": false
                },
                forgetPasswordData: {
                    "email": "",
                    "disabled": false
                },
                signupUser: {
                    "firstName": "",
                    "phone": "",
                    "lastName": "",
                    "email": "",
                    "password": "",
                    "rpassword": "",
                    "userType": "",
                    "otp": "",
                    "shopName": "",
                    "address1": "",
                    "address2": "",
                    "city": "",
                    "state": "",
                    "pinCode": "",
                    "gumasta": "",
                    "disabled": false
                },
                signupError: {
                    "firstName": "",
                    "lastName": "",
                    "phone": "",
                    "email": "",
                    "password": "",
                    "rpassword": "",
                    "otp": "",
                    "shopName": "",
                    "address1": "",
                    "address2": "",
                    "city": "",
                    "state": "",
                    "pinCode": "",
                    "gumasta": "",
                    "terms": ""
                },

            }
        },
        mixins: [UserMixin],
        created() {
            let that = this;
            that.getState();



            var url = document.URL.split("/");
            console.log(url[url.length - 2]);
            if (url[url.length - 2] == "seller") {
                that.loginUser.userType = 1;
                that.signupUser.userType = 1;
                if(localStorage.getItem('userType')==1){
                    that.loginUser.email = localStorage.getItem('email');
                    that.loginUser.password = localStorage.getItem('password');
                }

            } else {
                that.loginUser.userType = 2;
                that.signupUser.userType = 2;

                if(localStorage.getItem('userType')==2){
                    that.loginUser.email = localStorage.getItem('email');
                    that.loginUser.password = localStorage.getItem('password');
                }
            }
        },
        methods: {
            sendEmail: function () {
                let that = this;
                if (that.forgetPasswordData.email == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Email",
                        showConfirmButton: true
                    });
                }
                axios.post(APP_URL + '/send-email', that.forgetPasswordData).then(response => {
                        that.forgetPasswordData.disabled = true;
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: "We have sent a link on your email, please reset your password using that link",
                            showConfirmButton: true
                        }).then(function () {
                            that.forgetPasswordData.email = "";
                            that.forgetPasswordData.disabled = false;
                        });
                    }
                ).catch((error) => {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            login: function () {
                let that = this;
                var cur_url = document.URL.split("?");
                cur_url = decodeURIComponent(cur_url[1]).split("url=");
                cur_url = cur_url[1];

                if($("#remember_me").is(":checked")){
                    that.loginUser.remember = true
                }else{
                    that.loginUser.remember = false
                }

                if (that.loginUser.email == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Email",
                        showConfirmButton: true
                    });
                } else if (that.loginUser.password == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Password",
                        showConfirmButton: true
                    });
                } else {
                    that.loginUser.disabled = true;
                    axios.post(APP_URL + '/login', that.loginUser).then(response => {

                        that.loginUser.disabled = false;
                            response = response.data;

                            if(that.loginUser.remember){
                                localStorage.setItem("email",that.loginUser.email);
                                localStorage.setItem("password",that.loginUser.password);
                                localStorage.setItem("userType",response.res.user_type);
                            }else{
                                localStorage.removeItem("email");
                                localStorage.removeItem("password");
                                localStorage.removeItem("userType");
                            }

                            if (cur_url) {
                                window.location = cur_url;
                            } else {
                                if (that.loginUser.userType == 1) {
                                    window.location = APP_URL + "/seller/dashboard";
                                } else {
                                    window.location = APP_URL;
                                }
                            }
                        }
                    ).catch((error) => {
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
            validateForm: function () {
                let that = this;
                if (!that.signupUser.firstName) {
                    that.signupError.firstName = "First Name is required"
                } else if (!isNaN(that.signupUser.firstName)) {
                    that.signupError.firstName = "First Name is not valid"
                } else if (!that.signupUser.lastName) {
                    that.signupError.lastName = "Last Name is required"
                } else if (!isNaN(that.signupUser.lastName)) {
                    that.signupError.lastName = "Last Name is not valid"
                } else if (!that.signupUser.phone) {
                    that.signupError.phone = "Phone is required"
                } else if (!that.signupUser.otp) {
                    that.signupError.otp = "OTP is required"
                } else if (!that.signupUser.email) {
                    that.signupError.email = "Email is required";
                } else if (!that.signupUser.password) {
                    that.signupError.password = "Password is required";
                } else if (!that.signupUser.rpassword) {
                    that.signupError.rpassword = "Confirm Password is required";
                } else if (that.signupUser.password != that.signupUser.rpassword) {
                    that.signupError.rpassword = "Password not matched";
                } else if (that.signupUser.userType == 1) {
                    if (!that.signupUser.shopName) {
                        that.signupError.shopName = "Shop Name is required";
                    } else if (!that.signupUser.address1) {
                        that.signupError.address1 = "Address Line 1 is required";
                    } else if (!that.signupUser.address2) {
                        that.signupError.address2 = "Address Line 2 is required";
                    } else if ($("#billing_state").val() == "") {
                        that.signupError.state = "State is required";
                    } else if ($("#billing_city").val() == "") {
                        that.signupError.city = "City is required";
                    } else if (!that.signupUser.pinCode) {
                        that.signupError.pinCode = "Pin Code is required";
                    } else if (isNaN(that.signupUser.pinCode)) {
                        that.signupError.pinCode = "Please Enter Valid Pin Code";
                    } else if (that.signupUser.pinCode.length != 6) {
                        that.signupError.pinCode = "Please Enter 6 digit Pin Code";
                    } else if (!that.$refs.gumasta.files.length) {
                        that.signupError.gumasta = "Gumasta is required";
                    } else {
                        that.validateEmail();
                    }
                } else {
                    that.validateEmail();
                }

            },
            validateEmail: function () {
                let that = this;
                if (!that.signupUser.email) {
                    that.signupError.email = "Email is required";
                } else {
                    axios.post(APP_URL + '/check-email', {
                        'email': that.signupUser.email,
                    }).then(response => {
                        that.signupError.email = "";
                        that.validateOtp();
                    }).catch((error) => {
                        that.signupError.email = error.response.data.message;
                    });
                }
            },
            sendOtp: function () {
                let that = this;
                if (!that.signupUser.phone) {
                    that.signupError.phone = "Phone is required";
                } else {
                    axios.post(APP_URL + '/send-otp', {
                        'phone': that.signupUser.phone,
                    }).then(response => {
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: "OTP Sent successfully",
                            showConfirmButton: true
                        });
                    }).catch((error) => {
                        that.signupError.phone = error.response.data.message;
                    });
                }
            },
            validateOtp: function () {
                let that = this;

                if (!that.signupUser.otp) {
                    that.signupError.otp = "OTP is required";
                } else {
                    axios.post(APP_URL + '/check-otp', {
                        'phone': that.signupUser.phone,
                        'otp': that.signupUser.otp,
                    }).then(response => {
                        that.signup();
                    }).catch((error) => {
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
            signup: function () {
                let that = this;
                if ($("#terms").prop('checked') == false) {
                    that.signupError.terms = "Please Check Terms and Conditions";
                } else {
                    var cur_url = document.URL.split("?");
                    cur_url = decodeURIComponent(cur_url[1]).split("=");
                    cur_url = cur_url[1];
                    let formData = "";
                    if (that.signupUser.userType == 1) {
                        formData = new FormData();
                        var front_image = that.$refs.gumasta.files[0];
                        that.signupUser.state = $("#billing_state").val();
                        that.signupUser.city = $("#billing_city").val();
                        formData.append('gumasta', front_image);
                        formData.append('firstName', that.signupUser.firstName);
                        formData.append('lastName', that.signupUser.lastName);
                        formData.append('phone', that.signupUser.phone);
                        formData.append('email', that.signupUser.email);
                        formData.append('userType', that.signupUser.userType);
                        formData.append('password', that.signupUser.password);
                        formData.append('rpassword', that.signupUser.rpassword);
                        formData.append('otp', that.signupUser.otp);
                        formData.append('shopName', that.signupUser.shopName);
                        formData.append('address1', that.signupUser.address1);
                        formData.append('address2', that.signupUser.address2);
                        formData.append('city', that.signupUser.city);
                        formData.append('state', that.signupUser.state);
                        formData.append('pinCode', that.signupUser.pinCode);
                        formData.append('terms', that.signupUser.terms);
                    } else {
                        formData = that.signupUser;
                    }
                    that.signupUser.disabled = true;
                    $(".loading").css("display", "block");
                    axios.post(APP_URL + '/signup', formData).then(response => {
                        $(".loading").css("display", "none");
                        that.signupUser.disabled = false;
                        response = response.data;
                        if (cur_url) {
                            window.location.href = cur_url;
                        } else {
                            if (that.signupUser.userType == 1) {
                                window.location = APP_URL + "/seller/dashboard";
                            } else {
                                window.location = APP_URL;
                            }
                        }
                    }).catch((error) => {
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });

                    });
                }
            },
        }

    }
</script>
