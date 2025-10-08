@extends('admin.layouts.layout')

@section('title', 'Roles')
@section('admin')
@section('pagetitle', 'Role')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Role {{$role->name}}</h4>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary btn-sm">{{ __('Back') }}</a>
                </div>
                <div class="card-body mt-3">
                    <form action="{{ route('admin.roles.updatePermissions', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <table class="table table-bordered table-striped align-middle text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Module</th>
                                    @foreach($actions as $action)
                                    <th>{{ ucfirst($action) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module => $permissionGroup)
                                <tr>
                                    <td class="text-start fw-bold">{{ ucfirst($module) }}</td>
                                    @foreach($actions as $action)
                                    <td>
                                        @if(isset($permissionGroup[$action]))
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" role="switch"name="permission[]"
                                            value="{{ $permissionGroup[$action]['id'] }}"
                                            {{ $permissionGroup[$action]['checked'] ? 'checked' : '' }}>
                                        </div>
                                        @else
                                        <span class="text-muted">–</span>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @error('permission')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="mb-3 mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
