<template>

        <!--begin::Container-->
        <div class="">

            <div class="col-xl-12" style="width: 100%">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Сжатая информация по заказам</h3>
                        </div>
                    </div>

                    <form class="form" @submit.prevent="setFilter()">
                        <div class="card-body">

                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label>Цвет:</label>
                                    <input type="text" v-model="filter.color" class="form-control" placeholder="Введите цвет"/>
                                </div>
                                <div class="col-lg-3">
                                    <label>Артикул:</label>
                                    <input type="text" v-model="filter.article" class="form-control" placeholder="Введите артикул"/>
                                </div>
                                <div class="col-lg-3">
                                    <label>Все статусы:</label>
                                    <select class="form-control" id="project2" v-model="filter.realisation_status">
                                        <option :value="null">Статус не выбран</option>
                                        <option :key="item.realisation_status" :value="item.realisation_status" v-for="item in realisationStatus">{{item.decription}}</option>
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
                                <div class="col-lg-3 mt-6">
                                    <template>
                                        <el-select class="p-0 datepicker_width" v-model="filter.company_id" filterable placeholder="Компания" >
                                            <el-option
                                                v-for="item in companies"
                                                :key="item.AccountNum"
                                                :label="item.ContractorName"
                                                :value="item.AccountNum">
                                            </el-option>
                                        </el-select>
                                    </template>
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
                                style="width: 100%"
                                :fit=true
                            >
                                <el-table-column
                                    align="right"
                                    prop="OrderNumber"
                                    label="Номер заявки"
                                    width="150"
                                >
                                    <template slot-scope="scope">
                                        <el-button
                                            size="mini"
                                            ><a :href="'/zip/detail/' +  scope.row.id" target="_blank">{{scope.row.OrderNumber}}</a></el-button>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                    prop="Article"
                                    label="Артикул"
                                    width="180">
                                </el-table-column>
                                <el-table-column
                                    prop="Color"
                                    label="Цвет"
                                    width="180">
                                </el-table-column>
                                <el-table-column
                                    prop="Config"
                                    label="Конфигурация"
                                    width="140">
                                </el-table-column>
                                <el-table-column
                                    prop="Thickness"
                                    label="Толщина"
                                    width="100">
                                </el-table-column>
                                <el-table-column
                                    prop="SumQtySpeciallSku"
                                    label="Заказанный обьем"
                                    width="110">
                                </el-table-column>
                                <el-table-column
                                    prop="SumQtyIzm"
                                    label="Измеренный обьем"
                                    width="160">
                                </el-table-column>
                                <el-table-column
                                    prop="SumQtySales"
                                    label="Проданный обьем"
                                    width="180">
                                </el-table-column>
                                <el-table-column
                                    prop="DeliveryDate"
                                    label="Дата поставки"
                                    width="140">
                                </el-table-column>
                                <el-table-column
                                    prop="EndDate"
                                    label="Дата окончания"
                                    width="140">
                                </el-table-column>
                                <el-table-column
                                    prop="LeadOrLagTime"
                                    label="Отклонение от даты поставки"
                                    width="170">
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
    data() {
        return {
            tableData: [],
            url: "/test?",
            query: '',
            urlBase: '/test?',
            realisationStatus: {},
            companies: {},
            filter: {
                realisation_status: null,
                date_from: null,
                date_to: null,
                article: null,
                color: null,
                company_id: null,
            },
        }
    },

    methods: {
        getData() {
            axios.get(this.url).then((response) => {
                this.tableData = response.data.data;
            })
        },
        handleEdit(index, row) {
            console.log(index, row);
        },
        getRealisationStatuses() {
            axios.get('/realisation-status' ).then((response) => {
                this.realisationStatus = response.data.data;
            })
        },
        getCompanies() {
            axios.get('/manager/order/companies' ).then((response) => {
                this.companies = response.data.companies;
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
        resetFilter() {
                this.filter.date_from =
                    this.filter.realisation_status =
                        this.filter.date_to =
                            this.filter.article =
                                this.filter.color =
                                    this.filter.company_id = null;
        },
    },

    created() {
        this.getRealisationStatuses();
        this.getCompanies();
    },

    mounted() {
        //
    }
}
</script>

<style scoped>
.input_text_color {
    color: #3F4254;
}
.datepicker_width {
    width: 100%;
}
</style>
