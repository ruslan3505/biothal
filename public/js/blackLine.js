$(document).on('click','#save_black_header', function () {
    let black_line = $('.black_line').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/admin/send_black_header',
        type: 'POST',
        cache: false,
        data: {'black_line': black_line},
        dataType: 'json',
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Чёрная строка успешно обновлена',
                showConfirmButton: false,
                timer: 1500
            });
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Чёрная строка не заполнена',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
});
