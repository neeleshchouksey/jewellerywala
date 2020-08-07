<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Commission</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-4 mb-3">
                            <input type="hidden" v-model="commission.id">
                            <input type="text" class="form-control" placeholder="Commission Amount(In %)"
                                   v-model="commission.commission">
                        </div>
                        <div class="col-md-4  mb-3">
                            <button class="btn btn-theme" v-on:click="updateCommission" :disabled="commission.disabled">Update</button>
                        </div>
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
                commission:{
                    "commission":0,
                    "id":0
                }
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllCommission();
        },
        methods: {
            getAllCommission: function () {
                let that = this;
                axios.get('get-all-commission').then(response => {
                    response =response.data;
                    that.commission.commission = response.res.commission;
                    that.commission.id = response.res.id;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            updateCommission: function () {
                let that = this;
                that.commission.disabled = true;
                axios.post('update-commission', that.commission).then((response) => {
                    that.commission.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.getAllCommission();
                    })

                }).catch((error) => {
                    that.commission.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
        },
    }
</script>
