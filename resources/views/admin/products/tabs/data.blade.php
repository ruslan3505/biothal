
<div class="tab-pane" id="tab-data">
    <input type="hidden" name="link" value="my_link">
    <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-model">Модель</label>
        <div class="col-sm-10">
            <input type="text" name="model"
                   value="{{$product['model'] ?? ''}}" placeholder="Модель"
                   id="input-model" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-upc"><span data-toggle="tooltip"
                                                                    title="Универсальный код товара">Англ.название</span></label>
        <div class="col-sm-10">
            <input type="text" name="upc" value="{{$product['upc'] ?? ''}}"
                   placeholder="Англ.название" id="input-upc" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-ean"><span data-toggle="tooltip"
                                                                    title="Европейский код товара">Артикул</span></label>
        <div class="col-sm-10">
            <input type="text" name="ean" value="{{$product['ean'] ?? ''}}" placeholder="Артикул" id="input-ean"
                   class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-jan"><span data-toggle="tooltip"
                                                                    title="Японский код товара">Объем</span></label>
        <div class="col-sm-10">
            <input type="text" name="jan" value="{{$product['jan'] ?? ''}}" placeholder="Объем" id="input-jan"
                   class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label required" for="input-price">Цена</label>
        <div class="col-sm-10">
            <input type="text" name="price" value="{{$product['price'] ?? ''}}" placeholder="Цена" id="input-price"
                   class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label required" for="input-quantity">Количество</label>
        <div class="col-sm-10">
            <input type="text" name="quantity" value="{{$product['quantity'] ?? ''}}" placeholder="Количество"
                   id="input-quantity" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label required" for="input-minimum"><span
                    data-toggle="tooltip"
                    title="Минимальное количество товара в заказе (меньше данного кол-ва товара, добавление в корзину будет запрещено )">Минимальное количество</span></label>
        <div class="col-sm-10">
            <input type="text" name="minimum" value="{{$product['minimum'] ?? ''}}" placeholder="Минимальное количество"
                   id="input-minimum" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-subtract">Вычитать со
            склада</label>
        <div class="col-sm-10">
            <select name="subtract" id="input-subtract" class="form-control">
                @foreach(config('products.product_subtract') as $product_subtract_key => $product_subtract)
                    <option value="{{$product_subtract_key}}"
                            @if(!empty($product) && $product_subtract_key == $product['subtract'] || empty($product) && $product_subtract_key == 0)
                            selected="selected"
                            @endif
                    >
                        {{$product_subtract}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-stock-status"><span
                    data-toggle="tooltip"
                    title="Статус показывается, когда товара нет на складе">Отсутствие на складе</span></label>
        <div class="col-sm-10">
            <select name="stock_status_id" id="input-stock-status" class="form-control">
                @if(!empty($stock_statuses))
                    @foreach($stock_statuses as $stock_status)
                        <option value="{{$stock_status['stock_status_id']}}"
                                @if(!empty($product) && $stock_status['stock_status_id'] == $product['stock_status_id'] || empty($product) && $stock_status['stock_status_id'] == 0)
                                selected="selected"
                                @endif>
                            {{$stock_status['name']}}
                        </option>
                    @endforeach
                @else
                    <option value="0" selected="selected">--- Не выбрано ---</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status">Статус</label>
        <div class="col-sm-10">
            <select name="status" id="input-status" class="form-control">
                @foreach(config('products.products_statuses') as $product_status_key => $product_status)
                    <option value="{{$product_status_key}}"
                            @if(!empty($product) && $product_status_key == $product['status'] || empty($product) && $product_status_key == 0)
                            selected="selected"
                            @endif>
                        {{$product_status}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label required" for="input-sort-order">Порядок
            сортировки</label>
        <div class="col-sm-10">
            <input type="text"
                   name="sort_order"
                   value="{{$product['sort_order'] ?? ''}}"
                   placeholder="Порядок сортировки"
                   id="input-sort-order" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-is-recommended">Рекомендуемый товар</label>
        <div class="col-sm-10">
            <select name="is_recommended" id="input-is-recommended" class="form-control">
                @foreach(config('products.product_is_recommended') as $product_is_recommended_key => $product_is_recommended)
                    <option value="{{$product_is_recommended_key}}"
                            @if(!empty($product) && $product_is_recommended_key == $product['is_recommended'] || empty($product) && $product_is_recommended_key == 0)
                            selected="selected"
                        @endif
                    >
                        {{$product_is_recommended}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-is-best_seller">Бестселлер</label>
        <div class="col-sm-10">
            <select name="is_best_seller" id="input-is-best_seller" class="form-control">
                @foreach(config('products.product_is_best_seller') as $product_is_best_seller_key => $product_is_best_seller)
                    <option value="{{$product_is_best_seller_key}}"
                            @if(!empty($product) && $product_is_best_seller_key == $product['is_best_seller'] || empty($product) && $product_is_best_seller_key == 0)
                            selected="selected"
                        @endif
                    >
                        {{$product_is_best_seller}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-is-new">Новинка</label>
        <div class="col-sm-10">
            <select name="is_new" id="input-is-new" class="form-control">
                @foreach(config('products.product_is_new') as $product_is_new_key => $product_is_new)
                    <option value="{{$product_is_new_key}}"
                            @if(!empty($product) && $product_is_new_key == $product['is_new'] || empty($product) && $product_is_new_key == 0)
                            selected="selected"
                        @endif
                    >
                        {{$product_is_new}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
