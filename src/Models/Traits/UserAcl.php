<?php

namespace TJGazel\LaravelDocBlockAcl\Models\Traits;

use Illuminate\Support\Facades\Config;
use TJGazel\LaravelDocBlockAcl\Models\Permission;

/**
 * Trait UserAcl
 * @package TJGazel\LaravelDocBlockAcl\Models\Traits
 */
trait UserAcl
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(
            Config::get('acl.model.group'),
            Config::get('acl.table.group_user'),
            'user_id',
            'group_id'
        );
    }

    /**
     * @param \TJGazel\LaravelDocBlockAcl\Models\Permission $permission
     * @return bool
     */
    public function hasAclPermission(Permission $permission)
    {
        $groups = $permission->groups;

        if ($this->groups()->count()) {
            $userGroupIds = $this->groups()->pluck('id')->toArray();

            foreach ($groups as $group) {
                if (in_array($group->id, $userGroupIds)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param int|array $id
     * @return bool
     */
    public function hasAclGroup($id)
    {
        if (is_array($id)) {
            return $this->groups()->whereIn('id', $id)->count();
        }

        return $this->groups()->where('id', $id)->count();
    }
}
