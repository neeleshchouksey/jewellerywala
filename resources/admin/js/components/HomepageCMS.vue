<template>
    <div>

        <div class="tabs tabs-default">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#section1" role="tab">Section 1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#section2" role="tab">Section 2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#section4" role="tab">Section 4</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#section6" role="tab">Section 6</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="section1" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            Homepage Section 1 (Banner Section)
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Text 1</label>
                                    <input type="text" placeholder="Text 1" class="form-control"
                                           v-model="section1Data.text1">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Text 2</label>
                                    <input type="text" placeholder="Text 2" class="form-control"
                                           v-model="section1Data.text2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Text 3</label>
                                    <input type="text" placeholder="Text 3" class="form-control"
                                           v-model="section1Data.text3">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Text 4</label>
                                    <input type="text" placeholder="Text 4" class="form-control"
                                           v-model="section1Data.text4">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Link</label>
                                    <input type="text" placeholder="Link" class="form-control"
                                           v-model="section1Data.link">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Image</label>
                                    <input type="file" ref="section1_image" class="form-control">
                                </div>
                            </div>
                            <button class="btn btn-theme" :disabled="section1Data.disabled" v-on:click="addSection1">
                                Submit
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Text 1</th>
                                    <th>Text 2</th>
                                    <th>Text 3</th>
                                    <th>Text 4</th>
                                    <th>Link</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody v-if="section1.length">
                                <tr v-for="s1 in section1">
                                    <td><img height="50" width="50"
                                             :src="APP_URL+'/public/storage/homepage-section-images/'+s1.image"/></td>
                                    <td>{{s1.text1}}</td>
                                    <td>{{s1.text2}}</td>
                                    <td>{{s1.text3}}</td>
                                    <td>{{s1.text4}}</td>
                                    <td>{{s1.link}}</td>
                                    <td>
                                        <button class="btn btn-danger" v-on:click="deleteSection1(s1.id)">Delete
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                                <tbody v-if="!section1.length">
                                No Data Found
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="section2" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            Homepage Section 2
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Link 1</label>
                                    <input type="text" placeholder="Link" class="form-control"
                                           v-model="section2Data.link1">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Image 1</label>
                                    <input type="file" ref="section2_image1" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Image 1</label>
                                    <div>
                                        <img height="50" width="50"
                                             :src="APP_URL+'/public/storage/homepage-section-images/'+section2Data.image1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Link 2</label>
                                    <input type="text" placeholder="Link" class="form-control"
                                           v-model="section2Data.link2">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Image 2</label>
                                    <input type="file" ref="section2_image2" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Image 2</label>
                                    <div>
                                        <img height="50" width="50"
                                             :src="APP_URL+'/public/storage/homepage-section-images/'+section2Data.image2">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Link 3</label>
                                    <input type="text" placeholder="Link" class="form-control"
                                           v-model="section2Data.link3">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Image 3</label>
                                    <input type="file" ref="section2_image3" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Image 3</label>
                                    <div>
                                        <img height="50" width="50"
                                             :src="APP_URL+'/public/storage/homepage-section-images/'+section2Data.image3">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-theme" :disabled="section2Data.disabled" v-on:click="addSection2">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="section4" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            Homepage Section 4
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="hidden" v-model="section4Data.id"/>
                                    <label>Text 1</label>
                                    <input type="text" placeholder="Text 1" class="form-control"
                                           v-model="section4Data.text1">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Text 2</label>
                                    <input type="text" placeholder="Text 2" class="form-control"
                                           v-model="section4Data.text2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Text 3</label>
                                    <input type="text" placeholder="Text 3" class="form-control"
                                           v-model="section4Data.text3">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Text 4</label>
                                    <input type="text" placeholder="Text 4" class="form-control"
                                           v-model="section4Data.text4">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Link</label>
                                    <input type="text" placeholder="Link" class="form-control"
                                           v-model="section4Data.link">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Image</label>
                                    <input type="file" ref="section4_image" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Last Image</label>
                                    <div>
                                        <img height="50" width="50"
                                             :src="APP_URL+'/public/storage/homepage-section-images/'+section4Data.image">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-theme" :disabled="section4Data.disabled" v-on:click="addSection4">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="section6" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            Homepage Section 6
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Link 1</label>
                                    <input type="text" placeholder="Link" class="form-control"
                                           v-model="section6Data.link1">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Image 1</label>
                                    <input type="file" ref="section6_image1" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Image 1</label>
                                    <div>
                                        <img height="50" width="50"
                                             :src="APP_URL+'/public/storage/homepage-section-images/'+section6Data.image1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Link 2</label>
                                    <input type="text" placeholder="Link" class="form-control"
                                           v-model="section6Data.link2">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Image 2</label>
                                    <input type="file" ref="section6_image2" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Image 2</label>
                                    <div>
                                        <img height="50" width="50"
                                             :src="APP_URL+'/public/storage/homepage-section-images/'+section6Data.image2">
                                    </div>
                                </div>
                                <button class="btn btn-theme" :disabled="section6Data.disabled" v-on:click="addSection6">
                                    Submit
                                </button>
                            </div>
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
                section1Data: {
                    'text1': "",
                    'text2': "",
                    "text3": "",
                    "text4": "",
                    "image": "",
                    "link": "",
                    "disabled": false
                },
                section2Data: {
                    "image1": "",
                    "link1": "",
                    "image2": "",
                    "link2": "",
                    "image3": "",
                    "link3": "",
                    "disabled": false,
                    "id": ""
                },
                section4Data: {
                    'id': "",
                    'text1': "",
                    'text2': "",
                    "text3": "",
                    "text4": "",
                    "image": "",
                    "link": "",
                    "disabled": false
                },
                section6Data: {
                    "id":"",
                    "image1": "",
                    "link1": "",
                    "image2": "",
                    "link2": "",
                    "disabled": false
                },
                section1: [],
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getSection(1);
            that.getSection(2);
            that.getSection(4);
            that.getSection(6);

        },
        methods: {

            deleteSection1: function (id) {
                let that = this;
                that.$swal({
                    title: "Delete?",
                    text: "Are you sure? you want to delete",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!",
                }).then(function (result) {
                    if (result.value) {
                        axios.get(APP_URL + "/" + USER_TYPE + '/delete-section1/' + id).then(response => {
                            that.$swal({
                                type: "success",
                                title: "Success",
                                text: response.message,
                                showConfirmButton: true
                            }).then(function () {
                                that.getSection(1);
                            });
                        }).catch((error) => {
                            alert("error from 400");
                        });
                    }
                });
            },
            getSection: function (type) {
                let that = this;
                axios.get(APP_URL + "/" + USER_TYPE + '/get-section/' + type).then(response => {
                    response = response.data;
                    if (type == 1) {
                        that.section1 = response.res;
                    }
                    if (type == 2) {
                        if (response.res.length) {
                            that.section2Data = response.res[0];
                        }
                    }
                    if (type == 4) {
                        if (response.res.length) {
                            that.section4Data = response.res[0];
                        }
                    }
                    if (type == 6) {
                        if (response.res.length) {
                            that.section6Data = response.res[0];
                        }
                    }

                }).catch((error) => {
                    alert("error from 400");
                });
            },
            addSection1: function () {
                let that = this;
                let formData = new FormData();
                var image = that.$refs.section1_image.files[0];
                console.log(image);
                if (that.section1Data.text1 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 1 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section1Data.text2 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 2 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section1Data.text3 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 3 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section1Data.text4 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 4 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section1Data.link == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Link field is required",
                        showConfirmButton: true
                    });
                } else if (that.section4Data.id == "") {
                    if (image == undefined) {
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: "Image is required",
                            showConfirmButton: true
                        });
                    }
                } else {
                    that.section1Data.disabled = true;
                    formData.append('image', image);
                    formData.append('text1', that.section1Data.text1);
                    formData.append('text2', that.section1Data.text2);
                    formData.append('text3', that.section1Data.text3);
                    formData.append('text4', that.section1Data.text4);
                    formData.append('link', that.section1Data.link);
                    axios.post('add-section1', formData).then((response) => {
                        response = response.data;
                        if (response.status == "success") {
                            that.section1Data.disabled = false;
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: response.message,
                                showConfirmButton: true
                            }).then(function () {
                                const input = that.$refs.section1_image
                                input.type = 'text'
                                input.type = 'file'

                                that.section1Data.text1 = "";
                                that.section1Data.text2 = "";
                                that.section1Data.text3 = "";
                                that.section1Data.text4 = "";
                                that.section1Data.link = "";

                                that.getSection(1);
                            })

                        } else {
                            alert(response.message);
                        }

                    }).catch((error) => {
                        that.section1Data.disabled = false;
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
            addSection2: function () {
                let that = this;
                let formData = new FormData();
                var image1 = that.$refs.section2_image1.files[0];
                var image2 = that.$refs.section2_image2.files[0];
                var image3 = that.$refs.section2_image3.files[0];

                that.section2Data.disabled = true;
                formData.append('id', that.section2Data.id);
                formData.append('image1', image1);
                formData.append('image2', image2);
                formData.append('image3', image3);
                formData.append('link1', that.section2Data.link1);
                formData.append('link2', that.section2Data.link2);
                formData.append('link3', that.section2Data.link3);
                formData.append('old_image1', that.section2Data.image1);
                formData.append('old_image2', that.section2Data.image2);
                formData.append('old_image3', that.section2Data.image3);

                axios.post('add-section2', formData).then((response) => {
                    response = response.data;
                    if (response.status == "success") {
                        that.section2Data.disabled = false;
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            const input1 = that.$refs.section2_image1
                            input1.type = 'text'
                            input1.type = 'file'
                            const input2 = that.$refs.section2_image2
                            input2.type = 'text'
                            input2.type = 'file'
                            const input3 = that.$refs.section2_image3
                            input3.type = 'text'
                            input3.type = 'file'

                            that.section2Data.link1 = "";
                            that.section2Data.link2 = "";
                            that.section2Data.link3 = "";

                            that.getSection(2);
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.section2Data.disabled = false;
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: error.response.data.message,
                        showConfirmButton: true
                    });
                });

            },
            addSection4: function () {
                let that = this;
                let formData = new FormData();
                var image = that.$refs.section4_image.files[0];
                console.log(image);
                if (that.section4Data.text1 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 1 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section4Data.text2 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 2 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section4Data.text3 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 3 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section4Data.text4 == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Text 4 field is required",
                        showConfirmButton: true
                    });
                } else if (that.section4Data.link == "") {
                    this.$swal({
                        type: "error",
                        title: "error",
                        text: "Link field is required",
                        showConfirmButton: true
                    });
                } else if (that.section4Data.id == "") {
                    if (image == undefined) {
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: "Image is required",
                            showConfirmButton: true
                        });
                    }
                } else {
                    that.section4Data.disabled = true;
                    formData.append('image', image);
                    formData.append('id', that.section4Data.id);
                    formData.append('text1', that.section4Data.text1);
                    formData.append('text2', that.section4Data.text2);
                    formData.append('text3', that.section4Data.text3);
                    formData.append('text4', that.section4Data.text4);
                    formData.append('link', that.section4Data.link);
                    formData.append('old_image', that.section4Data.image);
                    axios.post('add-section4', formData).then((response) => {
                        response = response.data;
                        if (response.status == "success") {
                            that.section4Data.disabled = false;
                            this.$swal({
                                type: "success",
                                title: "Success",
                                text: response.message,
                                showConfirmButton: true
                            }).then(function () {
                                that.getSection(4);
                            })

                        } else {
                            alert(response.message);
                        }

                    }).catch((error) => {
                        that.section1Data.disabled = false;
                        this.$swal({
                            type: "error",
                            title: "error",
                            text: error.response.data.message,
                            showConfirmButton: true
                        });
                    });
                }
            },
            addSection6: function () {
                let that = this;
                let formData = new FormData();
                var image1 = that.$refs.section6_image1.files[0];
                var image2 = that.$refs.section6_image2.files[0];

                that.section6Data.disabled = true;
                formData.append('id', that.section6Data.id);
                formData.append('image1', image1);
                formData.append('image2', image2);
                formData.append('link1', that.section6Data.link1);
                formData.append('link2', that.section6Data.link2);
                formData.append('old_image1', that.section6Data.image1);
                formData.append('old_image2', that.section6Data.image2);

                axios.post('add-section6', formData).then((response) => {
                    response = response.data;
                    if (response.status == "success") {
                        that.section6Data.disabled = false;
                        this.$swal({
                            type: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: true
                        }).then(function () {
                            const input1 = that.$refs.section2_image1
                            input1.type = 'text'
                            input1.type = 'file'
                            const input2 = that.$refs.section2_image2
                            input2.type = 'text'
                            input2.type = 'file'

                            that.section6Data.link1 = "";
                            that.section6Data.link2 = "";

                            that.getSection(6);
                        })

                    } else {
                        alert(response.message);
                    }

                }).catch((error) => {
                    that.section6Data.disabled = false;
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
