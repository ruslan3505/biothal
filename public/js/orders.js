$(document).on('click', '.button_href_to_details', function (){
    let order_id = $(this).attr('id').slice(15);
    location.href = '/admin/orders/viewOrders/' + order_id;
});
$(document).on('click', '#button-filter', function () {
    getOrders()
});

$(document).ready(function () {
    getOrders()
});

function getOrders() {
    let filters = {
        filter_order_id: '',
        filter_client: '',
        filter_order_status: '',
        filter_total: '',
        filter_date_added: '',
        filter_date_modified: ''
    };
    let filter_order_id = $('input[name=\'filter_order_id\']').val();
    if (filter_order_id) {
        filters.filter_order_id = filter_order_id;
    }
    let filter_client = $('input[name=\'filter_client\']').val();
    if (filter_client) {
        filters.filter_client = filter_client;
    }
    let filter_order_status = $('select[name=\'filter_order_status\']').val();
    if (filter_order_status != '*') {
        filters.filter_order_status = filter_order_status;
    }
    let filter_total = $('input[name=\'filter_total\']').val();
    if (filter_total) {
        filters.filter_total = filter_total;
    }
    let filter_date_added = $('input[name=\'filter_date_added\']').val();
    if (filter_date_added) {
        filters.filter_date_added = filter_date_added;
    }
    let filter_date_modified = $('input[name=\'filter_date_modified\']').val();
    if (filter_date_modified) {
        filters.filter_date_modified = filter_date_modified;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
    });

    var table = $('#orders-table').DataTable({
        "order": [[ 0, "desc" ]],
        "language": {
            "processing": 'Загрузка......',
            "sInfo": 'Показано _START_ по _END_ с _TOTAL_ записей',
            "infoEmpty": 'Показано с 0 по 0 из 0 записей',
            "lengthMenu": 'Показать _MENU_ Записей',
            "paginate": {
                "first": "Первая",
                "last": "Последняя",
                "next": "Следующая",
                "previous": "Предыдущая"
            },
            "zeroRecords": 'Пусто'
        },
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/admin/orders/sort_orders_table",
            data: {
                "filters": filters
            },
            method: 'POST',
        },
        columns: [
            {"data": "id", "name": "id"},
            {"data": "name", "name": "name"},
            {"data": "order_status", "name": "order_status"},
            {"data": "total_sum", "name": "total_sum"},
            {"data": "created_at", "name": "created_at"},
            {"data": "updated_at", "name": "updated_at"},
            {"data": "change", "name": "change", "orderable": false},
        ],
        select: false,
        searching: false,


    });
}

