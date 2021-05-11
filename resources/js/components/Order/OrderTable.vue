<template>
    <div class="card card-custom">

        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Информация о заказе компании
                </h3>
<!--                <div class="scroll-table">-->
<!--                    <table>-->
<!--                        <thead>-->
<!--                        <tr>-->
<!--                            <th>Название блюда</th>-->
<!--                            <th>Белки</th>-->
<!--                            <th>Жиры</th>-->
<!--                            <th>Углеводы</th>-->
<!--                            <th>Ккал</th>-->
<!--                        </tr>-->
<!--                        </thead>-->
<!--                    </table>-->
<!--                    <div class="scroll-table-body">-->
<!--                        <table>-->
<!--                            <tbody>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Азу</td>-->
<!--                                <td>11,9</td>-->
<!--                                <td>14,2</td>-->
<!--                                <td>10,2</td>-->
<!--                                <td>214</td>-->
<!--                            </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-9 col-sm-12 p-10">

                <h3>Фильтры поиска</h3>

                <form class="form" @submit.prevent="setFilter()">

                    <div class="form-group">

                        <label for="project">Статус заказа</label>
                        <select class="form-control status_select" id="project" v-model="filter.status">
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
                class="table table-bordered table-checkable dataTable no-footer dtr-inline collapsed orders__tracking__table table-hover"
            />
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
                // 'AccountNum',
                'ContractorName',
                // 'ContractorNameActual',
                // 'OrderConfirmQTY',
                // 'Prodstatus',
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
                    // AccountNum: 'AccountNum',
                    ContractorName: 'ContractorName',
                    // ContractorNameActual: 'ContractorNameActual',
                    // OrderConfirmQTY: 'OrderConfirmQTY',
                    // Prodstatus: 'Prodstatus',
                },
                filterable: false,
                perPageValues: [], // [5, 10, 100, 500]
                // skin: 'table table-bordered table-checkable dataTable no-footer dtr-inline collapsed orders__tracking__table table-hover',
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
        this.$refs['task-table'].setLimit(10);
        this.interval = setInterval(() => {
            this.getData();
        }, 5000);
    },

    destroyed() {
        clearInterval(this.interval);
    }

}

</script>

<style scoped>
.scroll-table-body {
    height: 300px;
    overflow-x: auto;
    margin-top: 0px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.scroll-table table {
    width:100%;
    table-layout: fixed;
    border: none;
}
.scroll-table thead th {
    font-weight: bold;
    text-align: left;
    border: none;
    padding: 10px 15px;
    background: #d8d8d8;
    font-size: 14px;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
}
.scroll-table tbody td {
    text-align: left;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    padding: 10px 15px;
    font-size: 14px;
    vertical-align: top;
}
.scroll-table tbody tr:nth-child(even){
    background: #f3f3f3;
}

/* Стили для скролла */
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}
::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}

.status_select {
    width: 60%;
}
</style>
