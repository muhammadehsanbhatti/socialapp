@if (isset($data->id))
    @section('title', 'Update upload video')
@else
    @section('title', 'Add upload video')
@endif
@extends('layouts.master_dashboard')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">

    </div>
    <div class="content-body">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data->id)? 'Update video':'Add video' }}</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('upload_social_video.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')

                            @else
                                <form class="form" action="{{ route('upload_social_video.store') }}" method="POST" enctype="multipart/form-data">

                            @endif
                                @csrf
                                <div class="row">

                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="title">Video Title</label>
                                            <input value="{{old('title', isset($data->title)? $data->title: '')}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Video Title" name="title">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (isset($data->path) && !empty($data->path))
                                        <div class="col-md-6 col-12">
                                            <label for="upload_file">Social Video</label>
                                            <div class="">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text basic-addon">
                                                        <div class="display_images preview_upload_file">
                                                            <a data-fancybox href="{{ asset($data->path) }}" data-caption="{{ $data->name }}">
                                                                <video width="100" height="100" controls>
                                                                    <source src="{{ asset($data->path) }}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            </a>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-12 {{  isset($data->path) ? 'col-md-6' :'col-md-12' }} ">
                                        <label for="upload_file">Social Video</label>
                                        <div class="input-group">
                                            <input type="file" id="upload_file" data-img-val="preview_upload_file" class="form-control @error('upload_file') is-invalid @enderror" placeholder="Upload video" name="upload_file">
                                            @error('upload_file')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="col-md-12 col-12 mt-2">
                                        <div class="form-group">
                                            <label for="description">Descripe about video</label>
                                            <textarea data-length="120" name="description" class="form-control char-textarea" id="textarea-counter"
                                                rows="5" placeholder="Description" value="{{old('description', isset($data->description)? $data->description: '')}}">{!! @$data->description !!}
                                            </textarea>
                                            <small class="counter-value float-right"><span class="char-count">108</span> / 120
                                            </small>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
                                        <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

<script>
    $(document).ready(function() {
        $('[data-fancybox]').fancybox({
            video: {
                autoStart: true,
            },
        });
    });
    </script>
