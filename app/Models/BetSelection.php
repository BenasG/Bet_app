<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetSelection extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bet_selections';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['bet_id', 'selection_id', 'odds'];
}
