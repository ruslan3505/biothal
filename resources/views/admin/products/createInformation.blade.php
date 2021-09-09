@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')

    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> Статьи</div>
                    <div class="pull-right col-sm-6">
                        <button type="submit" form="form-information" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i class="fa fa-save"></i></button>
                        <a href="#" data-toggle="tooltip" title="Отменить" class="btn btn-default"><i class="fa fa-reply"></i></a>
                    </div>
                    <div class="breadcrumb col-sm-12" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href="{{route('admin.products.pageNew')}}"> Статьи</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="content">
        <div class="container-fluid" style="padding-left: 0;
        padding-right: 0;">
            <div class="panel panel-default">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> Редактирование</h3>
                </div>
                <div class="panel-body">
                    <form action="/admin/products/saveInformation" method="post" enctype="multipart/form-data" id="form-information" class="form-horizontal">
                        @csrf
                        <ul class="nav nav-tabs">
                            <li><a class="nav-link active" href="#tab-general" data-toggle="tab">Основное</a></li>
                            <li><a class="nav-link" href="#tab-data" data-toggle="tab">Данные</a></li>
                            <li><a class="nav-link" href="#tab-design" data-toggle="tab">Дизайн</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-general">
                                <ul class="nav nav-tabs" id="language">
                                    <li class="active"><a href="#language1" data-toggle="tab"><img src="{{ url('image/en-gb.png')}}" title="English" /> English</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane" id="language1">
                                        <br>
                                        <div class="form-group required">
                                            <label class="col-sm-2 control-label required" for="input-title1">Название статьи</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="title" placeholder="Название статьи" id="input-title1" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label  class="col-sm-2 control-label required" for="input-description1">Описание</label>
                                            <div class="col-sm-10">
                                                <textarea id="summernote" name="description" placeholder="Описание" id="input-description1" class="form-control summernote" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group required">
                                            <label class="col-sm-2 control-label required" for="input-meta-title1">Мета-тег Title</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="meta_title" placeholder="Мета-тег Title" id="input-meta-title1" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-meta-description1">Мета-тег Description</label>
                                            <div class="col-sm-10">
                                                <textarea name="meta_description" rows="5" placeholder="Мета-тег Description" id="input-meta-description1" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label" for="input-meta-keyword1">Мета-тег Keywords</label>
                                            <div class="col-sm-10">
                                                <textarea name="meta_keyword" rows="5" placeholder="Мета-тег Keywords" id="input-meta-keyword1" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-data">
                                <br>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label required" for="input-keyword"><span data-toggle="tooltip" title="Должно быть уникальным на всю систему и без пробелов">SEO URL</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="keyword" placeholder="SEO URL" id="input-keyword" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-bottom"><span data-toggle="tooltip" title="Показывать в нижней части сайта (футер, подвал)">Отображить снизу</span></label>
                                    <div class="col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="bottom" id="input-bottom" />
                                                &nbsp; </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-status">Статус</label>
                                    <div class="col-sm-10">
                                        <select name="status" id="input-status" class="form-control">
                                            <option selected="selected" value="1">Включено</option>
                                            <option value="0">Отключено</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label required" for="input-sort-order">Порядок сортировки</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="sort_order" placeholder="Порядок сортировки" id="input-sort-order" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-design">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <td class="text-left">Магазины</td>
                                            <td class="control-label required text-left">Выберите схему</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-left">Основной магазин</td>
                                            <td class="text-left"><select name="information_layout" class="form-control">
                                                    <option value="" selected="selected">Выбрать из списка</option>
                                                    @foreach($layouts as $layout)
                                                        <option value="{{$layout->id}}">{{ $layout->title }}</option>
                                                    @endforeach
                                                </select></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var apt_row = 2;
        function addAptengb() {
            html  = '<div id="apt_rowen-gb' + apt_row + '" class="row" style="margin-bottom: 20px">';
            html += '<div class="col-sm-2"><input type="text" name="product_apt_name[en-gb][]" value="" id="apt_name' + apt_row + '" class="form-control" /></div>';
            html += '<div class="col-sm-8"><textarea name="product_apt_desc[en-gb][]"  id="apt_desc_1' + apt_row + '" cols="45" rows="5" ></textarea></div>';
            html += '<div class="col-sm-1"><input type="text" name="tab_sort_order[en-gb][]" value="" id="sort_order' + apt_row + '" size="5" class="form-control"/></div>';
            html += '<div class="col-sm-1"><a onclick="$(\'#apt_rowen-gb' + apt_row  + '\').remove();" class="btn btn-danger"><i class="fa fa-minus-circle fa-fw"></i></a></div>';
            html += '</div>';

            $('#aptsen-gb #put-here').before(html);
            $('#apt_desc_1'+apt_row).summernote({height: 300});
            apt_row++;
        }
        $('#languages_apt a:first').tab('show');
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
@endsection
