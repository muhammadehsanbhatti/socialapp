<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Sr #</th>
                <th>Title</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($data['records']) && count($data['records'])>0)
                @php $any_permission_found = false; @endphp
                @foreach ($data['records'] as $key=>$item)
                @php
                    $sr_no = $key + 1;
                    if ($data['records']->currentPage()>1) {
                            $sr_no = ($data['records']->currentPage()-1)*$data['records']->perPage();
                            $sr_no = $sr_no + $key + 1;
                        }
                @endphp
                    <tr>
                        <td>{{ $sr_no }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ date('M d, Y H:i A', strtotime($item['created_at'])) }}</td>
                        <td>{{ date('M d, Y H:i A', strtotime($item['updated_at'])) }}</td>
                        <td>
                            @canany(['professional-role-type-update', 'professional-role-type-delete'])
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </button>
                                @php $any_permission_found = true; @endphp
                                <div class="dropdown-menu">
                                    @can('professional-role-type-update')
                                    <a class="dropdown-item" href="{{ url('professional_role_type')}}/{{$item['id']}}/edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span>Edit</span>
                                    </a>
                                    @endcan

                                    @can('item-create')
                                    <a class="dropdown-item" href="{{ url('prof_add_item')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span>Create items</span>
                                    </a>
                                    @endcan
                                    
                                    @can('professional-role-type-delete')

                                    <form action="{{ url('professional_role_type/'.$item['id']) }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button class="dropdown-item" id="delButton" type="submit" style="width: 100%">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Delete</span>

                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                            @endcanany
                            @if (!$any_permission_found)
                                {{ 'Not Available' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    
    <div class="permission_links">
        @if (isset($data['records']) && count($data['records'])>0)
            {{ $data['records']->links('vendor.pagination.bootstrap-4') }}
        @else
            <div class="alert alert-primary">Don't have records!</div>
        @endif
    </div>
    
</div>