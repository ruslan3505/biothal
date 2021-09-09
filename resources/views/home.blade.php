@extends('layouts.app')

@section('content')
    @include('layouts.nav')
    @include('layouts.carousel')


    <div class="home-container">
        <div class="main-body-container">
            @if($count_sale_product > 0)
                <div class="surprice" style="padding: 25px; text-align: center">
                    Подарки и скидки
                </div>
            @endif
            <div class="container">
                <div class="row justify-content-center">
                    @foreach($products as $value)
                        @if($value['sale_id'] != null)
                            <div class="col-md-4 col-sm-12" style="margin-bottom: 10px">
                                <div class="card-item text-center">
                                    <div id="heightCart" class="" style="background: #FFFFFF!important;">
                                        <a href="/product/{{$value->id}}">
                                            <img class="card-img-top img-fluid"
                                                 style="max-height: 207px; max-width: 180px; margin-right: 7px; justify-content: center;"
                                                 src="@if(isset($value->getImage['name']))
                                                 {{ Storage::url('/img/products/'.$value->getImage['name'])}}
                                                 @endif">
                                            <h5 class="card-title">{!!$value->name!!}</h5>
                                            <p class="card-text"><s>{!!$value->price . ' '!!}</s>грн.</p>
                                            <p class="card-text"><b>{!!$value->price_with_sale . ' '!!}грн.</b></p>
                                        </a>
                                        <button id="btn-buyHome"
                                                style="margin-top: 15px; width: 70%; background-color: #2f7484; border-color: #2f7484"
                                                class="btn btn-success rounded-pill btn-buy-item"
                                                value="{{$value->id}}">
                                            Купить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                {{--        <span style="display: flex; justify-content: space-around;">{{$products->links()}}</span>--}}
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    @foreach($categories as $value)
                        @if($value['parent_id'] == null)
                            @foreach($img_categories as $val)
                                @if($val['image_id_categories'] == $value['id'])
                                    <div>
                                        <a class="dropdown-item" style="margin-top: 10px"
                                           href="/category/{{$value['id']}}" id="categories_{{$value['id']}}">
                                            <img class="img-fluid" style="margin-top: 30px; padding: 7px"
                                                 src="{{Storage::url('img/'.$val['name'])}}" height="250"></a>
                                    </div>
                                @endif
                            @endforeach

                        @endif
                    @endforeach
                </div>
            </div>

            <div style="padding: 25px; text-align: center">
                <h2>Бестселлеры</h2>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    @foreach($products2 as $value)
                        @if($value['sale_id'] == null)
                            <div class="col-md-4 col-sm-12 card-item">
                                <div class=" text-center">
                                    <a href="product/{{$value->id}}">
                                        <img class="card-img-top img-fluid" style="max-height: 207px; max-width: 180px"
                                             src="
                                             @if(isset($value->getImage['name']))
                                                {{ Storage::url('/img/products/'.$value->getImage['name'])}}
                                             @endif">
                                        <div class="card-body">
                                            <h5 class="card-title">{!!$value->name!!}</h5>
                                            <p class="card-text"><b>{!!$value->price . ' '!!}грн.</b></p>
                                        </div>
                                    </a>
                                    <button id="btn-buyHome"
                                            style="width: 70%; background-color: #2f7484; border-color: #2f7484"
                                            class="btn btn-success rounded-pill btn-buy-item" value="{{$value->id}}">
                                        Купить
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <span style="display: flex; justify-content: space-around;">{{$products2->links()}}</span>
            </div>

            <div class="container textPc">
                <div style="padding-top: 25px; text-align: center">
                    <h2>ИНТЕРНЕТ-МАГАЗИН BIOTHAL</h2>
                </div>
                <div class="row" style="text-align: justify; padding: 10px">
                    <div class="col-md-1"></div>
                    <div style="padding: 10px; column-count: 2">
                        <p>Во Франции, на севере Бретани, находится заповедная территория, дикая и нетронутая природа,
                            крупнейшая
                            долина и историческое место сбора водорослей в Европе.</p>
                        <p>На протяжении многих лет здесь производят косметику на основе морских ингредиентов.</p>
                        <p>Именно здесь Специалисты компании Biothal в тесном сотрудничестве с известными микробиологами
                            и
                            альгологами разрабатывают уникальные формулы продуктов являющихся бесспорным лидером в
                            области Спа
                            услуг, ухода за кожей лица и тела. Успешное сочетание натуральных ингредиентов с
                            современными
                            технологиями позволило нам создать уникальные средства для продления молодости кожи с
                            максимальным
                            терапевтическим эффектом.</p>
                        <div id="myHide">
                            <p>Каждый продукт Biothal представляет собой настоящий эликсир красоты и молодости,
                                концентрат морской силы,
                                который работает в абсолютной синергии с кожей и соответствует самым высоким мировым
                                стандартам
                                качества. Тысячи женщин уже оценили профессиональный подход марки и высокую
                                эффективность продуктов
                                Biothal.</p>
                            <p>Водоросли - источник жизни и целебных свойств известный с древних времен, они
                                концентрируют в себе все
                                богатство морской воды: йод, магний, калий, железо, селен, цинк, медь, витамины и
                                аминокислоты,
                                передавая им свою силу детоксикации, минерализации и регенерации.</p>
                            <p>Бурая водоросль ламинария, произрастающая в морских водах, обладает целым комплексом
                                минералов и активных
                                элементов, необходимых нашей коже. Экстракт ламинарии регулирует работу сальных желёз,
                                способствует
                                выводу лишней жидкости, а также оказывают дезинфицирующее действие, убивая
                                болезнетворные бактерии.</p>
                            <p>Высокое содержание йода способствует нормализации функции щитовидной железы, активизирует
                                все виды обмена
                                веществ, нормализует обмен жиров и способствует липолизу, повышает потребление кислорода
                                клетками,
                                снижает вязкость крови, повышает тонус сосудов.</p>
                            <p>Экстракт морской водоросли фукус обладает целым комплексом полезных веществ, которые
                                укрепляют и
                                тонизируют кожу. Концентрат полезных элементов, содержащийся в водоросли, способствует
                                регенерации
                                клеток кожи за счёт стимуляции кровообращения. Фукус имеет высокое содержание йода,
                                также витамин С и
                                полисахариды. Его свойства благотворно влияют на кожу, восстанавливая эластичность и
                                создавая защитную
                                плёнку.</p>
                            <p>Фукус содержит вещества полифенолы, обладающие антисептическим и дезинфицирующим
                                действием. Водоросль
                                высоко ценится как источник тонизирующих жиросжигающих соединений. Применяется в
                                детокс-программах,
                                обладая антицеллюлитным и тонизирующим действием.
                            <p>Морской латук произрастает на солнечных скалах, найти его можно на различных глубинах.
                                Растение
                                предпочитает спокойные воды на побережье Атлантического океана, в Черном море и в Тихом
                                океане.
                                Ярко-зеленую морскую водоросль морской латук, растущую на скалах, собирают только во
                                время отлива.
                                Растение пропускает через себя морскую воду, оставляя только самые полезные минералы и
                                микроэлементы.
                                Растет в мелководных районах, богатых минералами. В составе водоросли содержится большой
                                процент магния.
                                Также витамины Е и С. Морской латук богат кальцием, железом и магнием.</p>
                            <p>Насыщенная хлорофиллом зеленая водоросль, действующая как увлажняющий и
                                противовоспалительный компонент.
                                Экстракт оказывает на кожу омолаживающее воздействие, придаёт эластичность и выводит
                                токсины.</p>
                            <p id="hide"><b>Скрыть часть текста</b></p>
                        </div>
                        <p id="show"><b>Читать далее</b></p>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    For Mobile--}}
    <div class="mobile-container">
        <div class="main-body-container">
            @if($count_sale_product > 0)
                <div class="surprice heading-mobile" style="margin-top: 25px; text-align: center">
                    Подарки и скидки
                </div>
            @endif
            <div class="container">
                <div class="row justify-content-center" style="display: block; column-count: 2">
                    @foreach($products as $value)
                        @if($value['sale_id'] != null)
                            <div class="col-md-2 col-sm-4" style="margin-bottom: 10px;">
                                <div class="card-item-mobile text-center">
                                    <div id="heightCart" style="background: #FFFFFF!important;">
                                        <a href="/product/{{$value->id}}" style="display: inline-table">
                                            <img class="card-img-top img-fluid"
                                                 style="max-height: 103px; max-width: 90px; justify-content: center;"
                                                 src="@if(isset($value->getImage['name']))
                                                 {{ Storage::url('/img/products/'.$value->getImage['name'])}}
                                                 @endif">
                                            <h5 class="card-title">{!!$value->name!!}</h5>
                                            <p class="card-text"><s>{!!$value->price . ' '!!}</s>грн.</p>
                                            <p class="card-text" style="margin-bottom: 9px"><b>{!!$value->price_with_sale . ' '!!}грн.</b></p>
                                        </a>
                                        <button id="btn-buyHome" style="width: 70%; background-color: #2f7484; border-color: #2f7484" class="btn btn-success rounded-pill btn-buy-item" value="{{$value->id}}">Купить</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                {{--        <span style="display: flex; justify-content: space-around;">{{$products->links()}}</span>--}}
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    @foreach($categories as $value)
                        @if($value['parent_id'] == null)
                            @foreach($img_categories as $val)
                                @if($val['image_id_categories'] == $value['id'])
                                    <div>
                                        <a class="dropdown-item"  href="/category/{{$value['id']}}" id="categories_{{$value['id']}}">
                                            <img class="img-fluid" style="padding: 7px" src="{{Storage::url('img/'.$val['name'])}}" height="250"></a>
                                    </div>
                                @endif
                            @endforeach

                        @endif
                    @endforeach
                </div>
            </div>

            <div class="best-heading-mobile">
                Бестселлеры
            </div>
            <div class="container">
                <div class="row justify-content-center" style="display: block; column-count: 2">
                    @foreach($products2 as $value)
                        @if($value['sale_id'] == null)
                            <div class="col-md-4 col-sm-12 card-item-mobile">
                                <div class="text-center">
                                    <a href="product/{{$value->id}}" style="display: inline-table">
                                        <img class="card-img-top img-fluid"  style="max-height: 103px; max-width: 90px; justify-content: center;"
                                             src="@if(isset($value->getImage['name']))
                                        {{ Storage::url('/img/products/'.$value->getImage['name'])}}
                                        @endif">
                                            <div class="card-title">{!!$value->name!!}</div>
                                            <p class="card-text" style="margin-bottom: 9px"><b>{!!$value->price . ' '!!}грн.</b></p>
                                    </a>
                                    <button id="btn-buyHome" style="width: 70%; background-color: #2f7484; border-color: #2f7484" class="btn btn-success rounded-pill btn-buy-item" value="{{$value->id}}">Купить</button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <span style="display: flex; justify-content: space-around;">{{$products2->links()}}</span>
            </div>

    <!-- Text mobile -->
            <section class="textMobile">
                <div class="container-fluid" style="background-color: #F7F7F7; height: content-box; padding: 5px">
                    <div class="container">
                        <div class="row">
                            <div style="padding: 20px;"><b>Интернет-магазин косметики Biothal</b></div>
                            <div style="padding-left: 20px; padding-right: 20px; text-align: justify">
                                Хоть раз открыв каталог интернет-магазина BIOTHAL уже не захочется
                                тратить время на походы по торговым центрам – каталог косметики и парфюмерии,
                                представленный здесь, превосходит даже смелые ожидания и удовлетворяет любые требования. Добавьте к этому понятный интерфейс, универсальную систему поиска, возможность получить свой заказ на дом в удобное время и вы получите идеальный ресурс, который постоянно совершенствуется уже 10 лет – с 2009 года.
                                <div style="margin-top: 20px; margin-bottom: 20px"><a style="color: #000000" href="/company/about"><b>Читать далее</b></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('layouts.footer')
    <script>
        $(document).ready(function(){
            $("#hide").click(function(){
                $("#myHide").hide();
            });
            $("#show").click(function(){
                $("#myHide").show();
            });
        });
    </script>
@endsection

