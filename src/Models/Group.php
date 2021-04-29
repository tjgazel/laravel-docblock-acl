<?php

namespace TJGazel\LaravelDocBlockAcl\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class Group
 * @package TJGazel\LaravelDocBlockAcl\Models
 */
class Group extends Model
{
	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var string[]
	 */
	protected $fillable = ['name', 'description'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function permissions()
	{
		return $this->belongsToMany(
			Config::get('acl.model.permission'),
			Config::get('acl.table.group_permission'),
			'group_id',
			'permission_id'
		);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(
			Config::get('acl.model.user'),
			Config::get('acl.table.group_user'),
			'group_id',
			'user_id'
		);
	}
}
