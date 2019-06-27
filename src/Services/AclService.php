<?php

namespace TJGazel\LaravelDocBlockAcl\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use TJGazel\LaravelDocBlockAcl\Facades\Acl;

class AclService
{
    /**
     * Synchronizes the mapping of driver permissions to the database.
     * Sincroniza o mapeamento das permissões dos controladores com o banco de dados.
     *
     * @return void
     * @throws \Exception
     */
    public function permissionsSync()
    {
        $mapPermissions = Acl::mapPermissions();

        if ($mapPermissions->count()) {

            try {
                DB::beginTransaction();

                $this->removeUnused($mapPermissions);

                $this->updateOrCreatePermission($mapPermissions);

                DB::commit();
            } catch (\Exception $e) {

                DB::rollBack();

                throw new \Exception($e->getMessage());
            }
        }
    }

    /**
     * Search and remove permissions on the database that are no longer mapped by the system.
     * Busca e remove permissões no banco de dados que não são mais mapeadas pelo sistema.
     *
     * @param Collection $mapPermissions
     * @return void
     */
    private function removeUnused(Collection $mapPermissions)
    {
        $permissionModel = Config::get('acl.model.permission');

        $unusedPermissions = $permissionModel::all()->filter(function ($permission) use ($mapPermissions) {

            $equals = false;

            foreach ($mapPermissions as $mapPermission) {

                if ($permission->action == $mapPermission['action']) {

                    $equals = true;
                }
            }

            return !$equals;
        });

        if ($unusedPermissions->count()) {

            foreach ($unusedPermissions as $permission) {

                if ($permission->groups->count()) {

                    $permission->groups()->detach();
                }

                $permission->delete();
            }
        }
    }

    /**
     * Updates or Creates permissions on the database according to the system mapping.
     * Atualiza ou Cria permissões no banco de dados de acordo com o mapeamento do sistema.
     *
     * @param Collection $mapPermissions
     * @return void
     */
    private function updateOrCreatePermission(Collection $mapPermissions)
    {
        $permissionModel = Config::get('acl.model.permission');

        $mapPermissions->each(function ($permission) use ($permissionModel) {

            $permissionModel::updateOrCreate(
                ['action' => $permission['action']],
                ['name' => $permission['name'], 'resource' => $permission['resource']]
            );
        });
    }
}
