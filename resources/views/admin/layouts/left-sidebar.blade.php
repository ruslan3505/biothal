<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
</a>

<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <a href="{{route('admin.logout')}}"><i class="fas fa-sign-out-alt"></i> Выйти</a>
            <div id="close-sidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="sidebar-header">
            <div class="user-pic">
                <img class="img-responsive img-rounded"
                     src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
                     alt="User picture">
            </div>
            <div class="user-info">
          <span class="user-name">
              {{Auth::user()->name}}
          </span>
                <span class="user-role">Администратор <p class="fa fa-eye"></p></span>
                <span class="user-status">
            <i class="fa fa-circle"></i>
            <span>Онлайн</span>
          </span>
            </div>
        </div>
        <!-- sidebar-header  -->
{{--        <div class="sidebar-search">--}}
{{--            <div>--}}
{{--                <div class="input-group">--}}
{{--                    <input type="text" class="form-control search-menu" placeholder="Поиск...">--}}
{{--                    <div class="input-group-append">--}}
{{--                      <span class="input-group-text">--}}
{{--                        <i class="fa fa-search" aria-hidden="true"></i>--}}
{{--                      </span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <!-- sidebar-search  -->
        <div class="sidebar-menu">
            <ul>
{{--                <li class="{{@checkIsActive('admin.statePanel.page')}}">--}}
{{--                    <a href="{{route('admin.statePanel.page')}}">--}}
{{--                        <i class="fa fa-tachometer-alt"></i>--}}
{{--                        <span>Панель состояния</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="{{@checkIsActive('admin.categories.page')}}">
                    <a href="{{route('admin.categories.page')}}">
                        <i class="fas fa-sitemap"></i>
                        <span>Категории</span>
                    </a>
                </li>
                <li class="{{@checkIsActive('admin.accessories.page')}}">
                    <a href="{{route('admin.accessories.page')}}">
                        <i class="far fa-gem"></i>
                        <span>Потребности</span>
                    </a>
                </li>
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-tags"></i>
                        <span>Каталог</span>
                        <span class="badge badge-pill badge-danger">5</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
{{--                            <li class="{{@checkIsActive('admin.products.page')}}">--}}
{{--                                <a href="{{route('admin.products.page')}}">--}}
{{--                                    <i class="fas fa-angle-double-right"></i>--}}
{{--                                    <span>Товары Old</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            <li class="{{@checkIsActive('admin.products.pageNew')}}">
                                <a href="{{route('admin.products.pageNew')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Товары</span>
                                </a>
                            </li>
                            <li class="{{@checkIsActive('admin.products.sales')}}">
                                <a href="{{route('admin.products.sales')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Скидки %</span>
                                </a>
                            </li>
                            <li class="{{@checkIsActive('admin.products.salesGlobal')}}">
                                <a href="{{route('admin.products.salesGlobal')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Глобальные скидки</span>
                                </a>
                            </li>
                            <li class="{{@checkIsActive('admin.products.salesGroup')}}">
                                <a href="{{route('admin.products.salesGroup')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Групповые скидки</span>
                                </a>
                            </li>
                            <li class="{{@checkIsActive('admin.products.information')}}">
                                <a href="{{route('admin.products.information')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Статьи</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-paper-plane"></i>
                        <span>Рассылка</span>
                        <span class="badge badge-pill badge-danger">2</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="{{@checkIsActive('admin.distribution.emailList')}}">
                                <a href="{{route('admin.distribution.emailList')}}">
                                    <i class="fas fa-envelope"></i>
                                    <span>E-mail рассылка</span>
                                </a>
                            </li>
                            <li class="{{@checkIsActive('admin.distribution.phoneList')}}">
                                <a href="{{route('admin.distribution.phoneList')}}">
                                    <i class="fas fa-phone"></i>
                                    <span>Смс рассылка</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="{{@checkIsActive('admin.orders.orders')}}">
                    <a href="{{route('admin.orders.orders')}}">
                        <i class="far fa-user"></i>
                        <span>Заказы</span>
                    </a>
                </li>
                <li class="{{@checkIsActive('admin.distribution.offer')}}">
                    <a href="{{route('admin.distribution.offer')}}">
                        <i class="fas fa-file"></i>
                        <span>Дистрибьюторы</span>
                    </a>
                </li>
{{--                <li class="{{@checkIsActive('admin.images.banner')}}">--}}
{{--                    <a href="{{route('admin.images.banner')}}">--}}
{{--                        <i class="far fa-images"></i>--}}
{{--                        <span>Баннеры</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="{{@checkIsActive('admin.images.page')}}">--}}
{{--                    <a href="{{route('admin.images.page')}}">--}}
{{--                        <i class="fas fa-camera"></i>--}}
{{--                        <span>Галерея</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="{{@checkIsActive('admin.layouts.header_content')}}">--}}
{{--                    <a href="{{route('admin.layouts.header_content')}}">--}}
{{--                        <i class="fas fa-th-large"></i>--}}
{{--                        <span>Чёрная полоска</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fas fa-th-large"></i>
                        <span>Дизайн</span>
                        <span class="badge badge-pill badge-danger">3</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li class="{{@checkIsActive('admin.images.banner')}}">
                                <a href="{{route('admin.images.banner')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Баннеры</span>
                                </a>
                            </li>
                            <li class="{{@checkIsActive('admin.images.page')}}">
                                <a href="{{route('admin.images.page')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Галерея</span>
                                </a>
                            </li>
                            <li class="{{@checkIsActive('admin.layouts.settings')}}">
                                <a href="{{route('admin.layouts.settings')}}">
                                    <i class="fas fa-angle-double-right"></i>
                                    <span>Настройки</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
              </ul>
        </div>
    </div>
</nav>
