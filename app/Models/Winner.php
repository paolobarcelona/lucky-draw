<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Winner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['draw_attempt_id', 'user_id', 'winning_number_id'];

    /** 
     * Returns the user for this winner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user(): Relation
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /** 
     * Returns the draw attempt for this winner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function drawAttempt(): Relation
    {
        return $this->belongsTo('App\Models\DrawAttempt', 'draw_attempt_id');
    }
}
