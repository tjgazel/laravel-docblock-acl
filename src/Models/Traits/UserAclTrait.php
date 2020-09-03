<?php

namespace TJGazel\LaravelDocBlockAcl\Models\Traits;

use Illuminate\Support\Facades\Config;

trait UserAclTrait
{
    public function group()
    {
        return $this->belongsTo(Config::get('acl.model.group'), 'group_id', 'id');
	}

	public function hasAclPermission($permission)
    {
        $groups = $permission->groups;

        foreach ($groups as $group) {
            if ($this->group_id == $group->id) {
                return true;
            }
        }

        return false;
    }
}
