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
            .owl-carousel .owl-item.active {
                transition: transform 0.5s ease-in-out;
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
            .btn-container, .video-buttons, .share-buttons {
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
                justify-content: end;
            }
            .share-buttons {
                bottom: 20px;
                display: flex;
                gap: 10px;
                justify-content: center;
            }
            .share-buttons img {
                width: 40px;
                height: 40px;
                cursor: pointer;
            }
            .navbar-brand img {
                height: 40px;
            }
        </style>
    </head>
    <body class="antialiased">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('app-assets/images/ico/apple-icon-120.png') }}" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <button type="button" onclick="window.location.href='{{ route('sp-login') }}'" class="btn btn-primary btn-lg">
                                Login
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div id="video-carousel" class="owl-carousel owl-theme">
                @include('upload_video.videos', ['data' => $data])
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            var page = 1;
        var endpoint = "{{ route('welcome') }}";
        var loading = false;

        function loadMoreVideos(page) {
            loading = true;
            $.ajax({
                url: endpoint + "?page=" + page,
                type: 'GET',
                success: function(response) {
                    if (response.html == '') {
                        alert("No more videos");
                        return;
                    }
                    var newItems = $(response.html).filter('.item');
                    newItems.each(function() {
                        $("#video-carousel").trigger('add.owl.carousel', [$(this).clone()]).trigger('refresh.owl.carousel');
                    });
                    loading = false;
                },
                error: function() {
                    console.log("Error loading more videos");
                    loading = false;
                }
            });
        }

        $('#video-carousel').owlCarousel({
            items: 1,
            loop: false,
            margin: 10,
            responsiveClass: true,
            dots: false,
            autoplayHoverPause: true,
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

        $('#video-carousel').on('changed.owl.carousel', function(event) {
            var items = event.item.count;
            var item = event.item.index;
            if (item === items - 1 && !loading) {
                page++;
                loadMoreVideos(page);
            }
        });
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

                        function downloadVideo(videoUrl) {
                            const link = document.createElement('a');
                            link.href = videoUrl;
                            link.download = 'video.mp4'; // Adjust the filename as needed
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    </script>
                </body>
                </html>
