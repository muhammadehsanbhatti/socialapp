<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Social videos</title>
        {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}"> --}}
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
        <style type="text/css">
            body, html {
                height: 100%;
                margin: 0;
                overflow: hidden;
            }
            #video-container {
                height: 100vh;
                overflow-y: scroll;
                scroll-snap-type: y mandatory;
                padding: 0;
            }
            .video-item video {
                max-height: 100%;
                max-width: 100%;
                height: inherit;
                object-fit: cover;
                cursor: pointer;
            }
            .video-item {
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                scroll-snap-align: start;
                position: relative;
                width: fit-content;
                margin: auto;
            }
            .video-actions button, .volume-control input {
                margin-bottom: 10px;
            }
            .video-actions, .volume-control {
                position: absolute;
                right: 10px;
                bottom: 84px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .video-actions button, .volume-control input {
                margin-bottom: 10px;
                background-color: rgba(0, 0, 0, 0.5);
                border: none;
                color: white;
                font-size: 18px;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .volume-control {
                right: 80px;
                display: none;
            }
            .volume-slider {
                -webkit-appearance: none;
                width: 100px;
                height: 5px;
                background: rgba(255, 255, 255, 0.7);
                outline: none;
                opacity: 0.7;
                transition: opacity .15s ease-in-out;
                cursor: pointer;
            }
            .volume-slider:hover {
                opacity: 1;
            }
            .play-pause-btn {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0, 0, 0, 0.5);
                border: none;
                color: white;
                font-size: 40px;
                border-radius: 50%;
                width: 80px;
                height: 80px;
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 1;
            }
            .navbar {
                position: absolute;
                width: 100%;
                top: 0;
                transition: top 0.3s;
                z-index: 99;
            }
            .videos_actions_bx {
                display: flex;
                flex-direction: column;
            }
        </style>
    </head>
    <body class="antialiased">
        {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
        </nav> --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Login</a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- <div class="container"> --}}
            <div id="video-container" class="container-fluid">
                @include('upload_video.videos', ['data' => $data])
            </div>
        {{-- </div> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
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
                        $(response.html).each(function(index, videoHtml) {
                            $('#video-container').append(videoHtml);
                        });
                        loading = false;
                    },
                    error: function() {
                        console.log("Error loading more videos");
                        loading = false;
                    }
                });
            }


            // Initial load of videos
            loadMoreVideos(page);

            // Load more videos on scroll to the bottom
            $('#video-container').on('scroll', function() {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 100 && !loading) {
                    page++;
                    loadMoreVideos(page);
                }
            });

            // Optional: Play video when in view
            $(document).on('scroll', function() {
                $('.video-item video').each(function() {
                    var video = $(this)[0];
                    var videoTop = $(this).offset().top;
                    var videoBottom = videoTop + $(this).outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();
                    if (videoBottom > viewportTop && videoTop < viewportBottom) {
                        video.play();
                    } else {
                        video.pause();
                    }
                });
            });
        });
    </script>
                </body>
                </html>
