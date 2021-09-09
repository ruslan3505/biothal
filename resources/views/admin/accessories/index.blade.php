@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .input-group {
            display: flex;
        }
    </style>
@endsection

@section('content')
    <div class="modal hide" id="add_access" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Добавить потребность</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="padre_accessory_select">Родительская
                                категория</label>
                        </div>
                        <select class="custom-select" id="padre_accessory_select" name="padre_accessory_select">
                            <option value="NoAccessory">Без родительской категории</option>
                            @if(!empty($categories))
                                @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{ $category->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название потребности</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Название потребности" id="accessory_title"
                               aria-label="accessory name" aria-describedby="basic-addon1" name="title_accessory"
                               autocomplete="off">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="ordering_accessory">Порядок сортировки</label>
                        </div>
                        <input type="number" class="form-control" placeholder="Введите номер для сортировки" min="1"
                               max="9999" id="ordering_accessory" name="ordering_accessory">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_accessory" class="btn btn-primary">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change_access" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Изменить потребность</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="padre_accessory_select">Родительская
                                категория</label>
                        </div>
                        <select class="custom-select" id="padre_accessory_select_change" name="padre_accessory_select">
                            <option value="NoAccessory">Без родительской категории</option>
                            @if(!empty($categories))
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{ $category->title}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название потребности</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Название потребности"
                               id="title_accessory_change" aria-label="accessory name" aria-describedby="basic-addon1"
                               name="title_accessory" autocomplete="off">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="ordering_accessory">Параметр сортировки</label>
                        </div>
                        <input type="number" class="form-control" placeholder="Введите номер для сортировки" min="1"
                               max="9999" name="ordering_accessory_change" id="ordering_accessory_change" value="">
                    </div>
                </div>
                <input type="hidden" name="accessory_hidden_id" id="accessory_hidden_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="b_change_accessory">Изменить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Потребности</div>
                    <div class="pull-right col-sm-6">
                        <a href="javascript" data-toggle="modal" data-target="#add_access" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                        <button id="delete_acc" data-toggle="tooltip" data-placement="right" title="Удалить выбранные потребности" class="btn btn-danger">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                    <div class="breadcrumb col-sm-12 pull-left" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href=""> Потребности</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border container-fluid" style="padding-left: 0;
        padding-right: 0;" id="accessory_page">
        <div class="panel panel-default">
                <div class="panel-body">
                    <div class="well">
            @if($accessories == null)
                <h3>Потребности отсутствуют</h3>
            @else
                <div class="container-fluid m-2">
                    <table class="table" id="accessory_table" style="width:100%">
                        <thead class="text-center">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Родительская потребность</th>
                            <th scope="col">Название</th>
                            <th scope="col">Порядок сортировки</th>
                            <th scope="col">Редактировать</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/accessories.js')}}"></script>
@endsection
