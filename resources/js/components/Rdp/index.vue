<template>
    <div class="container">
        <div class="card card-custom rdp_statistic_mg">
            <div class="card-header">
                <h3 class="card-title">
                    Статистика по подключениям RDP
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
                    <div class="alert alert-custom alert-default" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                        <div class="alert-text">
                            Статистика за текущую дату будет подсчитана на следующий день!
                        </div>
                    </div>
                </div>

                <form class="form" @submit.prevent="setFilter()">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-9">

                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label>Начальная дата поставки:</label>
                                                <date-picker v-model="filter.date_start" valueType="format" class="datepicker_width"></date-picker>
                                            </div>
                                            <div class="col-lg-4">
                                                <label>Конечная дата поставки:</label>
                                                <date-picker v-model="filter.date_end" valueType="format" class="datepicker_width"></date-picker>
                                            </div>
                                            <div class="col-lg-4 mt-6">
                                                <template>
                                                    <el-select class="p-0 datepicker_width" v-model="filter.login" filterable placeholder="Пользователь" >
                                                        <el-option
                                                            v-for="item in users"
                                                            :key="item.id"
                                                            :label="item.login"
                                                            :value="item.login">
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
                            </div>
                        </div>
                    </div>
                </form>

                <v-server-table
                    :url="url"
                    :columns="columns"
                    :options="options"
                    class="faq-table"
                    ref="rdp"
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
                    </div>
                </v-server-table>
            </div>
            <!--statistic table end-->

            <!--detail modal begin-->
            <modal name="detail" :height="'auto'" :width="'70%'">
                <div class="container">
                    <div class="card card-custom rdp_statistic_mg" style="margin-top: 10px">
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

        </div>
    </div>
</template>

<script>
export default {
    name: "index",

    data() {
        return {
            url: 'api/rdp/statistic?',
            query: '',
            urlBase: 'api/rdp/statistic?',
            users: {},
            columns: [
                'id',
                'login',
                'date',
                'work_time',
                'actions',
            ],
            options: {
                headings: {
                    'id': 'id',
                    'login': 'Логин',
                    'date': 'Дата',
                    'work_time': 'Время работы',
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
                login: null,
            },
            columnsDetail: [
                'event_run',
                'date_run',
                'day_time_run',
                'event_end',
                'date_end',
                'day_time_end',
                'work_minute'
            ],
            detailData: [],
            optionsDetail: {
                headings: {
                    'event_run': 'Событие',
                    'date_run': 'Дата начала работы',
                    'day_time_run': 'Время начала работы',
                    'event_end': 'Событие',
                    'date_end': 'Дата окончания работы',
                    'day_time_end': 'Время окончания работы',
                    'work_minute': 'Время работы в минутах',
                },
                filterable: false,
                texts: {
                    count: "Показано с {from} по {to} из {count} записей|{count} записей|Одна запись",
                },
                // perPage: 2, // количество отображений записей
            },
        }
    },

    methods: {
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

            this.url = this.query;
        },
        resetFilter() {
            this.filter.date_start =
                this.filter.date_end =
                    this.filter.userId =
                        this.filter.login = null;

            this.url = this.urlBase;
        },
        getUsers() {
            axios.get('/api/rdp/users' ).then((response) => {
                this.users = response.data.data;
            })
        },
        showDetail(user){
            this.detailData = []; // обнуляем детализацию

            axios.post('/api/rdp/detail', {login: user.login, date: user.date} ).then((response) => {
                this.detailData = response.data.data;
                console.log(response.data.data);
            })
            this.$modal.show('detail');
        },
    },

    created() {
        this.getUsers();
    },
}
</script>

<style scoped>

.rdp_statistic_mg {
    margin-top: -20px;
    margin-bottom: 10px;
}

</style>
