<?php

namespace App\Http\Controllers;

use App\Models\GameState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

class PlayController
{
    private function getGameState(bool $create = false): GameState|null
    {
        $gameState = null;

        $uuid = Cookie::get('state');
        if ($uuid !== null) {
            $gameState = GameState::find($uuid);
        }

        if ($create && !$gameState instanceof GameState) {
            $gameState = GameState::new();
            Cookie::queue('state', $gameState->getKey());
        }

        return $gameState;
    }

    public function index(): Response
    {
        return Inertia::render('Play', [
            'state' => $this->getGameState(true)->getData()
        ]);
    }

    public function reset(): JsonResponse
    {
        $gameState = $this->getGameState();
        if (!$gameState instanceof GameState) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid game state'
            ]);
        }
        $gameState->reset();
        return response()->json([
            'success' => true,
            'state' => $gameState->getData()
        ]);
    }

    public function move(Request $request): JsonResponse
    {
        $gameState = $this->getGameState();
        if (!$gameState instanceof GameState) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid game state'
            ]);
        }
        $parameters = $request->request->all();
        $from = $parameters['from'];
        $to = $parameters['to'];
        if (!is_int($from) || !is_int($to) || !$gameState->isValidMove($from, $to)) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid move'
            ]);
        }
        $data = $gameState->getData();
        $data['pieces'][$to] = $data['pieces'][$from];
        unset($data['pieces'][$from]);
        $gameState->setData($data);
        return response()->json([
            'success' => true,
            'state' => $gameState->getData()
        ]);
    }
}
