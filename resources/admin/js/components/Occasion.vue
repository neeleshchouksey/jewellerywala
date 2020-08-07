<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>Occasion</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-theme text-right" v-b-modal.add-occasion-modal>
                                    <b> Add Occasion</b>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="allOccasion.length"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Occasion</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in allOccasion">
                                <td>{{index+1}}</td>
                                <td>{{hs.occasion}}</td>
                                <td>
                                    <button class="btn btn-theme" v-on:click="getSingleOccasion(hs.id)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,4)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="allOccasionData"
                                    @pagination-change-page="getAllOccasion"></pagination>
                        <p v-if="!allOccasion.length">No Data Found</p>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="update-occasion-modal" title="Update Occasion" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Category:</label>
                    <input type="text" class="form-control col-md-8" placeholder="Enter Occasion"
                           v-model="singleOccasion.occasion">
                </div>
                <div class="row col-md-12 mb-3">
                    <button type="button" class="btn btn-theme text-right" :disabled="singleOccasion.disabled" v-on:click="updateOccasion">Update
                    </button>
                </div>
            </div>
        </b-modal>

        <b-modal id="add-occasion-modal" ref="add-occasion-modal" title="Add Occasion" hide-footer>
            <div class="col-md-12 d-block text-center">
                <div class="row mb-3">
                    <label class="col-md-4 text-left">Occasion:</label>
                    <input type="text" class="form-control  col-md-8" placeholder="Enter Occasion"
                           v-model="addOccasionData.occasion">
                </div>
                <div class="row col-md-12 mb-3">
                    <button class="btn btn-theme" v-on:click="addOccasion" :disabled="addOccasionData.disabled">Add</button>
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
                singleOccasion: {
                    "occasion": "",
                    "id": "",
                    "disabled":false
                },
                addOccasionData: {
                    "occasion": "",
                    "disabled":false
                },
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllOccasion();
        },
        methods: {
            updateOccasion: function () {
                let that = this;
                that.singleOccasion.disabled = true;
                axios.post('update-occasion', that.singleOccasion).then((response) => {
                    that.singleOccasion.disabled = false;
                    response = response.data;
                    this.$swal({
                        type: "success",
                        title: "Success",
                        text: response.message,
                        showConfirmButton: true
                    }).then(function () {
                        that.$refs['update-occasion-modal'].hide();
                        that.getAllOccasion();
                    })

                }).catch((error) => {
                    that.singleOccasion.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            addOccasion: function () {
                let that = this;
                that.addOccasionData.disabled = true;
                axios.post('add-occasion', that.addOccasionData).then((response) => {
                    that.addOccasionData.disabled = false;
                    response = response.data;
                    if (response.status == "success") {
                        that.$refs['add-occasion-modal'].hide();
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            that.addOccasionData.occasion = "";
                            that.getAllOccasion();
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.addOccasionData.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });
            },
            getSingleOccasion: function (id) {
                this.$refs['update-occasion-modal'].show();
                axios.get('get-single-occasion/' + id).then(response => {
                    let that = this;
                    response = response.data;
                    that.singleOccasion.occasion = response.res.occasion;
                    that.singleOccasion.id = response.res.id;
                }).catch((error) => {
                    alert("error from 400");
                });
            },

        },
    }
</script>
