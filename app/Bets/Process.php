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
            
            /*
             * Check for user balance 
             */
            if ($player->balance < $betslip->getStakeAmount()) {
                $betslip->addGlobalError('Insufficient balance');

                return $betslip;
            }
            
            /*
             * Checks if user bet in transaction 
             */
            if ($player->in_transaction == 1) {
                $betslip->addGlobalError('Your previous action is not finished yet');

                return $betslip;
            }

            $player->in_transaction = true;
            $player->save();

            $this->doTransaction($betslip, $player);
            
            $player->in_transaction = false;
            $player->save();
        } catch (ModelNotFoundException $e) {
            (new Player)->save();
        }
    }

    /**
     * Bet transaction
     * 
     * @param $betslip
     * @param $player
     */
    public function doTransaction($betslip, $player)
    {
        DB::transaction(function () use ($betslip, $player) {
            sleep(10);

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
    }
}