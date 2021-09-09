
<div class="tab-pane" id="tab-apt">
    <div class="tab-content">
        <ul class="nav nav-tabs" id="language">
            <li class="nav-item">
                <a class="nav-link active" role="tab" aria-selected="true" href="#languages_apt_1" data-toggle="tab">
                    <img src="{{ url('image/en-gb.png')}}" title="English"/>
                    English
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="languages_apt_1">
                <div>
                    <div class="row" style="font-size: 14px;font-weight: bold;margin-bottom: 17px;border-bottom: 2px solid #ccc;padding-bottom: 10px;">
                        <div class="col-sm-2">Заголовок</div>
                        <div class="col-sm-8">Содержимое</div>
                        <div class="col-sm-1">Порядок</div>
                        <div class="col-sm-1">Удалить</div>
                    </div>
                </div>
                <div id="apts_1" class="apt-list">
                    @if(isset($id))
                        @foreach ($product->productApts as $key => $productApt)
                            <div id="apt_row_{{ $productApt->language_id }}_{{ $key }}" class="row" style="margin-bottom: 20px">
                                <input type="hidden" name="product_apt[{{$key}}][language_id]" value ="{{$productApt['language_id']}}">
                                <div class="col-sm-2">
                                    <input type="text" name="product_apt[{{$key}}][tab_title]" value="{{ $productApt->tab_title }}" id="apt_name{{ $key }}" class="form-control"/>
                                </div>
                                <div class="col-sm-8">
                                <textarea name="product_apt[{{$key}}][tab_desc]" id="apt_desc_{{ $key }}"
                                          cols="45" rows="5" class="form-control">{{ $productApt->tab_desc }}</textarea>
                                </div>
                                <div class="col-sm-1">
                                    <input name="product_apt[{{$key}}][sort_order]" type="text" id="sort_order{{ $key }}" value="{{ $productApt->sort_order }}" size="5" class="form-control"/>
                                </div>
                                <div class="col-sm-1">
                                    <a onclick="$('#apt_row_{{ $productApt->language_id }}_{{ $key }}').remove();" class="btn btn-danger">
                                        <i class="fa fa-minus-circle fa-fw" style="color: #ffffff"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div id="put-here-1"></div>
                    <a onclick="addApt(1);" class="btn btn-success"><i
                                class="fa fa-plus fa-fw"></i> <span>Добавить вкладку</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
