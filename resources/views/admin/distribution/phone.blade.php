@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .input-group {
            display: flex;
        }
    </style>
    <style>

    </style>
@endsection

@section('content')
    <div class="modal hide" id="add_phones" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Добавить телефон для рассылки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Телефоный номер</span>
                        </div>
                        <input type="text" class="form-control" id="phone_add"  placeholder="Введите телефоный номер">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Группа</span>
                        </div>
                        <select id="group_id_add" class="custom-select">
                            <option value="0" selected>Без группы</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_phone" class="btn btn-primary">Добавить телефон</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change_phones" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Изменить телефонный номер</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Телефоный номер</span>
                        </div>
                        <input type="text" class="form-control" id="phone_edit"  placeholder="Введите телефоный номер">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Группа</span>
                        </div>
                        <select id="group_id_edit" class="custom-select">
                            <option value="0" selected>Без группы</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="email_hidden_id" id="phone_hidden_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="b_change_phone">Изменить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="send_phones" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Отправить СМС Рассылку</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Группа</span>
                        </div>
                        <select id="group_id_send" class="custom-select">
                            <option value="0" selected>Без группы</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Содержимое смс</span>
                        </div>
                        <textarea name="description_send"
                                  id="description_send"
                                  class="form-control">
                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="b_send_phones">Отправить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="groups" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="example_modal_label">Группы</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="groups_table" style="width:100%">
                        <thead class="text-center">
                        <tr>
                            <th scope="col">Идентификатор</th>
                            <th scope="col">Название группы</th>
                            <th scope="col">Редактировать</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="delete_group" type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i>
                    </button>
                    <button type="button" class="btn btn-success" data-target="#add_groups" data-toggle="modal">Добавить группу</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal hide" id="add_groups" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Добавить группу</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название</span>
                        </div>
                        <input type="text" class="form-control" id="name_add"  placeholder="Введите название группы">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_group" class="btn btn-primary">Добавить группу</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="change_groups" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example_modal_label">Изменить группу</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Название</span>
                        </div>
                        <input type="text" class="form-control" id="name_edit"  placeholder="Введите название">
                    </div>
                </div>
                <input type="hidden" name="group_hidden_id" id="group_hidden_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="b_change_group">Изменить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="prod-header border container-fluid">
        <div class="row page-header">
            <div class="container-fluid col-sm-12" >
                <div class="row">
                    <div class="h1-prod col-sm-6"><i class="fa fa-list"></i> СМС рассылка</div>
                    <div class="pull-right col-sm-6">
                        <a href="javascript" data-target="#add_phones" data-toggle="modal" title="Добавить" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                        <a href="javascript" data-target="#groups" data-toggle="modal" title="Группа" class="btn btn-info">Группы</a>
                        <a href="javascript" data-target="#send_phones" data-toggle="modal" title="Отправить Эмейл" class="btn btn-success">Отправить Рассылку</a>
                        <button id="delete_email" type="button" data-toggle="tooltip" title="Удалить" class="btn btn-danger">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                    <div class="breadcrumb col-sm-12 pull-left" style="background: none">
                        <div><a href="/admin/dashboard"><i class="fa fa-home fa-lg"></i></a></div>
                        <div style="margin-right: 5px">/ </div>
                        <div><a href="">СМС рассылка</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border container-fluid" style="padding-left: 0;
    padding-right: 0;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="well">
                    @if($phones == null)
                        <p>Телефоны для рассылки отсутствуют ¯\_(ツ)_/¯</p>
                    @else
                        <div class="container-fluid m-2">
                            <table class="table table-bordered table-hover" id="phones_table" style="width:100%">
                                <thead class="text-center">
                                <tr>
                                    <th scope="col">Идентификатор</th>
                                    <th scope="col">Телефонный номер</th>
                                    <th scope="col">Группа</th>
                                    <th scope="col">Редактировать</th>
                                </tr>
                                </thead>
                                <tbody class="text-center">
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script type="text/javascript">
        $('.summernote').summernote();
    </script>
    <script>
        $(function () {
            var table = $('#phones_table').DataTable({
                "language": {
                    "search":  'Поиск',
                    "processing": 'Загрузка......',
                    "sInfo": 'Показано _START_ по _END_ с _TOTAL_ записей',
                    "infoEmpty": 'Показано с 0 по 0 из 0 записей',
                    "lengthMenu": 'Показать _MENU_ Записей',
                    "paginate": {
                        "first":      "Первая",
                        "last":       "Последняя",
                        "next":       "Следующая",
                        "previous":   "Предыдущая"
                    },
                    "zeroRecords": 'Пусто'
                },
                processing: true,
                serverSide: true,
                ajax: "/admin/phoneList",
                columns: [
                    {"data": "number", "name": "id"},
                    {"data": "phone", "name": "phone"},
                    {"data": "group", "name": "group"},
                    {"data": 'action', "orderable": false, "searchable": false},
                ],
                select: {
                    style: 'multi',
                    selector: 'td:not(:last-child)'
                },
            });

            $(document).on("click", "#b_change_phone", function () {
                let phone = $('#phone_edit').val();
                let group_id = $('#group_id_edit').val();
                let id = $("#phone_hidden_id").val();

                $.ajax({
                    url: '/admin/editPhone',
                    method: 'POST',
                    data: {
                        "phone": phone,
                        "group_id": group_id,
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

                        // Меняем категорию
                        if (resp['message']) {
                            $('#change_phones').modal('hide');
                            $('.modal-backdrop').remove();
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: resp['message'],
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                })
            });


            // Добавление скидок
            $(document).on("click", '#add_phone', function () {
                let phone = $('#phone_add').val();
                let group_id = $('#group_id_add').val();

                $.ajax({
                    url: '/admin/addPhone',
                    method: 'POST',
                    data: {
                        "phone": phone,
                        "group_id": group_id
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
                        if (resp['message']) {
                            $('#add_phones').modal('hide');
                            $('.modal-backdrop').remove();
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: resp['message'],
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            window.location.replace("/admin/phoneList");
                        }
                    }
                });
            })

            $(document).on("click", "[id^=phones_change]", function () {
                let id = $(this).data('id');
                let phone = $(this).data('phone');
                let group_id = $(this).data('group_id');

                $("#phone_edit").val(phone);
                $("#phone_hidden_id").val(id);
                $("#group_id_edit").val(group_id);
            })


            $(document).on("click", "#delete_email", function () {
                var data = table.rows({selected: true}).data(), arr = [], count = table.rows({selected: true}).count();
                if (count !== 0) {
                    for (var i = 0; i < count; i++) {
                        arr[i] = data[i]['id'];
                    }
                }
                $.ajax({
                    url: '/admin/deletePhone',
                    method: 'POST',
                    data: {"checked": arr, "status": 0},
                    error: function (xhr, status, error) {
                        var errors = xhr.responseJSON.errors, errorMessage = "";
                        console.log(errors);
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
                        // Если родительская категория не выбрана, удаляем выбранные категории
                        if (resp['accepted']) {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: resp['accepted'],
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        if (resp['warning']) {
                            // Если роительская категория выбрана, уточняем у пользователя уверен ли он в своем выборе
                            Swal.fire({
                                title: resp['warning']['title'],
                                text: resp['warning']['text'],
                                showDenyButton: true,
                                showCancelButton: false,
                                confirmButtonText: 'Удалить',
                                denyButtonText: 'Отмена'
                            }).then((result) => {
                                // Если уверен, удаляем
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '/admin/deleteEmail',
                                        method: 'POST',
                                        data: {"checked": arr, "status": 1},
                                        success: function (ret) {
                                            if (ret['status']) {
                                                if (ret['accepted']) {
                                                    table.ajax.reload();
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: ret['accepted'],
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    });
                                                }
                                                if (ret['error']) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: resp['error'],
                                                        timer: 1700,
                                                        showConfirmButton: false,
                                                    })
                                                }
                                            } else {
                                                window.location.replace("/admin/phoneList");
                                            }
                                        }
                                    })
                                    // Если нет, отменяем
                                } else if (result.isDenied) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Телефоны не удалены',
                                        timer: 1300,
                                        showConfirmButton: false,
                                    })
                                }
                            })
                        }
                        if (resp['error']) {
                            Swal.fire({
                                icon: 'error',
                                title: resp['error'],
                                timer: 1700,
                                showConfirmButton: false,
                            })
                        }
                    }
                })
            })

            var groupTable = $('#groups_table').DataTable({
                "language": {
                    "search":  'Поиск',
                    "processing": 'Загрузка......',
                    "sInfo": 'Показано _START_ по _END_ с _TOTAL_ записей',
                    "infoEmpty": 'Показано с 0 по 0 из 0 записей',
                    "lengthMenu": 'Показать _MENU_ Записей',
                    "paginate": {
                        "first":      "Первая",
                        "last":       "Последняя",
                        "next":       "Следующая",
                        "previous":   "Предыдущая"
                    },
                    "zeroRecords": 'Пусто'
                },
                processing: true,
                serverSide: true,
                ajax: "/admin/groupPhoneList",
                columns: [
                    {"data": "id", "name": "id"},
                    {"data": "name", "name": "name"},
                    {"data": 'action', "orderable": false, "searchable": false},
                ],
                select: {
                    style: 'multi',
                    selector: 'td:not(:last-child)'
                },
            });

            $(document).on("click", "#b_change_group", function () {
                let name = $('#name_edit').val();
                let id = $('#group_hidden_id').val();

                $.ajax({
                    url: '/admin/editPhoneGroup',
                    method: 'POST',
                    data: {
                        "name": name,
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

                        // Меняем категорию
                        if (resp['message']) {
                            $('#change_groups').modal('hide');
                            $('.modal-backdrop').remove();
                            groupTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: resp['message'],
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                })
            });

            // Добавление скидок
            $(document).on("click", '#add_group', function () {
                let name = $('#name_add').val();

                $.ajax({
                    url: '/admin/addPhoneGroup',
                    method: 'POST',
                    data: {
                        "name": name
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
                        if (resp['message']) {
                            $('#add_groups').modal('hide');
                            $('.modal-backdrop').remove();
                            groupTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: resp['message'],
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            window.location.replace("/admin/phoneList");
                        }
                    }
                });
            })

            $(document).on("click", "[id^=groups_change]", function () {
                let id = $(this).data('id');
                let name = $(this).data('name');

                $("#group_hidden_id").val(id);
                $("#name_edit").val(name);
            })


            $(document).on("click", "#delete_group", function () {
                var data = groupTable.rows({selected: true}).data(), arr = [], count = groupTable.rows({selected: true}).count();
                if (count !== 0) {
                    for (var i = 0; i < count; i++) {
                        arr[i] = data[i]['id'];
                    }
                }
                $.ajax({
                    url: '/admin/deletePhoneGroup',
                    method: 'POST',
                    data: {"checked": arr, "status": 0},
                    error: function (xhr, status, error) {
                        var errors = xhr.responseJSON.errors, errorMessage = "";
                        console.log(errors);
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
                        // Если родительская категория не выбрана, удаляем выбранные категории
                        if (resp['accepted']) {
                            groupTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: resp['accepted'],
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        if (resp['warning']) {
                            // Если роительская категория выбрана, уточняем у пользователя уверен ли он в своем выборе
                            Swal.fire({
                                title: resp['warning']['title'],
                                text: resp['warning']['text'],
                                showDenyButton: true,
                                showCancelButton: false,
                                confirmButtonText: 'Удалить',
                                denyButtonText: 'Отмена'
                            }).then((result) => {
                                // Если уверен, удаляем
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '/admin/deletePhoneGroup',
                                        method: 'POST',
                                        data: {"checked": arr, "status": 1},
                                        success: function (ret) {
                                            if (ret['status']) {
                                                if (ret['accepted']) {
                                                    groupTable.ajax.reload();
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: ret['accepted'],
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    });
                                                }
                                                if (ret['error']) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: resp['error'],
                                                        timer: 1700,
                                                        showConfirmButton: false,
                                                    })
                                                }
                                            } else {
                                                window.location.replace("/admin/phoneList");
                                            }
                                        }
                                    })
                                    // Если нет, отменяем
                                } else if (result.isDenied) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'группы не удалены',
                                        timer: 1300,
                                        showConfirmButton: false,
                                    })
                                }
                            })
                        }
                        if (resp['error']) {
                            Swal.fire({
                                icon: 'error',
                                title: resp['error'],
                                timer: 1700,
                                showConfirmButton: false,
                            })
                        }
                    }
                })
            })

            $(document).on("click", "#b_send_phones", function () {
                let description = $('#description_send').val();
                let group_id = $('#group_id_send').val();
                showLoading();
                $.ajax({
                    url: '/admin/sendPhone',
                    method: 'POST',
                    data: {
                        "group_id": group_id,
                        "description": description
                    },
                    error: function (xhr, status, error) {
                        hideLoading();
                        var errors = xhr.responseJSON.errors, errorMessage = "";
                        $.each(errors, function (index, value) {
                            $.each(value, function (key, message) {
                                errorMessage += message + " ";
                            })
                        })
                        Swal.fire({
                            icon: 'error',
                            title: xhr.responseJSON.message ? xhr.responseJSON.message : errorMessage,
                            showConfirmButton: true,
                        })
                    },
                    success: function (resp) {
                        hideLoading();
                        // Меняем категорию
                        if (resp['message']) {
                            $('#send_phones').modal('hide');
                            $('.modal-backdrop').remove();
                            Swal.fire({
                                icon: 'success',
                                title: resp['message'],
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                })
            });

            $('#groups').on('hide.bs.modal', function (event) {
                location.reload();
            })
        });
    </script>
@endsection
