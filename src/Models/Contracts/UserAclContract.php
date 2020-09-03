<?php

namespace TJGazel\LaravelDocBlockAcl\Models\Contracts;

interface UserAclContract
{
	public function group();

	public function hasAclPermission($permission);
}
