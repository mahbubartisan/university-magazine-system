<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContributionSetting extends Model
{
    protected $fillable = ['academic_year','start_date','closure_date','final_closure_date'];
    protected $table = 'contribution_settings';
}
