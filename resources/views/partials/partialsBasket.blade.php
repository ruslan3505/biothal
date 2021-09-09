<div class="modal-content table-container">
    <div class="modal-header">
        <div>
            <h5 class="modal-title" id="exampleModalLongTitle">
                Корзина (<span class="countAll-container">
                    {{$countAll}}
                </span>)
            </h5>
        </div>

        <button type="button" class="close" style="margin-right: 5px" data-dismiss="modal"
                aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" style="margin-right: 30px;">
        <div class="container">
            <input type="hidden" value="{{$sumAll_sale}}">
            <input id="sumAll_not_sale" type="hidden" value="{{$sumAll_not_sale}}">
            @if(!empty($sum_modal))
            <input id="sum_modal" type="hidden" value="{{$sum_modal}}">
            @endif
            <input id="nova_poshta_price_delivery" type="hidden" value="{{env('NOVA_POSHTA_PRICE_DELIVERY')}}">
            @if(empty($sumAll) && !empty($procent_modal))
                <div style="margin-left: auto;margin-right: auto; margin-bottom: 15px">
                    Скидка {{$procent_modal.' %'}}
                    срабатывает от суммы {{$sum_modal.' '}}грн
                </div>
            @endif
            @if($sumAll_sale <= 0 )
                @if(!empty($procent_modal))
                    <div style="text-align: center; margin-bottom: 15px"><span class="sumAll_sale_first">Ваша
                                        скидка {{$procent_modal.' %'}}</span></div>
                @endif
            @endif
            @if(!empty($sum_modal))
                @if($sumAll_sale < $sum_modal && $sumAll_sale > 0)
                    <div class="sumAll_sale" style="text-align: center; margin-bottom: 10px">Еще <span class="sumAll_sale-container">{{$sumAll_sale}}</span>
                        грн и сработает скидка {{$procent_modal.' %'}}
                    </div>
                @endif
            @endif
            <div class="sumAll_sale2" style="text-align: center; margin-bottom: 15px; margin-left: 35px"></div>
            <input type="hidden" class="progress-count">
            <div class="progress-bar">
                <div style="width: 0"></div>
            </div>
            <div class="row justify-content-center">
                @foreach($cart_join as $cart)
                    <div class="col-6">
                        @foreach($products as $value)
                            @if($value->id == $cart->id)
                                @if(isset($value->getImage['name']))
                                    <img style="height: auto!important; padding: 10px" class="img-fluid"
                                         src="{{ Storage::url('/img/products/'.$value->getImage['name'])}}">
                                @endif
                            @endif
                        @endforeach
                    </div>
                    <div style="margin-top: 10px" class="col-6">
                        <p style="margin-bottom: 40px"><b>{{$cart->name}}</b></p>
                        <span>Количество</span>

                        <div>
                            <span id="{{$cart->id}}" class="minusik minus down">-</span>
                            <input id="valCount_{{$cart->id}}" type="text"
                                   style="text-align: center;width: 40px; border-color: transparent;"
                                   min="1" value="{{$cart->count}}"/>
                            <span id="{{$cart->id}}" class="plusik plus up">+</span>
                        </div>

                        @if((($cart->price_with_sale) != null))
                            <input class="price_{{$cart->id}}" type="hidden" value="{{$cart->price}}">
                            <input class="new_price_{{$cart->id}}" type="hidden" value="{{$cart->price_with_sale}}">
                            <s>Старая цена: <span class="old_cost_with_sale_{{$cart->id}}"> {{$cart->price * $cart->count}} </span>грн.
                            </s><br>
                            <b>Цена: <span class="price_{{$cart->id}}">{{$cart->price_with_sale * $cart->count}}</span> грн.</b>
                        @endif
                        @if((($cart->price_with_sale) == null))
                            <input class="price_{{$cart->id}}" type="hidden" value="{{$cart->price}}">
                            <input class="new_price_{{$cart->id}}" type="hidden" value="{{null}}">
                            <b>Цена:<span class="price_{{$cart->id}}">{{$cart->price * $cart->count}}</span>грн.</b>
                        @endif
                        <button class="btn-del btn btn-link"
                                style="padding-left: 0px!important; color:#9ea2a4; margin-bottom: 40px"
                                value="{{$cart->id}}">
                            Удалить из корзины
                        </button>
                    </div>
                    @if((($cart->price_with_sale) == null))
                        <input type="hidden" value="{{$sum}}">
                    @endif
                    @if((($cart->price_with_sale) != null))
                        <input type="hidden" value="{{$sum_sale}}">
                    @endif
                        <input type="hidden" value="{{$sumAll}}">
                @endforeach
            </div>
            @if(count($cart_prod_count) != null)
                <div class="row">
                    <div class="col-sm-12 total-cart-count">
                        @if($sumAll_sale <= 0 )
                            @if(!empty($procent_modal))
                                <div style="text-align: center;"><span class="sumAll_sale_first">Ваша
                                        скидка {{$procent_modal.' %'}}</span></div>
                            @endif
                        @endif
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
                        <div hidden>Стоимость доставки: <span
                                class="val_nova_poshta_price">{{env('NOVA_POSHTA_PRICE_DELIVERY')}}</span> грн.
                        </div>
                        <div hidden>Итого к оплате:
                            <b><span class="sumAll sumAll-delivery-container">{{($sumAll + env('NOVA_POSHTA_PRICE_DELIVERY')).toFixed(2)}}</span></b>
                            грн.
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center cart-div-buy">
                    <a href="/setCheck">
                        <button id="" type="submit" style="margin-top: 10px;" class="btn btn-myBuy cart-btn-buy">Оформить заказ
                        </button>
                    </a>
                </div>
            @endif
        </div>
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
            $('.progress-bar').hide()
        }
        function incrementProgress(barSelector, countSelector, incrementor) {
            var bar = document.querySelectorAll(barSelector)[0].firstElementChild,
                curWidth = parseFloat(bar.style.width),
                newWidth = curWidth + incrementor;
            if (newWidth > 100) {
                newWidth = 0;
            } else if (newWidth < 0) {
                newWidth = 100;
            }
            bar.style.width = newWidth + '%';
            document.querySelectorAll(countSelector)[0].innerHTML = newWidth.toFixed(1) + '%';
        }

        function incrementProgressLoop() {
            incrementProgress('.progress-bar', '.progress-count', Math.ceil(percent) + 1);
        }

        incrementProgressLoop();
    })
</script>
