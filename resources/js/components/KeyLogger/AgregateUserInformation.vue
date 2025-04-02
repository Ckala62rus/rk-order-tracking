<template>
    <div>
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
<!--                        <span class="label label-info label-inline mr-2">Общее время работы: {{workTime}}</span>-->
                    </div>
                </div>
            </div>
        </form>

        <div v-show="aggregateFormLoad">
            <pulse-loader :loading="aggregateFormLoad" :color="'#5dc596'" :size="'15px'"></pulse-loader>
        </div>

        <div
            v-loading="aggregateFormLoad"
            element-loading-text="Loading..."
            element-loading-spinner="el-icon-loading"
            element-loading-background="rgba(0, 0, 0, 0.5)"
        >
            <v-client-table
                :data="tableData"
                :columns="columns"
                :options="options"
            >
                <div class="work" slot="work_time" slot-scope="props">
                    <p>{{getWorktime(props.row)}}</p>
                </div>
                <div class="actions_column" slot="actions" slot-scope="props">
                    <a href="javascript:;" @click="showDetailAggregateInformation(props.row)">
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
            </v-client-table>
        </div>

        <!--detail active windows modal begin-->
        <modal
            name="detail-aggregation_information"
            :height="'auto'"
            :width="'70%'"
            :scrollable=true
        >
            <div class="container">
                <div class="card card-custom rdp_statistic_mg" style="margin-top: 10px">
                    <div class="card-header">
                        <h3 class="card-title" v-if="userInfo">{{userInfo.fio}}</h3>
                    </div>
                    <div class="card-body">
                        <pulse-loader
                            :loading="aggregateFormModalLoad"
                            :color="'#5dc596'"
                            :size="'15px'"
                        ></pulse-loader>
                        <v-client-table
                            v-show="!aggregateFormModalLoad"
                            :data="dataDetailDataWindows"
                            :columns="columnsDetailDataWindows"
                            :options="optionsDetailDataWindows"
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
    name: "RkAggregateUserInformation",

    components: {
        PulseLoader
    },

    data() {
        return {
            currentAggregateFormModalLoadRow: null,
            dataDetailDataWindows: [],
            columnsDetailDataWindows: [
                'session_id',
                'window',
                'seconds',
            ],
            optionsDetailDataWindows: {
                // see the options API
                perPageValues: [10,25,30,35,50,100],
                skin: "VueTables__table " +
                    "table " +
                    "table-striped " +
                    "table-bordered " +
                    "table-hover " +
                    "vue__table__row " +
                    "vue__table__row__header",
                filterable: false,
                texts: {
                    limit: 'Вывод записей',
                    count: "Показано с {from} по {to} из {count} записей|{count} записей|Одна запись",
                },
            },
            columns: [
                // 'id',
                // 'login',
                'fio',
                'department',
                'organization',
                'work_time',
                // 'first_time',
                // 'last_time',
                'actions',
            ],
            tableData: [],
            options: {
                // see the options API
                perPageValues: [10,25,30,35,50,100],
                skin: "VueTables__table " +
                    "table " +
                    "table-striped " +
                    "table-bordered " +
                    "table-hover " +
                    "vue__table__row " +
                    "vue__table__row__header",
                filterable: false,
                texts: {
                    limit: 'Вывод записей',
                    count: "Показано с {from} по {to} из {count} записей|{count} записей|Одна запись",
                },
            },
            filter: {
                date_start: null,
                date_end: null,
                loginId: null,
            },
            url: '/key-logger-detail-group?',
            query: '',
            urlBase: '/key-logger-detail-group?',
            // users: {},
            workTime: '00:00:00',
        }
    },

    computed: {
        ...mapGetters({
            users: gettersTypes.users,
            aggregateFormLoad: gettersTypes.aggregateFormLoad,
            aggregateFormModalLoad: gettersTypes.aggregateFormModalLoad,
        }),

        userInfo() {
            if (this.currentAggregateFormModalLoadRow) {
                return this.currentAggregateFormModalLoadRow
            }
        },
    },

    methods: {
        getWorktime(row) {
            let login = `RK\\${row.login}`.toLowerCase()
            if ( this.workTime[login] ) {
                return this.workTime[login]
            }
            return "00:00:00"
        },

        eventClearTool(){
            this.filter.login = null
        },

        async setFilter() {
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
                await this.loadData();
                return
            }

            this.url = this.query;
            await this.loadData();
        },
        async resetFilter() {
            this.filter.date_start =
                this.filter.date_end =
                    this.filter.login = null;
            this.query = this.urlBase;
            this.url = this.query;
            await this.loadData();
        },
        getStatistic() {
            axios.get('/key-logger' ).then((response) => {
                console.log(response.data.data);
            })
        },

        async showDetailAggregateInformation(row){
            this.currentAggregateFormModalLoadRow = null
            this.currentAggregateFormModalLoadRow = row

            await this.$store.dispatch(actionTypes.aggregateFormModalLoad, true)

            const params = new URLSearchParams({});
            params.append('login', row.id)  // params => key, value

            let url = '/key-logger-detail-group-by-login?'

            if (this.filter.date_start != null) {
                params.append('date_start', this.filter.date_start)
            }

            if (this.filter.date_end != null) {
                params.append('date_end', this.filter.date_end)
            }

            this.$modal.show('detail-aggregation_information');

            await axios
                .get(url + params.toString())
                .then((response) => {
                    // console.log(response.data.data);
                    this.dataDetailDataWindows = response.data.data
                })

            await this.$store.dispatch(actionTypes.aggregateFormModalLoad, false)
        },

        async loadData() {
            this.$store.dispatch(actionTypes.aggregateFormLoad, true)

            await axios.get(this.url).then(async (response) => {
                const data = Object.entries(response.data.data)
                this.workTime =  response.data.workTime
                let result = [];

                data.forEach(elem => {
                    let login = elem[0]
                    let userData = elem[1]
                    let userPerformData = {}

                    userPerformData.id = userData.id
                    userPerformData.login = userData.login
                    userPerformData.department = userData.department
                    userPerformData.fio = userData.fio
                    userPerformData.organization = userData.organization
                    userPerformData.first_time = userData.first_time
                    userPerformData.last_time = userData.last_active_time

                    result.push(userPerformData)
                })

                this.tableData = result
                await this.$store.dispatch(actionTypes.aggregateFormLoad, false)
            }).finally(async () => {
                await this.$store.dispatch(actionTypes.aggregateFormLoad, false)
            })
        },

        toExcel(){
            let params = this.filter;

            axios({
                method:'GET',
                url: '/export2',
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
                    }
                });
        }
    },

    async mounted(){
        await this.loadData();
    },
}
</script>

<style scoped>

.actions_column {
    width: max-content;
}

</style>
