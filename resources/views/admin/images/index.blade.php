@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .drop {
            width: auto;
            height: 200px;
            border: 3px dashed #DADFE3;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            background: white;
            /* margin: auto; */
            position: relative;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            /*&:hover
              cursor: pointer
              background: #f5f5f5*/;
        }

        .drop .cont {
            width: 500px;
            height: 170px;
            color: #8E99A5;
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        .drop .cont i {
            font-size: 400%;
            color: #8E99A5;
            position: relative;
        }

        .drop .cont .tit {
            font-size: 200%;
            text-transform: uppercase;
        }

        .drop .cont .desc {
            color: #A4AEBB;
        }

        .drop .cont .browse {
            margin: 10px 25%;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            background: #09f;
        }

        .drop input {
            width: 928px;
            height: 650px;
            cursor: pointer;
            background: red;
            opacity: 0;
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        #list {
            width: 100%;
            text-align: left;
            position: absolute;
            left: 0;
            top: 0;
        }

        #list .thumb {
            height: 100%;
            border: 1px solid #323a44;
            margin: 10px 5px 0 0;
        }
        output {
            padding-top: 0px !important;
        }
        img.thumbs {
            height: 91px;
            width: max-content;
        }
        .Upcard{
            margin-top: 24px;
            margin-left: 24px;
            margin-right: 24px;
            width: 94px;
            height: 94px;
            background: #FFFFFF 0% 0% no-repeat padding-box;
            border: 2px dashed #4981C24D;
            border-radius: 8px;
            opacity: 1;
            margin-bottom: 25px;
        }
        .remove_button{
            float: right;
            position: absolute;
            display: list-item;
            margin: -7px;
            border-radius: 15px;
            background-color: white;
        }
        .card_name{
            margin-bottom: 5px;
            max-width: 100%;
            overflow-wrap: break-word;
            line-height: 1.2;
            height: 26px;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6">Галерея</div>
                    <div class="pull-right col-sm-6">
                        <a href="javascript:void()" data-toggle="modal" data-target="#example_modal" data-toggle="tooltip" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                        <button id="deletePic"  type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>
                <div class="breadcrumb col-sm-12" style="background: none">
                    <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                    <div style="margin-right: 5px">/ </div>
                    <div><a href=""> Галерея</a></div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="input-name">Название картинки</label>
                        <input type="text" value="@if(!empty(request()->input('title_image'))){{request()->input('title_image')}}@endif" placeholder="Название картинки"
                               id="input-title-image" class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-1">
                    <a href="{{route('admin.images.page')}}" id="filter-href" style="color: #ffffff !important;">
                        <button type="button" id="button-filter" class="btn btn-primary pull-right">
                        <i class="fa fa-search"></i> Найти
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="border container-fluid" style="padding-left: 0;
    padding-right: 0;">
        <div class="panel panel-default">
            <!-- Modal -->
            <div class="modal fade" id="example_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{route('admin.addImage')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-center" id="example_modal_label">Добавить картинку</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                Выберите картинку которую хотите добавить
{{--                                <form class="box" method="post" action="" enctype="multipart/form-data">--}}
                                    <div class="drop">
                                        <div class="cont">
                                            <i class="fa fa-cloud-upload"></i>
                                            <div class="tit">
                                                Нажмите или переместите
                                            </div>
                                            <div class="desc">
                                                ваши изображение в рамку
                                            </div>
                                            <div class="browse">
                                                кликните для проводника
                                            </div>
                                        </div>
                                        <output id="list"></output>
                                        <input id="files" multiple="true" name="img[]" type="file" />
                                    </div>
                                    <div class="row" id="Cards">

                                    </div>
{{--                                </form>--}}
{{--                                <img style="margin-top: 10px" id="pic" src="http://placehold.it/180" class="col-md-4 ml-auto" alt="your image"--}}
{{--                                     width="180" height="180">--}}
{{--                                <input style="margin-top: 10px" id="img-input" type="file" name="img" onchange="readURL(this);">--}}
                            </div>
                            <div class="modal-footer">
                                <input type='submit' class="btn btn-dark" value="Добавить">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!--- Page -->
            <div id="img_page">
                <div class="page-header w-100 alert  p-0  mt-2 text-center">
                    <form style="margin-top: 10px;" action="{{route('admin.deleteImage')}}" method="post" >
                        @csrf
                        @method('POST')
                        @if(($images == null))
                            <p style="margin-top: 20px;margin-bottom: 20px;">ГАЛЕРЕЯ - ПУСТА!</p>
                        @else
                                @foreach($images as $image)
                                <div class="card-body justify-content-start col-2" style="display:inline-flex; column-count: 4; padding: 0.25rem;">
                                    <input type="checkbox" id="pictures_{{$image->id}}" name="checked[]"
                                           value="{{$image->id}}">
                                    <label class="text-center" style="color:black;" for="pictures_{{$image->id}}" id="pictures_label_{{$image->id}}">
                                        <a href="{{ Storage::disk('public')->url('storage/img/products/'.$image->name) }}"
                                           class="thumbnail">
                                            <img class="img-fluid" style="min-height: 5em"
                                                 src="{{Storage::disk('public')->url('storage/img/products/'.$image->name)}}"
                                                 width="200" height="200" alt="Изображение товара">
                                        </a>
                                        <h5>{{ $image->name }}</h5>
                                    </label>
                                </div>
                                @endforeach
                        @endif
                    </form>
                    <div class="row" style="margin-left:10px">
                        <div class="col-sm-6 text-left">{{ $images->appends(array('title_image' => !empty(request()->input('title_image')) ? request()->input('title_image') : ''))->links() }}</div>
                        <div style="padding-right: 25px;" class="col-sm-6 text-right">Показано с 1 по {{ $images->lastItem() }} из {{ $images->total() }} (страниц: {{ $images->lastPage() }})</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

            <!-- Modal -->
            <div class="modal fade" id="example_modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{route('admin.addGlobalImage')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-center" id="example_modal_label2">Добавить картинку</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                Выберите картинку которую хотите добавить
                            </div>
                            <div class="modal-footer">
                                <img id="pic2" src="http://placehold.it/2881x757" class="col-md-4 ml-auto"
                                     alt="your image"
                                     width="180" height="180">
                                <input id="img2-input" type="file" name="img2" onchange="readURL(this);">
                                <input type='submit' class="btn btn-dark" value="Добавить">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
<script>
    var drop = $("#files");
    var enter = false;
    drop.on('dragenter', function (e) {
        $(".drop").css({
            "border": "4px dashed #09f",
            "background": "rgba(0, 153, 255, .05)"
        });
        $(".cont").css({
            "color": "#09f"
        });
    }).on('dragleave dragend mouseout drop', function (e) {
        $(".drop").css({
            "border": "3px dashed #DADFE3",
            "background": "transparent"
        });
        $(".cont").css({
        "color": "#8E99A5"
        });
    });



    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
        let htmls = [];
        for (var i = 0, f; f = files[i]; i++) {

            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            // Closure to capture the file information.
            reader.onload = (function (theFile) {
                return function (e) {
                    // Render thumbnail.
                    var cards = document.getElementById('Cards');
                    let html = '';
                    html = '<div class="Upcard" id="upcard_'+i+'">'+
                                '<button onclick="deleteCard('+i+')" class="remove_button" type="button">'+
                                    '<span aria-hidden="true">&times;</span>'+
                                '</button>'+
                                '<div class="card-block text-center">'+
                                    '<img class="thumbs" src="'+e.target.result+'" title="'+ escape(theFile.name) + '"/>'+
                                    '<h6  class="card_name">'+ escape(theFile.name) +'</h6>'+
                                '</div>'+
                            '</div>';
                    cards.innerHTML = cards.innerHTML + html;
                };
            })(f);
            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }

    $('#files').change(handleFileSelect);

    function deleteCard(id)
    {
        $('#upcard_'+ id).remove();
    }

    function deleteCards()
    {
        $('.Upcard').remove();
    }
    $('#example_modal').on('hidden.bs.modal', function (e) {
        deleteCards()
    })

    $('#input-title-image').on('keyup', function (e) {
        var text = $('#input-title-image').val();
        var url = new URL($("#filter-href").attr("href"));
        var searchParams = new URLSearchParams(url.search);
        if(text !== '') {
            searchParams.set("title_image", text);
        } else {
            searchParams.delete("title_image");
        }
        $("#filter-href").attr("href", url.origin + url.pathname + "?" + searchParams.toString());
    })


    $( "#input-title-image" ).keyup(function() {

        if ($('#input-title-image').val().length > 3) {
            var text = $('#input-title-image').val();
            var url = new URL($("#filter-href").attr("href"));
            var searchParams = new URLSearchParams(url.search);

            if(text !== '') {
                searchParams.set("title_image", text);
                window.location.replace(url);
            } else {
                searchParams.delete("title_image");
            }

            $("#filter-href").attr("href", url.origin + url.pathname + "?" + searchParams.toString());
        }
    });
</script>
@endsection
