
<div class="tab-pane" id="tab-links">
    <!-- NeoSeo Exchange 1c - begin -->
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-1c_id">Код 1С</label>
        <div class="col-sm-10">
            <input type="text" name="productTo1C[1c_id]" value="{{(!empty($product) && $product['productTo1C']) ? $product['productTo1C']['1c_id'] : ''}}"
                   placeholder="Код 1С" id="input-1c_id" class="form-control"/>
        </div>
    </div>
    <!-- NeoSeo Exchange 1c - end -->
    <div class="form-group">
        <label class="col-sm-2 control-label required" for="input-category">Категория товара:</label>
        <div class="col-sm-10">
            <select onchange="getAccessories()" id="main_category_id" name="categoryProducts[category_id]" class="form-control">
                <option value="null"
                @if (empty($product['productCategory']['category_id']))
                    selected="selected"
                @endif> --- Не выбрано ---</option>
                @foreach($categories as $category)
                    <option value="{{$category['id']}}"
                        @if (!empty($product['productCategory']) && $category['id'] == $product['productCategory']['category_id'])
                        selected="selected"
                        @endif
                    >{{$category['full_name']}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-category">Потребность товара:</label>
        <div class="col-sm-10">
            <select id="accessory_id" class="form-control">
                <option id="noChoose" value="null"
                    @if (empty($accessories))
                        selected="selected"
                    @endif>
                    --- Не выбрано ---
                </option>
                @if (!empty($accessories))
                    @foreach($accessories as $accessory)
                        <option id="accessory_{{$accessory['id']}}" value="{{$accessory['id']}}">{{$accessory['title']}}</option>
                    @endforeach
                @endif
            </select>
        </div>


    </div>
    <div class="d-flex justify-content-end" style="margin-bottom: 15px">
        <a onclick="addAccessory()" class="btn btn-success"><i
                class="fa fa-plus fa-fw"></i> <span>Добавить потребность</span></a>
    </div>
    <div id="accessories-rows">
        @if(isset($id))
            @foreach ($product->accessories as $key => $accessory)
                @if(isset($accessory->accessoryDetails[0]))
                    <div class="form-group" id="accessory-row{{$accessory->accessory_id}}">
                        <div class="col-sm-2 d-flex justify-content-end"><button type="button" onclick="$('#accessory-row{{$accessory->accessory_id}}').remove();" data-toggle="tooltip" title="Удалить" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></div>
                        <div class="col-sm-10"><input disabled id="{{$key}}" type="text" value="{{$accessory->accessoryDetails[0]['title']}}" class="form-control" /></div>
                        <input hidden type="text" name="accessoryProducts[{{$key}}][accessory_id]" value="{{$accessory['accessory_id']}}" class="form-control" />
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

