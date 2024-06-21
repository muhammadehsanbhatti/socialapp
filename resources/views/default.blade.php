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
            bottom: 20px;
            top: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100px;
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
                    <a href="{{ asset($video_detail->path) }}" download class="btn btn-secondary">Download</a>
                    <button onclick="shareOnWhatsApp('{{ asset($video_detail->path) }}')" class="btn btn-success">WhatsApp</button>
                    <button onclick="shareOnFacebook('{{ asset($video_detail->path) }}')" class="btn btn-primary">Facebook</button>
                    <button onclick="shareOnInstagram('{{ asset($video_detail->path) }}')" class="btn btn-danger">Instagram</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
