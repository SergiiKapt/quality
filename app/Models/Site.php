<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    //
    protected $fillable = ['user_id', 'url', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function script()
    {
        return $this->belongsToMany(Script::class, 'user_id');
    }

}
