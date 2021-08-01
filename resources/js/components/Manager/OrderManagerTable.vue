<template>

    <div class="container-fluid">
        <div class="row">
            <div class="card card-custom order__card">

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
                            <div class="form-group mt-3">

                                <span class="form-text text__color__form">Статус заказа</span>
                                <select class="form-control status_select" id="project" v-model="filter.status">
                                    <option :value="null">Нет статуса</option>
                                    <option :key="item.id" :value="item.id" v-for="item in status">{{item.status}}</option>
                                </select>

                                <span class="form-text text__color__form">Статус реализации</span>
                                <select class="form-control status_select" id="project2" v-model="filter.realisation_status">
                                    <option :value="null">Нет статуса</option>
                                    <option :key="item.realisation_status" :value="item.realisation_status" v-for="item in realisationStatus">{{item.decription}}</option>
                                </select>

                                <span class="form-text text__color__form">Компания</span>
                                <select v-if="companies.length > 0" class="form-control status_select" v-model="filter.company_id">
                                    <option :value="null"></option>
                                    <option :key="company.AccountNum" :value="company.AccountNum" v-for="company in companies">{{company.ContractorName}}</option>
                                </select>

                                <div class=" p-0 mt-3">
                                    <button type="submit" class="btn color__button" id="Date">Найти</button>
                                    <button class="btn color__button ml-5" @click="resetFilter">Сброс</button>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 p-10">
                            <form>
                                <div class="form-group mt-3">
                                    <!--                                <label>Артикул</label>-->
                                    <span class="form-text text__color__form">Артикул</span>
                                    <input type="text" class="form-control" v-model="filter.article" placeholder="Введите артикул"/>

                                    <!--                                <label>Цвет</label>-->
                                    <span class="form-text text__color__form">Цвет</span>
                                    <input type="text" class="form-control" v-model="filter.color" placeholder="Введите цвет"/>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-3 p-10">
                            <form>
                                <div class="p-0 mt-3">
                                    <span class="form-text text__color__form">Начальная дата поставки</span>
                                    <date-picker v-model="filter.date_from" valueType="format"></date-picker>
                                </div>

                                <div class="p-0 mt-3">
                                    <span class="form-text text__color__form">Конечная дата поставки</span>
                                    <date-picker v-model="filter.date_to" valueType="format"></date-picker>
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
                                prop="SumQtySpeciallSku"
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
                                label="Детализация">
                                <template slot-scope="scope">
                                    <el-button
                                        size="mini"
                                        type="success"
                                        @click="getDetailOrderInfo(scope.row.OrderNumber)" style="background-color: #0BB7AF">Подробно</el-button>
                                </template>
                            </el-table-column>

                        </el-table>
                    </div>
                </div>
            </div>
        </div>

        <modal
            name="order-info"
            :width="'80%'"
            :height="'40%'"
        >
            <div class="example-modal-content">
                <el-table
                    :data="orderDetailItems"
                    height="350"
                    style="width: 100%"
                >
                    <el-table-column
                        prop="OrderNumber"
                        label="Номер заявки"
                        width="180">
                    </el-table-column>
                    <el-table-column
                        prop="OrderQTY"
                        label="Объем заявки"
                        width="180">
                    </el-table-column>
                    <el-table-column
                        prop="Article"
                        label="Артикул">
                    </el-table-column>
                    <el-table-column
                        prop="Color"
                        label="Цвет">
                    </el-table-column>
                    <el-table-column
                        prop="ManagerName"
                        label="Менеджер">
                    </el-table-column>
                </el-table>
            </div>
        </modal>

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
            companies: {},
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
                company_id: null,
            },
            url: '/orders?limit=500',
            urlBase: '/orders?limit=500',
            urlDetailOrder: '/detail/orders',
            query: '',
            interval: null,
            orderDetailItems: {},
        }
    },

    methods: {

        handleGetInfo(index, row) {
            console.log(index, row);
        },

        show(modal_name) {
            this.$modal.show(modal_name);
        },

        hide(modal_name) {
            this.$modal.hide(modal_name);
        },

        async getDetailOrderInfo(orderNumber){
            await axios.get(this.urlDetailOrder + "?order_number=" + orderNumber).then((response) => {
                this.orderDetailItems = response.data.data;
                this.$modal.show("order-info");
            })
        },

        getData() {
            axios.get(this.url).then((response) => {
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

        getDetailInformation() {
            axios.get('/detail/orders' ).then((response) => {
                console.log(response.data.data);
            })
        },

        getCompanies() {
            axios.get('/manager/order/companies' ).then((response) => {
                this.companies = response.data.companies;
            })
        },

        resetFilter() {
            this.filter.status =
                this.filter.date_from =
                    this.filter.realisation_status =
                        this.filter.date_to =
                            this.filter.article =
                                this.filter.color =
                                    this.filter.company_id = null;

            this.data.orders = [];
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

            if (this.filter.company_id != null) {
                this.query += '&company_id=' + this.filter.company_id;
            }

            this.url = this.query;

            if (this.filter.company_id === null) {
                return;
            }
            this.getData();
        },

    },

    created() {
        this.getStatuses();
        this.getRealisationStatuses();
        // this.getData();
        this.getCompanies();
    },

    mounted() {
        // this.interval = setInterval(() => {
        //     this.getData();
        // }, 25000);
    },

    destroyed() {
        // clearInterval(this.interval);
    }

}

</script>

<style scope>

.text__color__form {
    color: #fff;
}
</style>
