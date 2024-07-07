@foreach ($data as  $index =>$video_detail)
    <div class="video-item">
        <video autoplay muted>
            <source src="{{ asset($video_detail->path) }}" type="video/mp4">
        </video>
        {{-- <button class="play-pause-btn">▶️</button> --}}

        <div class="video-actions">
            {{-- <button class="like-btn">❤️</button> --}}
            @php
                $liked = false; // Replace with your condition to determine if video is liked
            @endphp
            <button class="likebtn {{ $liked ? 'liked' : '' }}">
                {!! $liked ? '&#x2665;' : '&#x2661;' !!}
            </button>
            <button class="share-btn">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256"
                    width="48px" height="48px">
                    <g fill-opacity="0" fill="#FFFFFF" fill-rule="nonzero" stroke="none" stroke-width="1"
                        stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray=""
                        stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none"
                        style="mix-blend-mode: normal">
                        <g transform="scale(5.33333,5.33333)">
                            <path
                                d="M36,5c-3.84823,0 -7,3.15178 -7,7c0,0.58577 0.19854,1.10946 0.33594,1.6543l-11.99023,5.99805c-1.28658,-1.57841 -3.1642,-2.65234 -5.3457,-2.65234c-3.84823,0 -7,3.15178 -7,7c0,3.84822 3.15177,7 7,7c2.1815,0 4.05912,-1.07394 5.3457,-2.65234l11.99023,5.99805c-0.13739,0.54483 -0.33594,1.06853 -0.33594,1.6543c0,3.84822 3.15177,7 7,7c3.84823,0 7,-3.15178 7,-7c0,-3.84822 -3.15177,-7 -7,-7c-2.1815,0 -4.05912,1.07394 -5.3457,2.65234l-11.99023,-5.99805c0.13739,-0.54483 0.33594,-1.06853 0.33594,-1.6543c0,-0.58577 -0.19854,-1.10946 -0.33594,-1.6543l11.99023,-5.99805c1.28658,1.57841 3.1642,2.65234 5.3457,2.65234c3.84823,0 7,-3.15178 7,-7c0,-3.84822 -3.15177,-7 -7,-7zM36,8c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4zM12,20c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4zM36,32c2.22691,0 4,1.77309 4,4c0,2.22691 -1.77309,4 -4,4c-2.22691,0 -4,-1.77309 -4,-4c0,-2.22691 1.77309,-4 4,-4z"
                                fill="#FFFFFF" stroke="#FFFFFF" stroke-width="2"></path>
                        </g>
                    </g>
                </svg>
            </button>
            <div class="overlay" id="overlay"></div>
            <div class="share-popup" id="sharePopup">

            <div class="modal-dialog">

                <div class="modal-content">
                <div class="modal-header">
                    <a href="https://api.whatsapp.com/send?text={{ asset($video_detail->path) }}" target="_blank">
                        <svg fill="#000000" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
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
                                    C233.168,179.508,230.845,178.393,227.904,176.981z"/>
                                <path id="XMLID_470_" d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716
                                    c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396
                                    c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z
                                    M156.734,268.994c-23.539,0-46.338-6.797-65.936-19.657c-0.659-0.433-1.424-0.655-2.194-0.655c-0.407,0-0.815,0.062-1.212,0.188
                                    l-40.035,12.726l12.924-38.129c0.418-1.234,0.209-2.595-0.561-3.647c-14.924-20.392-22.813-44.485-22.813-69.677
                                    c0-65.543,53.754-118.867,119.826-118.867c66.064,0,119.812,53.324,119.812,118.867
                                    C276.546,215.678,222.799,268.994,156.734,268.994z"/>
                            </g>
                            </svg>


                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ asset($video_detail->path) }}" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" height="30px" width="30px" version="1.1" id="Layer_1">    <path d="M25,3C12.85,3,3,12.85,3,25c0,11.03,8.125,20.137,18.712,21.728V30.831h-5.443v-5.783h5.443v-3.848 c0-6.371,3.104-9.168,8.399-9.168c2.536,0,3.877,0.188,4.512,0.274v5.048h-3.612c-2.248,0-3.033,2.131-3.033,4.533v3.161h6.588 l-0.894,5.783h-5.694v15.944C38.716,45.318,47,36.137,47,25C47,12.85,37.15,3,25,3z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/?url={{ asset($video_detail->path) }}" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" height="30px" width="30px"><path d="M 16 3 C 8.8324839 3 3 8.8324839 3 16 L 3 34 C 3 41.167516 8.8324839 47 16 47 L 34 47 C 41.167516 47 47 41.167516 47 34 L 47 16 C 47 8.8324839 41.167516 3 34 3 L 16 3 z M 16 5 L 34 5 C 40.086484 5 45 9.9135161 45 16 L 45 34 C 45 40.086484 40.086484 45 34 45 L 16 45 C 9.9135161 45 5 40.086484 5 34 L 5 16 C 5 9.9135161 9.9135161 5 16 5 z M 37 11 A 2 2 0 0 0 35 13 A 2 2 0 0 0 37 15 A 2 2 0 0 0 39 13 A 2 2 0 0 0 37 11 z M 25 14 C 18.936712 14 14 18.936712 14 25 C 14 31.063288 18.936712 36 25 36 C 31.063288 36 36 31.063288 36 25 C 36 18.936712 31.063288 14 25 14 z M 25 16 C 29.982407 16 34 20.017593 34 25 C 34 29.982407 29.982407 34 25 34 C 20.017593 34 16 29.982407 16 25 C 16 20.017593 20.017593 16 25 16 z"/></svg>
                    </a>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>

                </div>
            </div>
            </div>

            <button class="download-btn">
                <a href="{{ asset($video_detail->path) }}" download>
                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#FFFFFF" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487"
                            stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                </a>

            </button>
            <button class="mute-btn">🔇</button> <!-- Mute button -->
        </div>
        <div class="volume-control">
            <input type="range" min="0" max="1" step="0.1" value="0" class="volume-slider">
        </div>
        @if ($video_detail->adsterra_code)
            <div class="adsterra-ad">
                <!-- Adsterra Code -->
                <script async src="{{ $video_detail->adsterra_code }}"></script>
            </div>
        @endif
    </div>
@endforeach

