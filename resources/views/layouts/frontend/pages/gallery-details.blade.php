@include('layouts.frontend.inc.header',[
    $seo_title=SeoSettings()->gallery_page_seo_title.' | '.$gallery->event_name.' | '.$subGallery->title,
    $seo_description=SeoSettings()->gallery_page_seo_description,
    $seo_keywords=SeoSettings()->gallery_page_seo_keywords
])

<style>
    h1.event_title {
        font-size: 27px;
        line-height: 34px;
        background: linear-gradient(91.57deg, #0057FF 48.67%, #39C1DF 76.12%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        padding-top: 17px;
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
                            <h1 class="event_title">{{ $subGallery->title }}</h1>
                            <span class="title__span" style="margin:25px 0 50px 0">{!! $subGallery->description !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- hero section end here -->

    {{-- <div class="container">
        <a class="gallery_back_btn" href="{{ route('subgalleryPage',$slug) }}">Back Sub Gallery</a>
    </div> --}}


    <!-- gallery section start here -->
    <div class="single_gallery_section mt-5">
        <div class="container">
            <div class="gallery__inner_section">
                <div class="tz-gallery">
                    <div class="row mb-5">
                        @foreach ($multipleImage as $images)
                        <div class="col-sm-12 col-md-4 mb-4">
                            <a class="lightbox" href="{{ asset($images->image) }}">
                                <img width="100%" class="rounded-1" src="{{ asset($images->image) }}" alt="image">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- gallery section end here -->


</div>

@section('script')
    <script type="text/javascript">
        baguetteBox.run('.tz-gallery');
    </script>
@endsection


@include('layouts.frontend.inc.footer')


