@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-lg">
          <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h2 class="h5 m-0">{{ __('acl::view.users_groups') }}</h2>
          </div>
          <div class="card-body">
            @include('acl::_msg')
            <div class="table-responsive">
              <table class="table table-middle table-hover table-sm">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>{{ __('acl::view.group') }}</th>
                    <th>{{ __('acl::view.description') }}</th>
                    <td class="text-right">
                      <a href="{{ route(aclPrefixRoutName() . 'create') }}"
                        class="btn btn-circle btn-outline-primary btn-sm">
                        {{ __('acl::view.new') }}
                      </a>
                    </td>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($groups as $group)
                    <tr>
                      <td>{{ $group->id }}</td>
                      <td>{{ $group->name }}</td>
                      <td>{{ $group->description }}</td>
                      <td class="text-right">
                        <a href="{{ route(aclPrefixRoutName() . 'edit', ['id' => $group->id]) }}"
                          class="btn btn-outline-warning btn-sm">
                          {{ __('acl::view.edit') }}
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                          data-target="#aclModal{{ $group->id }}">
                          {{ __('acl::view.delete') }}
                        </button>
                        @include('acl::_confirm-modal-delete')
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
