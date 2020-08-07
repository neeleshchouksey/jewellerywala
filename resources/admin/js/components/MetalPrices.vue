<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Metal Prices</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body row">
                        <div v-for="mp in metalPrice" class="col-md-4 mb-3">
                            <label>{{mp.category_name}} {{mp.purity_name}}</label>
                            <input type="text" class="form-control" placeholder="Price"
                                   v-model="mp.price">
                        </div>
                    </div>
                    <div class="card-body row mt-0">
                        <div class="col-md-4">
                            <button class="btn btn-theme mt-18" v-on:click="updateMetalPrice" :disabled="disabled">Update</button>
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
                commission:{},
                metalPrice:[],
                disabled:false
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllMetalPrice();
        },
        methods: {
            getAllMetalPrice: function () {
                let that = this;
                axios.get(APP_URL+'/'+USER_TYPE+'/get-all-metal-price').then(response => {
                    response =response.data;
                    that.metalPrice = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },
            updateMetalPrice: function () {
                let that = this;
                that.disabled = true;
                axios.post(APP_URL+'/'+USER_TYPE+'/update-metal-price', that.metalPrice).then((response) => {
                    that.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.getAllMetalPrice();
                    })

                }).catch((error) => {
                    that.disabled = false;
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

