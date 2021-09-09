@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12">
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Настройки</div>
{{--                    <div class="pull-right col-sm-6">--}}
{{--                        <a href="/admin/black_header_edit" title="Редактировать" class="btn btn-primary">--}}
{{--                            <i aria-hidden="true" class="fa fa-pencil"></i>--}}
{{--                        </a>--}}
{{--                    </div>--}}
                    <div class="breadcrumb col-sm-12" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/</div>
                        <div><a href="{{route('admin.layouts.settings')}}"> Настройки</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border container-fluid" style="padding-left: 0;
        padding-right: 0;">
        <div class="panel panel-default">
            <div class="panel-body">
{{--                <p><a style="cursor: auto; " class="font-weight-bolder h3">Текущее содержание чёрной полоски: </a>--}}
{{--                </p>--}}
{{--                <p style="font-size: 15px;">{{$settings}}</u></p>--}}
                {{--                <div class="form-group row">--}}
                {{--                    <label style="padding-left: 80px" for="black_line" class="col-sm-2 col-form-label">Содержане чёрной строки--}}
                {{--                    </label>--}}
                {{--                    <div class="col-sm-10">--}}
                {{--                        <textarea class="form-control black_line" rows="3">{{$black_line}}</textarea>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <table class="table table-bordered table-hover" id="emails_table" style="width:100%">
                    <thead class="text-center">
                    <tr>
                        <th scope="col">Идентификатор</th>
                        <th scope="col">Настройка</th>
                        <th scope="col">Содержание настройки</th>
                        <th scope="col">Редактировать</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                    @foreach($settings as $setting)
                    <tr class="text-center">
                        <td>
                            {{$setting['id']}}
                        </td>
                        <td>
                            {{$setting['setting_name']}}
                        </td>
                        <td>
                            {{$setting['setting_content']}}
                        </td>
                        <td>
                            <a href="/admin/black_header_edit" title="Редактировать" class="btn btn-primary">
                                <i aria-hidden="true" class="fa fa-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/blackLine.js')}}"></script>
@endsection
