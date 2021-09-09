@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .input-group {
            display: flex;
        }

        .active-status {
            transform: scale(1.5) !important;
            flex-direction: column;
            justify-content: space-between;
            width: 50%;
            text-align: right;
        }

        .custom-control-input:checked~.custom-control-label::before{
            border-color: #25ff00 !important;
            background-color: #25ff00 !important;
        }
    </style>
@endsection

@section('content')

{{--    <ul class="nav nav-tabs" id="myTab" role="tablist">--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link active" id="profile-tab" style="color: #000000" data-toggle="tab" href="#profile"--}}
{{--               role="tab"--}}
{{--               aria-controls="profile" aria-selected="false"><b>Глобальные изображения</b></a>--}}
{{--        </li>--}}
{{--    </ul>--}}
<div class="prod-header border container-fluid">
    <div class="row page-header">
        <div class="container-fluid col-sm-12" >
            <div class="row">
                <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Глобальные изображения</div>
                <div class="pull-right col-sm-6">
                    <a href="javascript" data-toggle="modal" data-target="#example_modal2" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                    <button id="deletePic2" type="button" data-toggle="tooltip" data-placement="right" title="Удалить" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
                <div class="breadcrumb col-sm-12 pull-left" style="background: none">
                    <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                    <div style="margin-right: 5px">/ </div>
                    <div><a href=""> Глобальные изображения</a></div>
                </div>
            </div>
        </div>
    </div>
</div>


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
                    <div class="modal-body text-center">
                        <img id="pic2" src="http://placehold.it/2881x757" class="col-md-4 ml-auto"
                             alt="your image"
                             width="180" height="180">
                        <input id="img2-input" type="file" name="img2" onchange="readURL(this);">
                    </div>
                    <hr>
                    <div class="modal-body text-center">
                        Выберите картинку для мобильной версии
                    </div>
                    <div class="modal-body text-center">
                        <img id="pic_mobile" src="http://placehold.it/1081x1081" class="col-md-4 ml-auto"
                             alt="your image mobile"
                             width="180" height="180">
                        <input id="img-mobile-input" type="file" name="img_mobile" onchange="readURL(this);">
                    </div>
                    <div class="modal-body text-center">
                        <label for="basic-url">Ссылка банера</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="baner_url" id="basic-url" aria-describedby="basic-addon3">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <input type='submit' class="btn btn-dark" value="Добавить">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--- Page -->
    <div class="border container-fluid" id="img_page2" style="padding-left: 0;
    padding-right: 0;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="well">
                    <form action="{{route('admin.deleteGlobalImage')}}" method="post">
                        @csrf
                        @method('POST')
                        @if($imagesGlobal == null)
                            <div style="justify-content: center; display: flex">
                                <p>ГАЛЕРЕЯ - ПУСТА!</p>
                            </div>
                        @else
                            @foreach($imagesGlobal as $image_global)
                                <div class="card-body d-flex flex-row justify-content-start">
                                    @foreach($image_global as $global)
                                        @if(!$global->parent_id)
                                            <input type="checkbox" id="pictures_{{$global->id}}" name="checked2[]"
                                                   value="{{$global->id}}">
                                            <label for="pictures_{{$global->id}}" id="pictures_label_{{$global->id}}">
                                                <a href="{{ Storage::disk('public')->url('storage/img/carousel/'.$global->name) }}"
                                                   class="thumbnail" style="margin-bottom: 5px;">
                                                    <img class="card-body"
                                                         src="{{Storage::disk('public')->url('storage/img/carousel/'.$global->name)}}"
                                                         width="200" height="200" alt="Изображение товара">
                                                </a>
                                                <div class="active-status custom-control custom-switch">
                                                    <input :checked="{{$global->active}}" type="checkbox" class="custom-control-input" id="customSwitch_{{$global->id}}" onchange="changeState({{$global->id}})">
                                                    <label class="custom-control-label" for="customSwitch_{{$global->id}}"></label>
                                                </div>
                                            </label>
{{--                                        @else--}}
{{--                                            <label style="padding-left: 0!important; margin-left: 0!important" for="pictures_{{$global->id-1}}" id="pictures_label_{{$global->id-1}}">--}}
{{--                                                <a href="{{ Storage::disk('public')->url('storage/img/carousel/'.$global->name) }}"--}}
{{--                                                   class="thumbnail">--}}
{{--                                                    <img class="card-body"--}}
{{--                                                         src="{{Storage::disk('public')->url('storage/img/carousel/'.$global->name)}}"--}}
{{--                                                         width="71" height="71" alt="Изображение товара">--}}
{{--                                                </a>--}}
{{--                                            </label>--}}
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        function changeState(id){
            var activeStatus = $('#customSwitch_' + id).prop("checked") ? 1 : 0;

            $.ajax({
                url: "/admin/Images/changeImageActive",
                method: 'POST',
                data: {
                    id: id,
                    active: activeStatus
                },
                error: function (xhr, status, error) {
                    $('#customSwitch_' + id).prop("checked", !activeStatus);
                    Swal.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message,
                        showConfirmButton: true,
                    })
                },
                success: function (resp) {
                    if (resp['message']) {
                        Swal.fire({
                            icon: 'success',
                            title: resp['message'],
                            showConfirmButton: false,
                            timer: 1200
                        })
                    }
                }
            })
        }
        $("body").on("click", '#deletePic2', function () {
            var checked = document.querySelectorAll('input[id^=pictures]:checked'), i, arr = [];
            if (checked.length !== 0) {
                for (i = 0; i < checked.length; i++) {
                    arr[i] = parseInt(checked[i]['value']);
                }
            }
            $.ajax({
                url: '/admin/Images/deleteGlobal',
                method: 'POST',
                data: {"checked": arr},
                error: function (data) {
                    if (data.status === 422) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ой...',
                            text: 'Выберите хотя бы 1 изображение!'
                        });
                    }
                },
                success: function (resp) {
                    if (resp) return "ok",
                        Swal.fire({
                            icon: 'success',
                            title: 'Изображения успешно удалены',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            window.location.replace('/admin/Images/banner');
                        });

                }
            })
        })
    </script>

@endsection
