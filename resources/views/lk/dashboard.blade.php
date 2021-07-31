@extends('template.main')

@section('content')
    <div class="container" style="margin: 0 auto">
        <div class="alert alert-custom alert-white alert-shadow fade show gutter-b" role="alert">
            <div class="alert-icon">
                <span class="svg-icon svg-icon-primary svg-icon-xl">
                    <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Tools/Compass.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                            <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </div>
            <div class="alert-text">Metronic extends
                <code>Bootstrap Card</code>with
                <code>.card-custom</code>class to provide a wide range of options for multi-purpose cards.
                <br>For more info please visit Bootstrap Card's
                <a class="font-weight-bold" href="https://getbootstrap.com/docs/4.6/components/card/" target="_blank">Documentation</a>.</div>
        </div>

{{--        <div class="card card-custom">--}}
{{--            <div class="card-header flex-wrap border-0 pt-6 pb-0">--}}
{{--                <div class="card-title">--}}
{{--                    <h1 class="card-label">Статистика расходов--}}
{{--                    </h1>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="card card-custom gutter-b col-xl-12">--}}
{{--                <div class="card-header">--}}
{{--                    <table class="table table-hover">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th scope="col">Class</th>--}}
{{--                            <th scope="col">Heading</th>--}}
{{--                            <th scope="col">Heading</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        <tr class="table-active">--}}
{{--                            <th scope="row">Active</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th scope="row">Default</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}

{{--                        <tr class="table-primary">--}}
{{--                            <th scope="row">Primary</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr class="table-secondary">--}}
{{--                            <th scope="row">Secondary</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr class="table-success">--}}
{{--                            <th scope="row">Success</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr class="table-danger">--}}
{{--                            <th scope="row">Danger</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr class="table-warning">--}}
{{--                            <th scope="row">Warning</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr class="table-info">--}}
{{--                            <th scope="row">Info</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr class="table-light">--}}
{{--                            <th scope="row">Light</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        <tr class="table-dark">--}}
{{--                            <th scope="row">Dark</th>--}}
{{--                            <td>Cell</td>--}}
{{--                            <td>Cell</td>--}}
{{--                        </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
    </div>
@endsection
