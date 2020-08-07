export default {
    name: 'AdminSellerMixin',
    data() {
        return {
            APP_URL: APP_URL,
            AUTH_USER: AUTH_USER,
            USER_TYPE: USER_TYPE,
            allPurity: [],
            allPurityData: {},
            allSubCategories: [],
            allSubData: {},
            allCategories: [],
            allCategoriesData: {},
            allOccasion: [],
            allOccasionData: {},
            allColors: [],
            allColorsData: {},
            allProducts: [],
            allProductsData: {},
            filterData:{
                "category":"",
                "subCategory":"",
                "purity":"",
                "occasion":"",
                "name":"",
                "productId":"",
                "sellerName":""
            },
            categories: [],
            subcategories: [],
            purities: [],
            occasion: [],
            colors: [],
            stateData:[],
            cityData:[],
        }
    },
    created() {

    },
    methods: {



        getState: function () {
            let that = this;
            axios.get(APP_URL + '/get-states').then(response => {
                    that.stateData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        getCity: function () {
            let id = $("#billing_state").val();

            let that = this;
            axios.get(APP_URL + '/get-cities/' + id).then(response => {
                    that.cityData = response.data.res;
                }
            ).catch((error) => {

            });
        },
        getAllProducts: function (page = 1) {
            let that = this;
            axios.post(APP_URL + "/" + USER_TYPE + '/get-all-products?page=' + page,that.filterData).then(response => {
                response = response.data;
                that.allProducts = response.res.data;
                that.allProductsData = response.res;
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getAllCategories: function (page = 1) {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-all-categories?page=' + page).then(response => {
                response = response.data;
                that.allCategories = response.res.data;
                that.allCategoriesData = response.res;
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getAllOccasion: function (page = 1) {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-all-occasion?page=' + page).then(response => {
                response = response.data;
                that.allOccasion = response.res.data;
                that.allOccasionData = response.res;
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getAllColors: function (page = 1) {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-all-colors?page=' + page).then(response => {
                response = response.data;
                that.allColors = response.res.data;
                that.allColorsData = response.res;
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getAllSubCategories: function (page = 1) {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-all-sub-categories?page=' + page).then(response => {
                response = response.data;
                that.allSubCategories = response.res.data;
                that.allSubData = response.res;
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getAllPurities: function (page = 1) {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-all-purity?page=' + page).then(response => {
                response = response.data;
                that.allPurity = response.res.data;
                that.allPurityData = response.res;
            }).catch((error) => {
                alert("error from 400");
            });
        },
        getCategories: function () {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-categories').then(response => {
                response = response.data;
                that.categories = response.res;

            }).catch((error) => {
                alert("error from 400");
            });
        },
        getSubCategories: function (id) {
            let that = this;
            if (id) {
                axios.get(APP_URL + "/" + USER_TYPE + '/get-subcategories/' + id).then(response => {
                    response = response.data;
                    that.subcategories = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            }else{
                that.subcategories = [];
            }
        },
        getPurities: function (id) {
            let that = this;
            if (id) {
                axios.get(APP_URL + "/" + USER_TYPE + '/get-purities/' + id).then(response => {
                    response = response.data;
                    that.purities = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            }else{
                that.purities = [];
            }
        },
        getOccasion: function () {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-occasion').then(response => {
                response = response.data;
                that.occasion = response.res;

            }).catch((error) => {
                alert("error from 400");
            });
        },
        getColors: function () {
            let that = this;
            axios.get(APP_URL + "/" + USER_TYPE + '/get-colors').then(response => {
                response = response.data;
                that.colors = response.res;

            }).catch((error) => {
                alert("error from 400");
            });
        },
        deleteData: function (id, type, variant=null) {
            let that = this;
            var text = "Are you sure? If You delete this, all related products will be also deleted!";
            if (type == 2) {
                text = "Are you sure? If You delete this category, all related subcategory, purity and products will be also deleted!"
            }
            else if (type == 6) {
                text = "Are you sure? If You delete this product, all related variants and variant images will be also deleted!"
            }
            else if (type == 7 || type == 8 || type == 9 || type==11 || type==12 || type==13 || type==14|| type==15) {
                text = "Are you sure? You won't be able to revert this!"
            }
            that.$swal({
                title: "Delete?",
                text: text,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes, Delete it!",
            }).then(function (result) {
                if (result.value) {
                    axios.post(APP_URL+'/'+USER_TYPE+'/delete-data/' + id, {"type": type}).then((response) => {
                        response = response.data;
                        if (response.status == "success") {
                            that.$swal({
                                type: "success",
                                title: "Success",
                                text: response.message,
                                showConfirmButton: true
                            }).then(function () {
                                if (type == 1) {
                                    that.getAllSubCategories();
                                } else if (type == 2) {
                                    that.getAllCategories();
                                } else if (type == 3) {
                                    that.getAllPurities();
                                } else if (type == 4) {
                                    that.getAllOccasion();
                                } else if (type == 5) {
                                    that.getAllColors();
                                }else if (type == 6) {
                                    that.getAllProducts();
                                }else if (type == 7) {
                                    let id = document.URL.split('/')[document.URL.split('/').length-1];
                                    that.getProductVariants(id);
                                }else if (type == 8) {
                                    that.getSingleVariant(variant);
                                }else if (type == 9) {
                                    that.getAllUsers(2);
                                }else if (type == 10) {
                                    that.getAllUsers(1);
                                }else if (type == 11) {
                                    that.getAllCouponCode();
                                }else if (type == 12) {
                                    that.getAllSuperCoin();
                                }else if (type == 13) {
                                    that.getAllUserSearchKeywords();
                                }else if (type == 14) {
                                    that.getAllCommission();
                                }else if (type == 15) {
                                    that.getSubAdmin();
                                }
                            });
                        }
                    });
                }
            })
        },
    }
}
