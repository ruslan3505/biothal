{{--<link href="{{ asset('css/cart.css') }}" rel="stylesheet">--}}
<style>
    @media (min-width:1001px) {
        #myCarouselMobile {
            display: none;
        }
    }
    @media (min-width:0px) and (max-width:1000px) {
        #myCarousel {
            display: none;
        }
    }
</style>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">

        @foreach($files as $key=>$global)
        <li data-target="#myCarousel" data-slide-to="{{$key}}" class=""></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            @if(isset($global))
            <img class="img-fluid d-block w-100" src="{{substr($global,-27)}}" height="400" style="width:100%; ">
            @endif
        </div>
        @foreach($files as $key=>$global)
        <div class="carousel-item">
            <img class="img-fluid d-block w-100" src="{{substr($global,-27)}}" height="400" style="width:100%;">
        </div>
         @endforeach
    </div>
    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

{{--CarouselMobile--}}
<div id="myCarouselMobile" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">

        @foreach($files as $key=>$global)
            <li data-target="#myCarouselMobile" data-slide-to="{{$key}}" class=""></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            @if(isset($global))
                <img class="img-fluid d-block w-100" src="{{substr($global,-27)}}" height="300" style="width:30%">
            @endif
        </div>
        @foreach($files as $key=>$global)
            <div class="carousel-item">
                <img class="img-fluid d-block w-100" src="{{substr($global,-27)}}" height="300" style="width:30%">
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#myCarouselMobile" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarouselMobile" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>


