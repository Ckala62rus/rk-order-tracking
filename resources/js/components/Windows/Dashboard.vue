<template>
    <div class="container">
        <div class="card card-custom rdp_statistic_mg">
            <div class="card-header">
                <h3 class="card-title">
                    Удалённый мониторинг и управление службами на серверах.
                </h3>
                <div class="card-toolbar">
                    <div class="example-tools justify-content-center">
                        <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                        <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                    </div>
                </div>
            </div>

            <!--statistic table begin-->
            <div class="card-body">

                <div class="form-group mb-8">
                    <a href="javascript:;" @click="spinner" class="btn btn-primary font-weight-bolder" >
                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/metronic/themes/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                    <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                        Dashboard
                    </a>
                    <a href="javascript:;" @click="refreshData" class="btn btn-success font-weight-bolder" >
                        <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/metronic/themes/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                    <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                        Обновить
                    </a>
                </div>

                <div v-if="loading">
                    <pulse-loader :loading="loading" :color="'#5dc596'" :size="'15px'"></pulse-loader>
                </div>
                <div v-else>
                    <div v-for="server in servers" :key="server.id">
                        <h3>Сервер: {{server.server_name}}</h3>
                        <v-client-table
                            :data="server.services"
                            :columns="columns"
                            :options="options"
                        >
                            <div class="" slot="actions" slot-scope="props" v-if="userIsAdmin == 1">
                                <a
                                    href="javascript:;"
                                    :class="spinnerStartService ? classes : ''"
                                    class="btn btn-success"
                                    @click="startService(server.server_name, props.row)"
                                >Включить</a>

                                <a
                                    href="javascript:;"
                                    :class="spinnerStopService ? classes : ''"
                                    class="btn btn-danger"
                                    @click="stopService(server.server_name, props.row)"
                                >Отключить</a>
                            </div>
                        </v-client-table>
                    </div>
                </div>

            </div>
            <!--statistic table end-->

        </div>
    </div>
</template>

<script>

import PulseLoader from 'vue-spinner/src/PulseLoader.vue'

export default {
    name: "Dashboard",

    components: {
        PulseLoader
    },

    data() {
        return {
            columns: [
                'id',
                'server',
                'service_name',
                'display_name',
                'status',
                'actions',
            ],
            options: {
                headings: {
                    'id': 'id',
                    'server': 'Сервер',
                    'service_name': 'Служба',
                    'display_name': 'Описание службы',
                    'status': 'Статус',
                    'actions': 'Действия',
                },
                filterable: false,
                texts: {
                    limit: 'Вывод записей',
                    count: "Показано с {from} по {to} из {count} записей|{count} записей|Одна запись",
                    noResults: "Нет записей или ничего не выбрано для отображения",
                },
                perPageValues: [10,25,30,35,50,100],
                skin: "VueTables__table " +
                    "table " +
                    "table-striped " +
                    "table-bordered " +
                    "table-hover " +
                    "vue__table__row " +
                    "vue__table__row__header",
            },
            servers: {},
            loading: true,
            spinnerStartService: false,
            spinnerStopService: false,
            classes: ['spinner', 'spinner-white', 'spinner-right'],
            userIsAdmin: null,
        }
    },

    methods: {
        getData(){
            axios.get('/windows/server/services').then((response) => {
                this.servers = response.data.data;
                this.loading = false;
            }).catch( () => {
                this.loading = false;
            });
        },
        refreshData(hideSpinner = true) {
            if (hideSpinner) {
                this.loading = true;
            }

            axios.get('/windows/server-update').then((response) => {
                this.getData();
            }).catch(()=>{
                Swal.fire(
                    'Ошибка',
                     'Ошибка при получении данных, перезагрузите страницу или нажмите кнопку "Обновить"!',
                    'error'
                )
                this.loading = false;
            });
        },
        spinner() {
            this.loading = !this.loading;
        },
        startService(server, row) {
            let request = {server: server, service: row.service_name};

            this.triggerSpinnerStart();
            axios.post('/windows/server/services-start', request).then((response) => {
                if (response.data.data.Error) {
                    Swal.fire(
                        'Внимание',
                        response.data.data.Error,
                        'warning'
                    )
                    this.triggerSpinnerStart();
                    return;
                }
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Обновление данных',
                });
                this.triggerSpinnerStart();
                this.refreshData(false);
            }).catch((error) => {
                Swal.fire(
                    'Ошибка',
                    error,
                    'error'
                );
                this.triggerSpinnerStart();
            });
        },
        stopService(server, row) {
            let request = {server: server, service: row.service_name};

            this.triggerSpinnerStop();
            axios.post('/windows/server/services-stop', request).then((response) => {
                if (response.data.data.Error) {
                    Swal.fire(
                        'Внимание',
                        response.data.data.Error,
                        'warning'
                    );
                    this.triggerSpinnerStop();
                    return;
                }
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Обновление данных',
                });
                this.triggerSpinnerStop();
                this.refreshData(false);
            }).catch((error) => {
                Swal.fire(
                    'Ошибка',
                    error,
                    'error'
                );
                this.triggerSpinnerStop();
            });
        },
        triggerSpinnerStart() {
            //Переключаем спиннер на кнопке включения
            this.spinnerStartService = !this.spinnerStartService;
        },
        triggerSpinnerStop() {
            //Переключаем спиннер на кнопке выключения
            this.spinnerStopService = !this.spinnerStopService;
        },
        getMe() {
            axios.post('/user').then((response) => {
                this.userIsAdmin = response.data.user.is_admin;
            });
        },
    },

    mounted() {
        this.refreshData();
        this.getMe();
    }
}
</script>

<style scoped>

</style>
