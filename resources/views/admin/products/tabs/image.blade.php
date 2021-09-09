
<div class="tab-pane" id="tab-image">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left">Изображение товара</td>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="text-left"><a onclick="getModal('main')" data-toggle="modal" data-target="#myModal" id="thumb-image" data-toggle="image"
                                         class="img-thumbnail"><img
                                id="main"
                                src="{{ !empty($product->image->name) ? Storage::disk('public')->url('storage/img/products/'. $product->image->name) : 'https://biothal.com.ua/image/cache/no_image-100x100.png'}}"
                                alt="" title=""
                                width="100"
                                data-placeholder="https://biothal.com.ua/image/cache/no_image-100x100.png"/></a><input
                            type="hidden" name="image_id"
                            value="{{$product->image_id ?? 0}}"
                            id="input-image_main"/></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table id="images" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left">Дополнительные изображения</td>
                <td class="text-right">Порядок сортировки</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            @if (!empty($id))
                @foreach ($product->productImages as $key => $productImages)
                    <tr id="image-row{{ $key }}">
                        <td class="text-left">
                            <a onclick="getModal('{{ $key }}')" data-toggle="modal" data-target="#myModal" id="thumb-image{{ $key }}" data-toggle="image" class="img-thumbnail">
                                <img id="sub_image_{{ $key }}" src=" {{Storage::disk('public')->url('storage/img/products/'. $productImages->images->name)}}" width="100" alt="" title="">
                            </a>
                            <input type="hidden" name="product_image[{{ $key }}][image]" value="{{ $productImages->image }}" id="input-image0">
                        </td>
                        <td class="text-right" style="vertical-align: middle;">
                            <input type="text" name="product_image[{{ $key }}][sort_order]" value="{{ $productImages->sort_order }}" placeholder="Порядок сортировки" class="form-control">
                        </td>
                        <td class="text-left" style="vertical-align: middle;">
                            <button type="button" onclick="$('#image-row{{ $key }}').remove();" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="Удалить">
                                <i class="fa fa-minus-circle"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"></td>
                <td class="text-left">
                    <button type="button" onclick="addImage();" data-toggle="tooltip"
                            title="Добавить" class="btn btn-primary"><i
                                class="fa fa-plus-circle"></i></button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="display: block;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center"><i class="fas fa-images"></i> Галерея</h4>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Название картинки</label>
                        <input type="text" value="" placeholder="Название картинки"
                               id="input-title-image" class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-2">
                    <a onclick="getImages(1)" id="filter-href" style="color: #ffffff !important;">
                        <button type="button" id="button-filter" class="btn btn-primary" style="margin-top: 21px">
                            <i class="fa fa-search"></i> Найти
                        </button>
                    </a>
                </div>
            </div>
            <div class="modal-body">
                <div class="page-header w-100 alert  p-0  mt-2 text-center" id="imagesModal">

{{--                    <div class="row" style="margin-left:10px">--}}
{{--                        <div class="col-sm-6 text-left">{{ $images->links() }}</div>--}}
{{--                        <div style="padding-right: 25px;" class="col-sm-6 text-right">Показано с 1 по {{ $images->lastItem() }} из {{ $images->total() }} (страниц: {{ $images->lastPage() }})</div>--}}
{{--                    </div>--}}
                </div>
                <div id="paginate" class="row" style="margin-left: 10px; display:none;">
                    <div class="col-sm-6 text-left">
                        <nav id="paginate_data">
                        </nav>
                    </div>
                    <div class="col-sm-6 text-right" style="padding-right: 25px;">Показано с <span id="from"></span> по <span id="to"></span> из <span id="all_count"></span> (страниц: <span id="page_count"></span>)</div>
                </div>
            </div>
            <div class="modal-footer">
                <input id="image" type="hidden" />
                <input id="image_id" type="hidden" />
                <input id="image_name" type="hidden" />
                <button type="button" class="btn btn-default btn-success" data-dismiss="modal" onclick="addNewImage()">Добавить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>

    </div>
</div>

