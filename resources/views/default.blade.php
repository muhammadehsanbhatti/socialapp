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
    <style>
      body,
        html {
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



        .video-actions,
        .volume-control {
            position: absolute;
            right: 10px;
            bottom: 84px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .video-actions button,
        .volume-control input {
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

        .share-btn {
            cursor: pointer;
        }

        .modal-content {
            border: none;
        }

        .share-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 12%;
            max-width: 300px;
        }

        @media (max-width: 600px) {
            .share-popup button {
                font-size: 16px;
                padding: 15px;
            }
        }


        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .like-btn {
            font-size: 24px;
            border: none;
            background: none;
            cursor: pointer;
        }

        .liked {
            color: red;
        }
        .mute-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 22%;
            height: 8%;
            border: 2px solid black;
            padding: 7px;
            border-radius: 5px;
            background-color: black;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body class="antialiased">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Logo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sp-login') }}">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- <div class="container"> --}}
    <div id="video-container" class="container-fluid">
        @include('upload_video.videos', ['data' => $data])
    </div>


    <div class="modal fade" id="soundModal" tabindex="-1" role="dialog" aria-labelledby="soundModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="soundModalLabel">Play Videos with Sound?</h5>
                </div>
                <div class="modal-body">
                    Do you want to play the videos with sound?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="muteVideos">Mute</button>
                    <button type="button" class="btn btn-primary" id="playWithSound">Play with Sound</button>
                </div>
            </div>
        </div>
    </div>


    <div class="overlay" id="overlay"></div>
    {{-- </div> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-visible/1.2.0/jquery.visible.min.js"></script>
    <script>
        $(document).ready(function() {
            var page = 1;
            var endpoint = "{{ route('welcome') }}";
            var loading = false;
            var soundEnabled = false;

            $('#soundModal').modal('show');

            $('#muteVideos').on('click', function() {
                soundEnabled = false;
                $('#soundModal').modal('hide');
            });

            $('#playWithSound').on('click', function() {
                soundEnabled = true;
                $('#soundModal').modal('hide');
                playNextVideoWithSound();
            });

            function playNextVideoWithSound() {
                var currentVideo = $('#video-container video:visible')[0];
                var nextVideo = $(currentVideo).closest('.video-item').next().find('video')[0];
                if (nextVideo) {
                    nextVideo.muted = !soundEnabled;
                    nextVideo.play();
                    nextVideo.scrollIntoView({ behavior: 'smooth' });
                }
            }



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

                            $('.video-item video').off('click').on('click', function () {
                                this.muted = !this.muted;
                                $(this).siblings('.volume-on-icon').toggle();
                                $(this).siblings('.volume-off-icon').toggle();
                            });


                            // $('.share-btn').on('click', function() {
                            //     var videoSrc = $(this).closest('.video-item').find(
                            //         'video source').attr('src');
                            //     var shareUrl = videoSrc;
                            //     // alert(shareUrl);

                            //     $('#overlay').show();
                            //     $('#sharePopup').show();
                            // });

                            // $('#overlay, .close-popup').on('click', function() {
                            //     $('#overlay').hide();
                            //     $('#sharePopup').hide();
                            // });

                        });
                        loading = false;
                    attachVideoEndedEvent();
                    checkAndPlayVisibleVideos();
                    // setInitialVideoState();
                    },
                    error: function() {
                        console.log("Error loading more videos");
                        loading = false;
                    }
                });
            }


            loadMoreVideos(page);
            function pauseAllExceptCurrent(currentVideo) {
                $('.video-item video').each(function() {
                    if (this !== currentVideo) {
                        $(this)[0].pause();
                    }
                });
            }

            function attachVideoEndedEvent() {
                $('.video-item video').each(function() {
                    $(this).off('ended').on('ended', function() {
                        var nextVideoItem = $(this).closest('.video-item').next('.video-item');
                        if (nextVideoItem.length) {
                            var container = $('#video-container');
                            container.animate({
                                scrollTop: container.scrollTop() + nextVideoItem.position().top
                            }, 500, function() {
                                nextVideoItem.find('video')[0].play();
                            });
                        } else {
                            page++;
                            loadMoreVideos(page);
                        }
                    });
                });
            }






            function checkAndPlayVisibleVideos() {
                $('.video-item video').each(function() {
                    if ($(this).visible(true)) {
                        this.play();
                        this.muted = !soundEnabled;
                        $(this).siblings('.volume-on-icon').toggle(!this.muted);
                        $(this).siblings('.volume-off-icon').toggle(this.muted);
                    } else {
                        this.pause();
                    }
                });
            }

            function togglePlayPause(button, video) {
                if (video.paused) {
                    video.play();
                    button.hide();
                } else {
                    video.pause();
                    button.show();
                }
            }

            function toggleMute(video, button) {
                video.muted = !video.muted;
                button.text(video.muted ? '🔇' : '🔊');
            }

            attachVideoEndedEvent();

            $('#video-container').on('scroll', function() {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 100 && !loading) {
                    page++;
                    loadMoreVideos(page);
                }
                checkAndPlayVisibleVideos();
                // pauseAllVideos();
            });
            $('.play-pause-btn').on('click', function() {
                var video = $(this).siblings('video')[0];
                togglePlayPause($(this), video);
            });


            $('.video-item video').on('click', function() {
                togglePlayPause($(this).siblings('.play-pause-btn'), this);
                toggleMute(this, $(this).siblings('.volume-on-icon'));
            });


            $(document).on('scroll', function() {
                checkAndPlayVisibleVideos();
            });

            $('#video-container').on('play', 'video', function() {
                pauseAllExceptCurrent(this);
            });

            $('#video-container').on('click', '.video', function() {
                this.muted = !this.muted;
            });
            $('#video-container').on('click', '.share-btn', function() {
                var videoSrc = $(this).closest('.video-item').find('video source').attr('src');
                var shareUrl = videoSrc;
                $('#overlay').show();
                $('#sharePopup').show();
            });

            $('#overlay, .close-popup').on('click', function() {
                $('#overlay').hide();
                $('#sharePopup').hide();
            });



            $('#video-container').on('click', '.likebtn', function() {
                $(this).toggleClass('liked');
                var likedSvg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>' +
                '</svg>';

                var likedunlikedSvg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2"' +
                '</svg>';

                if ($(this).hasClass('liked')) {
                    $(this).html(likedSvg);
                } else {
                    $(this).html(likedunlikedSvg);
                }

            });

            checkAndPlayVisibleVideos();


            // Optional: Play video when in view
            // $(document).on('scroll', function() {
            //     $('.video-item video').each(function() {
            //         var video = $(this)[0];
            //         var videoTop = $(this).offset().top;
            //         var videoBottom = videoTop + $(this).outerHeight();
            //         var viewportTop = $(window).scrollTop();
            //         var viewportBottom = viewportTop + $(window).height();

            //         if (videoBottom > viewportTop && videoTop < viewportBottom) {
            //             video.play();
            //             pauseAllExceptCurrent(video);
            //         } else {
            //             video.pause();
            //         }
            //     });
            // });
        });
    </script>
</body>

</html>
