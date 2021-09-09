<!-- Footer -->
<style>
    a {
        color: #000000;
    }
    .footerMobile {
        background-color: #000000;
        color: white;
    }
    @media (min-width:1001px) {
        .footerMobile {
            display: none!important;
        }
        #logo{
            width: 6em!important;
            height: 4em!important;
        }

    }
    @media (min-width:0px) and (max-width:1000px) {
        footer {
            display: none!important;
        }
        #logo{
            width: 6em!important;
            height: 4em!important;
        }
        a {
            color: #ffffff;
        }

    }
</style>
<footer class="page-footer font-small mdb-color pt-4 col-sm-12" style="z-index: 15!important;">
    <div class="container-fluid" style="background-color: #2f7484; height: content-box; padding: 10px">
        <div class="present" style="color: white">
            <div>Узнавайте первыми о распродажах и новинках!</div>
            <div>Электронный адрес</div>
        </div>
    </div>
    <div class="container text-center text-md-left">
        <div class="row text-center text-md-left mt-3 pb-3">
            <div style="text-align: justify;" class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-4">
                <h6 class="text-uppercase mb-4 font-weight-bold img-fluid"><a href="/">
                        <img id="logo" style="height: 3em!important; width: 6em!important;" class="img-fluid"
                        src="{{ Storage::disk('public')->url('image/new-logo.png')}}"></a>
                </h6>
                <p>Каждый продукт Biothal представляет собой настоящий эликсир красоты и молодости, концентрат морской
                    силы, который работает в абсолютной синергии с кожей и соответствует самым высоким мировым
                    стандартам.</p>
            </div>
            <hr class="w-100 clearfix d-md-none">
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 font-weight-bold">Каталог</h6>
                <p>
                    <a href="/category/{{\App\Models\Categories::where('parent_id', null)->where('title', 'Для лица')->value('id')}}">-
                        Для лица</a>
                </p>
                <p>
                    <a href="/category/{{\App\Models\Categories::where('parent_id', null)->where('title', 'Для тела')->value('id')}}">-
                        Для тела</a>
                </p>
                <p>
                    <a href="#!">- Эффективные наборы</a>
                </p>
            </div>
            <hr class="w-100 clearfix d-md-none">
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 font-weight-bold">О нас</h6>
                <p>
                    <a href="/company/production">- Производство</a>
                </p>
                <p>
                    <a href="/company/about">- Философия</a>
                </p>
                <p>
                    <a href="/company/sea">- Море</a>
                </p>
                <p>
                    <a href="/company/vod">- Водоросли</a>
                </p>
            </div>
            <hr class="w-100 clearfix d-md-none">
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h6 class="text-uppercase mb-4 font-weight-bold">Мы в сетях</h6>
                <p><a href="https://www.facebook.com/biothal.ua"><img style="margin-right: 15px; margin-left: 10px"
                                                                      src="{{ Storage::disk('public')->url('image/footer/facebook.png')}}">Facebook</a>
                </p>
                <p>
                    <a href="https://www.youtube.com/channel/UCrfHUxmilxCSfhMG9TKLa1Q">
                        <svg style="width: 2.5em; height: 1.7em; padding-right: 3px" xmlns="http://www.w3.org/2000/svg"
                             width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                            <path
                                d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.122C.002 7.343.01 6.6.064 5.78l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                        </svg>
                        Youtube</a></p>
                <p><a href="https://www.instagram.com/biothal.ua"><img
                            style="margin-right: 15px; margin-left: 5px; width: 25px"
                            src="{{ Storage::disk('public')->url('image/footer/instagram.png')}}">Instagram</a></p>
            </div>
        </div>
        <hr>
        <div class="row d-flex align-items-center">
            <div class="col-md-7 col-lg-8">
                <p style="color: darkgray" class="text-center text-md-left">© 2021 Copyright: Все права защищены
            </div>
            <div class="col-md-5 col-lg-4 ml-lg-0">
                <!-- Social buttons -->
                <div class="text-center text-md-right">
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item">
                            <a class="btn-floating btn-sm rgba-white-slight mx-1">
                                Пользовательское соглашение
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>




<!-- Footer mobile -->
<section class="footerMobile">
    @if (!empty(request()->route()->action['as']) && request()->route()->action['as'] == 'products_id')
    <div class="container-fluid" style="background-color: #000000; height: content-box; padding: 5px">
        <div class="row justify-content-center">
            <div style="color: #ffffff;">Отправка заказов в течении 3-5 рабочих дней <svg style="margin: 10px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                </svg></div>
        </div>
    </div>
    @endif
    <div class="container-fluid special-offer">
        <div class="container cont">
            <div class="propose-mess">Будь в курсе специальных предложений</div>
            <div class="propose-mess-img" >
                <svg style="color: #000000; margin-top: 5px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gift" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.506V2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="container-fluid" style="background-color: #ffffff; height: content-box; padding: 20px">
        <div class="present" >
            <div style="color: #808080">Электронная почта</div>
            <div style="color: #0BB7B5">Подписаться</div>
        </div>
    </div>
    <div class="container">
        <div class="cont">
            <div><a  href="/"><img id="logotype" style="width: 8em; height: 2em; margin-top: 20px; margin-bottom: 20px" src="{{ Storage::disk('public')->url('image/logotype.png')}}"></a>
            </div>
            <div style="text-align: right;">
                <a href="https://www.youtube.com/channel/UCrfHUxmilxCSfhMG9TKLa1Q"><svg style="width: 2.5em; height: 1.7em; margin-top: 20px; margin-bottom: 20px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.122C.002 7.343.01 6.6.064 5.78l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                </svg></a>
                <a href="https://www.facebook.com/biothal.ua"><svg style="width: 2.5em; height: 1.7em; margin-top: 20px; margin-bottom: 20px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                    </svg></a>
                <a href="https://www.instagram.com/biothal.ua"><svg style="width: 2.5em; height: 1.7em; margin-top: 20px; margin-bottom: 20px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                    </svg></a>
            </div>
            <div>
                <a href="/{{env('FOOTER_FOR_LICO')}}">- Для лица</a><br>
                <a href="/{{env('FOOTER_FOR_TELO')}}">- Для тела</a><br>
                <a href="#!">- Эффективные наборы</a><br>
                <a href="/company/about">- О компании</a>
            </div>
            <div style="text-align: right;"><a href="tel:+380688881208">+38 (068) 888-12-08</a> <br> <p style="color: #296674">Обратный звонок</p></div>
            <div><button style="width: 13em; height: 2.7em; border-radius: 25px; margin-top: 15px">Стать дистрибьютером</button></div>
            <div style="text-align: right"><img style="width: 5em; height: 1.5em; margin-top: 33px;" src="{{ Storage::disk('public')->url('image/footer/VisaMasterCard.png')}}"></div>
            <div style="margin-top: 15px; margin-bottom: 25px">© BIOTHAL 2020—2021</div>
            <div style="margin-top: 15px; text-align: right; margin-bottom: 25px"><a href="">Пользовательское соглашение</a></div>
        </div>
    </div>
</section>
