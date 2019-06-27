<?php

namespace TJGazel\LaravelDocBlockAcl\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Group extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Config::get('acl.model.permission'), 'group_permission', 'group_id', 'permission_id');
    }

    public function users()
    {
        return $this->hasMany(Config::get('acl.model.user'), 'group_id', 'id');
    }
}
