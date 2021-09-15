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

<!--                            <div class="col-xl-9">-->
<!--                                <div class="form-group row">-->
<!--                                    <div class="col-lg-4">-->
<!--                                        <label>Цвет:</label>-->
<!--                                        <input type="text" v-model="filter.color" class="form-control" placeholder="Введите цвет"/>-->
<!--                                    </div>-->
<!--                                    <div class="col-lg-4">-->
<!--                                        <label>Артикул:</label>-->
<!--                                        <input type="text" v-model="filter.article" class="form-control" placeholder="Введите артикул"/>-->
<!--                                    </div>-->
<!--                                    <div class="col-lg-4">-->
<!--                                        <label>Все статусы:</label>-->
<!--                                        <select class="form-control" id="project2" v-model="filter.realisation_status">-->
<!--                                            <option :value="null">Статус не выбран</option>-->
<!--                                            <option :key="item.realisation_status" :value="item.realisation_status" v-for="item in realisationStatus">{{item.decription}}</option>-->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                                <div class="form-group row">-->
<!--                                    <div class="col-lg-4">-->
<!--                                        <label>Начальная дата поставки:</label>-->
<!--                                        <date-picker v-model="filter.date_from" valueType="format" class="datepicker_width"></date-picker>-->
<!--                                    </div>-->
<!--                                    <div class="col-lg-4">-->
<!--                                        <label>Конечная дата поставки:</label>-->
<!--                                        <date-picker v-model="filter.date_to" valueType="format" class="datepicker_width"></date-picker>-->
<!--                                    </div>-->
<!--                                    <div class="col-lg-4 mt-6">-->
<!--                                        <template>-->
<!--                                            <el-select class="p-0 datepicker_width" v-model="filter.company_id" filterable placeholder="Компания" >-->
<!--                                                <el-option-->
<!--                                                    v-for="item in companies"-->
<!--                                                    :key="item.AccountNum"-->
<!--                                                    :label="item.ContractorName"-->
<!--                                                    :value="item.AccountNum">-->
<!--                                                </el-option>-->
<!--                                            </el-select>-->
<!--                                        </template>-->
<!--                                    </div>-->

<!--                                    &lt;!&ndash;                                <div class="col-lg-3 pt-6">&ndash;&gt;-->
<!--                                    &lt;!&ndash;                                    <button type="submit" class="btn btn-primary mr-2">Найти</button>&ndash;&gt;-->
<!--                                    &lt;!&ndash;                                    <button type="reset" class="btn btn-secondary" @click="resetFilter">Сброс</button>&ndash;&gt;-->
<!--                                    &lt;!&ndash;                                </div>&ndash;&gt;-->
<!--                                </div>-->
<!--                            </div>-->

<!--                            <div class="container">-->
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class="col-xl-9">
                                                <div class="form-group row">
                                                    <div class="col-lg-4">
                                                        <label>Цвет:</label>
                                                        <input type="text" v-model="filter.color" class="form-control" placeholder="Введите цвет"/>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label>Артикул:</label>
                                                        <input type="text" v-model="filter.article" class="form-control" placeholder="Введите артикул"/>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label>Статусы реализации:</label>
                                                        <select class="form-control" id="project2" v-model="filter.realisation_status">
                                                            <option :value="null">Все статусы</option>
                                                            <option :key="item.realisation_status" :value="item.realisation_status" v-for="item in realisationStatus">{{item.decription}}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-lg-4">
                                                        <label>Начальная дата поставки:</label>
                                                        <date-picker v-model="filter.date_from" valueType="format" class="datepicker_width"></date-picker>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label>Конечная дата поставки:</label>
                                                        <date-picker v-model="filter.date_to" valueType="format" class="datepicker_width"></date-picker>
                                                    </div>
                                                    <div class="col-lg-4 mt-6">
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
                                                </div>
                                                <div class="col-lg-3 pt-6">
                                                    <button type="submit" class="btn btn-primary mr-2">Найти</button>
                                                    <button type="reset" class="btn btn-secondary" @click="resetFilter">Сброс</button>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 pt-0 mt-2">
                                                <!--begin::Tiles Widget 2-->
                                                <div class="card card-custom bg-danger gutter-b" style="height: auto">
                                                    <!--begin::Body-->
                                                    <div class="card-body d-flex flex-column p-0" style="position: relative;">
                                                        <!--begin::Stats-->
                                                        <div class="flex-grow-1 card-spacer-x pt-2 pb-2">
                                                            <div class="text-inverse-danger font-weight-bold">Заказанный обьём</div>
                                                            <div class="text-inverse-danger font-weight-bolder font-size-h3">{{volumes.SumQtySpeciallSku}}</div>
                                                            <div class="text-inverse-danger font-weight-bold">Измеренный обьём</div>
                                                            <div class="text-inverse-danger font-weight-bolder font-size-h3">{{volumes.SumQtyIzm}}</div>
                                                            <div class="text-inverse-danger font-weight-bold">Проданный обьём</div>
                                                            <div class="text-inverse-danger font-weight-bolder font-size-h3">{{volumes.SumQtySales}}</div>
                                                        </div>
                                                        <!--end::Stats-->
                                                        <!--begin::Chart-->
<!--                                                        <div id="kt_tiles_widget_2_chart" class="card-rounded-bottom" style="height: 50px; min-height: 50px;"><div id="apexcharts4zyv6cks" class="apexcharts-canvas apexcharts4zyv6cks apexcharts-theme-light" style="width: 215px; height: 50px;"><svg id="SvgjsSvg1121" width="215" height="50" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1124" class="apexcharts-annotations"></g><g id="SvgjsG1123" class="apexcharts-inner apexcharts-graphical"><defs id="SvgjsDefs1122"><clipPath id="gridRectMask4zyv6cks"><rect id="SvgjsRect1126" width="222" height="53" x="-3.5" y="-1.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMask4zyv6cks"><rect id="SvgjsRect1127" width="219" height="54" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs></g><g id="SvgjsG1128" class="apexcharts-area-series apexcharts-plot-series"></g><g id="SvgjsG1129" class="apexcharts-series" seriesName="NetxProfit" data:longestSeries="true" rel="1" data:realIndex="0"></g><g id="SvgjsG1130" class="apexcharts-series-markers-wrap apexcharts-element-hidden" data:realIndex="0"></g><g id="SvgjsG1131" class="apexcharts-datalabels" data:realIndex="0"></g></svg><div class="apexcharts-legend" style="max-height: 25px;"></div></div></div>-->
                                                        <!--end::Chart-->
                                                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 216px; height: 131px;"></div></div><div class="contract-trigger"></div></div></div>
                                                    <!--end::Body-->
                                                </div>
                                                <!--end::Tiles Widget 2-->
                                            </div>
                                        </div>
                                    </div>
                                </div>

<!--                            </div>-->

                        </div>
                    </form>

<!--                    <div class="card-footer"></div>-->

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
                                    width="220"
                                >
                                    <template slot-scope="scope">
                                        <el-button
                                            size="mini"
                                        ><a :href="'/zip/detail-orders/group/' +  scope.row.id" target="_blank" style="color: black">{{scope.row.Article}}</a></el-button>
                                    </template>
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
            volumes: {
                SumQtySpeciallSku: 0,
                SumQtyIzm: 0,
                SumQtySales: 0,
            },
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
                this.volumes = response.data.volumes;
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
