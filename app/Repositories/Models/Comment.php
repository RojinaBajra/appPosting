<?php

namespace App\Repositories\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /** Overwriting model fields */
    protected $fillable = ['reply', 'post_id'];

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    protected $dates = ['deleted_at'];

     public function post()
    {
        return $this->belongsTo('App\Repositories\Models\Post');
    }




 }   