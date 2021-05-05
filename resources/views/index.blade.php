<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      ACL - {{ __('acl::view.users_groups') }}
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="flex flex-col">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    #
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    {{ __('acl::view.group') }}
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                    {{ __('acl::view.description') }}
                  </th>
                  <th scope="col" class="px-6 py-4 whitespace-nowrap text-right">
                    <a href="{{ route(aclPrefixRoutName() . 'create') }}"
                      class="py-1 px-2 font-semibold rounded shadow-md text-white text-sm bg-blue-500 hover:bg-blue-600">
                      {{ __('acl::view.new') }}
                    </a>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($groups as $group)
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $group->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $group->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $group->description }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <a href="{{ route(aclPrefixRoutName() . 'edit', ['id' => $group->id]) }}"
                        class="py-1 px-2 mr-2 font-semibold rounded shadow-md text-white text-xs bg-yellow-500 hover:bg-yellow-600">
                        {{ __('acl::view.edit') }}
                      </a>
                      <button type="button"
                        class="py-1 px-2 font-semibold rounded shadow-md text-white text-xs bg-red-500 hover:bg-red-600"
                        onclick="openAclModal({{ $group->id }})">
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

  <script type="text/javascript">
    let aclModalElement = null;

    var openAclModal = function(id) {
      aclModalElement = document.getElementById('acl-modal-' + id);
      aclModalElement.classList.remove('hidden');
    }

    var closeAclModal = function(id) {
      aclModalElement = document.getElementById('acl-modal-' + id);
      aclModalElement.classList.add('hidden');
    }

  </script>
</x-app-layout>
