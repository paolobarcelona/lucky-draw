<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class WinningNumber extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['winning_number', 'user_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var mixed[]
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Returns the user for this winning number.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user(): Relation
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Returns the winner for this winning number.
     *
     * @return null|\Illuminate\Database\Eloquent\Relations\Relation
     */
    public function winner(): ?Relation
    {
        return $this->has('App\Models\Winner');
    }
}
