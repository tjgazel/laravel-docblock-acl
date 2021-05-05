<?php

namespace TJGazel\LaravelDocBlockAcl\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use TJGazel\LaravelDocBlockAcl\Services\AclService;

/** @permissionResource('ACL') */
class AclController extends Controller
{
    /**
     * @var AclService
     */
    private $service;

    public function __construct(AclService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @permissionName('List')
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $this->service->permissionsSync();

        $groupModel = Config::get('acl.model.group');

        $groups = $groupModel::all();

        if ($request->ajax()) {
            return response()->json($groups);
        }

        return view('acl::index', compact(['groups']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @permissionName('Create form')
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $permissionModel = Config::get('acl.model.permission');

        $resourcesPermissions = $permissionModel::orderBy('resource')
            ->orderBy('name')
            ->get()
            ->groupBy('resource');

        if ($request->ajax()) {
            return response()->json($resourcesPermissions);
        }

        $form = [
            'type' => 'create',
            'action' => route(aclPrefixRoutName() . 'store'),
            'method' => 'POST',
        ];

        return view('acl::form', compact(['form', 'resourcesPermissions']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @permissionName('Add')
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'max:255',
        ]);

        $permissions = $request->get('permissions');

        try {
            DB::beginTransaction();

            $groupModel = Config::get('acl.model.group');

            $group = $groupModel::create($request->only(['name', 'description']));

            if ($permissions && count($permissions) > 0) {
                $group->permissions()->attach($permissions);
            }

            DB::commit();

            if ($request->ajax()) {
                $group->load('permissions');
                return response()->json($group, 201);
            }

            if (class_exists('\\TJGazel\\Toastr\\ToastrServiceProvider')) {
                toastr()->success(__('acl::view.created'));
            }

            return redirect(route(aclPrefixRoutName() . 'index'), 201)
                ->with(Config::get('acl.session_success'), __('acl::view.created'));
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json($e->getMessage(), 409);
            }

            if (class_exists('\\TJGazel\\Toastr\\ToastrServiceProvider')) {
                toastr()->error($e->getMessage());
            }

            return back()->with(Config::get('acl.session_error'), $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @permissionName('Edit form')
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $groupModel = Config::get('acl.model.group');
        $permissionModel = Config::get('acl.model.permission');

        $group = $groupModel::findOrFail($id)->load('permissions');

        $resourcesPermissions = $permissionModel::orderBy('resource')
            ->orderBy('name')
            ->get()
            ->groupBy('resource');

        if ($request->ajax()) {
            return response()->json([$group, $resourcesPermissions]);
        }

        $form = [
            'type' => 'edit',
            'action' => route(aclPrefixRoutName() . 'update', ['id' => $group->id]),
            'method' => 'PUT',
        ];

        return view('acl::form', compact(['form', 'group', 'resourcesPermissions']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @permissionName('Update')
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'max:255',
        ]);

        try {
            DB::beginTransaction();

            $groupModel = Config::get('acl.model.group');

            $group = $groupModel::findOrfail($id);

            $group->update($request->only(['name', 'description']));

            $group->permissions()->sync($request->get('permissions'));

            DB::commit();

            if ($request->ajax()) {
                return response()->json([], 201);
            }

            if (class_exists('\\TJGazel\\Toastr\\ToastrServiceProvider')) {
                toastr()->success(__('acl::view.updated'));
            }

            return redirect(route(aclPrefixRoutName() . 'index'), 201)
                ->with(Config::get('acl.session_success'), __('acl::view.updated'));
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json($e->getMessage(), 409);
            }

            if (class_exists('\\TJGazel\\Toastr\\ToastrServiceProvider')) {
                toastr()->error($e->getMessage());
            }

            return back()->with(Config::get('acl.session_error'), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @permissionName('Delete')
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $groupModel = Config::get('acl.model.group');

            $group = $groupModel::findOrfail($id);

            if ($request->has('group_new_assoc')) {
                if ($request->get('group_new_assoc') != $group->id) {
                    foreach ($group->users as $user) {
                        $user->groups()->detach($id);

                        if (!$user->hasAclGroup($request->get('group_new_assoc'))) {
                            $user->groups()->attach($request->get('group_new_assoc'));
                        }
                    }
                } else {
                    throw new \Exception(__('acl::view.equal_assoc'));
                }
            }

            $group->permissions()->detach();

            $group->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([], 201);
            }

            if (class_exists('\\TJGazel\\Toastr\\ToastrServiceProvider')) {
                toastr()->success(__('acl::view.deleted'));
            }

            return redirect(route(aclPrefixRoutName() . 'index'), 201)
                ->with(Config::get('acl.session_success'), __('acl::view.deleted'));
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json($e->getMessage(), 409);
            }

            if (class_exists('\\TJGazel\\Toastr\\ToastrServiceProvider')) {
                toastr()->error($e->getMessage());
            }

            return back()->with(Config::get('acl.session_error'), $e->getMessage());
        }
    }
}
