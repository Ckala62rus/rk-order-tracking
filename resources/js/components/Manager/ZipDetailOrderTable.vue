<template>

    <!--begin::Container-->
    <div class="">

<!--        <h1>Детальная информация по заказам c id={{this.id}}</h1>-->

        <div class="col-xl-12" style="width: 100%">
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Производственные заказы</h3>
                    </div>
                </div>

                <form class="form" @submit.prevent="setFilter()">
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>Статусы реализации:</label>
                                <select class="form-control datepicker_width" v-model="filter.realisation_status">
                                    <option :value="null">Статус не выбран</option>
                                    <option
                                        :key="item.realisation_status"
                                        :value="item.realisation_status"
                                        v-for="item in realisationStatus"
                                    >{{item.decription}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>Статус заказа:</label>
                                <select class="form-control datepicker_width" v-model="filter.status">
                                    <option :value="null">Статус не выбран</option>
                                    <option :key="item.id" :value="item.id" v-for="item in status">{{item.status}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>Начальная дата поставки:</label>
                                <date-picker v-model="filter.date_from" valueType="format" class="datepicker_width"></date-picker>
                            </div>
                            <div class="col-lg-3">
                                <label>Конечная дата поставки:</label>
                                <date-picker v-model="filter.date_to" valueType="format" class="datepicker_width"></date-picker>
                            </div>

                            <div class="col-lg-3 pt-6">
                                <button type="submit" class="btn btn-primary mr-2">Найти</button>
                                <button type="reset" class="btn btn-secondary" @click="resetFilter">Сброс</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-footer"></div>

                <div class="card-body">

                    <!--begin::Example-->
                    <template>
                        <el-table
                            :data="tableData"
                            height="550"
                            style="width: 100%;"
                            :fit=true
                        >
                            <el-table-column
                                align="right"
                                prop="OrderNumber"
                                label="Номер заявки"
                                width="150"
                            >
                            </el-table-column>

                            <el-table-column
                                prop="Article"
                                label="Артикул"
                                width="150">
                            </el-table-column>
                            <el-table-column
                                prop="Color"
                                label="Цвет"
                                width="180">
                            </el-table-column>
                            <el-table-column
                                prop="Config"
                                label="Конфигурация"
                                width="125">
                            </el-table-column>
                            <el-table-column
                                prop="Thickness"
                                label="Толщина"
                                width="100">
                            </el-table-column>

                            <el-table-column
                                prop="OrderDate"
                                label="Дата размещения заказа"
                                width="145">
                            </el-table-column>

                            <el-table-column
                                prop="ProdOrderNumber"
                                label="Номер заказа"
                                width="130">
                            </el-table-column>

                            <el-table-column
                                prop="ProdOrderStatus"
                                label="Статус заказа"
                                width="190">
                            </el-table-column>

                            <el-table-column
                                prop="OrderedQTYPZ"
                                label="Подтвержденный обьем"
                                width="110">
                            </el-table-column>

                            <el-table-column
                                prop="SumQtyIzm"
                                label="Измеренный обьем"
                                width="110">
                            </el-table-column>

                            <el-table-column
                                prop="SumQtySales"
                                label="Проданный обьем"
                                width="110">
                            </el-table-column>

                            <el-table-column
                                prop="DeliveryDate"
                                label="Дата поставки"
                                width="120">
                            </el-table-column>

                            <el-table-column
                                prop="EndDate"
                                label="Дата окончания"
                                width="130"
                            >
                                <template slot-scope="scope">
                                    <div :class="{
                                            red_column_color: scope.row.RegistrChange == 1,
                                            green_column_color: scope.row.RegistrChange == 2,
                                        }">
                                        {{scope.row.EndDate}}
                                    </div>
                                </template>
                            </el-table-column>

                            <el-table-column
                                prop="LeadOrLagTime"
                                label="Отклонения от даты поставки"
                                width="130">
                            </el-table-column>

                        </el-table>
                    </template>
                    <!--end::Example-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>

    <!--end::Container-->
</template>

<script>
export default {
    props: [
        'id'
    ],

    data() {
        return {
            url: "/zip/detail-orders?",
            query: '',
            urlBase: '/zip/detail-orders?',
            tableData: [],
            realisationStatus: {},
            status: {},
            filter: {
                realisation_status: null,
                date_from: null,
                date_to: null,
                status: null,
            },
        }
    },

    methods: {
        getData() {
            axios.get(this.url + "&order_id=" + this.id).then((response) => {
                this.tableData = response.data.data;
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
        setFilter() {
            this.query = this.urlBase;

            if (this.filter.date_from != null) {
                this.query += '&date_from=' + this.filter.date_from;
            }

            if (this.filter.date_to != null) {
                this.query += '&date_to=' + this.filter.date_to;
            }

            if (this.filter.realisation_status != null) {
                this.query += '&realisation_status=' + this.filter.realisation_status;
            }

            if (this.filter.status != null) {
                this.query += '&status=' + this.filter.status;
            }

            this.url = this.query;

            this.getData();
        },
        resetFilter() {
            this.filter.date_from =
                this.filter.realisation_status =
                    this.filter.date_to =
                        this.filter.status = null;
        },
    },

    created() {
        this.getData();
        this.getStatuses();
        this.getRealisationStatuses();
    },

    mounted() {},
}
</script>

<style scoped>
.datepicker_width {
    width: 100%;
}
.red_column_color {
    color: #FF6666;
}

.green_column_color {
    color: #29bf51;
}
</style>
