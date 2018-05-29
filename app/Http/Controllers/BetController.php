<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bets\Process;

use Benasg\Bet\Bet;

class BetController extends Controller
{   
    /**
     * Create basic request with implemented betslip
     */
    public function createBasic()
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
                    'odds' => 2.5,
                    'errors' => [],
                ],
            ],
        ];

        $response = (new Bet)->make($betslip);

        if ($response->isSuccess()) {
            (new Process)->processBetslip($response->getBetslip());
        }

        return response()->json(array(
            'betslip' => $response->getBetSlip()->getBetslipArray(),
            'erros' => $response->getBetSlip()->getErrors()
        ), 200);
    }

    /**
     * Create request with user betslip
     */
    public function createRequest(Request $request)
    {
        $response = (new Bet)->make($request->input());

        if ($response->isSuccess()) {
            (new Process)->processBetslip($response->getBetslip());   
        }

        return response()->json(array(
            'betslip' => $response->getBetSlip()->getBetslipArray(),
            'erros' => $response->getBetSlip()->getErrors()
        ), 200);
    }
}
