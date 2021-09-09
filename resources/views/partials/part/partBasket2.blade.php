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
            <span id="{{$cart->id}}" class="plusik plus_prod plus up">+</span>
        </div>

        @if((($cart->price_with_sale) != null))
            <input class="price_{{$cart->id}}" type="hidden" value="{{$cart->price}}">
            <input class="new_price_{{$cart->id}}" type="hidden" value="{{$cart->price_with_sale}}">
             <s>Старая цена:
                <span class="old_cost_with_sale_{{$cart->id}}">
                    {{$cart->price * $cart->count}}
                </span>
                 грн.
             </s><br>
            <b>Цена:
                <span class="price_{{$cart->id}}">
                    {{$cart->price_with_sale * $cart->count}}
                </span>
            грн.</b>
        @endif
        @if((($cart->price_with_sale) == null))
            <input class="price_{{$cart->id}}" type="hidden" value="{{$cart->price}}">
            <input class="new_price_{{$cart->id}}" type="hidden" value="{{null}}">
            <b>Цена:
                <span class="price_{{$cart->id}}">
                    {{$cart->price * $cart->count}}
                </span>
            грн.</b>
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
        <input type="hidden"
               value="{{$sum_sale}}">
    @endif
    <input type="hidden" value="{{$sumAll}}">
@endforeach


