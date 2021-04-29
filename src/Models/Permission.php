<?php

namespace TJGazel\LaravelDocBlockAcl\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Permission extends Model
{
	public $timestamps = false;

	protected $fillable = ['name', 'resource', 'action'];

	public function groups()
	{
		return $this->belongsToMany(
			Config::get('acl.model.group'),
			Config::get('acl.table.group_permission'),
			'permission_id',
			'group_id'
		);
	}
}
