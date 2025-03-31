<template>
    <div>
        <!--statistic table begin-->
        <form class="form" @submit.prevent="setFilter()">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-9">

                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label>Начальная дата:</label>
                                        <date-picker v-model="filter.date_start" valueType="format" class="datepicker_width"></date-picker>
                                    </div>
                                    <div class="col-lg-4">
                                        <label>Конечная дата:</label>
                                        <date-picker v-model="filter.date_end" valueType="format" class="datepicker_width"></date-picker>
                                    </div>
                                    <div class="col-lg-4 mt-6">
                                        <template>
                                            <el-select
                                                class="p-0 datepicker_width"
                                                v-model="filter.login"
                                                filterable
                                                placeholder="Пользователь"
                                                :clearable=true
                                                @clear="eventClearTool"
                                            >
                                                <el-option
                                                    v-for="user in users"
                                                    :key="user.id"
                                                    :label="user.fio"
                                                    :value="user.id">
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
                        </div>
                        <button class="btn btn-primary mr-2" @click="toExcel">Выгрузить Excel</button>
                        <span class="label label-info label-inline mr-2">Общее время работы: {{workTime}}</span>
                    </div>
                </div>
            </div>
        </form>

        <div v-show="detailFormLoad">
            <pulse-loader :loading="detailFormLoad" :color="'#5dc596'" :size="'15px'"></pulse-loader>
        </div>

        <div
            v-loading="detailFormLoad"
            element-loading-text="Loading..."
            element-loading-spinner="el-icon-loading"
            element-loading-background="rgba(0, 0, 0, 0.5)"
        >
            <v-server-table
                :url="url"
                :columns="columns"
                :options="options"
                @loaded="onLoaded"
                @loading="onLoading"
                class="vue-tables"
                ref="key-logger"
            >
                <div class="" slot="actions" slot-scope="props">
                    <a href="javascript:;" @click="showDetail(props.row)">
                              <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg-->
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-06-223557/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                             width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path
                                                    d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path
                                                    d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z"
                                                    fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon-->
                                    </span>
                              </span>
                    </a>

                    <a href="javascript:;" @click="showDetailWindowsSeconds(props.row)">
                              <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Design/Substract.svg-->
                                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                  </svg><!--end::Svg Icon-->
                              </span>
                    </a>

                </div>
            </v-server-table>
        </div>

        <!--statistic table end-->

        <!--detail modal begin-->
        <modal name="detail" :height="'auto'" :width="'70%'">
            <div class="container">
                <div class="card card-custom rdp_statistic_mg" style="margin-top: 10px">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{userName}}
                        </h3>
                    </div>
                    <div class="card-body">
                        <v-client-table
                            :data="detailData"
                            :columns="columnsDetail"
                            :options="optionsDetail"
                        />
                    </div>
                </div>
            </div>
        </modal>
        <!--detail modal end-->

        <!--detail active windows modal begin-->
        <modal
            name="detail-windows-seconds"
            :height="'auto'"
            :width="'70%'"
            :scrollable=true
        >
            <div class="container">
                <div class="card card-custom rdp_statistic_mg" style="margin-top: 10px">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{userName}}
                        </h3>
                    </div>
                    <div class="card-body">
                        <v-client-table
                            :data="detailDataWindows"
                            :columns="columnsDetailWindows"
                            :options="optionsDetailWindows"
                        />
                    </div>
                </div>
            </div>
        </modal>
        <!--detail modal end-->
    </div>
</template>

<script>

import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import {mapGetters} from "vuex"
import {actionTypes, gettersTypes} from "../../store/modules/logger";

export default {
    name: "RkDetailUserInformation",

    components: {
        PulseLoader
    },

    data() {
        return {
            loading: true,
            url: '/key-logger?',
            query: '',
            urlBase: '/key-logger?',
            // users: {},
            columns: [
                'id',
                // 'login',
                'fio',
                'department',
                'organization',
                'first_time',
                'last_active_time',
                'time',
                'downtime',
                'actions',
            ],
            options: {
                pagination:{
                    virtual: true
                },
                headings: {
                    'id': 'id',
                    // 'login': 'Логин',
                    'fio': 'ФИО',
                    'department': 'Департамент',
                    'organization': 'Организация',
                    'first_time': 'Начало сессии',
                    'last_active_time': 'Конец сессии',
                    'time': 'Время работы',
                    'downtime': 'Время простоя',
                    'actions': 'Действия',
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
            },
            filter: {
                date_start: null,
                date_end: null,
                loginId: null,
            },

            // detail active windows
            columnsDetail: [
                'active_window',
                'date',
            ],
            detailData: [],
            optionsDetail: {
                headings: {
                    'active_window': 'Активное окно',
                    'date': 'Дата записи',
                },
                filterable: false,
                texts: {
                    count: "Показано с {from} по {to} из {count} записей|{count} записей|Одна запись",
                },
                // perPage: 2, // количество отображений записей
            },

            // active windows in seconds
            columnsDetailWindows: [
                'session_id',
                'seconds',
                'window',
            ],
            detailDataWindows: [],
            optionsDetailWindows: {
                headings: {
                    'session_id': 'Сессия',
                    'window': 'Активное окно',
                    'seconds': 'Количество секунд работы в окне',
                },
                filterable: false,
                texts: {
                    count: "Показано с {from} по {to} из {count} записей|{count} записей|Одна запись",
                },
                // perPage: 2, // количество отображений записей
            },

            workTime: '00:00:00',
            userName: '',
        }
    },

    computed: {
        ...mapGetters({
            users: gettersTypes.users,
            detailFormLoad: gettersTypes.detailFormLoad,
        }),
    },

    methods: {
        openFullScreen2() {
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            setTimeout(() => {
                loading.close();
            }, 2000);
        },

        handleClick(tab, event) {
            // console.log(tab, event);
            // console.log(
            //     `%c tab %c tap on tab v${tab} %c`,
            //     'background:#35495e ; padding: 1px; border-radius: 3px 0 0 3px;  color: #fff',
            //     'background:#41b883 ; padding: 1px; border-radius: 0 3px 3px 0;  color: #fff',
            //     'background:transparent'
            // )
        },

        eventClearTool(){
            this.filter.login = null
        },

        setFilter() {
            this.query = this.urlBase;

            if (this.filter.date_start != null) {
                this.query += '&date_start=' + this.filter.date_start;
            }

            if (this.filter.date_end != null) {
                this.query += '&date_end=' + this.filter.date_end;
            }

            if (this.filter.login != null) {
                this.query += '&login=' + this.filter.login;
            }

            if(this.query === this.url){
                this.$refs['key-logger'].refresh();
            }

            this.url = this.query;
        },
        resetFilter() {
            this.filter.date_start =
                this.filter.date_end =
                    this.filter.login = null;

            if (this.url === this.urlBase) {
                this.$refs['key-logger'].refresh();
            } else {
                this.url = this.urlBase;
            }
        },
        getStatistic() {
            axios.get('/key-logger' ).then((response) => {
                // this.users = response.data.data;
                console.log(response.data.data);
            })
        },
        showDetail(row){
            this.detailData = []; // обнуляем детализацию

            this.userName = '';
            this.userName = row.fio;

            this.detailData = row.details;
            this.$modal.show('detail');
        },

        showDetailWindowsSeconds(row){
            this.detailDataWindows = []; // обнуляем детализацию

            this.userName = '';
            this.userName = row.fio;

            this.detailDataWindows = row.activeWindowsSeconds;
            this.$modal.show('detail-windows-seconds');
        },

        onLoaded(e){
            this.$store.dispatch(actionTypes.detailFormLoad, false)
            this.workTime = e.data.workTime;
            this.$notify({
                group: 'foo',
                type: 'success',
                title: 'Данные обновлены',
                text: 'Данные обновлены'
            });
        },

        onLoading(e) {
            this.$store.dispatch(actionTypes.detailFormLoad, true)
        },

        toExcel(){
            let params = this.filter;

            axios({
                method:'GET',
                url: '/export',
                responseType: 'blob',
                params: {
                    date_start: params.date_start,
                    date_end: params.date_end,
                    login: params.login,
                }
            })
                .then((response) => {
                    if (response.status === 200){
                        // console.log(response.headers['accept-ranges'])
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'output.xlsx'); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                        // console.log(response);
                    }
                });
        }
    },

    mounted() {
        // this.$loading({
        // })
    }
}
</script>

<style scoped>

.rdp_statistic_mg {
    margin-top: -20px;
    margin-bottom: 10px;
}

.test{
    font-size: 20px;
}

</style>
