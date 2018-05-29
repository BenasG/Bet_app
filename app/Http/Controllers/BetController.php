<?php

namespace App\Http\Controllers;

use App\Bets\Process;

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

        $response = (new Bet)->make($betslip);

        if ($response->isSuccess()) {
            (new Process())->processBetslip($response->getBetslip());
        } else {
            print_r($response->getBetSlip()->getBetslipArray());
            print_r($response->getBetSlip()->getErrors());
        }
    }
}
