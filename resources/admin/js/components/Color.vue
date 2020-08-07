<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Colors</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-color-modal>
                                    <b> Add Color</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="allColors.length"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Color Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in allColors">
                                <td>{{index+1}}</td>
                                <td>{{hs.color}}</td>
                                <td>
                                    <button class="btn btn-theme" v-on:click="getSingleColor(hs.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,5)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="allColorsData"
                                    @pagination-change-page="getAllColors"></pagination>
                        <p v-if="!allColors.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="update-color-modal" title="Update Color" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Color:</label>
                    <input type="text" class="form-control col-md-8" placeholder="Enter Name"
                           v-model="singleColor.color">
                </div>
                <div class="row col-md-12 mb-3">
                    <button type="button" class="btn btn-theme text-right" :disabled="singleColor.disabled" v-on:click="updateColor">Update
                    </button>
                </div>
            </div>
        </b-modal>

        <b-modal id="add-color-modal" ref="add-color-modal" title="Add Color" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Color:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Name"
                           v-model="addColors.color">
                </div>
                <div class="row col-md-12  mb-3">
                    <button class="btn btn-theme" v-on:click="addColor" :disabled="addColors.disabled">Add</button>
                </div>
            </div>
        </b-modal>


    </div>
</template>

<script>

    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {

        data() {
            return {
                categories: [],
                singleColor: {
                    "color": "",
                    "id": "",
                    "disabled":false
                },
                addColors: {
                    "color": "",
                    "disabled":false
                },
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllColors();
        },
        methods: {
            updateColor: function () {
                let that = this;
                that.singleColor.disabled = true;
                axios.post('update-color', that.singleColor).then((response) => {
                    that.singleColor.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-color-modal'].hide();
                        that.getAllColors();
                    })

                }).catch((error) => {
                    that.singleColor.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            addColor: function () {
                let that = this;
                that.addColors.disabled = true;
                axios.post('add-color', that.addColors).then((response) => {
                    that.addColors.disabled = false;
                    response = response.data;
                    if (response.status == "success") {
                        that.$refs['add-color-modal'].hide();
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addColors.color = "";
                            that.getAllColors();
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addColors.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSingleColor: function (id) {
                this.$refs['update-color-modal'].show();
                axios.get('get-single-color/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singleColor.color = response.res.color;
                    that.singleColor.id = response.res.id;
                }).catch((error) => {
                    alert("error from 400");
                });
            },

        },
    }
</script>
