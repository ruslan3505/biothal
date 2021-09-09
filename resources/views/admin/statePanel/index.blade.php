@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    {{--  MODAL  --}}
    <div class="modal hide bd-example-modal-lg" id="state_panel_modal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="change_shop_cart">
                        <div class="form-row mb-3">
                            <div class="form-group col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Дата заказа</span>
                                    </div>
                                    <input type="text" class="form-control" id="date" placeholder="Дата"
                                           name="attribute_title">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Статус</span>
                                    </div>
                                    <select class="custom-select" id="status" name="status">
                                        @foreach($statuses as $key => $status)
                                            <option value="{{$key}}">{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Стоимость всех товаров</span>
                            </div>
                            <input type="text" class="form-control" id="full_price" placeholder="Полная стоимость"
                                   name="attribute_title">
                        </div>
                    </div>
                    <div class="container-fluid m-2">
                        <table class="table" id="products_state_panel_table">
                            <thead class="text-center">
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Изображение</th>
                                <th scope="col">Название товара</th>
                                <th scope="col">Количество</th>
                                <th scope="col">Скидка</th>
                                <th scope="col">Цена</th>
                                <th scope="col">Цена * Количество</th>
                            </tr>
                            </thead>
                            <tbody class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="b_change_shopping_cart" data-id=""
                            class="btn btn-warning position-relative">
                        Внести изменения
                    </button>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="prod-header border container-fluid">--}}
{{--        <div class="row page-header">--}}
{{--            <div class="container-fluid col-sm-12" >--}}
{{--                <div class="h1-prod col-sm-12">Добро Пожаловать, {{ auth()->user()->name }}</div>--}}
{{--                <div class="pull-right col-sm-3">--}}
{{--                    --}}{{--                    <button type="submit" id="button-shipping" form="form-order" formaction="" formtarget="_blank" data-toggle="tooltip" title="Распечатать список доставки" class="btn btn-info"><i class="fa fa-truck"></i></button>--}}
{{--                    --}}{{--                    <button type="submit" id="button-invoice" form="form-order" formaction="" formtarget="_blank" data-toggle="tooltip" title="Показать счет" class="btn btn-info"><i class="fa fa-print"></i></button>--}}
{{--                    --}}{{--                    <a href="" data-toggle="tooltip" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>--}}
{{--                    --}}{{--                    <button id="but-del" type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger" onclick="confirm('Данное действие необратимо. Вы уверены?') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>--}}
{{--                </div>--}}
{{--                <div class="col-sm-8">--}}
{{--                </div>--}}


{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Панель Состояния</div>
                    <div class="pull-right col-sm-6">
{{--                        <a href="javascript" data-toggle="modal" data-target="#add_access" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>--}}
{{--                        <button id="delete_acc" data-toggle="tooltip" data-placement="right" title="Удалить выбранные потребности" class="btn btn-danger">--}}
{{--                            <i class="fa fa-trash-o"></i>--}}
{{--                        </button>--}}
                    </div>
                    <div class="breadcrumb col-sm-12 pull-left" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href=""> Панель Состояния</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  PAGE  --}}
    <div class="border container-fluid" style="padding-left: 0;
    padding-right: 0;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="well">
                    <table class="table" id="state_panel_table">
                        <thead class="text-center">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Дата заказа</th>
                            <th scope="col">Полная стоимость</th>
                            <th scope="col">Редактировать</th>
                            <th scope="col">Товары</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/statePanel.js')}}"></script>
@endsection
