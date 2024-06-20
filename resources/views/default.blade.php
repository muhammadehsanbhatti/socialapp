<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Social videos</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .swiper-container {
            width: 100%;
            height: 100vh;
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .swiper-slide video {
            width: 100%;
            height: auto;
            max-height: 90vh;
        }
        .btn-container {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
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
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($data as $key => $video_detail)
                <div class="swiper-slide">
                    <video controls>
                        <source src="{{ asset($video_detail->path) }}" type="video/mp4">
                    </video>
                </div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.swiper-container', {
                direction: 'vertical',
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                on: {
                    slideChange: function () {
                        const videos = document.querySelectorAll('.swiper-slide video');
                        videos.forEach(video => video.pause());
                        const activeSlide = document.querySelector('.swiper-slide-active video');
                        if (activeSlide) {
                            activeSlide.play();
                        }
                    },
                    init: function () {
                        const firstVideo = document.querySelector('.swiper-slide-active video');
                        if (firstVideo) {
                            firstVideo.play();
                        }
                    },
                },
            });
        });
    </script>
</body>
</html>
