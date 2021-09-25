<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    protected $table = 'surveys_results';
    protected $fillable = [
        'survey_id', 'user_id', 'ip_address', 'json',
    ];
    protected $casts = [
        'json'  =>  'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey()
    {
        return $this->belongsTo('App\Models\Survey', 'survey_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
