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
@endsection

@section('content')
    <div class="modal hide" id="add_categ" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Добавить категорию</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3" id="tastingo">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="padre_category_select">Родительская
                                категория</label>
                        </div>
                        <select class="custom-select" id="padre_category_select" name="padre_category_select">
                            <option value="NoCategory">Без категории</option>
                            @if(!empty($categories))
                                @foreach($categories as $category)
                                    @if($category->is_demand == false)
                                        <option value="{{$category->id}}">{{$category->title}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="type_category">
                                Тип категории
                            </label>
                        </div>
                        <select required class="custom-select" id="type_category"
                                name="type_category">
                            <option selected value="forProduct" class="no_category">Для товаров</option>
                            <option value="info">Информационная категория</option>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название категории</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Название категории" id="category_title"
                               aria-label="Category name" aria-describedby="basic-addon1" name="title_category"
                               autocomplete="off">
                    </div>
{{--                    <input type="checkbox" id="demand" name="demand_category" value="check">--}}
{{--                    <label for="demand">--}}
{{--                        Категория - потребность--}}
{{--                    </label>--}}

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="ordering_category">Порядок сортировки</label>
                        </div>
                        <input type="number" class="form-control" placeholder="Введите номер для сортировки" min="1"
                               max="9999" id="ordering_category" name="ordering_category">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">SEO заголовок</span>
                        </div>
                        <input type="text" class="form-control" placeholder="SEO заголовок"
                               id="seo_title" aria-label="SEO title"
                               name="SEO_title" autocomplete="off">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">SEO описание</span>
                        </div>
                        <textarea id="summernote" name="seo_description_name"
                                  id="input-seo-description" aria-label="SEO description"
                                  class="form-control summernote">
                            {{$product['productDescription']['description'] ?? ''}}
                        </textarea>
                    </div>

                    <div class="input-group mb-3" id="btm_view" hidden>
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="bottom_view">
                                Отображать внизу
                            </label>
                        </div>
                        <select required class="custom-select" id="bottom_view"
                                name="bottom_view">
                            <option selected value="0" class="no_category">Нет</option>
                            <option value="1">Да</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_category" class="btn btn-primary">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change_categ" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Изменить категорию</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="padre_category_select_change">Родительская
                                категория</label>
                        </div>
                        <select class="custom-select" id="padre_category_select_change"
                                name="padre_category_select">
                            <option value="NoCategory" class="no_category">Без категории</option>
                            @if($categories != null)
                                @foreach($categories as $category)
                                    @if($category->is_demand == false)
                                        <option value="{{$category->id}}">{{$category->title}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="type_category_change">
                                Тип категории
                            </label>
                        </div>
                        <select class="custom-select" id="type_category_change"
                                name="type_category">
                            <option value="forProduct" class="no_category">Для товаров</option>
                            <option value="info">Информационная категория</option>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название категории</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Название категории"
                               id="title_category_change" aria-label="Category name" aria-describedby="basic-addon1"
                               name="title_category" autocomplete="off">
                    </div>

{{--                    <input type="checkbox" id="demand_change" name="demand_category" checked="" class="check_demand"--}}
{{--                           value="check">--}}
{{--                    <label for="demand">--}}
{{--                        Категория - потребность--}}
{{--                    </label>--}}

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="ordering_category">Порядок сортировки</label>
                        </div>
                        <input type="number" class="form-control" placeholder="Введите номер для сортировки" min="1"
                               max="9999" name="ordering_category_change" id="ordering_category_change" value="">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">SEO заголовок</span>
                        </div>
                        <input type="text" class="form-control" placeholder="SEO заголовок"
                               id="seo_title_change" aria-label="SEO title"
                               name="SEO_title" autocomplete="off">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">SEO описание</span>
                        </div>
                        <textarea id="summernote_change" name="seo_description_name"
                                  id="input-seo-description" aria-label="SEO description"
                                  class="form-control summernote">
                        </textarea>
                    </div>

                    <div class="input-group mb-3" id="btm_view_change">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="bottom_view_change">
                                Отображать внизу
                            </label>
                        </div>
                        <select required class="custom-select" id="bottom_view_change"
                                name="bottom_view">
                            <option selected value="0" class="no_category">Нет</option>
                            <option value="1">Да</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="category_hidden_id" id="category_hidden_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="b_change_category">Изменить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Категории</div>
                    <div class="pull-right col-sm-6">
                        <a href="javascript" data-target="#add_categ" data-toggle="modal" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                        <button id="delete_cat" type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                    <div class="breadcrumb col-sm-12 pull-left" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href=""> Категории</a></div>
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
                    @if($categories == null)
                        <p>Категории отсутствуют ¯\_(ツ)_/¯</p>
                    @else
                        <div class="container-fluid m-2">
                            <table class="table" id="category_table" style="width:100%">
                                <thead class="text-center">
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Родительская категория</th>
                                    <th scope="col">Тип категории</th>
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
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script type="text/javascript">
        $('.summernote').summernote();
    </script>
    <script src="{{asset('js/categories.js')}}"></script>
    <script>
        function getDescriptionText(id)
        {
            $.ajax({
                url: '/admin/categories/getText',
                method: 'GET',
                data: {
                    "id": id
                },
                error: function (xhr, status, error) {
                    var errors = xhr.responseJSON.errors, errorMessage = "";
                    $.each(errors, function (index, value) {
                        $.each(value, function (key, message) {
                            errorMessage += message + " ";
                        })
                    })
                    Swal.fire({
                        icon: 'error',
                        title: errorMessage,
                        showConfirmButton: true,
                    })
                },
                success: function (resp) {
                    $("#summernote_change").summernote('code', resp.text);  // SEO description
                    let type_category = $('#type_category_change').val();
                    console.log(type_category)
                    if(type_category === 'info'){
                        $("#btm_view_change").attr('hidden', false);
                    } else {
                        $("#btm_view_change").attr('hidden', true);
                    }
                }
            });
        }
    </script>
@endsection
