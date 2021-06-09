<template>

    <div class="container-fluid">
        <div class="row">
            <div class="card card-custom">

                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            Информация о заказе компании
                        </h3>
                    </div>
                </div>

                <form class="form" @submit.prevent="setFilter()">
                    <div class="row order__table">
                        <div class="col-lg-3 col-md-3 col-sm-3 p-10">
                            <div class="form-group">

                                <label for="project">Статус заказа</label>
                                <select class="form-control status_select" id="project" v-model="filter.status">
                                    <option :value="null">Нет статуса</option>
                                    <option :key="item.id" :value="item.id" v-for="item in status">{{item.status}}</option>
                                </select>

                                <label for="project">Статус реализации</label>
                                <select class="form-control status_select" id="project2" v-model="filter.realisation_status">
                                    <option :value="null">Нет статуса</option>
                                    <option :key="item.realisation_status" :value="item.realisation_status" v-for="item in realisationStatus">{{item.decription}}</option>
                                </select>

                                <div class=" p-0 mt-3">
                                    <button type="submit" class="btn btn-primary" id="Date">Найти</button>
                                    <button class="btn btn-bg-danger ml-5" @click="resetFilter">Сброс</button>
                                </div>

                            </div>
                        </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 p-10">
                        <form>
                            <div class="form-group">
                                <label>Артикул</label>
                                <input type="text" class="form-control" v-model="filter.article" placeholder="Введите артикул"/>

                                <label>Цвет</label>
                                <input type="text" class="form-control" v-model="filter.color" placeholder="Введите цвет"/>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 p-10">
                        <form>
                            <div class="p-0 mt-8">
                                <date-picker v-model="filter.date_from" valueType="format"></date-picker> Начальная дата
                            </div>

                            <div class="p-0 mt-8">
                                <date-picker v-model="filter.date_to" valueType="format"></date-picker> Конечная дата
                            </div>
                        </form>
                    </div>
                </div>
                </form>
                <div class="m-portlet__body">
                    <div class="col md 12">
                        <el-table
                            :data="data.orders"
                            height="550"
                            size="mini"
                            class="table table-bordered table-checkable dataTable no-footer dtr-inline collapsed orders__tracking__table table-hover"
                        >
                            <el-table-column
                                prop="OrderDate"
                                label="Дата размещения заказа"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="OrderNumber"
                                label="Номер заявки"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="OrderQTY"
                                label="Заказанный обьем"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="Article"
                                label="Артикул"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="ProdOrderNumber"
                                label="Номер заказа"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="ProdOrderStatus"
                                label="Статус заказа"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="ProdOrderQTY"
                                label="Обьем заказа"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="Color"
                                label="Цвет"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="Config"
                                label="Конфигурация"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="Thickness"
                                label="Толщина"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="DeliveryDate"
                                label="Дата поставки"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="EndDate"
                                label="Дата окончания"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="LeadOrLagTime"
                                label="Отклонения от даты поставки"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="ManagerName"
                                label="Менеджер"
                                width="auto"
                            >
                            </el-table-column>
                            <el-table-column
                                prop="ContractorName"
                                label="Кампания"
                                width="auto"
                            >
                            </el-table-column>

                        </el-table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

export default {

    props: {
    },

    data() {
        return {
            status: {},
            realisationStatus: {},
            data: {
                orders: [],
            },
            columns: [
                'OrderDate',
                'OrderNumber',
                'OrderQTY',
                'Article',
                'ProdOrderNumber',
                'ProdOrderStatus',
                'ProdOrderQTY',
                'Color',
                'Config',
                'Thickness',
                'DeliveryDate',
                'EndDate',
                'LeadOrLagTime',
                'ManagerName',
                'ContractorName',
            ],
            options: {
                headings: {
                    OrderDate: 'Дата размещения заказа',
                    OrderNumber: 'Номер заявки',
                    OrderQTY: 'Заказанный обьем',
                    Article: 'Артикул',
                    ProdOrderNumber: 'Номер заказа',
                    ProdOrderStatus: 'Статус заказа',
                    ProdOrderQTY: 'Обьем заказа',
                    Color: 'Цвет',
                    Config: 'Конфигурация',
                    Thickness: 'Толщина',
                    DeliveryDate: 'Дата поставки',
                    EndDate: 'Дата окончания',
                    LeadOrLagTime: 'Отклонения от даты поставки',
                    ManagerName: 'Менеджер',
                    ContractorName: 'Кампания',
                },
                filterable: false,
                perPageValues: [], // [5, 10, 100, 500]
                // skin: 'table table-bordered table-checkable dataTable no-footer dtr-inline collapsed orders__tracking__table table-hover',
            },
            filter: {
                status: null,
                realisation_status: null,
                date_from: null,
                date_to: null,
                article: null,
                color: null,
            },
            url: '/orders?limit=500',
            urlBase: '/orders?limit=500',
            query: '',
            interval: null,
        }
    },

    // watch: {
        // 'item.someOtherProp': function (newVal, oldVal){
        //     //to work with changes in someOtherProp
        // },
    // },

    methods: {

        getData() {
            axios.get(this.url ).then((response) => {
                this.data.orders = response.data.data;
            })
        },

        getStatuses() {
            axios.get('/status' ).then((response) => {
                this.status = response.data.data;
            })
        },

        getRealisationStatuses() {
            axios.get('/realisation-status' ).then((response) => {
                this.realisationStatus = response.data.data;
            })
        },

        resetFilter() {
            this.filter.status =
                this.filter.date_from =
                    this.filter.realisation_status =
                        this.filter.date_to =
                            this.filter.article =
                                this.filter.color = null;
        },

        setFilter() {
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

            if (this.filter.realisation_status != null) {
                this.query += '&realisation_status=' + this.filter.realisation_status;
            }

            if (this.filter.article != null) {
                this.query += '&article=' + this.filter.article;
            }

            if (this.filter.color != null) {
                this.query += '&color=' + this.filter.color;
            }

            this.url = this.query;
            this.getData();
        },

    },

    created() {
        this.getStatuses();
        this.getRealisationStatuses();
        this.getData();
    },

    mounted() {
        // this.$refs['task-table'].setLimit(10);
        this.interval = setInterval(() => {
            this.getData();
        }, 25000);
    },

    destroyed() {
        clearInterval(this.interval);
    }

}

</script>

