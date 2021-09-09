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

    <div class="border container-fluid" style="padding-left: 0;
        padding-right: 0;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="well">
                    <div class="row">
                        <form class="form" method="GET" action="{{route('admin.orders.orders')}}" style="width: 100%;" >
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-order-id">№ Заказа</label>
                                    <input type="text" name="filter_order_id" value="" placeholder="№ Заказа" id="input-order-id" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="input-customer">Клиент</label>
                                    <input type="text" name="filter_client" value="" placeholder="Клиент" id="input-customer" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-order-status">Статус заказа</label>
                                    <select name="filter_order_status" id="input-order-status" class="form-control">
                                        <option value="*"></option>
                                        @foreach($order_statuses as $order_status)
                                            <option value="{{$order_status['id']}}">
                                                @if ($order_status['name'] == 'active')
                                                    Закупка
                                                @elseif ($order_status['name'] == 'payment_process')
                                                    В процессе оплаты
                                                @elseif ($order_status['name'] == 'shipping_process')
                                                    В прочессе доставки
                                                @elseif ($order_status['name'] == 'finish')
                                                    Закончен
                                                @elseif ($order_status['name'] == 'pre_order')
                                                    Предзаказ
                                                @elseif ($order_status['name'] == 'paid')
                                                    Оплачено
                                                @elseif ($order_status['name'] == 'cancel')
                                                    Отменен
                                                @elseif ($order_status['name'] == 'unfinished')
                                                    Не закончен
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="input-total">Итого</label>
                                    <input type="text" name="filter_total" value="" placeholder="Итого" id="input-total" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="input-date-added">Дата добавления</label>
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" name="filter_date_added" value="" placeholder="Дата добавления" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                        </span></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="input-date-modified">Дата изменения</label>
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" name="filter_date_modified" value="" placeholder="Дата изменения" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                                        <span class="input-group-btn">
                                          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                         </span>
                                    </div>
                                </div>
                                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> Фильтр</button>
                                @if (request()->has(['filter_order_id', 'filter_client', 'filter_order_status', 'filter_date_modified', 'filter_total', 'filter_date_added']))
                                    <a style="    margin: 23px 10px 2px 2px;" href="{{route('admin.orders.orders')}}" class="btn btn-default pull-right">Reset Filters</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container-fluid m-2">
                    <table class="table table-bordered table-hover" id="orders-table" style="width:100%">
                        <thead class="text-center">
                        <tr>
                            <th scope="col">№ Заказа</th>
                            <th scope="col">Имя</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Итого</th>
                            <th scope="col">Дата добавления</th>
                            <th scope="col">Дата изменения</th>
                            <th scope="col">Действие</th>
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
        </script>
    <script src="{{asset('js/products.js')}}"></script>
    <script src="{{asset('js/orders.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
@endsection
