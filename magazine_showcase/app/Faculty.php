<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = ['name','slug'];
    protected $table = 'faculties';
    public function faculty_users()
    {
        return $this->belongsToMany('App\User', 'faculty_user',
            'faculty_id', 'user_id');
    }
}
