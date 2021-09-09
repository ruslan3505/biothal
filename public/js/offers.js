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

// Удаление Дистрибьютора
    $(document).on("click", "#delete_offer", function () {
        var data = table.rows({selected: true}).data(), arr = [], count = table.rows({selected: true}).count();
        if (count !== 0) {
            for (var i = 0; i < count; i++) {
                arr[i] = data[i]['id'];
            }
        }
        $.ajax({
            url: '/admin/deleteOffer',
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
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: resp['accepted'],
                    showConfirmButton: false,
                    timer: 1500
                })

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

    var table = $('#offer_table').DataTable({
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
        ajax: "/admin/offer",
        columns: [
            {"data": "number", "name": "id"},
            {"data": "name", "name": "name"},
            {"data": "email", "name": "email"},
            {"data": "phone", "name": "phone"},
            {"data": "message", "name": "message"},
        ],
        select: {
            style: 'multi',
        },
    });
});
