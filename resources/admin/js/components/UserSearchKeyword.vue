<template>
    <div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-actions row">
                            <div class="col-md-6 text-left">
                                <h5>User Search Keyword</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table v-if="searchKeyword.length" id="cat-datatable"
                               class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>S. No.</th>
                                <th>Keyword</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hs,index) in searchKeyword">
                                <td>{{index+1}}</td>
                                <td>{{hs.keyword}}</td>
                                <td>
                                    <button class="btn btn-danger" v-on:click="deleteData(hs.id,13)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="searchKeywordData"
                                    @pagination-change-page="getAllUserSearchKeywords"></pagination>
                        <p v-if="!searchKeyword.length">No Data Found</p>
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
                searchKeyword: [],
                searchKeywordData:{},
            }
        },
        mixins: [AdminSellerMixin],
        created() {
            let that = this;
            that.getAllUserSearchKeywords();
        },
        methods: {
            getAllUserSearchKeywords: function (page=1) {
                axios.get('get-all-user-search-keywords?page='+page).then(response => {
                    let that = this;
                    response = response.data;
                    that.searchKeyword = response.res.data;
                    that.searchKeywordData = response.res;
                }).catch((error) => {
                    alert("error from 400");
                });
            },

        },
    }
</script>
