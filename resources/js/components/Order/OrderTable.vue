<template>
    <div class="card card-custom">

        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Информация о заказе компании
                </h3>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-9 col-sm-12">

                <h3>Фильтры поиска</h3>

                <form class="form" @submit.prevent="setFilter()">

                    <div class="form-group">

                        <label for="project">Статус заказа</label>
                        <select class="form-control" id="project" v-model="filter.status">
                            <option :value="null">Нет проекта</option>
                            <option :key="item.id" :value="item.id" v-for="item in status">{{item.status}}</option>
                        </select>

                        <div class="p-0 mt-6">
                            <date-picker v-model="filter.date_from" valueType="format"></date-picker> Начальная дата
                        </div>

                        <div class="p-0 mt-6">
                            <date-picker v-model="filter.date_to" valueType="format"></date-picker> Конечная дата
                        </div>

                        <div class=" p-0 mt-3">
                            <button type="submit" class="btn btn-primary" id="Date">Найти</button>
                            <button class="btn btn-bg-danger ml-5" @click="resetFilter">Сброс</button>
                        </div>

                    </div>

                </form>
            </div>
        </div>

        <div class="m-portlet__body">
            <v-client-table
                :data="data.orders"
                :columns="columns"
                :options="options"
                ref="task-table"
            />
        </div>

<!--        <v-client-table :data="data.orders" :columns="columnss" :options="options"/>-->

<!--        <div class="card-body" style="overflow: auto">-->
<!--        <div class="m-portlet__body">-->
<!--            <v-server-table-->
<!--                :url="url"-->
<!--                :columns="columns"-->
<!--                :options="options"-->
<!--                class="faq-table "-->
<!--                ref="task-table"-->
<!--            >-->
<!--            </v-server-table>-->
<!--        </div>-->

    </div>
</template>

<script>

export default {

    props: {
    },

    data() {
        return {
            status: {},
            data: {
                orders: [],
            },
            columns: [
                'ProdOrderStatus',
                'AccountNum',
                'Article',
                'Color',
                'Config',
                'ContractorName',
                'ContractorNameActual',
                'DeliveryDate',
                'EndDate',
                'LeadOrLagTime',
                'ManagerName',
                'OrderConfirmQTY',
                'OrderDate',
                'OrderNumber',
                'OrderQTY',
                'ProdOrderNumber',
                'ProdOrderQTY',
                'Prodstatus',
                'Thickness',
            ],
            options: {
                headings: {
                    ProdOrderStatus: 'ProdOrderStatus',
                    AccountNum: 'AccountNum',
                    Article: 'Article',
                    Color: 'Color',
                    Config: 'Config',
                    ContractorName: 'ContractorName',
                    ContractorNameActual: 'ContractorNameActual',
                    DeliveryDate: 'DeliveryDate',
                    EndDate: 'EndDate',
                    LeadOrLagTime: 'LeadOrLagTime',
                    ManagerName: 'ManagerName',
                    OrderConfirmQTY: 'OrderConfirmQTY',
                    OrderDate: 'OrderDate',
                    OrderNumber: 'OrderNumber',
                    OrderQTY: 'OrderQTY',
                    ProdOrderNumber: 'ProdOrderNumber',
                    ProdOrderQTY: 'ProdOrderQTY',
                    Prodstatus: 'Prodstatus',
                    Thickness: 'Thickness',
                },
                filterable: false,
                perPageValues: [], // [5, 10, 100, 500]
                skin: 'table table-bordered table-checkable dataTable no-footer dtr-inline collapsed',
            },
            filter: {
                status: null,
                date_from: null,
                date_to: null,
            },
            url: '/orders?limit=500',
            urlBase: '/orders?limit=500',
            query: '',
            interval: null,
        }
    },

    methods: {

        getData() {
            axios.get(this.url ).then((response) => {
                this.data.orders = response.data.data;
                console.log(response);
            })
        },

        getStatuses() {
            axios.get('/status' ).then((response) => {
                this.status = response.data.data;
            })
        },

        resetFilter() {
            this.filter.status =
                this.filter.date_from =
                    this.filter.date_to = null;
        },

        setFilter() {
            console.log('Go filter');
            this.query = this.urlBase;

            if (this.filter.status != null) {
                this.query += '&status=' + this.filter.status;
            }

            if (this.filter.date_from != null) {
                this.query += '&date_from=' + this.filter.date_from;
            }

            if (this.filter.date_to != null) {
                this.query += '&date_to=' + this.filter.date_to;
            }

            this.url = this.query;
            // this.$refs['task-table'].refresh();
            this.getData();
        },

    },

    created() {
        this.getStatuses();
        this.getData();
    },

    mounted() {
        this.$refs['task-table'].setLimit(500);
        this.interval = setInterval(() => {
            this.getData();
            // this.$refs['task-table'].refresh();
        }, 5000);
    },

    destroyed() {
        clearInterval(this.interval);
    }

}

</script>

<style scoped>
</style>
