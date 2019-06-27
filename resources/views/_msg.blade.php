@if( session('acl-error') )
  <div class="alert alert-danger alert-dismissible fade show my-1" role="alert">
    {{ session('acl-error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

@if( session('acl-success') )
  <div class="alert alert-success alert-dismissible fade show my-1" role="alert">
    {{ session('acl-success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif