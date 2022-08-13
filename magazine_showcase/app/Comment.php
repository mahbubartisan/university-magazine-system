<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id','contribution_id','comment'];
    protected $table = 'comments';
    public function comment_user(){
        return $this->hasMany('App\User', 'id');
    }
}
