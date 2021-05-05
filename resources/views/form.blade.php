<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      @if ($form['type'] == 'create')
        ACL - {{ __('acl::view.new_group') }}
      @else
        ACL - {{ __('acl::view.editing_group', ['group' => $group->name]) }}
      @endif
    </h2>
  </x-slot>

  <div class="max-w-7xl rounded mx-auto px-4 sm:px-6 lg:px-8 pt-8 bg-white">
    <form action="{{ $form['action'] }}" method="post">
      @csrf @method( $form['method'] )

      <div class="mb-4">
        <x-label for="name" :value="__('acl::view.name')" />
        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
          :value="old('name') ?? ($group->name ?? '')" autofocus />
        @if ($errors->has('name'))
          <span class="text-red-500 text-sm">{{ $errors->first('name') }}</span>
        @endif
      </div>

      <div class="mb-4">
        <x-label for="description" :value="__('acl::view.description')" />
        <x-input id="description" class="block mt-1 w-full" type="text" name="description"
          :value="old('description') ?? ($group->description ?? '')" autofocus />
        @if ($errors->has('description'))
          <span class="text-red-500 text-sm">{{ $errors->first('description') }}</span>
        @endif
      </div>

      <h3 class="h4 my-3 border-bottom">{{ __('acl::view.group_permissions') }}</h3>

      @foreach ($resourcesPermissions as $resource => $permissions)
        <fieldset class="border px-3 pb-2 mb-6 shadow roudend">
          <legend class="border bg-light py-1 pr-4 font-bold text-xl roudend">
            <div class="custom-control custom-checkbox ml-3">
              <input type="checkbox" id="customCheck{{ $resource }}" data-title="{{ $resource }}"
                class="custom-control-input toggle-box rounded" onclick="selectAll(event)">
              <label class="custom-control-label" for="customCheck{{ $resource }}">
                {{ $resource }}
              </label>
            </div>
          </legend>
          <div class="flex">
            @foreach ($permissions as $permission)
              <div class="w-6/12 lg:w-3/12 xl:w-4/12 my-6">
                <input type="checkbox" name="permissions[{{ $permission->id }}]" value="{{ $permission->id }}"
                  id="customCheck{{ $permission->id }}" class="custom-control-input {{ $resource }} rounded"
                  {{ (old("permissions[{$permission->id}]") ? 'checked' : isset($group) && $group->permissions->where('id', $permission->id)->count() > 0) ? 'checked' : '' }}>
                <label class="custom-control-label" for="customCheck{{ $permission->id }}">
                  {{ $permission->name }}
                </label>
              </div>
            @endforeach
          </div>
        </fieldset>
      @endforeach

      <div class="text-right py-6">
        <a href="{{ route(aclPrefixRoutName() . 'index') }}"
        class="rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
          {{ __('acl::view.cancel') }}
        </a>&nbsp;&nbsp;&nbsp;
        <button type="submit"
          class="rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-gray-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
          {{ __('acl::view.save') }}
        </button>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    window.onload = function() {
      Array.from(document.getElementsByClassName('toggle-box')).forEach(function(toggle) {
        var all = true;
        Array.from(document.getElementsByClassName(toggle.dataset.title)).forEach(function(permission) {
          if (!permission.checked) {
            all = false;
          }
        });
        if (all) {
          toggle.checked = true;
        }
      });
    };

    function selectAll(event) {
      var toggle = event.target;
      Array.from(document.getElementsByClassName(toggle.dataset.title)).forEach(function(permission) {
        if (toggle.checked) {
          permission.checked = true;
        } else {
          permission.checked = false;
        }
      });
    };

  </script>
</x-app-layout>
