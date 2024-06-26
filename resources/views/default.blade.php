<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Social videos</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .owl-carousel .item {
            height: 100vh;
            width: 550px;
            min-width: 300px;
            max-width: 550px;
            margin: auto;
            position: relative;
        }
        .item video {
            height: 100%;
            width: 100%;
            min-width: 300px;
            max-width: 550px;
            object-fit: cover;
        }
        .btn-container, .video-buttons {
            position: absolute;
            right: 20px;
            z-index: 10;
        }
        .video-buttons {
            bottom: 83px;
            top: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            /* width: 100px; */
            justify-content: end;
        }
    </style>
</head>
<body class="antialiased">
    <div class="container">
        <div class="btn-container">
            <button type="button" onclick="window.location.href='{{ route('sp-login') }}'" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">
                Login
            </button>
        </div>

        <div class="owl-carousel owl-theme">
            @foreach($data as $key => $video_detail)
            <div class="item">
                <video controls>
                    <source src="{{ asset($video_detail->path) }}" type="video/mp4">
                </video>
                <div class="video-buttons">
                    <a href="{{ asset($video_detail->path) }}" download>
                        <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="#" onclick="shareOnWhatsApp('{{ asset($video_detail->path) }}')">
                        <svg fill="#FFFFFF" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 308 308" xml:space="preserve">
                            <g id="XMLID_468_">
                                <path id="XMLID_469_" d="M227.904,176.981c-0.6-0.288-23.054-11.345-27.044-12.781c-1.629-0.585-3.374-1.156-5.23-1.156
                                    c-3.032,0-5.579,1.511-7.563,4.479c-2.243,3.334-9.033,11.271-11.131,13.642c-0.274,0.313-0.648,0.687-0.872,0.687
                                    c-0.201,0-3.676-1.431-4.728-1.888c-24.087-10.463-42.37-35.624-44.877-39.867c-0.358-0.61-0.373-0.887-0.376-0.887
                                    c0.088-0.323,0.898-1.135,1.316-1.554c1.223-1.21,2.548-2.805,3.83-4.348c0.607-0.731,1.215-1.463,1.812-2.153
                                    c1.86-2.164,2.688-3.844,3.648-5.79l0.503-1.011c2.344-4.657,0.342-8.587-0.305-9.856c-0.531-1.062-10.012-23.944-11.02-26.348
                                    c-2.424-5.801-5.627-8.502-10.078-8.502c-0.413,0,0,0-1.732,0.073c-2.109,0.089-13.594,1.601-18.672,4.802
                                    c-5.385,3.395-14.495,14.217-14.495,33.249c0,17.129,10.87,33.302,15.537,39.453c0.116,0.155,0.329,0.47,0.638,0.922
                                    c17.873,26.102,40.154,45.446,62.741,54.469c21.745,8.686,32.042,9.69,37.896,9.69c0.001,0,0.001,0,0.001,0
                                    c2.46,0,4.429-0.193,6.166-0.364l1.102-0.105c7.512-0.666,24.02-9.22,27.775-19.655c2.958-8.219,3.738-17.199,1.77-20.458
                                    C233.168,179.508,230.845,178.393,227.904,176.981z" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="2"/>
                                <path id="XMLID_470_" d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716
                                    c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396
                                    c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z
                                    M156.734,268.994c-23.539,0-46.338-6.797-65.936-19.657c-0.659-0.433-1.424-0.655-2.194-0.655c-0.407,0-0.815,0.062-1.212,0.188
                                    l-40.035,12.726l12.924-38.129c0.418-1.234,0.209-2.595-0.561-3.647c-14.924-20.392-22.813-44.485-22.813-69.677
                                    c0-65.543,53.754-118.867,119.826-118.867c66.064,0,119.812,53.324,119.812,118.867
                                    C276.546,215.678,222.799,268.994,156.734,268.994z" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="2"/>
                            </g>
                        </svg>
                    </a>
                    {{-- <a href="#" onclick="shareOnWhatsApp('{{ asset($video_detail->path) }}')" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" onclick="shareOnFacebook('{{ asset($video_detail->path) }}')" class="btn btn-primary">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" onclick="shareOnInstagram('{{ asset($video_detail->path) }}')" class="btn btn-danger">
                        <i class="fab fa-instagram"></i>
                    </a> --}}
                </div>

                @if($video_detail->adsterra_code)
                    <div class="adsterra-ad">
                        <!-- Adsterra Code -->
                        <script async src="{{ $video_detail->adsterra_code }}"></script>
                    </div>
                @endif

            </div>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function(){
            var owl = $('.owl-carousel');
            owl.owlCarousel({
                items:1,
                loop:true,
                margin:10,
                responsiveClass:true,
                autoplay:true,
                dots: false,
                autoplayTimeout:5000,
                autoplayHoverPause:true,
                onInitialized: playActiveSlideVideo,
                onTranslated: playActiveSlideVideo,
            });

            function playActiveSlideVideo() {
                const videos = document.querySelectorAll('.item video');
                videos.forEach(video => video.pause());
                const activeSlide = document.querySelector('.owl-item.active .item video');
                if (activeSlide) {
                    activeSlide.play();
                }
            }
        });

        function shareOnWhatsApp(videoUrl) {
            const whatsappUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(videoUrl)}`;
            window.open(whatsappUrl, '_blank');
        }

        function shareOnFacebook(videoUrl) {
            const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(videoUrl)}`;
            window.open(facebookUrl, '_blank');
        }

        function shareOnInstagram(videoUrl) {
            const instagramUrl = `https://www.instagram.com/?url=${encodeURIComponent(videoUrl)}`;
            window.open(instagramUrl, '_blank');
        }
    </script>
</body>
</html>
