<template>
    <div class="row">
        <div class="content d-flex flex-column flex-column-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-3">
                    <div class="card-title">
                        <h3 class="card-label">
                            Список пользователей
                        </h3>
                    </div>
                    <div class="card-toolbar">

                        <a href="#" class="btn btn-primary font-weight-bolder" @click="createUserModal">
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
                            Создать пользователя
                        </a>
                        <!--end::Button-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <v-server-table
                            :url="url"
                            :columns="columns"
                            :options="options"
                            class="faq-table"
                            ref="user-table"
                        >
                            <div class="" slot="actions" slot-scope="props">
                                <a href="javascript:;" @click="showUser(props.row)">
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
                                <a href="javascript:;" @click="deleteUser(props.row)">
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
                            </div>
                        </v-server-table>
                    </div>
                </div>
            </div>
        </div>

        <modal name="user-info" :height="'auto'">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">
                        Информация о пользователе
                    </h3>
                </div>
                <!--begin::Form-->
                <form @submit.prevent="editUser">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Имя<span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter name"
                                v-model="form.user.name"
                            />
                        </div>
                        <div class="form-group">
                            <label>Электронная почта<span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control"
                                :placeholder="form.user.email"
                                disabled
                            />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Пароль</label>
                            <input
                                type="password"
                                class="form-control"
                                id="exampleInputPassword1"
                                placeholder="Password"
                                v-model="form.user.password"
                            />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">Подтверждение пароля</label>
                            <input
                                type="password"
                                class="form-control"
                                id="exampleInputPassword2"
                                placeholder="Password"
                                v-model="form.user.password_confirmation"
                            />
                        </div>
                        <div class="form-group">
                            <label>Номер аккаунта (для статистики)<span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Account id"
                                v-model="form.user.account_id"
                            />
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1">Менеджер<span class="text-danger">*</span></label>
                            <select
                                class="form-control"
                                id="exampleSelect1"
                                v-model="form.user.is_manager"
                            >
                                <option :value="1">Да</option>
                                <option :value="0">Нет</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect2">Администратор<span class="text-danger">*</span></label>
                            <select
                                class="form-control"
                                id="exampleSelect2"
                                v-model="form.user.is_admin"
                            >
                                <option :value="1">Да</option>
                                <option :value="0">Нет</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">Сохранить</button>
                        <button type="reset" class="btn btn-secondary" @click="hide('user-info')">Отмена</button>
                    </div>
                </form>
                <!--end::Form-->
            </div>
        </modal>

        <modal name="user-create" :height="'auto'">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">
                        Информация о пользователе
                    </h3>
                </div>
                <!--begin::Form-->
                <form @submit.prevent="createUser">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Имя<span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter name"
                                v-model="new_user.name"
                            />
                        </div>
                        <div class="form-group">
                            <label>Электронная почта<span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Enter email"
                                v-model="new_user.email"
                            />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPasswordCreate">Пароль</label>
                            <input
                                type="password"
                                class="form-control"
                                id="exampleInputPasswordCreate"
                                placeholder="Password"
                                v-model="new_user.password"
                            />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPasswordConfirm">Подтверждение пароля</label>
                            <input
                                type="password"
                                class="form-control"
                                id="exampleInputPasswordConfirm"
                                placeholder="Password"
                                v-model="new_user.password_confirmation"
                            />
                        </div>
                        <div class="form-group">
                            <label>Номер аккаунта (для статистики)<span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Account id"
                                v-model="new_user.account_id"
                            />
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectCreate">Менеджер<span class="text-danger">*</span></label>
                            <select
                                class="form-control"
                                id="exampleSelectCreate"
                                v-model="new_user.is_manager"
                            >
                                <option :value="1">Да</option>
                                <option :value="0">Нет</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectCreateAdmin">Администратор<span class="text-danger">*</span></label>
                            <select
                                class="form-control"
                                id="exampleSelectCreateAdmin"
                                v-model="new_user.is_admin"
                            >
                                <option :value="1">Да</option>
                                <option :value="0">Нет</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">Сохранить</button>
                        <button type="reset" class="btn btn-secondary" @click="hide('user-create')">Отмена</button>
                    </div>
                </form>
                <!--end::Form-->
            </div>
        </modal>

        <notifications group="foo" />

    </div>

</template>

<script>
export default {
    name: "UserIndex",

    data () {
        return {
            new_user: {},
            form: {
                user: {},
            },
            url: 'admin/users',
            columns: [
                'id',
                'email',
                'name',
                'is_admin',
                'is_manager',
                'account_id',
                'actions',
            ],
            options: {
                headings: {
                  'id': 'номер',
                  'account_id': 'id аккаунта',
                  'email': 'почта',
                  'is_admin': 'админ',
                  'is_manager': 'менеджер',
                  'name': 'имя',
                  'actions': 'действия',
                },
                filterable: false,
            }
        }
    },

    methods: {
        showUser(user){
            this.form.user = Object.assign({},user);
            this.$modal.show('user-info');
        },
        deleteUser(user){
            Swal.fire({
                title: 'Удалить пользователя?',
                text: "Выбранный пользователь будет удален",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Да, удалить',
                cancelButtonText: 'Отмена',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('admin/users/' + user.id).then(response => {
                        this.$notify({
                            group: 'foo',
                            type: 'success',
                            title: 'даление пользователя',
                            text: 'Пользователь успешно удален'
                        });
                        Swal.fire(
                            'Удалено!',
                            'Пользователь удален',
                            'success'
                        )
                        this.$refs['user-table'].refresh();
                    });
                }
            })
        },
        show(modal_name, row) {
            this.$modal.show(modal_name, row);
        },
        hide(modal_name) {
            this.$modal.hide(modal_name);
        },
        editUser(){
            axios.put('admin/users/' + this.form.user.id, this.form.user).then(response => {
                this.$refs['user-table'].refresh();
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Редактирование пользователья',
                    text: 'Пользователь успешно отредактирован'
                });
                this.$modal.hide('user-info');
            }).catch(error => {
                this.$notify({
                    group: 'foo',
                    type: 'error',
                    title: 'Редактирование пользователья',
                    text: 'Во время редактирования пользователя произошла ошибка'
                });
            });
        },
        createUserModal(){
            this.form.user = Object.assign({});
            this.show('user-create', this.form.user);
        },
        createUser(){
            console.log('create new user');
            console.log(this.new_user);
            axios.post('admin/users', this.new_user).then(response => {
                this.$refs['user-table'].refresh();
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Создание пользователя',
                    text: 'Пользователь успешно создан!'
                });
                this.$modal.hide('user-create');
            }).catch(error => {
                this.$notify({
                    group: 'foo',
                    type: 'error',
                    title: 'Создание пользователя пользователья',
                    text: 'Во время создания пользователя произошла ошибка'
                });
            });
        },
    },
}
</script>

<style scoped>

</style>
