
{{-- @extends('backend.layouts.master') --}}
@extends('backend.layouts-new.app')


@section('content')
<style>
    .text-left{
        float: left;
    }
    .dataTableRole{
        width: 100%;
    }
</style>

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">Roles List</h4>
                    <p class="float-right mb-2">
                        @if (Auth::guard('admin')->user()->can('role.create'))
                            <a class="btn btn-primary text-white" style="float: right" data-bs-toggle="modal" data-bs-target="#modalCreate">
                                Create</a>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.roles.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="name">Role Name</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter a Role Name">
                                            </div>
                    
                                            <div class="form-group">
                                                <label for="name">Permissions</label>

                                                <div class="row">
                                                        @php $i = 1; @endphp
                                                        <table class="table dataTableRole" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <td>Menu</td>
                                                                    <td>Permissions</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $i = 1; @endphp
                                                                @foreach ($permission_groups as $group)
                                                                <tr>
                                                                    <td>
                                                                        <label class="form-check-label" for="checkPermission">{{ $group->name }}</label>
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                        $permissions = App\User::getpermissionsByGroupName($group->name);
                                                                            $j = 1;
                                                                        @endphp
                                                                        @foreach ($permissions as $permission)
                                                                            <div class="form-check">
                                                                                <input type="checkbox" class="form-check-input" name="permissions[]" id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                                                                <label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ str_replace('.', ' ', $permission->name) }}
                                                                                </label>
                                                                            </div>
                                                                            @php  $j++; @endphp
                                                                        @endforeach
                                                                        <br>
                                                                    </td>
                                                                </tr>
                                                                @php  $i++; @endphp
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                </div>
                                                 
                    
                                                
                                            </div>
                                           
                                            
                                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Role</button>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                    </p>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">NO</th>
                                    <th width="10%">Name</th>
                                    {{-- <th width="60%">Permissions</th> --}}
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($roles as $role)
                               <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $role->name }}</td>
                                    {{-- <td>
                                        @foreach ($role->permissions as $perm)
                                            <span class="badge bg-info mr-1">
                                                {{ str_replace('.', ' ', $perm->name) }}
                                            </span>
                                        @endforeach
                                    </td> --}}
                                    <td>
                                        @if (Auth::guard('admin')->user()->can('admin.edit'))
                                            <a class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $role->id }}">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit{{ $role->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Create</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="form-group">
                                                                <label class="text-left" for="name">Role Name</label>
                                                                <input type="text" class="form-control" id="name" value="{{ $role->name }}" name="name" placeholder="Enter a Role Name">
                                                            </div>
                                    
                                                            <div class="form-group">
                                                                <label class="" for="name">Permissions</label>
                                    
                                                                {{-- <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1" {{ App\User::roleHasPermissions($role, $all_permissions) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="checkPermissionAll">All</label>
                                                                </div>
                                                                <hr> --}}
                                                                @php $i = 1; @endphp
                                                                <div class="row">
                                                                    <table class="table dataTableRole" width="100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <td>Menu</td>
                                                                                <td>Permissions</td>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($permission_groups as $group)
                                                                                @php
                                                                                    $permissions = App\User::getpermissionsByGroupName($group->name);
                                                                                    $j = 1;
                                                                                @endphp
                                                                        
                                                                                <tr>
                                                                                    <td>
                                                                                        <label class="form-check-label" for="checkPermission">{{ $group->name }}</label>
                                                                                    </td>
                                                                                    <td>
                                                                                        @foreach ($permissions as $permission)
                                                                                            <div class="form-check">
                                                                                                <label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ str_replace('.', ' ', $permission->name) }}</label>
                                                                                                <input type="checkbox" class="form-check-input" onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management', {{ count($permissions) }})" name="permissions[]" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                                                                            </div>
                                                                                            @php  $j++; @endphp
                                                                                        @endforeach
                                                                                    </td>
                                                                                </tr>
                                                                            
                                                                            @php  $i++; @endphp
                                                                        @endforeach
                                                                    
                                                                    </tbody>
                                                                </table>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4" style="float: right">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (Auth::guard('admin')->user()->can('admin.edit'))
                                            <a class="btn btn-danger text-white" href="{{ route('admin.roles.destroy', $role->id) }}"
                                            onclick="event.preventDefault(); document.getElementById('delete-form-{{ $role->id }}').submit();">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                            <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: none;">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
        
    </div>
</div>
@include('backend.pages.roles.partials.scripts')

@endsection
