$(function () {
    // Токен
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
    });

    var shoppingCartId = 0;
    var productTable = productTable = $('#products_state_panel_table').DataTable({
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
        ajax: {
            "url": '/admin/panel/get/products',
            "data": {
                "cartId": shoppingCartId,
            }
        },
        columns: [
            {"data": "id", "name": "id"},
            {"data": "image", "orderable": false, "name": "image"},
            {"data": "name", "name": "name"},
            {"data": "count", "name": "count"},
            {"data": "sale", "name": "sale"},
            {"data": "price", "name": "price"},
            {"data": "p*c", "name": "p*c"},
        ],
        select: {
            style: 'multi',
            selector: 'td:not(:last-child)'
        },
        language: {
            emptyTable: 'Нет товаров.'
        }
    });

    var statePanelTable = $('#state_panel_table').DataTable({
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
        ajax: '/admin/panel',
        columns: [
            {"data": "id", "name": "id"},
            {"data": "status", "name": "status"},
            {"data": "created_at", "name": "created_at"},
            {"data": "fullPrice", "name": "fullPrice"},
            {"data": "action", "orderable": false, "name": "action"},
            {"data": "products", "orderable": false, "name": "products"},
        ],
        select: {
            style: 'multi',
            selector: 'td:not(:nth-child(5),:last-child)'
        },
        language: {
            emptyTable: 'Нет заказов.'
        }
    });

    $(document).on('click', '[id^=b_shopping_products_]', openModal);

    $(document).on('click', '[id^=b_shopping_cart_]', openModal);

    //#region functions
    function openModal() {
        console.log(this);
        shoppingCartId = parseInt($(this).data('id'));
        $('#state_panel_modal').modal('show');

        if (this.id == 'b_shopping_cart_' + $(this).data('id')) {
            $('#b_change_shopping_cart').show();
            $('#change_shop_cart').show();

            ajax('/admin/panel/get/cart');

            var title = 'Редакция заказа';
        } else if (this.id == 'b_shopping_products_' + $(this).data('id')) {
            $('#b_change_shopping_cart').hide();
            $('#change_shop_cart').hide();

            var title = 'Продукты';
        }

        $('#modal_title').text(title);

        ajax('/admin/panel/get/products');
    }

    // SweetAlert FatalError
    function fatalError(xhr) {
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
    }

    function ajax(url) {
        $.ajax({
            url: url,
            data: 'cartId=' + shoppingCartId,
            error: function (xhr, status, error) {
                fatalError(xhr);
            },
            success: function (resp) {
                if (url == '/admin/panel/get/cart') {
                    $('#full_price').val(resp['fullPrice']);
                    $('#date').val(resp['date']);
                    $('#status option[value=' + resp['status'] + ']').prop('selected', true);
                }
                if (url == '/admin/panel/get/products') {
                    productTable.clear();
                    productTable.rows.add(resp.data).draw();

                }
            }
        })
    }
});
