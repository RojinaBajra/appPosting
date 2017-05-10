<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    /** Overwriting model fields */
    protected $fillable = ['topic', 'description'];

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    protected $dates = ['deleted_at'];

     public function comment()
    {
        return $this->hasMany('App\Repositories\Models\Comment')->orderBy('created_at', 'desc');
    }



 }   