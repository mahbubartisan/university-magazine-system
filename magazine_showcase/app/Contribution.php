<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = ['user_id','title','file','type','status','contribution_setting_id','m_coordinator_notify','faculty_id'];
    protected $table = 'contributions';
    public function file_user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    public function contribution_contribution_settings(){
        return $this->belongsTo('App\ContributionSetting', 'contribution_settings_id');
    }
    public function contribution_comment(){
        return $this->hasMany('App\Comment', 'contribution_id');
    }
}
