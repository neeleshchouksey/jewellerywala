<template>
    <main class="body-content site-main" id=content>
        <div id=primary class=body-content>
            <div class="container post-16 page type-page status-publish hentry" id=post-16 itemscope=itemscope
                 itemtype=http://schema.org/CreativeWork>
                <div class="entry-content entry-content" itemprop=text>
                    <div class=woocommerce>
                        <div class=woocommerce-notices-wrapper></div>
                        <div class="">
                            <div class=register-form>
                                <div class="container row" id=customer_login>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4 col-sm-4 col-sm-offset-4 sign-in">
                                        <div class="bac-in-fo text-center">
                                            <h4>Reset Password</h4>
                                            <form
                                                class="mt-5 woocomerce-form woocommerce-form-login login register-form outer-top-xs">
                                                <div
                                                    class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
                                                    <label for="npassword" class="info-title">Enter New Password&nbsp;<span class="required">*</span></label>
                                                    <input type="password"  name="email" id="npassword" value="" v-model="forgetData.npassword" autocomplete="fo-password" class="woocommerce-Input woocommerce-Input--text input-text form-control unicase-form-control">
                                                </div>
                                                <div
                                                    class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group">
                                                    <label for="cpassword" class="info-title">Enter New Password&nbsp;<span
                                                        class="required">*</span></label>
                                                    <input type="password" name="email" id="cpassword" value=""
                                                           v-model="forgetData.cpassword" autocomplete="fo-password"
                                                           class="woocommerce-Input woocommerce-Input--text input-text form-control unicase-form-control">
                                                </div>

                                                <div class="form-row">
                                                    <button v-on:click="resetpassword" type="button" name="login" class="btn btn-theme">
                                                        Reset Password
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</template>
<script>
    import UserMixin from "../mixins/UserMixin";

    export default {
        name: 'Product',
        data() {
            return {
                forgetData:{"npassword":"","cpassword":"","token":""}

            }
        },
        created() {
            let that = this;
            var url = document.URL.split('/')[document.URL.split('/').length - 1];
            console.log(url);
            that.forgetData.token = url;
        },
        mixins: [UserMixin],
        methods: {

            resetpassword: function () {
                let that = this;
                if(that.forgetData.npassword==""){
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter New Password",
                        showConfirmButton: true
                    });
                }else if(that.forgetData.cpassword==""){
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Please Enter Confirm Password",
                        showConfirmButton: true
                    });
                }else if(that.forgetData.npassword != that.forgetData.cpassword){
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Password not matched",
                        showConfirmButton: true
                    });
                }else {
                    axios.post(APP_URL + '/reset-password', that.forgetData).then(response => {
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: "Password Changed Successfully",
                                showConfirmButton: true
                            }).then(function () {
                                window.location = APP_URL;
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
                }
            },

        }

    }
</script>

