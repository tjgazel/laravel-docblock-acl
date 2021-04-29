<div class="modal fade" id="aclModal{{ $group->id }}" tabindex="-1" role="dialog"
  aria-labelledby="aclModal{{ $group->id }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="aclModal{{ $group->id }}Label">
          {{ __('acl::view.confirm_delete_title', ['group' => $group->name]) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-left">
        <form id="form-{{ $group->id }}"
          action="{{ route(aclPrefixRoutName() . 'destroy', ['id' => $group->id]) }}" method="post">
          @csrf @method('DELETE')
          @if ($group->users()->count())
            <p>{{ __('acl::view.confirm_delete_msg1') }}</p>
            <p>
              <select name="group_new_assoc" class="form-control">
                @foreach ($groups as $g)
                  @if ($g->id != $group->id)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                  @endif
                @endforeach
              </select>
            </p>
          @else
            <p>{{ __('acl::view.confirm_delete_msg2') }}</p>
          @endif
          <div class="text-right">
            <button type="button" class="btn btn-outline-secondary"
              data-dismiss="modal">{{ __('acl::view.cancel') }}</button>
            <button type="submit" class="btn btn-outline-danger">{{ __('acl::view.delete') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
