<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Sr #</th>
                {{-- <th>Status</th> --}}
                <th>Title</th>
                <th>Video Name</th>
                <th>File Size</th>
                <th>File Extension</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php $any_permission_found = false; @endphp
            @foreach ($data['social_video_record'] as $key => $item)

                @php
                    $sr_no = $key + 1;
                    if ($data['social_video_record']->currentPage()>1) {
                        $sr_no = ($data['social_video_record']->currentPage()-1)*$data['social_video_record']->perPage();
                        $sr_no = $sr_no + $key + 1;
                    }
                @endphp

                <tr>
                    <td>{{ $sr_no }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->extension }}</td>
                    <td>{{ $item->vedio_status }}</td>
                    <td>{{ date('M d, Y H:i A', strtotime($item->created_at)) }}</td>

                    <td>
                        @canany(['upload-social-video-edit', 'upload-social-video-delete', 'upload-social-video-status'])
                        <div class="dropdown">
                            {{-- @if ( $item->hasRole('Admin') ) --}}
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </button>
                                @php $any_permission_found = true; @endphp
                                <div class="dropdown-menu">
                                    @can('upload-social-video-edit')
                                    <a class="dropdown-item" href="{{ url('upload_social_video')}}/{{$item->id}}/edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span>Edit</span>
                                    </a>
                                    @endcan

                                    @if($data['roles'] == 1)

                                    <form class="video_status">
                                        @method('post')
                                        @csrf

                                        @php
                                        if ($item->vedio_status == 'Pending') {
                                            $video_status =  'Approved';
                                            $video_status_value =  'Approve';
                                        }
                                        elseif ($item->vedio_status == 'Approved') {
                                            $video_status =  'NotApprove';
                                            $video_status_value =  'Not Approve';
                                        }
                                        elseif ($item->vedio_status == 'NotApprove') {
                                            $video_status =  'Pending';
                                            $video_status_value =  'Pending';
                                        }

                                        @endphp
                                         <input type="hidden" name="id" value="{{ $item['id'] }}">
                                        <button type="button" name="vedio_status" value="{{ $video_status }}" class="dropdown-item" id="video_status" style="width:100%">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check mr-50"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span>{{ $video_status_value }}</span>
                                        </button>
                                    </form>

                                    <form class="add_adsterra_code">
                                        @method('post')
                                        @csrf

                                        @php
                                            $adsterra_value_text = "Add adsterra";
                                            $adsterra_value = (config('app.ADSTERRA_CODE'));
                                            if (isset($item->adsterra_code)){
                                                $adsterra_value_text = "Remove adsterra";
                                                $adsterra_value = "NULL";
                                            }
                                        @endphp
                                        <input type="hidden" name="id" value="{{ $item['id'] }}">
                                        <button type="button" name="adsterra_code"  value="{{ $adsterra_value }}"  class="dropdown-item" id="adsterra_code" style="width:100%">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check mr-50"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span>{{ $adsterra_value_text }}</span>
                                        </button>
                                    </form>

                                    @endif

                                    @can('upload-social-video-delete')
                                        <form action="{{ url('upload_social_video/'.$item['id']) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="dropdown-item" id="delButton" style="width:100%">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                <span>Delete</span>

                                            </button>
                                        </form>
                                    @endcan



                                </div>
                            {{-- @endif --}}
                        </div>
                        @endcanany
                        @if (!$any_permission_found)
                            {{ 'Not Available' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination_links">
        {{-- {!! $data['social_video_record']->links() !!} --}}
        @if (isset($data['social_video_record']) && count($data['social_video_record'])>0)
            {{ $data['social_video_record']->links('vendor.pagination.bootstrap-4') }}
        @else
            <div class="alert alert-primary">Don't have records!</div>
        @endif

    </div>

</div>
@section('upload_video_script')

<script>
    $(document).ready(function(){
         $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // change video status
    $(document).on('click', '#video_status', function(event){
        event.preventDefault();
        var button = $(this);
        var form = button.closest('form');
        var id = form.find('input[name="id"]').val();
        var vedio_status = button.val();
        $.ajax({
            url: "{{ url('upload_social_video_verify') }}/" + id,
            type: 'POST',
            data: {
                id: id,
                vedio_status: vedio_status
            },

            // context: this,
            dataType: 'json',
            success: function(data) {

                swal({
                    title: `Video status updated successfully`,
                    icon: "success",
                    buttons:"OK",
                    dangerMode: false,
                })
                .then(function(){
                    location.reload();
                    // button.find('span').text(data.new_status);
                    // form.find('input[name="vedio_status"]').val(data.new_status == 'Approve' ? 'Approved' : 'NotApprove');
                });
            },
            error: function(xhr) {
                alert('An error occurred.');
            }
        });
    });

    // Add adsterra code
    $(document).on('click', '#adsterra_code', function(event){

        event.preventDefault();
        var button = $(this);
        var form = button.closest('form');
        var id = form.find('input[name="id"]').val();
        var adsterra_code = button.val();
        $.ajax({
            url: "{{ url('add_adsterra') }}/" + id,
            type: 'POST',
            data: {
                id: id,
                adsterra_code: adsterra_code
            },
            // context: this,
            dataType: 'json',
            success: function(data) {
                swal({
                    title: `Adsterra code updated successfully`,
                    icon: "success",
                    buttons:"OK",
                    dangerMode: false,
                })
                .then(function(){
                    location.reload();
                });
            },
            error: function(xhr) {
                alert('An error occurred.');
            }
        });
    });
});
</script>
@endsection
