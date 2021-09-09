@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <form method="post" action="#" id="form_product">
        @csrf
        <div class="modal hide bd-example-modal-lg" id="add_product">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example_modal_label">Добавить товар</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="tab">
                            <button type="button" class="tablinks" onclick="openCity(event, 'Main')" id="defaultOpen">
                                Основное
                            </button>
                            <button type="button" class="tablinks dis" onclick="openCity(event, 'Attributes')"
                                    id="dispersia">Данные
                            </button>
                            <button type="button" class="tablinks" onclick="openCity(event, 'Img')">Изображения</button>
                            <button type="button" class="tablinks" onclick="openCity(event, 'Sale')">Скидки</button>
                        </div>

                        <div id="Main" class="tabcontent">
                <span onclick="this.parentElement.style.display='none'" id="topright1"
                      class="topright fa fa-chain"></span>
                            <h3>Основное</h3>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="title_product">Название</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Название товара" name="name">
                            </div>
                            <div class="input-group mb-3" id="tastingo">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="">Категория</label>
                                </div>
                                <select class="custom-select" id="" name="categories">
                                    <option value="NoCategory">Выберите категорию</option>
                                    @if($categories != null)
                                        @foreach($categories as $category)
                                            @if($category->is_demand == false)
                                                <option value="{{$category->id}}">{{$category->title}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="input-group mb-3" id="tastingo">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="">Потребность</label>
                                </div>
                                <select class="custom-select" id="" name="accessories">
                                    <option value="NoAccessory">Выберите потребность</option>
                                    @if($accessories != null)
                                        @foreach($accessories as $accessory)
                                            <option value="{{$accessory->id}}">{{$accessory->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Описание:</span>
                                </div>
                                <textarea class="description" placeholder="Описание товара" name="description"
                                          id="product_description"></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="title_product">Цена</span>
                                </div>
                                <input type="text" class="form-control"
                                       placeholder="Цена товара" step=".00" name="price">
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" data-role="tagsinput" placeholder="Мета теги keyword"
                                       id="meta_keyword_product" name="meta_keywords">
                                <small id="emailHelp" class="form-text text-muted">Введите ключевое слово и нажмите
                                    tab\enter</small>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Введите путь к товару"
                                       aria-describedby="link" name="link">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="link_product">Путь к товару</span>
                                </div>
                            </div>
                            <div class="input-group  mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text"
                                      id="meta_description_product">Мета тег description:</span>
                                </div>
                                <textarea placeholder="Введите мета теги" class="form-control" aria-label="description"
                                          name="meta_description"></textarea>
                            </div>
                            <div class="input-group  mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Состав:</span>
                                </div>
                                <textarea class="description" name="composition" id="product_composition"></textarea>
                            </div>

                        </div>

                        <!--    Данные -->
                        <div id="Attributes" class="tabcontent">
                <span onclick="this.parentElement.style.display='none'" id="topright{{2}}"
                      class="topright fa fa-chain"></span>
                            <h3>Данные</h3>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <div class="btn btn-group pull-right">
                                    <button type="button" data-toggle="modal" data-target="#add_attributes_modal"
                                            id="create_attribute"
                                            class="btn btn-dark">Добавить атрибут
                                    </button>
                                    <button type="button" id="delete_attributes" class="btn btn-dark"
                                            data-title="tooltip"
                                            data-placement="top" title="Выберите из таблицы">Удалить атрибуты
                                    </button>
                                </div>
                            </div>
                            <div class="container-fluid m-2">
                                <table class="table" id="attributes_table" style="width:100%">
                                    <thead class="text-center">
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Название атрибута</th>
                                        <th scope="col">Значение атрибута</th>
                                        <th scope="col">Отредактировать атрибут</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!--    Изображения -->
                        <div id="Img" class="tabcontent">
                <span onclick="this.parentElement.style.display='none'" id="topright{{4}}"
                      class="topright fa fa-chain"></span>
                            <h3>Изображения</h3>
                            @if($images == null)
                                <p>ГАЛЕРЕЯ - ПУСТА!</p>
                            @else
                                <div class="card-deck">
                                    @foreach($images as $image)
{{--                                        @dd($image->name)--}}
                                        <div class="col-sm-4">
                                            <div class="card border-0">
                                                <a href="{{ Storage::url('img/products/'.$image->name) }}">
                                                    <img class="card-img-top"
                                                         src="{{ Storage::url('img/products/'.$image->name) }}"
                                                         width="200"
                                                         height="200" alt="Изображение не загрузилось">
                                                </a>
                                                <div class="card-body">
                                                    {{--                                                    <button type="button" id="b_delete_image_{{$image->id}}"--}}
                                                    {{--                                                            data-id="{{$image->id}}"--}}
                                                    {{--                                                            class="btn btn-outline-dark btn-sm">Удалить &#160--}}
                                                    {{--                                                        <span class="fa fa-trash"></span>--}}
                                                    {{--                                                    </button>--}}
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="image_radio" id="pictures_{{$image->id}}"
                                                               value="{{$image->id}}">
                                                        <label class="form-check-label"
                                                               for="pictures_{{$image->id}}">
                                                            Сделать главным
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!--    Скидки -->
                        <div id="Sale" class="tabcontent">
                <span onclick="this.parentElement.style.display='none'" id="topright{{5}}"
                      class="topright fa fa-chain"></span>
                            <h3>Скидки</h3>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <div class="btn btn-group pull-right">
                                    <button type="button" data-toggle="modal" data-target="#add_sales_modal"
                                            id="create_sale"
                                            class="btn btn-dark">Добавить скидку
                                    </button>
                                    <button type="button" id="delete_sales" class="btn btn-dark">Удалить скидки</button>
                                </div>
                            </div>
                            <div class="container-fluid m-2">
                                <table class="table" id="sales_table" style="width:100%">
                                    <thead class="text-center">
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Название скидки</th>
                                        <th scope="col">Начальная дата</th>
                                        <th scope="col">Конечная дата</th>
                                        <th scope="col">% скидки</th>
                                        <th scope="col">Редактировать</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" value="Добавить" id="b_add_product" class="btn btn-success"
                                data-title="tooltip" data-placement="top" title="Добавить товар">Добавить
                        </button>
                        <input type="submit" value="Изменить" id="b_change_product" class="btn btn-success"
                               data-title="tooltip" data-placement="top" title="Внести изменения">
                        <input type="hidden" value="hello" id="hidden_id_product" name="product_id">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--    add Attribute Modal    -->
    <div class="modal hide bd-example-modal-lg" id="add_attributes_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-atri">Добавить Атрибут <span class="sale-green fas fa-leaf"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название атрибута</span>
                        </div>
                        <input type="text" class="form-control" id="attribute_title" placeholder="Название атрибута"
                               name="attribute_title">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Значение атрибута</span>
                        </div>
                        <input type="text" class="form-control" id="attribute_value" placeholder="Значение атрибута"
                               name="attribute_value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="b_add_attribute" class="btn btn-warning">Добавить атрибут</button>
                    <button type="button" id="b_change_attribute" data-id="" class="btn btn-warning position-relative">
                        Изменить
                        атрибут
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--    add Sale Modal  -->
    <div class="modal hide bd-example-modal-lg" id="add_sales_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sale">Добавить Скидку <span class="sale-green">$</span>_<span
                            class="sale-green">$</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название скидки</span>
                        </div>
                        <input type="text" class="form-control" id="sale_title" placeholder="Название скидки"
                               name="sale_title">
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control datepicker" id="sale_first_date" name="sale_first_date"/>
                        <small id="sale_first" class="form-text text-muted">Выберите начальную дату</small>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control datepicker" id="sale_last_date" name="sale_last_date"/>
                        <small id="sale_last" class="form-text text-muted">Выберите конечную дату</small>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">% скидки</span>
                        </div>
                        <input type="number" min="0" max="100" class="form-control" id="sale_percent"
                               name="sale_percent">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="b_add_sale" class="btn btn-warning">Добавить скидку</button>
                    <button type="button" id="b_change_sale" data-id="" class="btn btn-warning position-relative">
                        Изменить скидку
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--    Choice your sale    -->
    <div class="modal hide bd-example-modal-lg" id="choice_your_sale_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Присвоить скидку</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <select class="custom-select" id="select_sale_name" name="sale_name">
                            @if($sales != null)
                                <option value="NoValue">Выберите скидку</option>
                                @foreach($sales as $sale)
                                    <option value="{{$sale->id}}">{{$sale->title}}</option>
                                @endforeach
                            @else
                                <option value="NoValue">Скидок нет</option>
                            @endif
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="" id="sale_first_date_prev" disabled/>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="" id="sale_last_date_prev" disabled/>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="" id="sale_percent_prev" disabled/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="b_confirm_your_sales" class="btn btn-warning">Выбрать скидку</button>
                </div>
            </div>
        </div>
    </div>

    <!--    PAGE    -->
    <div class="container" id="product_page">
        <div class="page-header w-100 alert bg-light p-0 shadow-sm mt-2">
            <div class="btn-group" role="group" aria-label="Basic example">
                <div class="btn btn-group pull-right">
                    <button type="button" id="create_product" data-toggle="modal" data-target="#add_product"
                            class="btn btn-dark"
                            data-title="tooltip" data-placement="top" title="Добавить товар">Добавить
                    </button>
                    <button type="button" id="delete_products" class="btn btn-dark"
                            data-title="tooltip" data-placement="top" title="Выберите товары в таблице">Удалить
                    </button>
                    <button type="button" id="choice_your_sale" class="btn btn-dark" data-toggle="modal"
                            data-target="#choice_your_sale_modal" data-title="tooltip" data-placement="top"
                            title="Скидки">Скидки
                    </button>
                    <button type="button" id="delete_sales_for_products" class="btn btn-dark" data-title="tooltip"
                            data-placement="top" title="Выберите товары, у которых хотите убрать скидку">Очистить скидки
                    </button>
                    <button type="button" data-toggle="modal" data-target="#modal_global_sale"
                            id="global_sales_for_products"
                            class="btn btn-dark" data-title="tooltip" data-placement="top"
                            title="Добавить глобальную скидку для опциональной полоски в корзину">Глобальная скидка
                    </button>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modal_global_sale" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel"
                 style="margin-top: 10%" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form>
                                <div class="row justify-content-center" style="margin-top: 10px; color: #000000">
                                    <p align="center">Введите глобальную сумму скидки и процент
                                    <p>
                                    <p></p>
                                </div>
                                <div class="container">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label" style="color: #000000">Введите
                                            сумму</label>
                                        <input type="number" min="1" class="form-control"
                                               style="font-weight: bold; background: #F7F7F7;" id="sum_modal">
                                    </div>
                                    <div class="form-group">
                                        <label for="recipient-phone" class="col-form-label" style="color: #000000">Введите
                                            процент</label>
                                        <input type="number" min="1" max="100" class="form-control"
                                               style="font-weight: bold; background: #F7F7F7;" id="procent_modal">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                <span rel="/globalsale" id="global_sale" type="submit"
                                      style="margin-top: 10px; width: 225px; padding: 10px" class="btn btn-myBuy">Добавить глобальную скидку</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid m-2">
                <table class="table" id="products_table" style="width:100%">
                    <thead class="text-center">
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Изображение</th>
                        <th scope="col">Название товара</th>
                        <th scope="col">Описание</th>
                        <th scope="col">Состав</th>
                        <th scope="col">Скидка</th>
                        <th scope="col">Цена</th>
                        <th scope="col">Изменить</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/products.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
@endsection
