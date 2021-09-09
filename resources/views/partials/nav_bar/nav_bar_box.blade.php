{{--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">--}}

<style>
    @media (min-width:0px) and (max-width:1000px) {
        .free_delivery {
            display: none!important;
        }
    }
    .navbar {
        background-color: #ffffff!important;
    }

    .dropdown-menu {

    }
</style>

<div class="container-fluid free_delivery" style="background-color: #000000; height: content-box; padding: 5px">
    <div class="justify-content-center">
        <div style="color: #ffffff; text-align: center">Отправка заказов в течении 3-5 рабочих днейF <svg style="margin: 10px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
            </svg></div>
    </div>
</div>
<nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-light">
    <div id="logo" class="row col-sm-3">
        <a class="navbar-brand" href="/">
            <img id="logo_img" class="img-fluid" style="margin-left: 30px; margin-right: auto; margin-top:5px; width: 7em"
                 src="{{ Storage::disk('public')->url('image/new-logo.svg')}}" width="127"></a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span id="navtreeline" class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse col-sm-7" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Категории
                </a>
                <div class="dropdown-menu" style="column-count: 2" aria-labelledby="navbarDropdownMenuLink">
                    @foreach($categories as $value)
                        @if($value['parent_id'] == null)
                            <a class="dropdown-item" style="margin-top: 10px" href="/category/{{$value['id']}}"
                               id="categories_{{$value['id']}}"><b>{{$value['title']}}</b></a>
                            @foreach($categories as $child)
                                @if($child['parent_id'] == $value['id'] )
                                    <a class="dropdown-item"
                                       href="/category/{{$child['parent_id']. '/'. $child['id']}}"
                                       id="categories_{{$child['id']}}">{{$child['title']}}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Потребности
                </a>
                <div class="dropdown-menu" style="column-count: 2" aria-labelledby="navbarDropdownMenuLink">
                    @foreach($accessories as $value)
                        @if($value['parent_id'] == null)
                            <div style="display: flex; flex-direction: column;">
                                <div>
                                    <a class="dropdown-item" style="margin-top: 10px; margin-bottom: 15px"
                                       href="/accessory/{{$value['id']}}"
                                       id="accessories_{{$value['id']}}"><b>{{$value['title']}}</b></a>
                                </div>
                                @foreach($accessories as $child)
                                    @if($child['parent_id'] == $value['id'] )
                                        <div>
                                            <a class="dropdown-item"
                                               href="/accessory/{{$child['parent_id']. '/'. $child['id']}}"
                                               id="accessories_{{$child['id']}}">{{$child['title']}}</a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Эффективные наборы</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><b>Стать дистрибьютером</b></a>
            </li>
        </ul>
    </div>

    <div class="col-sm-2" id="icons" style="display: flex; justify-content: space-evenly">
        <svg data-toggle="modal" data-target="#exampleModal" width="1.2em" height="1.2em" viewBox="0 0 16 16"
             class="bi bi-person" fill="currentColor"
             xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
        </svg>
        @if (empty($checkout))
            <!-- Button trigger modal -->
            <span class="badge badge-light">
                <svg data-toggle="modal" data-target="#exampleModalLong" width="1.2em" height="1.2em" viewBox="0 0 16 16"
                     class="bi bi-bag" fill="currentColor"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M8 1a2.5 2.5 0 0 0-2.5 2.5V4h5v-.5A2.5 2.5 0 0 0 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5H2z"/>
                </svg>
                @if(count($cart_prod_count) != null)
                    <span class="countAll-container">
                        {{$countAll}}
                    </span>
                @endif
            </span>
        @endif
    </div>
</nav>

<nav id="navbarMobile" class="navbar navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdownMobile">
        <img class="img_navbarMobile" src="{{ Storage::disk('public')->url('image/burger.svg')}}">
    </button>
    <div id="logo-mobile" class="row col-sm-3">
        <a class="navbar-brand" href="/">
            <img id="logo_img" class="img-fluid" style="margin-left: 30px; margin-right: auto; margin-top:5px; width: 7em"
                 src="{{ Storage::disk('public')->url('image/new-logo.svg')}}" width="127"></a>
    </div>
    <div class="col-sm-2" id="icons" style="display: flex; justify-content: space-evenly">
        <svg data-toggle="modal" data-target="#exampleModal" width="1.2em" height="1.2em" viewBox="0 0 16 16"
             class="bi bi-person" fill="currentColor"
             xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
        </svg>
        @if (empty($checkout))
            <!-- Button trigger modal -->
            <span class="badge badge-light">
                <svg data-toggle="modal" data-target="#exampleModalLong" width="1.2em" height="1.2em" viewBox="0 0 16 16"
                     class="bi bi-bag" fill="currentColor"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M8 1a2.5 2.5 0 0 0-2.5 2.5V4h5v-.5A2.5 2.5 0 0 0 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5H2z"/>
                </svg>
                @if(count($cart_prod_count) != null)
                    <span class="countAll-container">
                        {{$countAll}}
                    </span>
                @endif
            </span>
        @endif
    </div>
    <div class="collapse navbar-collapse" id="navbarNavDropdownMobile">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkMobile" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Категории
{{--                    <i class="fas fa-arrow-right" style="float: right"></i>--}}
                    <img  src="{{ Storage::disk('public')->url('image/ArrowRight.png')}}" style="float: right">
                </a>

                <div class="dropdown-divider"></div>
                <div class="dropdown-menu" style="border: none" aria-labelledby="navbarDropdownMenuLinkMobile">
                    @foreach($categories as $value)
                        @if($value['parent_id'] == null)
                            <a class="dropdown-item" style="margin-top: 10px" href="/category/{{$value['id']}}"
                               id="categories_{{$value['id']}}"><b>{{$value['title']}}</b></a>
                            <div class="dropdown-divider"></div>
                            @foreach($categories as $child)
                                @if($child['parent_id'] == $value['id'] )
                                    <a class="dropdown-item"
                                       href="/category/{{$child['parent_id']. '/'. $child['id']}}"
                                       id="categories_{{$child['id']}}">- {{$child['title']}}</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarAccessoriesDropdownMenuLink" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Потребности
                    <img  src="{{ Storage::disk('public')->url('image/ArrowRight.png')}}" style="float: right">
                </a>
                <div class="dropdown-divider"></div>
                <div class="dropdown-menu" style="border: none" aria-labelledby="navbarAccessoriesDropdownMenuLink">
                    @foreach($accessories as $value)
                        @if($value['parent_id'] == null)
                            <div style="display: flex; flex-direction: column;">
                                    <a class="dropdown-item"
                                       href="/accessory/{{$value['id']}}"
                                       id="accessories_{{$value['id']}}"><b>{{$value['title']}}</b></a>
                                    <div class="dropdown-divider"></div>
                                @foreach($accessories as $child)
                                    @if($child['parent_id'] == $value['id'] )
                                            <a class="dropdown-item"
                                               href="/accessory/{{$child['parent_id']. '/'. $child['id']}}"
                                               id="accessories_{{$child['id']}}">- {{$child['title']}}</a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Эффективные наборы</a>
                <div class="dropdown-divider"></div>
            </li>
            <div class="nav-item">
                <a class="nav-link" id="a_nav_link" href="#"><span>Стать дистрибьютером</span></a>
            </div>
        </ul>
    </div>
</nav>

<!-- Modal1 -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Личный кабинет</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row justify-content-center modal-body">
                <div class="btn-group-vertical">
                    @if ((Auth::check() == false))
                        <a href="{{route('login')}}">
                            <button style="margin-bottom: 15px; width: 200px" type="button"
                                    class="btn btn-info">
                                Войти
                            </button>
                        </a>
                        <a href="{{route('register')}}">
                            <button style="margin-bottom: 15px; width: 200px" type="button"
                                    class="btn btn-info">
                                Зарегистрироваться
                            </button>
                        </a>
                    @endif
                    @if(Auth::user())
                        @if(Auth::user()->type == 'admin')
                            <a href="{{route('admin.dashboard')}}">
                                <button style="margin-bottom: 15px; width: 200px" type="button"
                                        class="btn btn-info">
                                    Admin Panel
                                </button>
                            </a>
                        @endif
                    @endif
                    @if (Auth::check())
                        <a href="{{route('logout')}}">
                            <button style="margin-bottom: 15px; width: 200px" type="button"
                                    class="btn btn-info">
                                Выйти
                            </button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal2 -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div style="margin-right: 0px!important; margin-top: 0px!important;" class="modal-dialog" role="document">
        <div class="pre-conteiner">
            @include('partials.partialsBasket')
        </div>
    </div>
</div>
