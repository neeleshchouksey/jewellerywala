<template>
    <div>

        <div class="card">
            <div class="card-header">
                Shipping Charges
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Shipping Charges</label>
                        <input type="text" placeholder="Shihpping Charges" class="form-control"
                               v-model="shippingData.charges">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Minimum Criteria</label>
                        <input type="text" placeholder="Minimum Criteria" class="form-control"
                               v-model="shippingData.criteria">
                    </div>
                </div>

                <button class="btn btn-theme" :disabled="shippingData.disabled" v-on:click="addShippingData">
                    Submit
                </button>
            </div>
        </div>

    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {

                shippingData: {
                    "id": "",
                    "charges": "",
                    "criteria": "",
                    "disabled": false
                },
                section1: [],
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getShippingData();

        },
        methods: {

            getShippingData: function () {
                let that = this;
                axios.get(APP_URL + "/" + USER_TYPE + '/get-shipping-data').then(response => {
                    response = response.data;
                    if(response.res){
                        that.shippingData.criteria = response.res.shipping_criteria;
                        that.shippingData.charges = response.res.shipping_charges;
                        that.shippingData.id = response.res.id;
                    }
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            addShippingData: function () {
                let that = this;

                if (that.shippingData.charges == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Shipping Charges field is required",
                        showConfirmButton: true
                    });
                } else if (that.shippingData.criteria == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Shipping criteria field is required",
                        showConfirmButton: true
                    });
                } else {
                    that.shippingData.disabled = true;
                    axios.post('add-shipping-data', that.shippingData).then((response) => {
                        response = response.data;
                        if (response.status == "success") {
                            that.shippingData.disabled = false;
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: response.message,
                                showConfirmButton: true
                            }).then(function () {

                                that.getShippingData();
                            })

                        } else {
                            alert(response.message);
                        }

                    }).catch((error) => {
                        that.shippingData.disabled = false;
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
        },
    }
</script>
