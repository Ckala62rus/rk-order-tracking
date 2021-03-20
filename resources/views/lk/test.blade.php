@extends('template.main')

@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h1 class="card-label">Статистика расходов
                </h1>
            </div>
        </div>

        <div class="card card-custom gutter-b col-xl-6">
            <div class="card-header">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Class</th>
                        <th scope="col">Heading</th>
                        <th scope="col">Heading</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="table-active">
                        <th scope="row">Active</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr>
                        <th scope="row">Default</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>

                    <tr class="table-primary">
                        <th scope="row">Primary</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr class="table-secondary">
                        <th scope="row">Secondary</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr class="table-success">
                        <th scope="row">Success</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr class="table-danger">
                        <th scope="row">Danger</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr class="table-warning">
                        <th scope="row">Warning</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr class="table-info">
                        <th scope="row">Info</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr class="table-light">
                        <th scope="row">Light</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    <tr class="table-dark">
                        <th scope="row">Dark</th>
                        <td>Cell</td>
                        <td>Cell</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-custom gutter-b col-xl-12">
            <div class="card-header">
                <table class="table table-bordered table-checkable dataTable no-footer dtr-inline ">
                    <thead>
                    <tr>
                        <th scope="col">Номер заказа покупателя</th>
                        <th scope="col">Максимальная дата поставки</th>
                        <th scope="col"> Запрошенная дата поставки</th>
                        <th scope="col">Количество заказанное клиентом (дм<sup>2</sup>)</th>
                        <th scope="col">Фактически сделанное (дм<sup>2</sup>)</th>
                        <th scope="col">КПП/ИНН</th>
                        <th scope="col">Менеджер</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                    </tr>
                    <tr>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                    </tr>
                    <tr>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                        <th scope="row">test</th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    Base Controls
                </h3>
                <div class="card-toolbar">
                    <div class="example-tools justify-content-center">
                        <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                        <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                    </div>
                </div>
            </div>
            <!--begin::Form-->
            <form>
                <div class="card-body">
                    <div class="form-group mb-8">
                        <div class="alert alert-custom alert-default" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                            <div class="alert-text">
                                The example form below demonstrates common HTML form elements that receive updated styles from Bootstrap with additional classes.
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control"  placeholder="Enter email"/>
                        <span class="form-text text-muted">We'll never share your email with anyone else.</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <label>Static:</label>
                        <p class="form-control-plaintext text-muted">email@example.com</p>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect1">Example select <span class="text-danger">*</span></label>
                        <select class="form-control" id="exampleSelect1">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect2">Example multiple select <span class="text-danger">*</span></label>
                        <select multiple="" class="form-control" id="exampleSelect2">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group mb-1">
                        <label for="exampleTextarea">Example textarea <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="reset" class="btn btn-primary mr-2">Submit</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
@endsection
