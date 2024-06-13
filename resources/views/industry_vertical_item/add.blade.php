@if (isset($data->id))
    @section('title', 'Update Industry Verticals')
@else
    @section('title', 'Add Industry Verticals')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Industry Verticals Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form industry_vertical_submit" action="{{ route('industry_vertical_item.update', $data->id) }}" method="post">
                                @method('PUT')
                                
                            @else
                                <form class="form industry_vertical_submit" action="{{ route('industry_vertical_item.store') }}" method="POST">
                                
                            @endif
                                @csrf
                                
                                    <input class="form-control" type="hidden" name="industry_title_status" value="5">
                                <div class="add_industry_div">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                 <div class="input-group mb-1">

                                                    <input class="form-control @error("role_type") is-invalid @enderror" type="text"name="industry_title[1]" id="icon"  placeholder = "Industry vertical" value="{{old('title', isset($data->title)? $data->title: '')}}" required>

                                                    <span class="input-group-text add_industry_vertical_item" role-type-count="1" id="basic-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                                        </svg></span>
                                                </div>
                                                @error('title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
                                        <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                                        <button type="button" class="btn btn-primary mr-1 waves-effect waves-float waves-light add_industry float-right">Add Industry</button>
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
