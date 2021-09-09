<style type="text/css">
    a {
        text-decoration: none;
    }
    a:hover {
        text-decoration: none;
        color: black;
    }
</style>
<div class="row justify-content-center" style="margin-bottom: 15px; margin-top: 20px"><b>Оформление заказа</b>
</div>
<input type="hidden" value="{{$sumAll_sale}}">
<input id="sum_modal" type="hidden" value="{{$sum_modal}}">
@if(empty($sumAll))
    <div style="text-align: center; margin-bottom: 15px">
        Скидка {{$procent_modal.' %'}}
        срабатывает от суммы {{$sum_modal.' '}}грн
    </div>
@endif
@if($sumAll_sale < $sum_modal && $sumAll_sale > 0)
    <div class="sumAll_sale" style="text-align: center; margin-left: auto;margin-right: auto; margin-bottom: 15px">Еще <span class="sumAll_sale-container">{{$sumAll_sale}}</span>
        грн и сработает скидка {{$procent_modal.' %'}}
    </div>
@endif
@if($sumAll_sale <= 0 )
    <div class="sumAll_sale" style="text-align: center; margin-left: auto;margin-right: auto; margin-bottom: 15px">
        Ваша скидка {{$procent_modal.' %'}}</div>
@endif
<div class="sumAll_sale2" style="text-align: center; margin-bottom: 15px"></div>
<input type="hidden" class="progress-count2">

<div class="progress-bar2" style="margin-bottom: 15px">
    <div style="width: 0%"></div>
</div>
<form>
    <div class="form-row">
        <div class="col">
            <div class="form-group">
                <label for="formGroupExampleInput">Введите номер телефона*</label>
                <input type="tel" value="" class="colorInput form-control" id="phone" data-mask="+38 (___) ___-__-__"
                       placeholder="+38(___) ___ - __ - __">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Введите имя*</label>
                <input type="text" value="" class="colorInput form-control" id="name" placeholder="Имя">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Введите фамилию*</label>
                <input type="text" class="colorInput form-control" id="LastName" placeholder="Фамилия">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Выберите область*</label>
                <select id="region" class="colorInput form-control">
                    @foreach($region as $key => $reg)
                        <option @if($key === 0) selected disabled value="" @else value="{{$reg['id']}}" @endif">
                        {{$reg['region']}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Введите город*</label>
                <input type="text" class="colorInput form-control" id="cities" placeholder="Введите город">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Выберите отделение Новой Почты*</label>
                <select id="department" class="colorInput form-control">
                    <option selected value="">Отделение</option>
                </select>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Выберите способ оплаты*</label>
                <select id="payment" class="colorInput form-control">
                    <option id="" selected>Оплата при доставке курьером Новой Почты</option>
                    <option id="" selected>Оплата при получении в отделении Новой Почты</option>
                </select>
            </div>
            <div class="checkout-PC">
            <div class="container">
                <div class="row">
                    <div class="col-sm-10">
                        <label for="formGroupExampleInput2">Не нужно перезванивать мне для подтверждения
                            заказа</label>
                    </div>
                    <div class="checkbox col-sm-1">
                        <input type="checkbox" name="not_call" id="not_call_0">
                    </div>
                    <div class="col-sm-1 wrapper">
                        <p class="icon pulse" data-title="В случае выбора этой опции, ваш заказ будет сформирован и отправлен без звонка менеджера.
                                Пожалуйста, проверьте внимательно все ли данные внесены корректно!">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle"
                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path
                                    d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
                                <circle cx="8" cy="4.5" r="1"/>
                            </svg>
                        </p>
                    </div>
                </div>
            </div>
                <div class="row justify-content-center">
                    <div class="col-sm-5">
                            <span rel="/check" id="check" type="submit" style="margin-top: 10px; padding: 10px"
                                  class="btn btn-myBuy">Оформить заказ</span>
                    </div>
                    <div class="col-sm-5">
                            <span data-toggle="modal" data-target="#modalOneClick" class="btn btn-link" style="color:#020202; margin-top: 10px; padding: 10px">
                                <b>Оформить в 1 клик</b></span>
                    </div>
                </div>
            </div>
            <div class="checkout-mobile">
                <div class="container">
                    <div class="row">
                        <div style="display: inline-flex">
                            <div class="col-sm-10">
                                <label for="formGroupExampleInput2">Не нужно перезванивать мне для подтверждения
                                    заказа</label>
                            </div>
                            <div class="checkbox col-sm-1" style="width: 20%">
                                <input style="margin-top: 25px" type="checkbox" name="not_call" id="not_call_0">
                            </div>
                            <div class="col-sm-1 wrapper" style="width: 20%">
                                <p class="icon pulse" data-title="В случае выбора этой опции, ваш заказ будет сформирован и отправлен без звонка менеджера.
                                Пожалуйста, проверьте внимательно все ли данные внесены корректно!">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle"
                                         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path
                                            d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
                                        <circle cx="8" cy="4.5" r="1"/>
                                    </svg>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-sm-5">
                            <span rel="/check" id="check" type="submit" style="margin-top: 10px; padding: 10px; width: 100%"
                                  class="btn btn-myBuy">Оформить заказ</span>
                    </div>
                    <div class="col-sm-5">
                            <span data-toggle="modal" data-target="#modalOneClick" class="btn btn-link" style="display: flex; justify-content: center; color:#020202; margin-top: 10px; padding: 10px">
                                Оформить в 1 клик</span>
                    </div>
                </div>
            </div>
        </div>

{{--        Only PC--}}
        <div class="col PC">
            <div class="row justify-content-center verticalscroll">
                @foreach($cart_join as $cart)
                    @foreach($products as $value)
                        @if($value->id == $cart->id)
                            <div class="col-6">
                                <img class="img-fluid" style="max-width: 14em; margin-bottom: 30px" src="{{ Storage::url('/img/products/'.$value->getImage['name'])}}">
                            </div>
                        @endif
                    @endforeach
                    <div class="col-6">
                        <div style="margin-top: 20px; margin-left: 20px" class="">
                            <p style="margin-bottom: 40px"><b>{{$cart->name}}</b></p>
                            <span>Количество</span>
                            <div>
                                <span id="{{$cart->id}}" class="minus_prod minus down">-</span>
                                <input id="valCount{{$cart->id}}" type="text"
                                       style="text-align: center;width: 40px; border-color: transparent;"
                                       min="1" value="{{$cart->count}}"/>
                                <span id="{{$cart->id}}" class="plus_prod plus up">+</span>
                            </div>
                            @if((($cart->price_with_sale) != null))
                                <input class="price_{{$cart->id}}" type="hidden" value="{{$cart->price}}">
                                <input class="new_price_{{$cart->id}}" type="hidden" value="{{$cart->price_with_sale}}">
                                <s>Старая цена:<span class="old_cost_with_sale_{{$cart->id}}">{{$cart->price * $cart->count}}</span> грн.</s><br>
                                <b>Цена: <span class="price_{{$cart->id}}">{{$cart->price_with_sale * $cart->count}}</span> грн.</b>
                            @endif
                            @if((($cart->price_with_sale) == null))
                                <input class="price_{{$cart->id}}" type="hidden" value="{{$cart->price}}">
                                <input class="new_price_{{$cart->id}}" type="hidden" value="{{null}}">
                                <b>Цена: <span class="price_{{$cart->id}}"> {{$cart->price * $cart->count}}</span> грн.</b>
                            @endif
                            <button class="btn-del btn btn-link"
                                    style="padding-left: 0px!important; color:#9ea2a4; margin-bottom: 40px"
                                    value="{{$cart->id}}">
                                Удалить из корзины
                            </button>
                        </div>
                        </div>
                        @if((($cart->price_with_sale) == null))
                            <input type="hidden" value="{{$sum}}">
                        @endif
                        @if((($cart->price_with_sale) != null))
                            <input type="hidden"
                                   value="{{$sum_sale}}">
                        @endif
                        <input type="hidden" value="{{$sumAll}}">
                        @endforeach
                    </div>
            <div class="row">
                <div class="col-sm-12" style="margin-left: 30px">
                    @if(!empty($sum_modal))
                        @if($sumAll_sale < $sum_modal && $sumAll_sale > 0)
                            <div class="sumAll_sale">
                                Еще <span class="sumAll_sale-container">{{$sumAll_sale}}</span>
                                грн и сработает скидка {{$procent_modal.' %'}}
                            </div>
                        @endif
                    @endif
                    <span class="sumAll_sale2" style="text-align: center; margin-bottom: 15px"></span>
                    <div>Стоимость товаров:
                        <span class="sumAll-container">{{$sumAll}} грн.</span>
                    </div>
                    <div>Стоимость доставки: <span>{{env('NOVA_POSHTA_PRICE_DELIVERY')}}</span>грн.</div>
                    <div>Итого к оплате:
                        <b><span class="sumAll sumAll-delivery-container">{{($sumAll + env('NOVA_POSHTA_PRICE_DELIVERY')).toFixed(2)}}</span></b> грн.
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

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

<div class="PC-product">
    <div style="margin-bottom: 35px; text-align: center">
        <h2>Рекомендуемые товары</h2>
    </div>
    <div class="row">
        @foreach($products as $value)
            @if($value['sale_id'] == null)
                <div class="col-md-4 col-sm-12" style="margin-bottom: 20px">
                    <div class="card-item text-center" style="width: 18rem;">
                        <div>
                            <a href="product/{{$value->id}}">
                                <img class="img-fluid" style="width: 9em"
                                     src="{{ Storage::url('/img/products/'.$value->getImage['name'])}}"
                                     class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">{!!$value->name!!}</h5>
                                    <p class="card-text"><b>{!!$value->price . ' '!!}грн.</b></p>
                                </div>
                            </a>
                            <button id="btn-buyHome"
                                    style="width: 150px; background-color: #2f7484; border-color: #2f7484"
                                    class="btn btn-success rounded-pill btn-buy-item" value="{{$value->id}}">
                                Купить
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
<script>
    $(document).ready(function () {
        let sumAll = $('.sumAll').html();
        let val_nova_poshta_price = $('.val_nova_poshta_price').html();
        let sum_modal = $('#sum_modal').val();
        let sumAll_not_sale = $('#sumAll_not_sale').val();
        let percent = (parseInt(sumAll_not_sale) - parseInt(val_nova_poshta_price)) * 100 /(sum_modal);
        if (sumAll_not_sale >= sum_modal){
            $('.progress-bar2').hide()
        }
        function incrementProgress2(barSelector, countSelector, incrementor) {
            var bar = document.querySelectorAll(barSelector)[0].firstElementChild,
                newWidth = incrementor;
            if (newWidth > 100) {
                newWidth = 100;
            } else if (newWidth < 0) {
                newWidth = 0;
            }
            bar.style.width = newWidth + '%';
            document.querySelectorAll(countSelector)[0].innerHTML = newWidth.toFixed(1) + '%';
        }

        function incrementProgressLoop2() {
            incrementProgress2('.progress-bar2', '.progress-count2', Math.ceil(percent) + 1);
        }

        incrementProgressLoop2();
    })
</script>
