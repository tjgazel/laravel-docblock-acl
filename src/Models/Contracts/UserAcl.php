<?php

namespace TJGazel\LaravelDocBlockAcl\Models\Contracts;

/**
 * Interface UserAcl
 * @package TJGazel\LaravelDocBlockAcl\Models\Contracts
 */
interface UserAcl
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
	public function hasAclGroup($id);
}
