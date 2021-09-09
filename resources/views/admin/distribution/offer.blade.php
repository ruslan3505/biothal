@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .input-group {
            display: flex;
        }
    </style>
    <style>

    </style>
@endsection

@section('content')

    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Дистрибьюторы</div>
                    <div class="pull-right col-sm-6">
                        <button id="delete_offer" type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                    <div class="pull-right col-sm-6">
{{--                        <a href="javascript" data-target="#add_emails" data-toggle="modal" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>--}}
{{--                        <a href="javascript" data-target="#groups" data-toggle="modal" title="Группа" class="btn btn-info">Группы</a>--}}
{{--                        <a href="javascript" data-target="#send_emails" data-toggle="modal" title="Отправить Эмейл" class="btn btn-success">Отправить Рассылку</a>--}}
{{--                        <button id="delete_email" type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger">--}}
{{--                            <i class="fa fa-trash-o"></i>--}}
{{--                        </button>--}}
                    </div>
                    <div class="breadcrumb col-sm-12 pull-left" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href=""> Дистрибьюторы</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border container-fluid" style="padding-left: 0;
    padding-right: 0;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="well">
                    @if($offers == null)
                        <p>Дистрибьюторы отсутствуют ¯\_(ツ)_/¯</p>
                    @else
                        <div class="container-fluid m-2">
                            <table class="table table-bordered table-hover" id="offer_table" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th scope="col">Идентификатор</th>
                                        <th scope="col">Имя</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Телефоный номер</th>
                                        <th scope="col">Сообщение</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script type="text/javascript">
        $('.summernote').summernote();
    </script>
    <script src="{{asset('js/offers.js')}}"></script>
@endsection
