<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bet';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['player_id', 'stake_amount'];
}
