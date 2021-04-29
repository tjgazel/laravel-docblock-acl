<?php

namespace TJGazel\LaravelDocBlockAcl\Models\Contracts;

/**
 * Interface UserAclContract
 * @package TJGazel\LaravelDocBlockAcl\Models\Contracts
 */
interface UserAclContract
{
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function groups();

	/**
	 * @param $permission
	 * @return bool
	 */
	public function hasAclPermission($permission);

	/**
	 * @param $id
	 * @return bool
	 */
	public function hasGroup($id);
}
