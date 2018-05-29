<?php

namespace App\Bets;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

use App\Models\Player;
use App\Models\BalanceTransaction;
use App\Models\Bet as BetModel;
use App\Models\BetSelection;

class Process 
{
    public function processBetslip($betslip)
    {
        try {
            $player = Player::findOrFail($betslip->getPlayerId());
            
            if ($player->balance < $betslip->getStakeAmount()) die('Insufficient balance');

            if ($player->in_transaction == 1) die('Your previous action is not finished yet');

            $player->in_transaction = true;
            $player->save();

            DB::transaction(function () use ($betslip, $player) {
                sleep(1);

                $betModel = BetModel::create([
                    'player_id' => $player->id,
                    'stake_amount' => $betslip->getStakeAmount()
                ]);

                foreach ($betslip->getSelections() as $selection) {
                    BetSelection::create([
                        'bet_id' => $betModel->id,
                        'selection_id' => $selection['id'],
                        'odds' => $selection['odds']
                    ]);
                }
                
                BalanceTransaction::create([
                    'player_id' => $player->id,
                    'amount' => $player->balance - $betslip->getStakeAmount(),
                    'amount_before' => $player->balance
                ]);

                $player->balance = $player->balance - $betslip->getStakeAmount();
                $player->save();
            });
            
            $player->in_transaction = false;
            $player->save();
            
            exit('Success');
        } catch (ModelNotFoundException $e) {
            (new Player)->save();
        }
    }
}