
@foreach($data as $video_detail)
<div class="video-item">
    <video controls autoplay muted>
        <source src="{{ asset($video_detail->path) }}" type="video/mp4">
    </video>
    {{-- <button class="play-pause-btn">▶️</button> --}}

            <div class="video-actions">
                <button class="like-btn">❤️</button>
                <button class="share-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="48px" height="48px"><g fill-opacity="0" fill="#FFFFFF" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.33333,5.33333)"><path d="M36,5c-3.84823,0 -7,3.15178 -7,7c0,0.58577 0.19854,1.10946 0.33594,1.6543l-11.99023,5.99805c-1.28658,-1.57841 -3.1642,-2.65234 -5.3457,-2.65234c-3.84823,0 -7,3.15178 -7,7c0,3.84822 3.15177,7 7,7c2.1815,0 4.05912,-1.07394 5.3457,-2.65234l11.99023,5.99805c-0.13739,0.54483 -0.33594,1.06853 -0.33594,1.6543c0,3.84822 3.15177,7 7,7c3.84823,0 7,-3.15178 7,-7c0,-3.84822 -3.15177,-7 -7,-7c-2.1815,0 -4.05912,1.07394 -5.3457,2.65234l-11.99023,-5.99805c0.13739,-0.54483 0.33594,-1.06853 0.33594,-1.6543c0,-0.58577 -0.19854,-1.10946 -0.33594,-1.6543l11.99023,-5.99805c1.28658,1.57841 3.1642,2.65234 5.3457,2.65234c3.84823,0 7,-3.15178 7,-7c0,-3.84822 -3.15177,-7 -7,-7zM36,8c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4zM12,20c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4zM36,32c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4z" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="2"></path></g></g></svg>
                </button>
                <div class="overlay" id="overlay"></div>
                <div class="share-popup" id="sharePopup">
                    <button class="close-popup">Close</button>
                    <button id="whatsappShare">
                        <svg fill="#FFFFFF" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 308 308" xml:space="preserve">
                            <g id="XMLID_468_">
                                <path id="XMLID_469_" d="M227.904,176.981c-0.6-0.288-23.054-11.345-27.044-12.781c-1.629-0.585-3.374-1.156-5.23-1.156 c-3.032,0-5.579,1.511-7.563,4.479c-2.243,3.334-9.033,11.271-11.131,13.642c-0.274,0.313-0.648,0.687-0.872,0.687 c-0.201,0-3.676-1.431-4.728-1.888c-24.087-10.463-42.37-35.624-44.877-39.867c-0.358-0.61-0.373-0.887-0.376-0.887 c0.088-0.323,0.898-1.135,1.316-1.554c1.223-1.21,2.548-2.805,3.83-4.348c0.607-0.731,1.215-1.463,1.812-2.153 c1.86-2.164,2.688-3.844,3.648-5.79l0.503-1.011c2.344-4.657,0.342-8.587-0.305-9.856c-0.531-1.062-10.012-23.944-11.02-26.348 c-2.424-5.801-5.627-8.502-10.078-8.502c-0.413,0,0,0-1.732,0.073c-2.109,0.089-13.594,1.601-18.672,4.802 c-5.385,3.395-14.495,14.217-14.495,33.249c0,17.129,10.87,33.302,15.537,39.453c0.116,0.155,0.329,0.47,0.638,0.922 c17.873,26.102,40.154,45.446,62.741,54.469c21.745,8.686,32.042,9.69,37.896,9.69c0.001,0,0.001,0,0.001,0 c2.46,0,4.429-0.193,6.166-0.364l1.102-0.105c7.512-0.666,24.02-9.22,27.775-19.655c2.958-8.219,3.738-17.199,1.77-20.458 C233.168,179.508,230.845,178.393,227.904,176.981z" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="2" />
                                <path id="XMLID_470_" d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716 c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396 c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z M156.734,268.994c-24.274,0-47.877-6.357-68.702-18.393c-1.187-0.688-2.582-0.871-3.898-0.505l-45.469,14.446l13.94-41.118 c0.678-2.002,0.322-4.229-0.952-5.948c-13.739-18.198-21.04-39.879-21.04-62.332c0-83.038,67.632-150.278,150.721-150.278 c83.087,0,150.722,67.24,150.722,150.278C307.455,201.754,239.821,268.994,156.734,268.994z" fill="#FFFFFF" stroke="#FFFFFF" stroke-width="2" />
                            </g>
                        </svg>
                        <span>Share on WhatsApp</span>
                    </button>
                </div>

            <button class="download-btn">
                    <a href="{{ asset($video_detail->path) }}" download>
                <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

            </a>

        </button>
        <button class="mute-btn">🔇</button> <!-- Mute button -->
            </div>
            <div class="volume-control">
                <input type="range" min="0" max="1" step="0.1" value="0" class="volume-slider">
            </div>
        @if($video_detail->adsterra_code)
            <div class="adsterra-ad">
                <!-- Adsterra Code -->
                <script async src="{{ $video_detail->adsterra_code }}"></script>
            </div>
        @endif
</div>

<script>
      $(document).ready(function() {
        $('.share-btn').on('click', function() {
            var videoSrc = $(this).closest('.video-item').find('video source').attr('src');
            var shareUrl = videoSrc;
            // alert(shareUrl);

            $('#whatsappShare').attr('href', 'https://api.whatsapp.com/send?text=' + encodeURIComponent(shareUrl));

            $('#overlay').show();
            $('#sharePopup').show();
        });

        $('#overlay, .close-popup').on('click', function() {
            $('#overlay').hide();
            $('#sharePopup').hide();
        });
    });
</script>

@endforeach

