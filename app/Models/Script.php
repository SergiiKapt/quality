<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    //
    protected $fillable = ['site_id', 'name', 'body'];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
