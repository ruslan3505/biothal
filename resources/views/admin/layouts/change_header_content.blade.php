@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Настройки</div>
                    <div class="pull-right col-sm-6">
                        <button type="button" title="Сохранить" class="btn btn-primary" id="save_black_header">
                            <i aria-hidden="true" class="fa fa-save"></i>
                        </button>
                        <a href="/admin/settings" title="Отменить" class="btn btn-default">
                            <i aria-hidden="true" class="fa fa-reply"></i>
                        </a>
                    </div>
                    <div class="breadcrumb col-sm-12" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
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
                <div class="form-group row">
                    <label style="padding-left: 80px" for="black_line" class="col-sm-2 col-form-label">Содержане чёрной
                        строки
                    </label>
                    <div class="col-sm-10">
                        <textarea class="form-control black_line" rows="3">{{$black_line}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/blackLine.js')}}"></script>
@endsection
