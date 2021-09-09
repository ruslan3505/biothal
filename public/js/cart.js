$(function () {
    // Токен
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
    });
console.log('qwerty');
// Добавление товаров в корзину со страницы продуктов
    $(document).on("click", '#btn-buy', function () {
        let product_id = $('#product_id').val();
        let count = $('#count_products').val();
        $.ajax({
            url: '/buyCart',
            method: 'POST',
            data: {
                "product_id": product_id,
                "count": count,
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors, errorMessage = "";
                $.each(errors, function (index, value) {
                    $.each(value, function (key, message) {
                        errorMessage += message + " ";
                    })
                })
                $('#btn-buy').attr("disabled", false);
                Swal.fire({
                    icon: 'error',
                    title: errorMessage,
                    showConfirmButton: true,
                })
            },
            success: function () {
                location.reload();//todo disabled
                Swal.fire({
                    icon: 'success',
                    title: 'Товар добавлен в корзину',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    })
});

// Добавление товаров в корзину с главной страницы
$(document).on("click", '#btn-buyHome', function () {
    var product_id = $(this).val();
    var count = 1;
    $.ajax({
        url: '/buyCartHome',
        method: 'POST',
        data: {
             "product_id": product_id,
             "count": count,
        },
        error: function (xhr, status, error) {
            let errors = xhr.responseJSON.errors, errorMessage = "";
            $.each(errors, function (index, value) {
                $.each(value, function (key, message) {
                    errorMessage += message + " ";
                })
            })
            $('#btn-buyHome').attr("disabled", false);
            Swal.fire({
                icon: 'error',
                title: errorMessage,
                showConfirmButton: true,
            })
        },
        success: function (data) {
            $('.table-container').html(data.html)
            $('.checkout-container').html(data.html_for_checkout)
            $('.countAll-container').html(data.countAll)
            // location.reload();//todo disabled
            Swal.fire({
                icon: 'success',
                title: 'Товар добавлен в корзину',
                showConfirmButton: false,
                timer: 1500
            });
        }
    })
});

// Удаление товара из корзины
$(document).on("click", '.btn-del', function () {
    var product_id = $(this).val();
    $.ajax({
        url: '/delCart', method: 'POST',
        data: {
            "product_id": product_id,
         },
        error: function (xhr, status, error) {
            var errors = xhr.responseJSON.errors, errorMessage = "";
            $.each(errors, function (index, value) {
                $.each(value, function (key, message) {
                    errorMessage += message + " ";
                })
            })
            $('#btn-del').attr("disabled", false);
            Swal.fire({
                icon: 'error',
                title: errorMessage,
                showConfirmButton: true,
            })
        },
        success: function (data) {
            $('.table-container').html(data.html)
            $('.checkout-container').html(data.html_for_checkout)
            $('.countAll-container').html(data.countAll)
            Swal.fire({
                icon: 'success',
                title: 'Товар удален из корзины',
                showConfirmButton: false,
                timer: 1500
            });
        }
    })
});

// Изменение количества товаров "Плюс" и "Минус"
$(document).ready(function() {
    $('body').on('click', '.plusik', function() {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount_" + cart_id).val()) + 1;

        $.ajax({
            url: '/plus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                $("#valCount_" + cart_id).val(count);
                const old_value_sumAll = $('.sumAll').html();
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2));
                if((data.sumAll_not_sale > data.sumAll)){
                    if(old_value_sumAll){
                        $('.sumAll_sale2').html('Ваша скидка ' + data.procent_modal + '%')
                    }
                    $('.progress-bar').hide()
                    $('.sumAll_sale').hide()
                    $('.sumAll_sale_first').hide()
                }else {
                    $('.sumAll_sale2').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar').show()
                    $('.sumAll_sale').hide()
                    $('.sumAll_sale_first').hide()
                }
                let percent = parseInt(data.sumAll) * 100 /sum_modal;
                console.log(percent)
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar(Math.ceil(percent))
            }
        })
    });

    $(".plusik").click(function() {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount_" + cart_id).val()) + 1;
        $.ajax({
            url: '/plus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                const old_value_sumAll = $('.sumAll').html();
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2));
                if((data.sumAll_not_sale > data.sumAll)){
                    if(old_value_sumAll){
                        $('.sumAll_sale2').html('Ваша скидка ' + data.procent_modal + '%')
                    }
                    $('.progress-bar').hide()
                    $('.sumAll_sale').hide()
                    $('.sumAll_sale_first').hide()
                }else {
                    $('.sumAll_sale2').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar').show()
                    $('.sumAll_sale').hide()
                    $('.sumAll_sale_first').hide()
                }
                let percent = parseInt(data.sumAll) * 100 /sum_modal;
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar(Math.ceil(percent))
            }
        })
    });

    $(".minusik").click(function() {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount_" + cart_id).val());
        if (count >= 2) {
            count = count - 1;
        }

        $.ajax({
            url: '/minus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                const old_value_sumAll = $('.sumAll').html();
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                // const old_value_percent = parseInt(old_value_sumAll) * 100 / (sum_modal);
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2) );


                if((data.sumAll_not_sale > data.sumAll)){
                    $('.sumAll_sale2').html('Ваша скидка ' + data.procent_modal + '%')
                    $('.progress-bar').hide()
                    $('.sumAll_sale').hide()
                    $('.sumAll_sale_first').hide()
                }else {
                    $('.sumAll_sale2').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar').show()
                    $('.sumAll_sale').hide()
                    $('.sumAll_sale_first').hide()
                }

                // let sumAll = $('.sumAll').html();
                let percent = parseInt(data.sumAll) * 100 /sum_modal;
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar(Math.ceil(percent))
            }
        })
    });

    $("body").on('click', '.minusik', function() {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount_" + cart_id).val());
        if (count >= 2) {
            count = count - 1;
        }

        $.ajax({
            url: '/minus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                $("#valCount_" + cart_id).val(count);
                const old_value_sumAll = $('.sumAll').html();
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                const old_value_percent = parseInt(old_value_sumAll) * 100 / (sum_modal);
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2) );
                if((data.sumAll_not_sale - data.sumAll) == 0){
                    $('.sumAll_sale').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar').show()
                }else {
                    $('.progress-bar').hide()
                    $('.sumAll_sale').html('Ваша скидка ' + data.procent_modal + '%')

                }

                // let sumAll = $('.sumAll').html();
                let percent = parseInt(data.sumAll) * 100 /sum_modal;
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar(Math.ceil(percent))
            }
        })
    });
});

function progressBar (percent) {
    function incrementProgress(barSelector, countSelector, incrementor) {
        var bar = document.querySelectorAll(barSelector)[0].firstElementChild,
            // curWidth = parseFloat(bar.style.width),
            newWidth = incrementor;
        if (newWidth > 100) {
            newWidth = 100;
        } else if (newWidth < 0) {
            newWidth = 0;
        }
        bar.style.width = newWidth + '%';
        document.querySelectorAll(countSelector)[0].innerHTML = newWidth.toFixed(1) + '%';
    }

    function incrementProgressLoop() {
        incrementProgress('.progress-bar', '.progress-count', percent);
    }

    incrementProgressLoop();
}

// Изменение количества товаров "Плюс" и "Минус" со страницы SetCheck
$(document).ready(function() {
    $(".plus_prod").click(function() {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount" + cart_id).val()) + 1;

        $.ajax({
            url: '/plus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                $("#valCount" + cart_id).val(count);
                const old_value_sumAll = $('.sumAll').html();
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2));
                if((data.sumAll_not_sale > data.sumAll)){
                    if(old_value_sumAll){
                        $('.sumAll_sale2').html('Ваша скидка ' + data.procent_modal + '%')
                    }
                    $('.progress-bar2').hide()
                    $('.sumAll_sale').hide()
                }else {
                    $('.sumAll_sale2').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar2').show()
                    $('.sumAll_sale').hide()
                }
                let percent = Math.ceil(parseInt(data.sumAll) * 100 /sum_modal);
                // console.log(Math.ceil(percent));
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar2(Math.ceil(percent))
            }
        })
    });

    $("body").on('click', '.plus_prod', function() {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount" + cart_id).val()) + 1;

        $.ajax({
            url: '/plus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                $("#valCount" + cart_id).val(count);
                const old_value_sumAll = $('.sumAll').html();
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2));
                if((data.sumAll_not_sale > data.sumAll)){
                    if(old_value_sumAll){
                        $('.sumAll_sale2').html('Ваша скидка ' + data.procent_modal + '%')
                    }
                    $('.progress-bar2').hide()
                    $('.sumAll_sale').hide()
                }else {
                    $('.sumAll_sale2').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar2').show()
                    $('.sumAll_sale').hide()
                }
                let percent = parseInt(data.sumAll) * 100 /sum_modal;
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar2(Math.ceil(percent))
            }
        })
    });

    $('.minus_prod').click(function () {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount" + cart_id).val());
        if (count >= 2) {
            count = count - 1;
        }

        $.ajax({
            url: '/minus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                $("#valCount" + cart_id).val(count);
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2) );
                if((data.sumAll_not_sale - data.sumAll) == 0){
                    $('.sumAll_sale2').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar2').show()
                    $('.sumAll_sale').hide()
                }else {
                    $('.progress-bar2').hide()
                    $('.sumAll_sale2').html('Ваша скидка ' + data.procent_modal + '%')
                    $('.sumAll_sale').hide()

                }

                let percent = parseInt(data.sumAll) * 100 /sum_modal;
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar2(Math.ceil(percent))
            }
        })
    });

    $("body").on('click', '.minus_prod', function() {
        let cart_id = $(this).attr('id');
        let count = parseInt ($("#valCount" + cart_id).val());
        if (count >= 2) {
            count = count - 1;
        }

        $.ajax({
            url: '/minus_count',
            method: 'POST',
            data: {
                "product_id": cart_id,
                "count": count,
            },
            error: function () {

            },
            success: function (data) {
                $("#valCount_" + cart_id).val(count);
                let sum_modal = $('#sum_modal').val();
                let nova_poshta_price_delivery = $('#nova_poshta_price_delivery').val();
                $('.countAll-container').html(data.countAll)
                $('.sumAll-container').html(data.sumAll.toFixed(2))
                $('.sumAll-delivery-container').html((data.sumAll + Number(nova_poshta_price_delivery)).toFixed(2) );
                if((data.sumAll_not_sale - data.sumAll) == 0){
                    $('.sumAll_sale2').html('Еще ' + (sum_modal - data.sumAll).toFixed(2) + ' и сработает скидка '+ data.procent_modal + '%')
                    $('.progress-bar2').show()
                    $('.sumAll_sale').hide()
                }else {
                    $('.progress-bar2').hide()
                    $('.sumAll_sale2').html('Ваша скидка ' + data.procent_modal + '%')
                    $('.sumAll_sale').hide()

                }

                let percent = parseInt(data.sumAll) * 100 /sum_modal;
                let price = ($(".price_" + cart_id).val());
                let new_price = ($(".new_price_" + cart_id).val());
                if (new_price) {
                    $('.old_cost_with_sale_' + cart_id ).html((count * price).toFixed(2));
                    $('.price_' + cart_id ).html((count * new_price).toFixed(2));
                } else {
                    $('.price_' + cart_id ).html((count * price).toFixed(2));
                }
                progressBar2(Math.ceil(percent))
            }
        })
    });
});

function progressBar2 (percent) {
    function incrementProgress2(barSelector, countSelector, incrementor) {
        var bar = document.querySelectorAll(barSelector)[0].firstElementChild,
            newWidth = incrementor;
        if (newWidth > 100) {
            newWidth = 100;
        } else if (newWidth < 0) {
            newWidth = 0;
        }
        bar.style.width = newWidth + '%';
        document.querySelectorAll(countSelector)[0].innerHTML = newWidth.toFixed(1) + '%';
    }

    function incrementProgressLoop2() {
        incrementProgress2('.progress-bar2', '.progress-count2', Math.ceil(percent));
    }

    incrementProgressLoop2();
}


// Оформление товаров со страницы setCheck
$(document).on("click", '#check', function () {
    let phone = $('#phone').val();
    let name = $('#name').val();
    let LastName = $('#LastName').val();
    let region = $('#region').val();
    let cities = $('#cities').val();
    let department = $('#department').val();
    let not_call = $('#not_call_0').is(':checked');
    $.ajax({
        url: '/check',
        method: 'POST',
        data: {
            "phone": phone,
            "name": name,
            "LastName": LastName,
            "region": region,
            "cities": cities,
            "department": department,
            "order_type": 2,
            "not_call": not_call,
        },
        error: function (xhr, status, error) {
            var errors = xhr.responseJSON.errors, errorMessage = "";
            $.each(errors, function (index, value) {
                $.each(value, function (key, message) {
                    errorMessage += message + " ";
                })
            })
            $('#check').attr("disabled", false);
            Swal.fire({
                icon: 'error',
                title: errorMessage,
                showConfirmButton: true,
            })
        },
        success: function () {
            Swal.fire({
                icon: 'success',
                title: 'Товар оформлен, спасибо за покупку!',
                showConfirmButton: false,
                timer: 1500
            }).then(function () {
                window.location.replace("/");

            });
        }
    })
});

// Оформление товаров со страницы setCheck с модалки "Оформить в 1 клик"
$(document).on("click", '#checkModalOneClick', function () {
    let phone = $('#phoneModal').val();
    let name = $('#nameModal').val();

    $.ajax({
        url: '/checkModalOneClick',
        method: 'POST',
        data: {
            "phone": phone,
            "name": name,
            "order_type": 1,
        },
        error: function (xhr, status, error) {
            var errors = xhr.responseJSON.errors, errorMessage = "";
            $.each(errors, function (index, value) {
                $.each(value, function (key, message) {
                    errorMessage += message + " ";
                })
            })
            $('#checkModalOneClick').attr("disabled", false);
            Swal.fire({
                icon: 'error',
                title: errorMessage,
                showConfirmButton: true,
            })
        },
        success: function () {
            Swal.fire({
                icon: 'success',
                title: 'Спасибо за покупку, с Вами свяжется наш оператор!',
                showConfirmButton: true,
                timer: 1500
            }).then(function () {
                window.location.replace("/");
            });
        }
    })
});

//counter
$(document).ready(function () {
    $('.down').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.up').click(function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });
});

//табы
function openTabs(evt, tabsName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabsName).style.display = "block";
    evt.currentTarget.className += " active";
}

$(document).ready(function () {
    $('#cities').change(function () {
        let cities = $('#cities').val();
        let region = $('#region').val();
        $.ajax({
            url: '/checkout', method: 'POST',
            data: {
                "cities": cities,
                "region": region,
            },
            error: function (xhr, status, error) {

            },
            success: function (response) {
                if( response.data.data.length != null) {
                    $("#department").empty();
                    response.data.data.forEach((item) => {
                        $("#department").prepend('<option>'  + item.DescriptionRu + ' '  + '</option>');
                    })
                }
            }
        })
    })
});


//Маска для телефона для оформить в 1 клик со страницы setCheck и со страницы с продуктом
window.addEventListener('DOMContentLoaded', function() {
    var inputs = document.querySelectorAll('input[type="tel"]');

    Array.prototype.forEach.call(inputs, function(input) {
        new InputMask({
            selector: input, // в качестве селектора может быть элемент, или, собственно css селектор('#input', '.input', 'input'). Если селектор - тег или класс - будет получен только первый элемент
            layout: input.dataset.mask
        })
    })

})

function InputMask(options) {
    this.el = this.getElement(options.selector);
    if (!this.el) return console.log('Что-то не так с селектором');
    this.layout = options.layout || '+_ (___) ___-__-__';
    this.maskreg = this.getRegexp();

    this.setListeners();
}

InputMask.prototype.getRegexp = function() {
    var str = this.layout.replace(/_/g, '\\d')
    str = str.replace(/\(/g, '\\(')
    str = str.replace(/\)/g, '\\)')
    str = str.replace(/\+/g, '\\+')
    str = str.replace(/\s/g, '\\s')

    return str;
}

InputMask.prototype.mask = function(e) {
    var _this = e.target,
        matrix = this.layout,
        i = 0,
        def = matrix.replace(/\D/g, ""),
        val = _this.value.replace(/\D/g, "");

    if (def.length >= val.length) val = def;

    _this.value = matrix.replace(/./g, function(a) {
        return /[_\d]/.test(a) && i < val.length ? val.charAt(i++) : i >= val.length ? "" : a
    });

    if (e.type == "blur") {
        var regexp = new RegExp(this.maskreg);
        if (!regexp.test(_this.value)) _this.value = "";
    } else {
        this.setCursorPosition(_this.value.length, _this);
    }
}

InputMask.prototype.setCursorPosition = function(pos, elem) {
    elem.focus();
    if (elem.setSelectionRange) elem.setSelectionRange(pos, pos);
    else if (elem.createTextRange) {
        var range = elem.createTextRange();
        range.collapse(true);
        range.moveEnd("character", pos);
        range.moveStart("character", pos);
        range.select()
    }
}

InputMask.prototype.setListeners = function() {
    this.el.addEventListener("input", this.mask.bind(this), false);
    this.el.addEventListener("focus", this.mask.bind(this), false);
    this.el.addEventListener("blur", this.mask.bind(this), false);
}

InputMask.prototype.getElement = function(selector) {
    if (selector === undefined) return false;
    if (this.isElement(selector)) return selector;
    if (typeof selector == 'string') {
        var el = document.querySelector(selector);
        if (this.isElement(el)) return el;
    }
    return false
}

InputMask.prototype.isElement = function(element) {
    return element instanceof Element || element instanceof HTMLDocument;
}
