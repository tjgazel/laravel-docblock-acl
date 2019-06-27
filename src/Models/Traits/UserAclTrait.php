<?php

namespace TJGazel\LaravelDocBlockAcl\Models\traits;

use Illuminate\Support\Facades\Config;

trait UserAcltrait
{
    public function group()
    {
        return $this->belongsTo(Config::get('acl.model.group'), 'group_id', 'id');
    }
}
