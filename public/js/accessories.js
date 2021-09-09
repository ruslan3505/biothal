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

// Удаление потребностей
    $(document).on("click", "#delete_acc", function () {
        var data = table.rows({selected: true}).data(), arr = [], count = table.rows({selected: true}).count();
        if (count !== 0) {
            for (var i = 0; i < count; i++) {
                arr[i] = data[i]['id'];
            }
        }
        $.ajax({
            url: '/admin/accessories/delete',
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
                // Если родительская категория не выбрана, удаляем выбранные потребности
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
                    // Если потребность выбрана, уточняем у пользователя уверен ли он в своем выборе
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
                                url: '/admin/accessories/delete',
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
                                                $('#padre_accessory_select option[value=\"' + value + '\"]').remove();
                                                $('#padre_accessory_select_change option[value=\"' + value + '\"]').remove();
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
                                        window.location.replace("/admin/accessories");
                                    }
                                }
                            })
                            // Если нет, отменяем
                        } else if (result.isDenied) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Потребности не удалены',
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

// Изменение Потребностей
    $(document).on("click", "[id^=accessory_change]", function () {
        let parentId = $(this).data('parentId');
        let id = $(this).data('id');
        let title = $(this).data('title');
        let order = $(this).data('order');

        $("#padre_accessory_select_change").val(parentId); // Parent id
        $("#title_accessory_change").val(title); // Title
        $("#accessory_hidden_id").val(id);     // Id
        $("#ordering_accessory_change").val(order);  // Ordering

        // if (parentId != null) { // Материнская категория
        //     $("#padre_accessory_select_change option[value=" + "\"" + parentId + "\"" + "]").prop('selected', true);
        // } else {
        //     $("#padre_accessory_select_change option[value=" + null + "]").prop('selected', true);
        // }

        // if (demand == 1) { // Категория\Потребность
        //     $("#demand_change").prop("checked", true);
        // } else {
        //     $("#demand_change").prop("checked", false);
        // }
    })

    $(document).on("click", "#b_change_accessory", function () {
        let parent_id = $('#padre_accessory_select_change').val();
        let title = $('#title_accessory_change').val();
        let ordering = $('#ordering_accessory_change').val();
        let id = $('#accessory_hidden_id').val();

        $.ajax({
            url: "/admin/accessories/change",
            method: 'POST',
            data: {
                "title": title,
                "ordering": ordering,
                "id": id,
                "parent_id": parent_id,
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
                    $('#change_access').modal('hide');
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

    // Добавление потребностей
    $(document).on("click", '#add_accessory', function () {
        $('#add_accessory').attr("disabled", true);
        var parent_id = $('#padre_accessory_select').val();
        var title = $('#accessory_title').val();
        var ordering = $('#ordering_accessory').val();
        parent_id = parent_id === "NoAccessory" ? null : parent_id
        $.ajax({
            url: '/admin/accessories/add',
            method: 'POST',
            data: {
                "parent_id": parent_id,
                "title": title,
                "ordering": ordering,
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors, errorMessage = "";
                $.each(errors, function (index, value) {
                    $.each(value, function (key, message) {
                        errorMessage += message + " ";
                    })
                })
                $('#add_accessory').attr("disabled", false);
                Swal.fire({
                    icon: 'error',
                    title: errorMessage,
                    showConfirmButton: true,
                })
            },
            success: function (resp) {
                if (resp['message']){
                    $('#add_access').modal('hide');
                    $('.modal-backdrop').remove();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: resp['message'],
                        showConfirmButton: false,
                        timer: 1500
                    });

                    if (resp['value']) {
                        $('#padre_accessory_select').append('<option value="' + resp['value'] + '">' + resp['parent'] + '</option>');
                        $('#padre_accessory_select_change').append('<option value="' + resp['value'] + '">' + resp['parent'] + '</option>');
                    }
                    $('#add_accessory').attr("disabled", false);
                    $("#padre_accessory_select option[value=NoAccessory]").prop('selected', true);
                    $('#accessory_title').val("");
                    $('#ordering_accessory').val(null);
                } else {
                    window.location.replace("/admin/accessories");
                    $('#add_accessory').attr("disabled", false);
                }
            }
        });
    })

    var table = $('#accessory_table').DataTable({
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
        ajax: "/admin/accessories",
        columns: [
            {"data": "number", "name": "id"},
            {"data": "parent_id", "name": "parent_id"},
            {"data": "title", "name": "title"},
            {"data": "ordering", "name": "ordering"},
            {"data": 'action', "orderable": false, "searchable": false},
        ],
        select: {
            style: 'multi',
            selector: 'td:not(:last-child)'
        },
    });
});
