@extends('layouts.app')

@section('content')
    @include('layouts.nav')

    <div class="empty-div-pc" style="padding: 35px">
        {{--        --}}
    </div>

    <div class="container" style="margin-top: 15px">
        <a class="restore_menu" href="/">Главная /</a>

        @foreach($products as $value)
            @if($value['id'] == $id)
                @foreach($value['categories'] as $value)
                        <a href="/category/{{$value['id']}}" class="restore_menu" id="categories_{{$value['id']}}">{{$category_srting}} /</a>
                @endforeach
                @endif
        @endforeach

        <div class="row">
            <div class="col-md-6">
                @foreach($products as $value) @if($value['id'] == $id)
                        @if(isset($value->getImage['name']))
                        <div class="card text-center card-image" style="">
                            <img class="img-fluid card-img-top" src="{{ Storage::url('/img/products/'.$value->getImage['name'])}}">
                        </div>
                        @endif
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <h5 class="card-title">{!!$value->name!!}</h5>
                    <p class="card-title">{!!$value->meta_keywords!!}</p>
                    <div class="row">
                        <div class="col-md-6 price-div price-div-mobile">
                            <div class="price-info" style="margin-left: 20px"><b>{!!$value->price_with_sale!!} грн</b></div>
                            <div class="old-price-PC">
                                <p class="old-price"><s>{!!$value->price!!}</s> грн</p>
                            </div>
                            <div class="old-price-mobile">
                                <p class="old-price" style="float: left; margin-left: 10px; margin-top: 7px"><s>Старая цена {!!$value->price!!}</s> грн</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-quantity">
                        <span style="font-size: 11px; margin-top: 0px; color:#87c8d7">В наличии</span>
                        <p>Количество</p>
                        <div class="amount">
                            <span class="down">-</span>
                            <input id="count_products" type="text" style="width: 40px; border-color: transparent" min="1" value="1"/>
                            <span class="plus up">+</span>
                        </div>
                    </div>
                    <button id="btn-buy" type="submit" style="margin-left: 5px" class="btn btn-myBuy card-btn-buy">Добавить в корзину</button><br>
                    <div class="card-quantity">
                        <a data-toggle="modal" data-target="#modalOneClick" class="add_click col-sm-1"><b>Оформить товар в 1 клик</b></a>
                    </div>
                    <input type="hidden" id="product_id" value="{{$value->id}}" />
                </div>
            </div>
            @endif
            @endforeach
        </div>
        <div class="row justify-content-center" style="margin-right: 20px">
            <div class="col-md-2 card-options">
                <button type="button" class="btn btn-link tablinks active"
                        onclick="openTabs(event, 'desc')">Описание
                </button>
            </div>
            <div id="" class="col-md-2 card-options">
                <button type="button" class="btn btn-link tablinks"
                        onclick="openTabs(event, 'composition')">Состав
                </button>
            </div>
            <div id="" class="col-md-2 card-options">
                <button type="button" class="btn btn-link tablinks"
                        onclick="openTabs(event, 'application')">Применение
                </button>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
        </div>
    </div>
    <div class="container">
        @foreach($products as $value)
            @if($value['id'] == $id)
                <div id="desc" class="tabcontent active">
                    <p>{!!$value->description!!}</p>
                </div>
                <div id="composition" class="tabcontent">
                    <p>{!!$value->composition!!}</p>
                </div>
                <div id="application" class="tabcontent">
                    <p>{!!$value->meta_description!!}</p>
                </div>
                <div id="reviews" class="tabcontent">
                    <p>Здесь будут отзывы</p>
                </div>
            @endif
        @endforeach
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalOneClick" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         style="margin-top: 10%" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form>
                        <div class="row justify-content-center" style="margin-top: 10px">
                            <p><b>Оформить заказ в 1 клик</b></p>
                            <p align="center">Наш менеджер свяжется с вами в течении<br>
                                30 минут в рабочее время
                            <p>
                            <p></p>
                        </div>
                        <div class="container">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Введите имя</label>
                                <input type="text" class="form-control" style="font-weight: bold; background: #F7F7F7;" id="nameModal">
                            </div>
                            <div class="form-group">
                                <label for="recipient-phone" class="col-form-label">Введите номер телефона</label>
                                <input type="tel" name="tel1" class="form-control" style="font-weight: bold; background: #F7F7F7;" id="phoneModal" data-mask="+38 (___) ___-__-__">
                            </div>
                        </div>

                        <div class="row justify-content-center">
                                <span rel="/checkModalOneClick" id="checkModalOneClick" type="submit"
                                      style="margin-top: 10px; width: 225px; padding: 10px" class="btn btn-myBuy">Оформить быстрый заказ</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
@endsection
