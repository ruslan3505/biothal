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
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Заказы</div>
                    <div class="pull-right col-sm-6">
                        {{--                    <button type="submit" id="button-shipping" form="form-order" formaction="" formtarget="_blank" data-toggle="tooltip" title="Распечатать список доставки" class="btn btn-info"><i class="fa fa-truck"></i></button>--}}
                        {{--                    <button type="submit" id="button-invoice" form="form-order" formaction="" formtarget="_blank" data-toggle="tooltip" title="Показать счет" class="btn btn-info"><i class="fa fa-print"></i></button>--}}
                        {{--                    <a href="" data-toggle="tooltip" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>--}}
                        {{--                    <button id="but-del" type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger" onclick="confirm('Данное действие необратимо. Вы уверены?') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>--}}
                    </div>
                    <div class="breadcrumb col-sm-12" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href="{{route('admin.products.pageNew')}}"> Заказы</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="padding-left: 0;
        padding-right: 0;">
        <div class="row" style="margin-left: -10px;">
            <div class="col-md-6">
                <div class="panel panel-default" style="margin-top: 10px;">
                    <div class="panel-heading" style="margin-top: 0px;">
                        <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Заказ</h3>
                    </div>
                    <table class="table">
                        <tr>
                            <td style="width: 1%;"><button data-toggle="tooltip" title="Магазин" class="btn btn-info btn-xs"><i class="fa fa-shopping-cart fa-fw"></i></button></td>
                            <td><a href="/" target="_blank">Biothal</a></td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="Дата добавления" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                            <td>{{ date( 'd.m.y H:i', strtotime($order['created_at'])) }}</td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="Способ оплаты" class="btn btn-info btn-xs"><i class="fa fa-credit-card fa-fw"></i></button></td>
                            <td>{{ $order['order_type']['title'] ?? 'Не выбрано' }}</td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="Способ доставки" class="btn btn-info btn-xs"><i class="fa fa-truck fa-fw"></i></button></td>
                            <td>Доставка Новой Почтой</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default" style="margin-top: 10px;">
                    <div class="panel-heading" style="margin-top: 0px;">
                        <h3 class="panel-title"><i class="fa fa-user"></i> Клиент</h3>
                    </div>
                    <table class="table">
                        <tr>
                            <td style="width: 1%;"><button data-toggle="tooltip" title="Клиент" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
                            <td>@if(!empty($registered_user['name'])) {{$registered_user['name']}} @else N/A @endif </td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="Тип клиента" class="btn btn-info btn-xs"><i class="fa fa-group fa-fw"></i></button></td>
                            <td>
                                @if(!empty($registered_user['type']))
                                    {{ucfirst($registered_user['type'])}}
                                @else
                                    Не зарегистрирован
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="E-Mail" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
                            <td>
                                <a href="mailto:nomail@biothal.com.ua">
                                    @if(!empty($registered_user['email']))
                                        {{$registered_user['email']}}
                                    @else
                                        N/A
                                    @endif
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><button data-toggle="tooltip" title="Телефон" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
                            <td>
                                @if(!empty($registered_user['phone']))
                                    {{$registered_user['phone']}}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
{{--            <div class="col-md-4">--}}
{{--                <div class="panel panel-default">--}}
{{--                    <div class="panel-heading">--}}
{{--                        <h3 class="panel-title"><i class="fa fa-cog"></i> Опции</h3>--}}
{{--                    </div>--}}
{{--                    <table class="table">--}}
{{--                        <tbody>--}}
{{--                        <tr>--}}
{{--                            <td>Счет</td>--}}
{{--                            <td id="invoice" class="text-right"></td>--}}
{{--                            <td style="width: 1%;" class="text-center">                  <button id="button-invoice" data-loading-text="Загрузка..." data-toggle="tooltip" title="Генерировать" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Бонусные баллы</td>--}}
{{--                            <td class="text-right">0</td>--}}
{{--                            <td class="text-center">                  <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>--}}
{{--                            </td>--}}
{{--                            <!-- NeoSeo Exchange 1c - begin -->--}}
{{--                        <tr>--}}
{{--                            <td>Выгрузить заказ в 1С:</td>--}}
{{--                            <td class="text-right"  colspan="2">--}}
{{--                                <input type="checkbox" id="order_export_exchange1c_status" name="order_export_exchange1c_status"  value="1" id="input-override" />--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Партнер                  </td>--}}
{{--                            <td class="text-right">0 грн</td>--}}
{{--                            <td class="text-center">                  <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-info-circle"></i> Детали заказа № {{$order['id']}}</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td style="width: 50%;" class="text-left">Адрес плательщика</td>
                        <td style="width: 50%;" class="text-left">Адрес доставки</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left">{{$order['user_address']['name']}} {{$order['user_address']['LastName']}}<br />{{$order['user_address']['is_address_delivery'] ? $order['user_address']['department'] . ' (АДРЕСНАЯ)' : $order['user_address']['department']}}<br />{{$order['user_address']['cities']}}<br />{{$order['user_address']['region']}}<br />{{$order['user_address']['not_call'] ? 'Не перезванивать для подтверждения':''}}</td>
                        <td class="text-left">{{$order['user_address']['name']}} {{$order['user_address']['LastName']}}<br />{{$order['user_address']['is_address_delivery'] ? $order['user_address']['department'] . ' (АДРЕСНАЯ)' : $order['user_address']['department']}}<br />{{$order['user_address']['cities']}}<br />{{$order['user_address']['region']}}<br />{{$order['user_address']['not_call'] ? 'Не перезванивать для подтверждения':''}}</td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td class="text-left">Товар</td>
                        <td class="text-left">Модель</td>
                        <td class="text-right">Количество</td>
                        <td class="text-right">Цена за единицу</td>
                        <td class="text-right">Скидка на единицу</td>
                        <td class="text-right">Итого</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product_key => $product)
                        <tr>
                            <td class="text-left">
                                @if (!empty($product['attr']))
                                    <a href="">{{$product['attr']['model']}}</a>
                                @else
                                    Товар был удален
                                @endif
                            </td>
                            @if (!empty($product['attr']))
                                <td class="text-left">{{$product['attr']['model']}}</td>
                            @else
                                <td class="text-left">Товар был удален</td>
                            @endif
                            <td class="text-right">{{$product['quantity']}}</td>
                            <td class="text-right">
                                {{$product['price']}} грн
                            </td>
                            <td class="text-right">
                                @if (!empty($product['is_sales']))
                                    - {{$product['price'] - $product['price_with_sales']}} грн
                                @else
                                    0 грн
                                @endif
                            </td>
                            <td class="text-right">
                                @if (!empty($product['is_sales']))
                                    {{$product['price_with_sales'] * $product['quantity']}} грн
                                @else
                                    {{$product['price'] * $product['quantity']}} грн
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right">Стоимость товаров</td>
                        <td class="text-right">{{$totalProductPrice}} грн</td>
                    </tr>
{{--                    <tr>--}}
{{--                        <td colspan="4" class="text-right">Самовывоз из магазина</td>--}}
{{--                        <td class="text-right">0 грн</td>--}}
{{--                    </tr>--}}
                    <tr>
                        <td colspan="4" class="text-right">Процент скидки</td>
                        <td class="text-right">{{$sale ? $sale['percent'] ? $sale['percent'] : $sale['procent_modal'] : 0}} %</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Всего скидки</td>
                        <td class="text-right">- {{$totalProductPrice - $order['total_sum']}} грн </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Всего к оплате</td>
                        <td class="text-right">{{$order['total_sum']}} грн</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-comment-o"></i> История заказа</h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#tab-history" data-toggle="tab">История</a></li>
{{--                    <li class="nav-item"><a class="nav-link" href="#tab-additional" data-toggle="tab">Дополнительно</a></li>--}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-history">
                        <div id="history">
                            @include('admin.orders.partials.orderHistoryPartial')
                        </div>
                        <br />
                        <fieldset>
                            <legend>Добавить в историю</legend>
                            <form class="form-horizontal" id="addHistory">
                                <input type="hidden" name="order-id" value="{{$id}}">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-order-status">Статус заказа</label>
                                    <div class="col-sm-10">
                                        <select name="order_status_id" id="input-order-status" class="form-control">
                                            @foreach($order_statuses as $key => $order_status)
                                                <option value="{{$key}}">{{$order_status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label" for="input-override"><span data-toggle="tooltip" title="Если заказ заблокирован системой Защиты от мошенников, то устанавливая крыж, можно установить свой статус заказа, не зависимо от системы защиты.">Переопределить</span></label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input type="checkbox" name="override" value="0" id="input-override" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label" for="input-notify">Уведомить покупателя</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input type="checkbox" name="notify" value="0" id="input-notify" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-comment">Комментарий</label>
                                    <div class="col-sm-10">
                                        <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                        <div class="text-right">
                            <button id="button-history" data-loading-text="Загрузка..." class="btn btn-primary"><i class="fa fa-plus-circle"></i> Добавить</button>
                        </div>
                    </div>
{{--                    <div class="tab-pane" id="tab-additional">--}}
{{--                        <div class="table-responsive">--}}
{{--                            <table class="table table-bordered">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <td colspan="2">Browser</td>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td>IP адрес</td>--}}
{{--                                    <td>128.124.116.30</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>User Agent</td>--}}
{{--                                    <td>Mozilla/5.0 (iPhone; CPU iPhone OS 14_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.2 Mobile/15E148 Safari/604.1</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>Язык</td>--}}
{{--                                    <td>ru</td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('input[name^=\'selected\']').on('change', function() {
            $('#button-shipping, #button-invoice').prop('disabled', true);
            var selected = $('input[name^=\'selected\']:checked');
            if (selected.length) {
                $('#button-invoice').prop('disabled', false);
            }
            for (i = 0; i < selected.length; i++) {
                if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
                    $('#button-shipping').prop('disabled', false);
                    break;
                }
            }
        });

        $('#button-shipping, #button-invoice').prop('disabled', true);
        $('input[name^=\'selected\']:first').trigger('change');

        // IE and Edge fix!
        $('#button-shipping, #button-invoice').on('click', function(e) {
            $('#form-order').attr('action', this.getAttribute('formAction'));
        });
        $('#button-delete').on('click', function(e) {
            $('#form-order').attr('action', this.getAttribute('formAction'));
            if (confirm('Данное действие необратимо. Вы уверены?')) {
                $('#form-order').submit();
            } else {
                return false;
            }
        });

        $("body").on("click", "#button-history", function() {
            $.ajax({
                type:"POST",
                url:"/admin/orders/save/history",
                data: {
                    order_id:  $('input[name="order-id"]').val(),
                    status: $('select[name="order_status_id"]').val(),
                    override: $('input[name="override"]').prop("checked"),
                    notify: $('input[name="notify"]').prop("checked"),
                    comment: $('textarea[name="comment"]').val()
                },
                success: function (data) {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Успех!',
                            text: 'Вы успешно добавили историю к заказу'
                        });
                    }
                    location.reload();
                }
            });
        });

        $('#history').on('click', ".pagination a", function(){
            $.ajax({
                url:"/admin/orders/get/history?" + $(this).attr('href').split('?')[1],
                type:"GET",
                success: function (response) {
                    if (response.html) {
                        $("#history").html(response.html);
                    }
                }
            });
            return false;
        });
    </script>
    <script src="{{asset('js/products.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
@endsection
