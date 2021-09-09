@extends('layouts.app')

@section('content')
    @include('layouts.nav')

    <div>
        <img class="img-fluid myHeight d-block w-100" src="{{ Storage::disk('public')->url('image/pro.jpg')}}" height="250" alt="1"
             style="width:100%;">
    </div>

    <div class="container">
        <div style="padding-top: 25px; text-align: center">
            <h2>Производство</h2>
        </div>
        <div style="padding-bottom: 10px">
            <p>Мы работаем в области открытия новых морских ингредиентов и формул, компания Biothal установила тесные связи с ведущими микробиологами и альгологами Франции а также с Университетами Бретани, изучающими свойства морских растений. </p>
            <p style="margin-bottom: 0px; line-height: normal; min-height: 14px;">&nbsp;</p>
            <ul style="margin-left: 10px; line-height: normal;">
                <li>Продукция Biothal состоит из органических и/или натуральных ингредиентов.</li>
                <li>Мы используем активные компоненты и сырье от лучших европейских производителей.&nbsp;</li>
                <li>Продукция не тестируется на животных.</li>
                <li>Мы никогда не используем парабены, сульфаты и ГМО.</li>
                <li>Производство соответствует самым высоким мировым стандартам.</li>
                <li>На всех стадиях производства компания Biothal осуществляет контроль, гарантируя высокое качество продукта.</li>
            </ul>
        </div>
    </div>
    </div>


    @include('layouts.footer')
@endsection

