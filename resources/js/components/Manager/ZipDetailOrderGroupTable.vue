<template>

    <!--begin::Container-->
    <div class="">

        <!--        <h1>Детальная информация по заказам c id={{this.id}}</h1>-->

        <div class="col-xl-12" style="width: 100%">
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Сгрупированная информация по заказам</h3>
                    </div>
                </div>

                <form class="form" @submit.prevent="setFilter()">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>Статусы реализации:</label>
                                <select class="form-control datepicker_width" v-model="filter.realisation_status">
                                    <option :value="null">Все статусы</option>
                                    <option
                                        :key="item.realisation_status"
                                        :value="item.realisation_status"
                                        v-for="item in realisationStatus"
                                    >{{item.decription}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>Цвет:</label>
                                <input type="text" v-model="filter.color" class="form-control" placeholder="Введите цвет"/>
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
                                prop="Article"
                                label="Артикул"
                                width="250">
                            </el-table-column>
                            <el-table-column
                                prop="Color"
                                label="Цвет"
                                width="250">
                            </el-table-column>
                            <el-table-column
                                prop="Config"
                                label="Конфигурация"
                                width="250">
                            </el-table-column>
                            <el-table-column
                                prop="Thickness"
                                label="Толщина"
                                width="250">
                            </el-table-column>
                            <el-table-column
                                prop="SumQtySpeciallSku"
                                label="Заказанный обьем"
                                width="250">
                            </el-table-column>

                            <el-table-column
                                prop="SumQtyIzm"
                                label="Измеренный обьем"
                                width="250">
                            </el-table-column>
                            <el-table-column
                                prop="SumQtySales"
                                label="Проданный обьем"
                                width="250">
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
            url: "/zip/detail-orders/group/?" + "order_id=" + this.id,
            query: '',
            urlBase: '/zip/detail-orders/group/?' + "order_id=" + this.id,
            tableData: [],
            realisationStatus: [
                // {decription: "Реализовано", realisation_status: "3"},
                {decription: "Частично реализовано", realisation_status: "2"},
                {decription: "Не реализовано", realisation_status: "1"},
            ],
            status: {},
            filter: {
                realisation_status: null,
                color: null,
            },
        }
    },

    methods: {
        getData() {
            axios.get(this.url).then((response) => {
                this.tableData = response.data.data;
            })
        },
        getRealisationStatuses() {
            axios.get('/realisation-status' ).then((response) => {
                this.realisationStatus = response.data.data;
            })
        },
        setFilter() {
            this.query = this.urlBase;

            if (this.filter.realisation_status != null) {
                this.query += '&realisation_status=' + this.filter.realisation_status;
            }

            if (this.filter.color != null) {
                this.query += '&color=' + this.filter.color;
            }

            this.url = this.query;

            if (this.filter.company_id === null) {
                return;
            }
            this.getData();
        },
        resetFilter() {
            this.filter.realisation_status =
                this.filter.color = null;
            this.setFilter();
        },
    },

    created() {
        this.getData();
        // this.getRealisationStatuses();
    },

    mounted() {},
}
</script>

<style scoped>
.datepicker_width {
    width: 100%;
}
</style>
