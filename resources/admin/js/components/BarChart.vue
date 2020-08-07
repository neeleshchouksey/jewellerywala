<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="icon-fa icon-fa-line-chart text-warning"></i>{{label}}</h6>
                </div>
                <div class="col-md-6 text-right">
                    <select  id="get_daily_orders" class="form-control" v-on:change="getDailyOrders">
                        <option value="1">Daily</option>
                        <option value="2">Weekly</option>
                        <option value="3">Monthly</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="graph-container">
                <canvas id="graph" ref="graph" style="pointer-events: none;"/>
            </div>
        </div>
    </div>
</template>

<script>
    import AdminSellerMixin from "../mixins/AdminSellerMixin";

    export default {
        data() {
            return {
                date: [],
                amount: [],
                label:"Total Sale Amount"
            }
        },
        mixins: [AdminSellerMixin],

        created() {
            let that = this;
            setTimeout(function() {
                that.getDailyOrders();
            },500);
        },
        methods: {
            getDailyOrders: function () {
                let that = this;
                let get_daily_orders = $("#get_daily_orders").val();
                axios.get(APP_URL + '/' + USER_TYPE + '/get-daily-orders/'+get_daily_orders).then(response => {
                        response = response.data;
                        that.date = response.date;
                        that.amount = response.amount;

                        let context = this.$refs.graph.getContext('2d')

                        let options = {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            }
                        }

                        if(get_daily_orders==2){
                            that.label = "Total Sale Amount in "+response.last_month;
                        }else{
                            that.label = "Total Sale Amount";
                        }

                        let data = {
                            labels: that.date,
                            datasets: [
                                {
                                    label: 'Total Sale Amount',
                                    backgroundColor: 'rgba(79, 196, 127,0.2)',
                                    borderColor: 'rgba(79, 196, 127,1)',
                                    borderWidth: 1,
                                    hoverBackgroundColor: 'rgba(79, 196, 127,0.4)',
                                    hoverBorderColor: 'rgba(79, 196, 127,1)',
                                    data: that.amount
                                }
                            ]
                        }

                        let myBarChart = new Chart(context, {
                            type: 'bar',
                            data: data,
                            options: options
                        })

                    }
                ).catch((error) => {

                });
            },
        }
    }
</script>

<style>
    .graph-container {
        height: 300px;
    }
</style>
