$(function () {
    // Токен
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
    });

    //#region init

    $('.datepicker').each(function () {
        $(this).datepicker({
            uiLibrary: 'bootstrap4',
        })
    });

    $('#b_change_product').hide();
    $('#b_change_attribute').hide();
    $('#b_change_sale').hide();

    // Создаю таблицу продуктов
    var productTable = $('#products_table').DataTable({
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
        ajax: "/admin/products",
        columns: [
            {"data": "number", "name": "id"},
            {"data": "image_id", "orderable": false, "name": "image"},
            {"data": "name", "name": "name"},
            {"data": "description", "name": "description"},
            {"data": "composition", "name": "composition"},
            {"data": "sale_id", "name": "sale"},
            {"data": "price", "name": "price"},
            {"data": "action", "orderable": false, "name": "action"},
        ],
        select: {
            style: 'multi',
            selector: 'td:not(:nth-child(2), :last-child)'
        },
        // columnDefs: [{
        //     targets: 3,
        //     render: $.fn.dataTable.render.ellipsis(30),
        // }],
        //TODO: Заменить коллбек на делегирование
        "drawCallback": function (settings) {
            $('.thumbnail').viewbox({

                // template
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
        }
    });

    // Создаю таблицу Данных
    var attributes = $('#attributes_table').DataTable({
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
        // ajax: '/admin/products/attributes',
        columns: [
            {"data": "id", "name": "id"},
            {"data": "name", "name": "name"},
            {"data": "value", "name": "value"},
            {"data": "change", "name": "change", "orderable": false},
        ],
        select: {
            style: 'multi',
            selector: 'td:not(:last-child)'
        },
        language: {
            emptyTable: 'no result found.'
        },
        "drawCallback": function (settings) {
            $('[data-help-attribute="tooltip"]').tooltip();
        }
    });

    // Создаю таблицу Данных
    var sales = $('#sales_table').DataTable({
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
        ajax: '/admin/products/sales',
        columns: [
            {"data": "id", "name": "id"},
            {"data": "title", "name": "title"},
            {"data": "first_date", "name": "first_date", "orderable": false},
            {"data": "last_date", "name": "last_date", "orderable": false},
            {"data": "percent", "name": "percent"},
            {"data": "change", "name": "change", "orderable": false},
        ],
        select: {
            style: 'multi',
            selector: 'td:not(:last-child)'
        },
        language: {
            emptyTable: 'Скидок нет'
        },
        "drawCallback": function (settings) {
            $('.datepicker_sale').each(function () {
                $(this).datepicker({
                    uiLibrary: 'bootstrap4',
                })
            });
            $('[data-help-sale="tooltip"]').tooltip();
        }
    });

    // Редактор для описания
    tinymce.init({
        selector: '.description',
        width: 740,
    });

    // Всплывающие подсказки
    $('[data-title="tooltip"]').tooltip();

    // Настройки для отображения модальных окон
    $('#add_attributes_modal').on('shown.bs.modal', function (e) {
        $('#add_product').modal('hide');
    })

    $('#add_attributes_modal').on('hidden.bs.modal', function (e) {
        $('.modal-backdrop').remove();
        $('#add_product').modal('show');
    })

    $('#add_sales_modal').on('shown.bs.modal', function (e) {
        $('#add_product').modal('hide');
    })

    $('#add_sales_modal').on('hidden.bs.modal', function (e) {
        $('.modal-backdrop').remove();
        $('#add_product').modal('show');
    })

    $('#choice_your_sale_modal').on('hidden.bs.modal', function (e) {
        $('.modal-backdrop').remove();
    })

    // Select для выбора скидки
    $('#select_sale_name').change(function () {
        var id = $('#select_sale_name').val();

        if (id != 'NoValue') {
            $.ajax({
                url: '/admin/products/get/sale',
                data: 'id=' + id,
                error: function (xhr, status, error) {
                    fatalError(xhr)
                },
                success: function (resp) {
                    $('#sale_first_date_prev').val(resp['first_date']);
                    $('#sale_last_date_prev').val(resp['last_date']);
                    $('#sale_percent_prev').val(resp['percent']);
                }
            });
        } else {
            $('#sale_first_date_prev').val('');
            $('#sale_last_date_prev').val('');
            $('#sale_percent_prev').val('');
        }
    });

    // Открыть модальное окно для создания нового продукта
    $('#create_product').on('click', function () {
        initModal('add_product');
    });

    // Открыть модальное окно для создания нового атрибута
    $("#create_attribute").on('click', function () {
        $('.text-atri').html('Добавить Атрибут <span class="sale-green fas fa-leaf"></span>');
        initModal('create_attribute');
    })

    // Открыть модальное окно для создания новой скидки
    $("#create_sale").on('click', function () {
        $('.text-sale').html('Добавить скидку');
        initModal('create_sale');
    })

    // Отправка формы через ajax (Добавить \ Изменить продукт)
    $("#form_product").submit(function (event) {
        var id = $("input[type=submit][clicked=true]").prevObject[0].activeElement.id;
        var form = $(this);
        event.preventDefault();
        if (id == 'b_add_product') {
            var url = '/admin/products/add';
        } else {
            var url = '/admin/products/change';
        }
        $.ajax({
            url: url,
            method: 'POST',
            data: form.serialize(),
            error: function (xhr, status, error) {
                fatalError(xhr);
            },
            success: function (resp) {
                productTable.ajax.reload();

                successMessage(resp);

                $('#add_product').modal('hide');
                $('.modal-backdrop').remove();
            }
        });
    });

    // Добавить атрибут
    $('#b_add_attribute').on('click', function () {
        addItem('b_add_attribute');
    });

    // Добавить скидку
    $('#b_add_sale').on('click', function () {
        addItem('b_add_sale');
    });

    // Открыть продукт для редактирования
    $(document).on('click', '[id^=b_product_change_]', function () {
        var id = parseInt($(this).data('id'));
        $.ajax({
            url: '/admin/products/get',
            data: 'id=' + id,
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: '?',
                    showConfirmButton: true,
                })
            },
            success: function (resp) {
                if (resp['message']) {
                    // Обновляю таблицу Данных
                    $.ajax({
                        url: '/admin/products/attributes',
                        data: {'id': id},
                        success: function (data) {
                            attributes.clear();
                            attributes.rows.add(data.data).draw();
                        },
                    });

                    // Заполняем модальное окно, данными о продукте
                    initModal('change_product', resp);
                }

                if (resp['error']) {
                    errorMessage(resp)
                }
            }
        });
    });

    // Добавление глобальной скидки
    $(document).on("click", '#global_sale', function () {
        $('#global_sale').attr("disabled", true);
        let sum_modal = $('#sum_modal').val();
        let procent_modal = $('#procent_modal').val();

        $.ajax({
            url: '/admin/products/add/globalsale',
            method: 'POST',
            data: {
                "sum_modal": sum_modal,
                "procent_modal": procent_modal,
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors, errorMessage = "";
                $.each(errors, function (index, value) {
                    $.each(value, function (key, message) {
                        errorMessage += message + " ";
                    })
                })
                $('#global_sale').attr("disabled", false);
                Swal.fire({
                    icon: 'error',
                    title: errorMessage,
                    showConfirmButton: true,
                })
            },
            success: function (resp) {
                if (resp['message']){
                    $('#global_sale').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: resp['message'],
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
                }
            }
        });
    })


    // Открыть атрибут для редактирования
    $(document).on('click', '[id^=b_attribute_change_]', function () {
        var id = parseInt($(this).data('id'));
        prepChange('b_attribute_change_', id);
    });

    // Открыть скидку для реактирования
    $(document).on('click', '[id^=b_sale_change_]', function () {
        var id = parseInt($(this).data('id'));
        prepChange('b_sale_change_', id);
    });

    $('#b_change_sale').on('click', function () {
        var id = $(this).data('id');
        changeItem('b_change_sale', id);
    });

    $('#b_change_attribute').on('click', function () {
        var id = $(this).data('id');
        changeItem('b_change_attribute', id);
    });

    // Удаление продуктов
    $("#delete_products").on("click", function () {
        // var delProd = $(this).data('delProd');
        deleteItems('delete_products');
    });

    // Удаление Атрибутов
    $("#delete_attributes").on('click', function () {
        deleteItems('delete_attributes');
    });

    // Удаление Скидок
    $("#delete_sales").on("click", function () {
        deleteItems('delete_sales');
    });

    //Удалить скидки у продуктов
    $("#delete_sales_for_products").on("click", function () {
        var count = productTable.rows({selected: true}).count();
        var products = productTable.rows({selected: true}).data();
        var productsId = [];

        for (var i = 0; i < count; i++) {
            productsId[i] = products[i]['id'];
        }

        $.ajax({
            url: '/admin/products/clear/sales',
            method: 'PUT',
            data: {
                'productsId':productsId,
            },
            error: function (xhr, status, error) {
                fatalError(xhr);
            },
            success: function (resp) {
                successMessage(resp);
                productTable.ajax.reload();
            }
        });
    });

    // Сделать скидки продуктам
    $('#b_confirm_your_sales').on("click", function () {

        var saleId = $('#select_sale_name').val();

        var productsId = [];
        $.each($("input[name='selected[]']:checked"), function () {
            productsId.push($(this).val());
        })

        $.ajax({
            url: '/admin/products/set/sale',
            method: 'PUT',
            data: {
                'id': productsId,
                'saleId': saleId,
            },
            error: function (xhr, status, error) {
                fatalError(xhr);
            },
            success: function (resp) {
                successMessage(resp);
                location.reload()
                $('#choice_your_sale_modal').modal('hide');
            }
        })
    });

// Модальное окно, добавить\удалить
    function initModal(buttonId, resp) {
        if (buttonId == 'add_product') {
            $('#dispersia').addClass('dis');        // Делает таб "Данные" не активным

            $('#b_change_product').hide();      // Спрятать кнопку изменить
            $('#b_add_product').show();

            $('[name="name"]').val('');
            $('[name="price"]').val('');
            tinymce.get('product_description').setContent('');
            tinymce.get('product_composition').setContent('');
            $('[name="link"]').val('');
            $('[name="meta_description"]').val('');
            $('#meta_keyword_product').tagsinput('removeAll');
            $('[id^=pictures_]').prop('checked', false);
            $('#hidden_id_product').val('hello');

            document.getElementById("defaultOpen").click();     // Открывает первый таб

            return true;
        }
        if (buttonId == 'change_product') {
            $('#dispersia').removeClass('dis');     // Делает таб "Данные" активным

            $('#b_add_product').hide();     // Спрятать кнопку добавить
            $('#b_change_product').show();

            $('[name="name"]').val(resp['name']);
            $('[name="price"]').val(resp['price']);
            tinymce.get('product_description').setContent(resp['description']);
            tinymce.get('product_composition').setContent(resp['composition']);
            $('[name="link"]').val(resp['link']);
            $('[name="meta_description"]').val(resp['meta_description']);
            $('#meta_keyword_product').tagsinput('add', resp['meta_keywords']);
            $('#pictures_' + resp['image_id']).prop('checked', true);
            $('#hidden_id_product').val(resp['id']);

            return true;
        }
        if (buttonId == 'create_attribute') {
            $('#attribute_title').val('');
            $('#attribute_value').val('');
            $('#b_change_attribute').hide();
            $('#b_add_attribute').show();
        }
        if (buttonId == 'create_sale') {
            $('#sale_title').val('');
            $('#sale_first_date').val('');
            $('#sale_last_date').val('');
            $('#sale_percent').val('');
            $('#b_change_sale').data('id', '');
            $('#b_change_sale').hide();
            $('#b_add_sale').show();
        }
    }

// SweetAlert success
    function successMessage(resp) {
        Swal.fire({
            icon: 'success',
            title: resp['message'],
            timer: 1500,
            showConfirmButton: false,
        });
    }

// SweetAlert error
    function errorMessage(resp) {
        Swal.fire({
            icon: 'error',
            title: resp['error'],
            showConfirmButton: false,
            timer: 1500
        });
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

// Add func
    function addItem(buttonId) {
        var id = $('#hidden_id_product').val();

        if (buttonId == 'b_add_attribute') {
            var title = $('#attribute_title').val();
            var value = $('#attribute_value').val();
            var url = '/admin/products/add/attribute';
            var arr = {
                'title': title,
                'value': value,
                'id': id,
            };
            var modal = '#add_attributes_modal';
        }
        if (buttonId == 'b_add_sale') {
            var title = $('#sale_title').val();
            var first_date = $('#sale_first_date').val();
            var last_date = $('#sale_last_date').val();
            var percent = $('#sale_percent').val();
            var url = '/admin/products/add/sale';
            var arr = {
                'title': title,
                'first_date': first_date,
                'last_date': last_date,
                'percent': parseInt(percent),
            };
            var modal = '#add_sales_modal';
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: arr,
            error: function (xhr, status, error) {
                fatalError(xhr)
            },
            success: function (resp) {
                if (buttonId == 'b_add_attribute') {
                    $.ajax({
                        url: '/admin/products/attributes',
                        data: 'id=' + id,
                        success: function (data) {
                            attributes.clear();
                            attributes.rows.add(data.data).draw();
                        },
                    });
                }
                if (buttonId == 'b_add_sale') {
                    sales.ajax.reload();
                    $('#select_sale_name').append('<option value="' + resp['id'] + '">' + resp['name'] + '</option>');
                }
                successMessage(resp);

                $(modal).modal('hide');
            }
        });

    }

// Prep change
    function prepChange(buttonId, id) {
        if (buttonId == 'b_attribute_change_') {
            $('#b_add_attribute').hide();
            $('#b_change_attribute').show();
            $('.text-atri').html('Изменить Атрибут <span class="sale-green fas fa-leaf"></span>');
            var url = '/admin/products/get/attributes';
        }
        if (buttonId == 'b_sale_change_') {
            $('#b_add_sale').hide();
            $('#b_change_sale').show();
            $('.text-sale').html('Опыты над Скидкой <span class="sale-green">$</span>_<span class="sale-green">$</span>');
            var url = '/admin/products/get/sales';
        }

        $.ajax({
            url: url,
            data: 'id=' + id,
            error: function (xhr, status, error) {
                fatalError(xhr);
                if (buttonId == 'b_attribute_change_'){
                    $('#add_attributes_modal').modal('hide');
                }
                if (buttonId == 'b_sale_change_'){
                    $('#add_sales_modal').modal('hide');
                }
            },
            success: function (resp) {
                if (resp['message']) {
                    if (buttonId == 'b_attribute_change_') {

                        // Обновляю модальное окно
                        $('#attribute_title').val(resp['name']);
                        $('#attribute_value').val(resp['value']);
                        $('#b_change_attribute').data('id', id);
                    }
                    if (buttonId == 'b_sale_change_') {

                        // Обновляю таблицу Данных
                        $('#sale_title').val(resp['title']);
                        $('#sale_first_date').val(resp['first_date']);
                        $('#sale_last_date').val(resp['last_date']);
                        $('#sale_percent').val(resp['percent']);
                        $('#b_change_sale').data('id', resp['id']);
                    }
                }
                if (resp['error']) {
                    errorMessage(resp)
                }
            }
        });
    }

// Change func
    function changeItem(buttonId, itemId) {
        if (buttonId == 'b_change_sale') {
            var title = $('#sale_title').val();
            var first_date = $('#sale_first_date').val();
            var last_date = $('#sale_last_date').val();
            var percent = $('#sale_percent').val();
            var data = {
                'title': title,
                'first_date': first_date,
                'last_date': last_date,
                'percent': parseInt(percent),
                'id': itemId,
            }
            var url = '/admin/products/change/sale';
            var modal = '#add_sales_modal';
        }
        if (buttonId == 'b_change_attribute') {
            var title = $('#attribute_title').val();
            var value = $('#attribute_value').val();
            var data = {
                'id': itemId,
                'title': title,
                'value': value,
            };
            var url = '/admin/products/change/attribute';
            var modal = '#add_attributes_modal';
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            error: function (xhr, status, error) {
                fatalError(xhr)
            },
            success: function (resp) {
                if (buttonId == 'b_change_attribute') {
                    var id = $('#hidden_id_product').val();
                    $.ajax({
                        url: '/admin/products/attributes',
                        data: 'id=' + id,
                        success: function (data) {
                            attributes.clear();
                            attributes.rows.add(data.data).draw();
                        },
                    });
                }
                if (buttonId == 'b_change_sale') {
                    sales.ajax.reload();
                    $(`#select_sale_name option[value=${itemId}]`).text(title);
                    if (resp['reload']) {
                        console.log('reload');
                        productTable.ajax.reload();
                    }
                }

                successMessage(resp);

                $(modal).modal('hide');
            }
        });
    }

// Delete func
    function deleteItems(buttonId) {
        if (buttonId == 'delete_products') {
            var url = '/admin/products/delete';
            var chosenTable = productTable;
        }
        if (buttonId == 'delete_attributes') {
            var url = '/admin/products/delete/attributes'
            var product_id = $('#hidden_id_product').val();
            var chosenTable = attributes;
        }
        if (buttonId == 'delete_sales') {
            var url = '/admin/products/delete/sales'
            var chosenTable = sales;
        }

        var data = chosenTable.rows({selected: true}).data();
        var count = chosenTable.rows({selected: true}).count();

        var datasId = [];
        for (var i = 0; i < count; i++) {
            datasId[i] = data[i]['id'];
        }

        $.ajax({
            url: url,
            method: 'DELETE',
            data: {'id': datasId},
            error: function (xhr, status, error) {
                fatalError(xhr)
            },
            success: function (resp) {
                if (buttonId == 'delete_attributes') {
                    $.ajax({
                        url: '/admin/products/attributes',
                        data: {'id': product_id},
                        success: function (data) {
                            chosenTable.clear();
                            chosenTable.rows.add(data.data).draw();
                        },
                    });
                }

                if (buttonId == 'delete_products') {
                    chosenTable.ajax.reload();
                }

                if (buttonId == 'delete_sales') {
                    chosenTable.ajax.reload();

                    $.each(resp['id'], function (index, value) {
                        $(`#select_sale_name option[value=${index}]`).remove();
                    });

                    if (resp['reload']) {
                        productTable.ajax.reload();
                    }
                }

                if (resp['message']) {
                    successMessage(resp);
                }
            }
        });
    }
});

