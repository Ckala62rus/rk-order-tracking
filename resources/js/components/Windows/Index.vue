<template>
    <div class="container">
        <div class="card card-custom rdp_statistic_mg">
            <div class="card-header">
                <h3 class="card-title">
                    Список серверов
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
                    <a href="#" class="btn btn-primary font-weight-bolder" @click="createServerModal">
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
                        Создать сервер
                    </a>
                    <a href="#" class="btn btn-success font-weight-bolder" @click="updateServices">
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

                <v-server-table
                    :url="url"
                    :columns="columns"
                    :options="options"
                    class="faq-table"
                    ref="server"
                >
                    <div class="" slot="active" slot-scope="props">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-outline checkbox-success" style="margin: 0 auto">
                                <input
                                    type="checkbox"
                                    name="Checkboxes15"
                                    :checked="props.row.enable"
                                    @change="check(props.row)"
                                >
                                <span></span>
                                <!--Checked-->
                            </label>
                        </div>
                    </div>

                    <div class="" slot="actions" slot-scope="props">
                        <a href="javascript:;" @click="showServer(props.row)">
                              <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg-->
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Edit.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                              </span>
                        </a>
                        <a href="javascript:;" @click="deleteServer(props.row.id)">
                              <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg><!--end::Svg Icon-->
                              </span>
                        </a>
                        <a :href="'/windows/service/' + props.row.id" target="_blank">
                              <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg-->
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-06-223557/theme/html/demo1/dist/../src/media/svg/icons/General/Visible.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
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
                        <h3>Редактирование</h3>
                    </div>
                </div>
            </modal>
            <!--detail modal end-->

            <modal name="server-create" :height="'auto'">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">
                            Создание сервера
                        </h3>
                    </div>
                    <!--begin::Form-->
                    <form @submit.prevent="createServer">
                        <div class="card-body" style="background-color: white;">
                            <div class="form-group">
                                <label>Имя сервера<span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter server name"
                                    v-model="new_server.server_name"
                                />
                            </div>
                            <div class="form-group">
                                <label>Описание сервера<span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter description server"
                                    v-model="new_server.description"
                                />
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Сохранить</button>
                            <button type="reset" class="btn btn-secondary" @click="hide('server-create')">Отмена</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </modal>

        </div>
    </div>
</template>

<script>
export default {
    name: "Index",

    data() {
        return {
            url: '/api/win/server',
            columns: [
                'id',
                'server_name',
                'description',
                'active',
                'actions',
            ],
            options: {
                headings: {
                    'id': 'id',
                    'server_name': 'Сервер',
                    'description': 'Описание',
                    'active': 'Включить для отображения',
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
            new_server: {},
        }
    },

    methods: {
        check(row){
            axios.post('/windows/server/enable', {server: row.id} ).then((response) => {
                this.$refs['server'].refresh();
            })
        },
        show(modal_name, row) {
            this.$modal.show(modal_name, row);
        },
        hide(modal_name) {
            this.$modal.hide(modal_name);
        },
        showServer(row) {
            console.log(row);
            this.$modal.show('detail');
        },
        createServerModal() {
            this.new_server = Object.assign({});
            this.show('server-create', this.new_server);
        },
        createServer() {
            axios.post('/api/win/server', this.new_server).then(response => {
                console.log(response);
                this.$refs['server'].refresh();
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Сервер создан',
                    text: 'Сервер успешно создан!'
                });
                this.$modal.hide('server-create');
            }).catch(error => {
                console.log(error);
                this.$notify({
                    group: 'foo',
                    type: 'error',
                    title: 'Ошибка создания сервера',
                    text: 'Во время создания сервера произошла ошибка'
                });
            });
        },
        deleteServer(id) {
            Swal.fire({
                title: 'Удалить сервер?',
                text: "Выбранный сервер будет удален",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да, удалить',
                cancelButtonText: 'Отмена',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/win/server/' + id).then(response => {
                        this.$notify({
                            group: 'foo',
                            type: 'success',
                            title: 'Удаление сервера',
                            text: 'Сервер успешно удален'
                        });
                        Swal.fire(
                            'Удалено!',
                            'Сервер удален',
                            'success'
                        )
                        this.$refs['server'].refresh();
                    });
                }
            })
        },
        updateServices(){

            this.$notify({
                group: 'foo',
                type: 'success',
                title: 'Обновление сервисов',
                text: 'Сервисы обновляются!'
            });

            axios.get('/windows/server-update').then((response) => {
                Swal.fire(
                    'Обновлено!',
                    'Обновление серисов прошло успешно.',
                    'success'
                )
            })
        },
    },
}
</script>

<style scoped>

</style>
