<!DOCTYPE html>
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
            position: relative;
        }
        .swiper-slide video {
            width: 100%;
            height: auto;
            max-height: 82vh;
        }
        .btn-container, .video-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        .video-buttons {
            bottom: 20px;
            top: auto;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
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
                    <div class="video-buttons">
                        <a href="{{ asset($video_detail->path) }}" download class="btn btn-secondary">Download</a>
                        <button onclick="shareOnWhatsApp('{{ asset($video_detail->path) }}')" class="btn btn-success">WhatsApp</button>
                        <button onclick="shareOnFacebook('{{ asset($video_detail->path) }}')" class="btn btn-primary">Facebook</button>
                        <button onclick="shareOnInstagram('{{ asset($video_detail->path) }}')" class="btn btn-danger">Instagram</button>
                    </div>
                </div>
                @endforeach
            </div>
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
                    init: function () {
                        playActiveSlideVideo();
                    },
                    slideChange: function () {
                        playActiveSlideVideo();
                    },
                },
            });

            function playActiveSlideVideo() {
                const videos = document.querySelectorAll('.swiper-slide video');
                videos.forEach(video => video.pause());
                const activeSlide = document.querySelector('.swiper-slide-active video');
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