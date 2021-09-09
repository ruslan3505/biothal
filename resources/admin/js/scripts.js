$(function ($) {
    $(".sidebar-dropdown > a").click(function () {
        $(".sidebar-submenu").slideUp(200);
        if (
            $(this)
                .parent()
                .hasClass("active")
        ) {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .parent()
                .removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .next(".sidebar-submenu")
                .slideDown(200);
            $(this)
                .parent()
                .addClass("active");
        }
    });

    $("#close-sidebar").click(function () {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function () {
        $(".page-wrapper").addClass("toggled");
    });
});

function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

$("#imgInp").change(function () {
    readURL(this);
});

$(document).ready(function () {
    $('[id^=topright]').hover(
        function () {
            $(this).removeClass('fa fa-chain');
            $(this).addClass('fa fa-chain-broken');
        },
        function () {
            $(this).removeClass('fa fa-chain-broken');
            $(this).addClass('fa fa-chain');
        });
});

$(document).ready(function () {
    // Токен
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Удаление Изображений
    $("#deletePic").on("click", function () {
        var checked = document.querySelectorAll('input[id^=pictures]:checked'), i, arr = [];
        if (checked.length !== 0) {
            for (i = 0; i < checked.length; i++) {
                arr[i] = parseInt(checked[i]['value']);
            }
        }
        $.ajax({
            url: '/admin/Images/delete',
            method: 'POST',
            data: {"checked": arr},
            error: function (data) {
                if (data.status === 422) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ой...',
                        text: 'Выберите хотя бы 1 изображение!'
                    });
                }
            },
            success: function (resp) {
                if (resp) return "ok",
                    Swal.fire({
                        icon: 'success',
                        title: 'Изображения успешно удалены',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        window.location.replace('/admin/Images');
                    });

            }
        })
    })

    // Удаление Категорий
    $("#delete_cat").on("click", function () {
        var checked = document.querySelectorAll('input[name^=selected]:checked'), i, arr = [];
        if (checked.length !== 0) {
            for (i = 0; i < checked.length; i++) {
                arr[i] = parseInt(checked[i]['id']);
            }
        } else {
            arr = 0;
        }
        $.ajax({
            url: '/admin/categories/delete',
            method: 'POST',
            data: {"checked": arr, "status": 0},
            success: function (resp) {
                // Если родительская категория не выбрана, удаляем выбранные категории
                // if (resp) return "ok",
                if (resp == 14) {
                    console.log(14);
                    Swal.fire({
                        icon: 'success',
                        title: 'Категории успешно удалены',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        window.location.replace('/admin/categories');
                    });
                }
                // if (resp) return "false",
                if (resp == 15) {
                    // Если роительская категория выбрана, уточняем у пользователя уверен ли он в своем выборе
                    Swal.fire({
                        title: 'Вы уверены что хотите удалить эту категорию?',
                        text: 'Это приведет к удалению дочерних категорий!',
                        showDenyButton: true,
                        showCancelButton: true,
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
                                    // if (ret) return "ok",
                                    if (ret == 14) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Категории успешно удалены',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(function () {
                                            window.location.replace("/admin/categories");
                                        });
                                    }
                                }
                            })
                            // Если нет, отменяем
                        } else if (result.isDenied) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Категории не удалены',
                                timer: 1500
                            }).then(function () {
                                window.location.replace("/admin/categories");
                            });
                        }
                    })
                }
            }
        })
    })
});
