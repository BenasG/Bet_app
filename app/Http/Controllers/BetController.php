<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

use App\Models\Player;
use App\Models\BalanceTransaction;
use App\Models\Bet as BetModel;
use App\Models\BetSelection;

use Benasg\Bet\Bet;

class BetController extends Controller
{   
    public function createOne()
    {
        $betslip = [
            'player_id' => 1,
            'stake_amount' => 10,
            'errors' => [],
            'selections' => [
                [
                    'id' => 1,
                    'odds' => 1.601,
                    'errors' => [],
                ],
                [
                    'id' => 2,
                    'odds' => 1.601,
                    'errors' => [],
                ],
            ],
        ];

        $bet = new Bet();
        $bet->make($betslip);

        if ($bet->getSuccess()) {
            try {
                
                $betslip = $bet->getBetslip();

                $player = Player::findOrFail($betslip['player_id']);

                if ($player->in_transaction == 1) die('In transaction');

                $player->in_transaction = true;
                $player->save();

                DB::transaction(function () use ($betslip, $player) {
                    sleep(20);

                    $betModel = BetModel::create([
                        'player_id' => $betslip['player_id'],
                        'stake_amount' => $betslip['stake_amount']
                    ]);

                    foreach ($betslip['selections'] as $selection) {
                        BetSelection::create([
                            'bet_id' => $betModel->id,
                            'selection_id' => $selection['id'],
                            'odds' => $selection['odds']
                        ]);
                    }
                    
                    BalanceTransaction::create([
                        'player_id' => $player->id,
                        'amount' => $player->balance - 10,
                        'amount_before' => $player->balance
                    ]);

                    $player->balance = $player->balance - 10;
                    $player->save();
                });
                
                $player->in_transaction = false;
                $player->save();

            } catch (ModelNotFoundException $e) {
                (new Player)->save();
            }
        } else {

        }
    }
}
