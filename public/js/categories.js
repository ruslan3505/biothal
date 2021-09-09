$(function () {
    // Токен
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
    });

    // Всплывающие подсказки
    $('[data-toggle="tooltip"]').tooltip();

// Удаление Категорий
    $(document).on("click", "#delete_cat", function () {
        var data = table.rows({selected: true}).data(), arr = [], count = table.rows({selected: true}).count();
        if (count !== 0) {
            for (var i = 0; i < count; i++) {
                arr[i] = data[i]['id'];
            }
        }
        $.ajax({
            url: '/admin/categories/delete',
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
                                url: '/admin/categories/delete',
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
                                        if (ret['value']) {
                                            $.each(ret['value'], function (key, value) {
                                                $('#padre_category_select option[value=\"' + value + '\"]').remove();
                                                $('#padre_category_select_change option[value=\"' + value + '\"]').remove();
                                            })
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
                                        window.location.replace("/admin/categories");
                                    }
                                }
                            })
                            // Если нет, отменяем
                        } else if (result.isDenied) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Категории не удалены',
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

// Изменения Категорий
    $(document).on("click", "[id^=category_change]", function () {
        var id = $(this).data('id');
        var parentId = $(this).data('parentId');
        var typeCategory = $(this).data('typeCategoryChange');
        var title = $(this).data('title');
        var order = $(this).data('order');
        var seo_title = $(this).data('seoTitle');
        var bottom_view = $(this).data('bottom');
        var demand = $(this).data('demand');

        $("#type_category_change").val(typeCategory); // typeCategory
        $("#title_category_change").val(title); // Title
        $("#category_hidden_id").val(id);     // Id
        $("#ordering_category_change").val(order);  // Ordering
        $("#seo_title_change").val(seo_title);  // SEO title
        $("#bottom_view_change").val(bottom_view);  // SEO title

        if (typeCategory) {
            $("#padre_category_select_change option[value=NoCategory]").prop('selected', true);
            $("#padre_category_select_change").attr('disabled', true);
        } else {
            $("#padre_category_select_change").attr('disabled', false);

            if (parentId != null) { // Материнская категория
                $("#padre_category_select_change option[value=" + "\"" + parentId + "\"" + "]").prop('selected', true);
            } else {
                $("#padre_category_select_change option[value=NoCategory]").prop('selected', true);
            }
        }


        if (typeCategory) { // Тип категории
            $("#type_category_change option[value=info]").prop('selected', true);
            if (bottom_view) { // Тип категории
                $("#bottom_view_change option[value=1]").prop('selected', true);
            } else {
                $("#bottom_view_change option[value=0]").prop('selected', true);
            }
        } else {
            $("#type_category_change option[value=forProduct]").prop('selected', true);
            $("#btm_view_change").attr('hidden', true);
            $("#bottom_view_change option[value=0]").prop('selected', true);
        }

        if (demand === 1) { // Категория\Потребность
            $("#demand_change").prop("checked", true);
        } else {
            $("#demand_change").prop("checked", false);
        }
    })

    $(document).on("click", "#b_change_category", function () {
        var parent_id = $('#padre_category_select_change').val();
        var type_category = $('#type_category_change').val();
        var title = $('#title_category_change').val();
        var ordering = $('#ordering_category_change').val();
        var seo_title = $('#seo_title_change').val();
        var seo_description = $('#summernote_change').val();
        var bottom_view = $('#bottom_view_change').val();

        var is_demand = $('#demand_change').prop("checked") ? 1 : 0;
        var id = $('#category_hidden_id').val();

        $.ajax({
            url: "/admin/categories/change",
            method: 'POST',
            data: {
                "parent_id": parent_id,
                "type_category": type_category,
                "title": title,
                "ordering": ordering,
                "seo_title": seo_title,
                "seo_description": seo_description,
                "is_demand": is_demand,
                "id": id,
                "bottom_view": bottom_view
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
                    $('#change_categ').modal('hide');
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

    // Добавление Категорий
    $(document).on("click", '#add_category', function () {
        $('#padre_category_select').attr('disabled', false);
        $('#add_category').attr("disabled", true);
        var parent_id = $('#padre_category_select').val();
        var type_category = $('#type_category').val();
        var title = $('#category_title').val();
        var ordering = $('#ordering_category').val();
        var seo_title = $('#seo_title').val();
        var seo_description = $('#summernote').val();
        var bottom_view = $('#bottom_view').val();

        // var is_demand = $('#demand').prop("checked") ? 1 : 0;
        $.ajax({
            url: '/admin/categories/add',
            method: 'POST',
            data: {
                "parent_id": parent_id,
                "type_category": type_category,
                "title": title,
                "ordering": ordering,
                "seo_title": seo_title,
                "seo_description": seo_description,
                "bottom_view": bottom_view
                // "is_demand": is_demand, //to do удалить demand из базы, контролерра и вообще кругом
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors, errorMessage = "";
                $.each(errors, function (index, value) {
                    $.each(value, function (key, message) {
                        errorMessage += message + " ";
                    })
                })
                $('#add_category').attr("disabled", false);
                Swal.fire({
                    icon: 'error',
                    title: errorMessage,
                    showConfirmButton: true,
                })
            },
            success: function (resp) {
                if (resp['message']){
                    $('#add_categ').modal('hide');
                    $('.modal-backdrop').remove();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: resp['message'],
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if (resp['value']) {
                        $('#padre_category_select').append('<option value="' + resp['value'] + '">' + resp['parent'] + '</option>');
                        $('#padre_category_select_change').append('<option value="' + resp['value'] + '">' + resp['parent'] + '</option>');
                    }
                    $('#add_category').attr("disabled", false);
                    $("#padre_category_select option[value=NoCategory]").prop('selected', true);
                    $("#type_category option[value=forProduct]").prop('selected', true);
                    $('#category_title').val("");
                    $('#ordering_category').val(null);
                    $('#seo_title').val(null);
                    $('#summernote').val(null);
                    $("#demand").prop("checked", false);
                } else {
                    window.location.replace("/admin/categories");
                    $('#add_category').attr("disabled", false);
                }
            }
        });
    })

    var table = $('#category_table').DataTable({
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
        ajax: "/admin/categories",
        columns: [
            {"data": "number", "name": "id"},
            {"data": "parent_id", "name": "parent_id"},
            {"data": "type_category", "name": "type_category"},
            {"data": "title", "name": "title"},
            {"data": "ordering", "name": "ordering"},
            // {"data": "is_demand", "name": "is_demand"},
            {"data": 'action', "orderable": false, "searchable": false},
        ],
        select: {
            style: 'multi',
            selector: 'td:not(:last-child)'
        },
    });

    document.getElementById('type_category_change').addEventListener('change', function() {
        let type_category = $('#type_category_change').val();

        if(type_category === 'info'){
            $("#padre_category_select_change option[value=NoCategory]").prop('selected', true);
            $("#padre_category_select_change").attr('disabled', true);
            $("#btm_view_change").attr('hidden', false);
        } else {
            $('#padre_category_select_change').attr('disabled', false);
            $("#btm_view_change").attr('hidden', true);
            $("#bottom_view_change option[value=0]").prop('selected', true);
        }
    })
    document.getElementById('type_category').addEventListener('change', function() {
        let type_category = $('#type_category').val();

        if(type_category === 'info'){
            $("#padre_category_select option[value=NoCategory]").prop('selected', true);
            $("#padre_category_select").attr('disabled', true);
            $("#btm_view").attr('hidden', false);

        } else {
            $('#padre_category_select').attr('disabled', false);
            $("#btm_view").attr('hidden', true);
            $("#bottom_view option[value=0]").prop('selected', true);
        }
    })

});

