<template>
    <div class="container">
        <div class="card card-custom rdp_statistic_mg">
            <div class="card-header">
                <h3 class="card-title">
                    Список сервисов
                </h3>
                <div class="card-toolbar">
                    <div class="example-tools justify-content-center">
                        <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                        <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <v-server-table
                    :url="url"
                    :columns="columns"
                    :options="options"
                    class="faq-table"
                    ref="service"
                >
                    <div class="" slot="visible" slot-scope="props">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-outline checkbox-success" style="margin: 0 auto">
                                <input
                                    type="checkbox"
                                    name="Checkboxes15"
                                    :checked="props.row.visible"
                                    @change="check(props.row)"
                                >
                                <span></span>
                                <!--Checked-->
                            </label>
                        </div>
                    </div>
                </v-server-table>

            </div>

        </div>
    </div>
</template>

<script>

export default {
    name: "Services",

    props: [
        'id'
    ],

    data() {
        return {
            url: '/api/win/service?server_id=' + this.id,
            columns: [
                'id',
                'server',
                'service_name',
                'display_name',
                'status',
                'visible',
                // 'active'
            ],
            options: {
                headings: {
                    'id': 'id',
                    'server': 'Сервер',
                    'service_name': 'Имя сервиса',
                    'display_name': 'Описание сервиса',
                    'status': 'Статус',
                    'visible': 'Отображать в dashboard',
                    // 'active': 'Включить для отображения',
                },
                filterable: false,
                texts: {
                    limit: 'Вывод записей',
                    count: "Показано с {from} по {to} из {count} записей|{count} записей|Одна запись",
                },
                perPageValues: [10,25,30,35,50,100],
                skin: "VueTables__table " +
                    "table " +
                    "table-striped " +
                    "table-bordered " +
                    "table-hover " +
                    "vue__table__row " +
                    "vue__table__row__header",
                perPage: 500,
            },
        }
    },

    methods: {
        check(row){
            axios.post('/windows/service/enable', {service: row.id} ).then((response) => {
                this.$refs['service'].refresh();
            })
        },
    },
}

</script>

<style scoped>

</style>
