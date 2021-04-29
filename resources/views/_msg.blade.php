@if (session(config('acl.session_error')))
  <div class="alert alert-danger alert-dismissible fade show my-1" role="alert">
    {{ session(config('acl.session_error')) }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if (session(config('acl.session_success')))
  <div class="alert alert-success alert-dismissible fade show my-1" role="alert">
    {{ session(config('acl.session_success')) }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
