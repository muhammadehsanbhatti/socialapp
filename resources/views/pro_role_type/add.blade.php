@if (isset($data->id))
@section('title', 'Update Professional Role')
@else
@section('title', 'Add Professional Role')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Professional Role Detail</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul></ul>
                            </div>
                            <div class="alert alert-success" role="alert" hidden></div>

                            @if (Session::has('message'))
                            <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                            <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                            <form class="form professional_role_submit" action="{{ route('professional_role_type.update', $data->id) }}" method="post">
                                @method('PUT')

                                @else
                                <form class="form professional_role_submit" action="{{ route('professional_role_type.store') }}" method="POST" enctype="multipart/form-data">

                                    @endif
                                    @csrf
                                    <input class="form-control" type="hidden" name="prof_title_status" value="2">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <label for="prof_title">Professional Role</label>
                                            @if(isset($data))
                                            <div class="input-group mb-2">
                                                <input id="prof_title" type="text" class="form-control @error('prof_title') is-invalid @enderror" placeholder="Enter professional role title" aria-label="Enter professional role title" name="prof_title" aria-describedby="basic-addon2" value="{{ $data['generalTitle']['title'] }}" disabled>
                                                {{-- <span class="input-group-text add_prof_type_btn" id="basic-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                                    </svg></span> --}}
                                                @error('prof_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            @else

                                            <div class="input-group mb-2">
                                                <input id="prof_title" type="text" class="form-control @error('prof_title') is-invalid @enderror" placeholder="Enter professional role title" aria-label="Enter professional role title" name="prof_title" aria-describedby="basic-addon2">
                                                <span class="input-group-text add_prof_type_btn" id="basic-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                                    </svg></span>
                                                @error('prof_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            @endif
                                        </div>

                                        <div class="col-md-12 col-12">
                                            <div class="add_professional_role">


                                                @if (isset($data))
                                                    <div class="row">
                                                        <div class="col-md-5 col-12">
                                                            <label for="prof_title">Professional Role Type</label>
                                                            <div class="input-group mb-1">

                                                                <input class="form-control @error("role_type") is-invalid @enderror" type="text" name="role_type" id="icon" placeholder="Enter role item here" value="{{ $data['title'] }}" required>

                                                                <span class="input-group-text add_prof_item_btn" role-type-count="1" id="basic-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                                        <circle cx="12" cy="12" r="10"></circle>
                                                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                                                    </svg></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  
                                                    @foreach($data['profRoleTypeItem'] as $pro_role_type_item_key => $pro_role_type_item_value)
                                                    
                                                        <div class="row ml-2"  id={{ $pro_role_type_item_value['id'] }}>
                                                            <div class="col-md-5  col-12">
                                                                <div class="input-group mb-1">
                                                                    <input class="form-control @error("update_role_item") is-invalid @enderror" type="text" name="update_role_item[{{ $pro_role_type_item_key }}]" placeholder="Enter role item here" id="icon" value="{{ $pro_role_type_item_value['title'] }}" required>

                                                                    <span class="input-group-text remove" type="button" id="delete_role_items" id-attr="{{$pro_role_type_item_value['id']}}" action-attr="{{ url('destroy_professional_role_type_items/'.$pro_role_type_item_value['id']) }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                
                                                {{-- <div class="row"> --}}
                                                    <div class="col-md-5 col-12">
                                                        <div class="input-group mb-2" style="margin-left:6px">
                                                            <input type="text" class="form-control @error(" role_type") is-invalid @enderror" placeholder="Enter role type here" name="role_type[1]" aria-describedby="basic-addon2" required>
                                                            <span class="input-group-text add_prof_item_btn" role-type-count="1" id="basic-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                                </svg></span>
                                                            @error('role_type')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                {{-- </div> --}}
                                                @endif
                                            </div>

                                            
                                        </div>
                                        
                                   
                                    </div>
                                     <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
                                        <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
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
