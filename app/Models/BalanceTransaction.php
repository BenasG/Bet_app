<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'balance_transaction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['player_id', 'amount', 'amount_before'];
}
