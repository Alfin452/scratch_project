<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    protected $fillable = ['module_id', 'title', 'content', 'order'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
