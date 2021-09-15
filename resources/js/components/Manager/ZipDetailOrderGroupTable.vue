<template>

    <!--begin::Container-->
    <div class="">

        <!--        <h1>Детальная информация по заказам c id={{this.id}}</h1>-->

        <div class="col-xl-12" style="width: 100%">
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Подробная информация по заказам</h3>
                    </div>
                </div>

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
                                prop="OrderDate"
                                label="Дата размещения заказа"
                                width="145">
                            </el-table-column>

                            <el-table-column
                                prop="SumQtySpeciallSku"
                                label="Заказанный обьем"
                                width="110">
                            </el-table-column>

                            <el-table-column
                                prop="ProdOrderNumber"
                                label="Номер заказа"
                                width="115">
                            </el-table-column>

                            <el-table-column
                                prop="ProdOrderStatus"
                                label="Статус заказа"
                                width="120">
                            </el-table-column>

                            <el-table-column
                                prop="ProdOrderQTY"
                                label="Обьем заказа"
                                width="120">
                            </el-table-column>

                            <el-table-column
                                prop="DeliveryDate"
                                label="Дата поставки"
                                width="120">
                            </el-table-column>

                            <el-table-column
                                prop="EndDate"
                                label="Дата окончания"
                                width="130">
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
            url: "/zip/detail-orders/group/?",
            query: '',
            urlBase: '/zip/detail-orders/group/?',
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
            axios.get(this.url + "order_id=" + this.id).then((response) => {
                this.tableData = response.data.data;
            })
        },
    },

    created() {
        this.getData();
    },

    mounted() {},
}
</script>

<style scoped>
.datepicker_width {
    width: 100%;
}
</style>
