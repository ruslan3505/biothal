@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .viewbox-container{
            z-index: 1100 !important;
        }
        .card-body:hover{
            box-shadow: 0 2px 8px rgb(0 0 0 / 25%);
        }
    </style>
@endsection

@section('content')
    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Товары</div>
                    <div class="pull-right col-sm-6">
                        <button type="submit" form="form-product" data-toggle="tooltip" title="Сохранить" class="btn btn-primary">
                            <i class="fa fa-save"></i>
                        </button>
                        <a href="{{ route('admin.products.pageNew') }}" data-toggle="tooltip" title="Отменить" class="btn btn-default">
                            <i class="fa fa-reply"></i>
                        </a>
                    </div>
                    <div class="breadcrumb col-sm-12" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href="{{route('admin.products.pageNew')}}"> Товары</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container-fluid" style="padding-left: 0;
        padding-right: 0;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> @if(isset($id))Редактирование@elseСоздание@endif</h3>
                </div>
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ isset($id) ? route('admin.products.changeNewProd', ['id' => $product['id']]) : route('admin.products.createProd') }}" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
                        @csrf
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-general" role="tab" aria-controls="home" aria-selected="true">Основное</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-data" role="tab" aria-controls="home" aria-selected="false">Данные</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-links" role="tab" aria-controls="home" aria-selected="false">Связи</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-image" role="tab" aria-controls="home" aria-selected="false">Изображения</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-apt" role="tab" aria-controls="home" aria-selected="false">Вкладки</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            @include('admin.products.tabs.general')
                            @include('admin.products.tabs.data')
                            @include('admin.products.tabs.links')
                            @include('admin.products.tabs.image')
                            @include('admin.products.tabs.apt')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="storage" value="{{Storage::disk('public')->url('storage/img/products/')}}" />
@endsection

@section('script')
<script type="text/javascript">
    var apt_row = {{ !empty($product) ? $product->productApts->count() : 0}};
    function addApt(lang_id) {
        html  = '<div id="apt_row_' + lang_id + '_' + apt_row + '" class="row" style="margin-bottom: 20px">';
        html += '<input type="hidden" name="product_apt[' + apt_row + '][language_id]" value ="' + lang_id + '">';
        html += '<div class="col-sm-2"><input type="text" name="product_apt[' + apt_row + '][tab_title]" value="" id="apt_name' + apt_row + '" class="form-control" /></div>';
        html += '<div class="col-sm-8"><textarea name="product_apt[' + apt_row + '][tab_desc]"  id="apt_desc_1_' + apt_row + '" cols="45" rows="5" ></textarea></div>';
        html += '<div class="col-sm-1"><input type="text" name="product_apt[' + apt_row + '][sort_order]" value="" id="sort_order' + apt_row + '" size="5" class="form-control"/></div>';
        html += '<div class="col-sm-1"><a onclick="$(\'#apt_row_' + lang_id + '_' + apt_row  + '\').remove();" class="btn btn-danger"><i class="fa fa-minus-circle fa-fw"></i></a></div>';
        html += '</div>';

        $('#apts_' + lang_id + ' #put-here-' + lang_id).before(html);
        $('#apt_desc_1_'+apt_row).summernote({height: 300});
        apt_row++;
    }
    $('#languages_apt a:first').tab('show');

    function getImages(page)
    {
        let title_image = $('#input-title-image').val();

        let storage = $('#storage').val();
        $('#paginate').hide();
        $.ajax({
            method: 'GET',
            url: "/admin/Images/getImages",
            data: {
                page: page,
                title_image: title_image
            },
            success : function(result){
                let images = result.data.data;
                let to = result.data.to;
                let from = result.data.from;
                let pages = result.data.last_page;
                let total = result.data.total;
                let per_page = result.data.per_page;
                let current_page = result.data.current_page;

                if($.trim(images)){
                    let htmls = '';
                    images.forEach((item, key) => {
                        let html = '<div class="card-body justify-content-start col-3" style="display:inline-flex; column-count: 4; padding: 0.25rem;">'+
                                        '<input type="radio" id="pictures_'+item.id+'" name="image_gallary_input" onclick="setImage('+item.id+',\''+item.name+'\')"'+
                                        'value="'+item.id+'">'+
                                        '<label class="text-center" style="color:black;" for="pictures_'+item.id+'" id="pictures_label_'+item.id+'">'+
                                            '<a href="'+storage + item.name+'" class="thumbnail">'+
                                                '<img class="img-fluid" style="min-height: 5em"'+
                                                'src="'+storage + item.name+'"'+
                                                'width="200" height="200" alt="Изображение товара">'+
                                            '</a>'+
                                            '<h5>'+item.name+'</h5>'+
                                        '</label>'+
                                    '</div>';
                        htmls = htmls + html;
                    });
                    $('#imagesModal').html(htmls);
                    let paginate = '<ul class="pagination">'+
                                        '<li href="javascript:void(0)" onclick="'+ (current_page > 1 ?  "getImages("+ (current_page - 1) +")" : "" ) +'" aria-disabled="true" aria-label="« Previous" class="page-item '+ (current_page <= 1 ? "disabled" : "") +'">'+
                                            '<a aria-hidden="true" class="page-link">‹</a>'+
                                        '</li>';
                    for (var i = 1; i <= pages; i++) {
                        paginate = paginate + '<li href="javascript:void(0)" onclick="'+ (i === current_page ? "" : "getImages("+ i +")") +'" class="page-item '+ (i === current_page ? "active" : "")  +'">'+
                                                    '<a class="page-link">'+ i +'</a>' +
                                                '</li>';
                    }
                    paginate = paginate + '<li href="javascript:void(0)" onclick="'+ (current_page != pages ? "getImages("+ (current_page + 1) +")" : "") +'"  class="page-item '+ (current_page != pages ? "" : "disabled") +'">'+
                                            '<a rel="next" aria-label="Next »" class="page-link">›</a>'+
                                        '</li>'+
                                    '</ul>';
                    $('#paginate_data').html(paginate);
                    $('#all_count').text(total)
                    $('#page_count').text(pages)
                    $('#from').text(from);
                    $('#to').text(to);
                    $('#paginate').show();
                    $('.thumbnail').viewbox({
                        template: '<div class="viewbox-container"><div class="viewbox-body"><div class="viewbox-header"></div><div class="viewbox-content"></div><div class="viewbox-footer"></div></div></div>',

                        // loading spinner
                        loader: '<div class="loader"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>',

                        // show title
                        setTitle: true,

                        // margin in px
                        margin: 20,

                        // duration in ms
                        resizeDuration: 300,
                        openDuration: 200,
                        closeDuration: 200,

                        // show close button
                        closeButton: true,

                        // show nav buttons
                        navButtons: true,

                        // close on side click
                        closeOnSideClick: true,

                        // go to next image on content click
                        nextOnContentClick: true,

                        // enable touch gestures
                        useGestures: true,

                        // image extensions
                        // used to determine if a target url is an image file
                        imageExt: ['png', 'jpg', 'jpeg', 'webp', 'gif']
                    });
                } else {
                    let html = '<p style="margin-top: 20px;margin-bottom: 20px;">ГАЛЕРЕЯ - ПУСТА!</p>';
                    $('#imagesModal').html(html);
                }
            }
        });
    }

    function getModal(image)
    {
        $('#image').val(image);
        getImages(1);
        $('#input-title-image').val('');
    }

    $( "#input-title-image" ).keyup(function() {
        if ($('#input-title-image').val().length > 3) {
            getImages(1);
        }
    });

    function addNewImage()
    {
        let direction = $('#image').val();
        let image = '';
        let input = ''
        if(direction === 'main'){
            image = $('#'+ direction);
            input = $('#input-image_'+ direction);
        } else {
            image = $('#sub_image_'+ direction);
            input = $('#input-image'+ direction);
        }
        let id = $('#image_id').val();
        let name = $('#image_name').val();
        let storage = $('#storage').val();
        if(id > 0){
            input.val(id);
            image.attr('src', storage + name);
        }
    }

    function setImage(id, name)
    {
        $('#image_id').val(id);
        $('#image_name').val(name);
    }
</script>
<script type="text/javascript">
  var image_row = {{ !empty($product) ? $product->productImages->count(): 0 }};

  function addImage() {
    html  = '<tr  id="image-row' + image_row + '">';
    html += '  <td class="text-left"><a onclick="getModal('+ image_row +')" data-toggle="modal" data-target="#myModal" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img id="sub_image_' + image_row + '" src="https://biothal.com.ua/image/cache/no_image-100x100.png" alt="" title="" data-placeholder="https://biothal.com.ua/image/cache/no_image-100x100.png" /></a><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
    html += '  <td class="text-right" style="vertical-align: middle;"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="Порядок сортировки" class="form-control" /></td>';
    html += '  <td class="text-left" style="vertical-align: middle;"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="Удалить" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#images tbody').append(html);

    image_row++;
  }
</script>
<script type="text/javascript">
    $('#language a:first').tab('show');
    $('#option a:first').tab('show');
</script>

    <script src="{{asset('js/products.js')}}"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script type="text/javascript">
        $('.summernote').summernote();
    </script>

<script type="text/javascript">
    function getAccessories() {

        let id = $('#main_category_id').val();

        if (id !== 'null') {
            $.ajax({
                url: "/admin/accessories/get",
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
                    $('#accessory_id option').each( function () {
                            $(this).remove()
                        }
                    )
                    $('#accessories-rows').each( function () {
                            $(this).remove()
                        }
                    )
                    $('#tab-links').append('<div id="accessories-rows"><div>');
                    $('#accessory_id').append('<option id="noChoose" value="null">--- Не выбрано ---</option>');
                    if($.trim(resp['accessories'])){
                        let html = '';
                        resp['accessories'].forEach(function (item, index) {
                            html += '<option id="accessory_'+item.id+'" value="'+item.id+'">'
                            html +=     item.title
                            html += '</option>'
                        });
                        $('#accessory_id').append(html);
                    } else {
                        $('#accessory_id option').each( function () {
                                $(this).remove()
                            }
                        )
                        $('#accessory_id').append('<option id="noChoose" value="null">--- Не выбрано ---</option>');
                    }
                }
            })
        } else {
            $('#accessory_id option').each( function () {
                    $(this).remove()
                }
            )
            $('#accessories-rows').each( function () {
                    $(this).remove()
                }
            )
            $('#tab-links').append('<div id="accessories-rows"><div>');
            $('#accessory_id').append('<option id="noChoose" value="null">--- Не выбрано ---</option>');
        }
    }

    function addAccessory() {

        let accessory_id = $('#accessory_id').val();
        let accessory_title = $('#accessory_' + accessory_id).text();

        if ($('#main_category_id').val() !== 'null'){
            if ($('#accessory_id').val() !== 'null') {
                if (!$('#accessory-row' + accessory_id).length) {
                    html  = '<div class="form-group" id="accessory-row' + accessory_id + '">';
                    html += '   <div class="col-sm-2 d-flex justify-content-end"><button type="button" onclick="$(\'#accessory-row' + accessory_id  + '\').remove();" data-toggle="tooltip" title="Удалить" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></div>';
                    html += '   <div class="col-sm-10"><input disabled id="' + accessory_id + '" type="text" value="' + accessory_title + '" class="form-control" /></div>';
                    html += '   <input hidden type="text" name="accessoryProducts[' + accessory_id + '][accessory_id]" value="' + accessory_id + '" class="form-control" />';
                    html += '</div>';

                    $('#accessories-rows').append(html);
                }

            } else {
                $('#noChoose').text('--- Не выбрана потребность! ---');
            }

        } else {
            $('#noChoose').text('--- Не выбрана категория! ---');
        }
    }
</script>
@endsection
