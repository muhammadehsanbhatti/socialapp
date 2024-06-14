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

                                    @if($data['roles'] == 1)
                                    <form action="{{ url('upload_social_video/'.$item['id']) }}" method="post">
                                        @method('post')
                                        @csrf
                                        <button type="submit" class="dropdown-item" id="delButton" style="width:100%">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check mr-50"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span>{{ $item->vedio_status == 'Pending'? 'Approve': 'Not Approve' }}</span>

                                        </button>
                                    </form>
                                    @endif
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
