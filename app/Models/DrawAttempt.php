<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class DrawAttempt extends Model
{
    /**
     * @var string
     */
    public const GRAND_PRIZE = 'grand_prize';

    /**
     * @var string
     */    
    public const GRAND_PRIZE_READABLE = 'Grand Prize';

    /**
     * @var string
     */
    public const SECOND_PRIZE_FIRST = 'second_prize_1';

    /**
     * @var string
     */    
    public const SECOND_PRIZE_FIRST_READABLE = 'Second Prize 1st winner';

    /**
     * @var string
     */
    public const SECOND_PRIZE_SECOND = 'second_prize_2';

    /**
     * @var string
     */    
    public const SECOND_PRIZE_SECOND_READABLE = 'Second Prize 2nd winner';

    /**
     * @var string
     */
    public const THIRD_PRIZE_FIRST = 'third_prize_1';

    /**
     * @var string
     */    
    public const THIRD_PRIZE_FIRST_READABLE = 'Third Prize 1st winner';

    /**
     * @var string
     */
    public const THIRD_PRIZE_SECOND = 'third_prize_2';

    /**
     * @var string
     */    
    public const THIRD_PRIZE_SECOND_READABLE = 'Third Prize 2nd winner';

    /**
     * @var string
     */
    public const THIRD_PRIZE_THIRD = 'third_prize_3';

    /**
     * @var string
     */    
    public const THIRD_PRIZE_THIRD_READABLE = 'Third Prize 3rd winner';

    /**
     * @var string[]
     */
    public const PRIZES = [
        self::GRAND_PRIZE,
        self::SECOND_PRIZE_FIRST,
        self::SECOND_PRIZE_SECOND,
        self::THIRD_PRIZE_FIRST,
        self::THIRD_PRIZE_SECOND,
        self::THIRD_PRIZE_THIRD
    ];

    /**
     * @var mixed[]
     */
    public const PRIZES_READABLE = [
        self::GRAND_PRIZE => self::GRAND_PRIZE_READABLE,
        self::SECOND_PRIZE_FIRST => self::SECOND_PRIZE_FIRST_READABLE,
        self::SECOND_PRIZE_SECOND => self::SECOND_PRIZE_SECOND_READABLE,
        self::THIRD_PRIZE_FIRST => self::THIRD_PRIZE_FIRST_READABLE,
        self::THIRD_PRIZE_SECOND => self::THIRD_PRIZE_SECOND_READABLE,
        self::THIRD_PRIZE_THIRD => self::THIRD_PRIZE_THIRD_READABLE
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['is_generated_randomly', 'winning_number', 'prize'];    

    /**
     * The attributes that should be cast to native types.
     *
     * @var mixed[]
     */
    protected $casts = [
        'created_at' => 'datetime',
        'is_generated_randomly' => 'boolean',
        'updated_at' => 'datetime'
    ];

    /** 
     * Returns the winner for this draw attempt.
     *
     * @return null|\Illuminate\Database\Eloquent\Relations\Relation
     */
    public function winner(): ?Relation
    {
        return $this->hasOne('App\Models\Winner');
    }    
}
