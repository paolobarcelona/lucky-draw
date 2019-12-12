<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'has_won', 'name', 'email', 'is_admin', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var string[]
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var mixed[]
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'has_won' => 'boolean'
    ];

    /** 
     * Returns the winning numbers of the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function winningNumbers(): Relation
    {
        return $this->hasMany('App\Models\WinningNumber');
    }
}
