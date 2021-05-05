@if (session(config('acl.session_error')))
  <div id="acl-alert-error"
    class="flex justify-between items-center bg-red-500 relative text-gray-200 py-3 px-3 rounded">
    <div>
      {{ session(config('acl.session_error')) }}
    </div>
    <div>
      <button type="button" class="text-gray-200" onclick="closeAclAlert('acl-alert-error')">
        <span class="text-2xl text-gray-200">&times;</span>
      </button>
    </div>
  </div>
@endif

@if (session(config('acl.session_success')))
  <div id="acl-alert-success"
    class="flex justify-between items-center bg-green-500 relative text-gray-200 py-3 px-3 rounded">
    <div>
      {{ session(config('acl.session_success')) }}
    </div>
    <div>
      <button type="button" class="text-gray-200" onclick="closeAclAlert('acl-alert-success')">
        <span class="text-2xl text-gray-200">&times;</span>
      </button>
    </div>
  </div>
@endif

<script type="text/javascript">
  var closeAclAlert = function(id) {
    aclAlertElement = document.getElementById(id);
    aclAlertElement.classList.add('hidden');
  }

</script>
