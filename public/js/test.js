$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
    });

    // console.log('auf');
    // $.ajax({
    //     url: 'http://biothal/test',
    //     success: function (resp) {
    //         console.log('here');
    //         console.log(resp);
    //         Swal.fire({
    //             icon: 'success',
    //             title: resp['uuid'],
    //         })
    //         localStorage.setItem('uuid', 'bc737302-b81a-410a-8305-d0872bf28aa4');
    //
    //         // var a = localStorage.getItem('uuid');
    //         // console.log(localStorage);
    //         // console.log("op");
    //         // console.log(a);
    //     }
    // });

    $('#getMailing').on('click', function () {
        $.ajax({
            url: '/test/ip',
            data: {
                'uuid': localStorage.uuid,
                'product_id': 2,
            },
            success: function (resp) {
                console.log('getMailing');
                console.log(resp);
                Swal.fire({
                    icon: 'success',
                    title: resp['message'],
                })
            }
        });
    });

    $('#home').on('click', function () {
        $.ajax({
            url: '/test/pagin',
            data: {
                'uuid': localStorage.uuid,
                'id': 10,
                'count': 2,
            },
            success: function (resp) {
                console.log('home');
                console.log(resp);
                Swal.fire({
                    icon: 'success',
                    title: resp['message'],
                })
            }
        });
    });

    // delete localStorage.uuid;

    if (localStorage.uuid == undefined){
        localStorage.setItem('uuid', 'bc737302-b81a-410a-8305-d0872bf28aa4');
    } else {
        console.log(localStorage.uuid);
    }
    // delete localStorage.uuid;
    // console.log("here");
    // console.log(localStorage.uuid);


    // var a = localStorage.getItem('uuid');
    // $.ajax({
    //     url: 'http://biothal/test/ip',
    //     data: 'uuid=' + a,
    //     success: function (resp) {
    //         if (resp['cart']){
    //             console.log(resp['cart'][0]['name']);
    //         }
    //         if (resp['error']){
    //             console.log('error');
    //         }
    //         // Swal.fire({
    //         //     icon: 'success',
    //         //     title: resp[''],
    //         // })
    //     }
    // })
    // console.log(localStorage);

})
