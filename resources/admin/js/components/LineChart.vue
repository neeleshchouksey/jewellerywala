<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="icon-fa icon-fa-line-chart text-warning"></i>{{label}}</h6>
                </div>
                <div class="col-md-6 text-right">
                    <select  id="get_daily_total_orders" class="form-control" v-on:change="getDailyTotalOrders">
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
    export default {
        data() {
            return {
                date: [],
                amount: [],
                label:"Total No of Orders"
            }
        },
        created() {
            let that = this;
            setTimeout(function(){
                that.getDailyTotalOrders();
            },500)
        },

        methods: {
            getDailyTotalOrders: function () {
                let that = this;
                let get_daily_total_orders = $("#get_daily_total_orders").val();
                axios.get(APP_URL + '/' + USER_TYPE + '/get-daily-total-orders/'+get_daily_total_orders).then(response => {
                        response = response.data;
                        that.date = response.date;
                        that.amount = response.amount;

                        if(get_daily_total_orders==2){
                            that.label = "Total No of Orders in "+response.last_month;
                        }else{
                            that.label = "Total No of Orders";
                        }

                        let context = this.$refs.graph.getContext('2d')

                        let options = {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            }
                        }

                        let data = {
                            labels: that.date,
                            datasets: [
                                {
                                    label: 'Total No of Orders',
                                    fill: false,
                                    lineTension: 0.1,
                                    backgroundColor: 'rgba(0,125,204,0.4)',
                                    borderColor: 'rgba(0,125,204,1)',
                                    borderCapStyle: 'butt',
                                    borderDash: [],
                                    borderDashOffset: 0.0,
                                    borderJoinStyle: 'miter',
                                    pointBorderColor: 'rgba(75,192,192,1)',
                                    pointBackgroundColor: '#fff',
                                    pointBorderWidth: 1,
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: 'rgba(75,192,192,1)',
                                    pointHoverBorderColor: 'rgba(220,220,220,1)',
                                    pointHoverBorderWidth: 2,
                                    pointRadius: 1,
                                    pointHitRadius: 10,
                                    data: that.amount
                                }
                            ]
                        }

                        let myLineChart = new Chart(context, {
                            type: 'line',
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
