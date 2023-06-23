@include('layouts.frontend.inc.header',[
    $seo_title=SeoSettings()->gallery_page_seo_title.' | '.$gallery->event_name,
    $seo_description=SeoSettings()->gallery_page_seo_description,
    $seo_keywords=SeoSettings()->gallery_page_seo_keywords
])

<style>
     h3.event_title {
        font-size: 27px;
    line-height: 27px;
    background: linear-gradient(91.57deg, #0057FF 48.67%, #39C1DF 76.12%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    padding-top: 0;
    margin: 0;
    }

    h1.event_title {
        background: linear-gradient(91.57deg, #0057FF 48.67%, #39C1DF 76.12%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    @media only screen and (max-width: 600px) {
        .gallery_item {
            padding: 4px;
        }
        h3.event_title {
            font-size: 23px;
            line-height: 28px;
        }
    }

</style>

<!-- banner and hero section start here -->
<div class="banner__section">

    @include('layouts.frontend.inc.menu')

    <!-- hero section start here -->
    <div class="hero__section">
        <div class="container">
            <div class="hero__inner">
                <div class="row align-items-center">
                    <div class="col-md-10 m-auto">
                        <div class="title">
                            <h1 class="event_title">{{ $gallery->event_name }}</h1>
                            <span class="title__span" style="margin:25px 0 50px 0">{!! $gallery->event_desc !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- hero section end here asd -->

    <!-- hero section start here -->
    {{-- <div class="hero__section">
        <div class="container">
            <div class="hero__inner">
                <div class="row align-items-center">
                    <div class="col-md-10 m-auto">
                        <div class="title">
                            <h1 class="event_title">Sub Gallery</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- hero section end here -->

    <div class="container">
        <a class="gallery_back_btn" href="{{ route('galleryPage.index') }}">Back Gallery</a>
    </div>

    <!-- Service section start here -->
    <div class="service_section">
        <div class="container">
            <div class="service_inner">
                <div class="container">
                    <div class="row">
                        @foreach ($subGalleries as $gallery)
                        <div class="col-md-4 mb-4">
                            <a class="software__service__swiper" href="{{ route('galleryPage.details', $gallery->slug ) }}">
                                <div class="card" >
                                    <img src="{{ asset($gallery->thumbnail_image) }}" class="card-img-top" style="max-height: 270px" alt="{{$gallery->title }}">
                                    <div class="card-body">
                                        <h3 class="event_title">{{  $gallery->title }}</h3>
                                    </div>
                                  </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service section end here -->

</div>


@include('layouts.frontend.inc.footer')
