<?php

namespace App\Models;

use App\Behaviors\PawnBehavior;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class GameState extends Model
{
    use HasUuids;

    protected $table = 'state';
    protected $primaryKey = 'uuid';

    public static function get(bool $create = false): GameState|null
    {
        $gameState = null;

        $uuid = Cookie::get('state');
        if ($uuid !== null) {
            $gameState = GameState::find($uuid);
        }

        if ($create && !$gameState instanceof GameState) {
            $gameState = new GameState();
            $gameState->resetData();
            Cookie::queue('state', $gameState->getKey());
        }

        return $gameState;
    }

    public function getData(): array
    {
        return json_decode($this->getAttribute('data'), true);
    }

    public function setData($data): void
    {
        $this->setAttribute('data', json_encode($data))->save();
    }

    public function resetData(): void
    {
        $data = array();

        $pieces = array();
        $pieces[1] = array('color' => 'white', 'type' => 'rook');
        $pieces[2] = array('color' => 'white', 'type' => 'knight');
        $pieces[3] = array('color' => 'white', 'type' => 'bishop');
        $pieces[4] = array('color' => 'white', 'type' => 'queen');
        $pieces[5] = array('color' => 'white', 'type' => 'king');
        $pieces[6] = array('color' => 'white', 'type' => 'bishop');
        $pieces[7] = array('color' => 'white', 'type' => 'knight');
        $pieces[8] = array('color' => 'white', 'type' => 'rook');
        for ($n = 9; $n <= 16; $n++) {
            $pieces[$n] = array('color' => 'white', 'type' => 'pawn');
        }
        for ($n = 49; $n <= 56; $n++) {
            $pieces[$n] = array('color' => 'black', 'type' => 'pawn');
        }
        $pieces[57] = array('color' => 'black', 'type' => 'rook');
        $pieces[58] = array('color' => 'black', 'type' => 'knight');
        $pieces[59] = array('color' => 'black', 'type' => 'bishop');
        $pieces[60] = array('color' => 'black', 'type' => 'queen');
        $pieces[61] = array('color' => 'black', 'type' => 'king');
        $pieces[62] = array('color' => 'black', 'type' => 'bishop');
        $pieces[63] = array('color' => 'black', 'type' => 'knight');
        $pieces[64] = array('color' => 'black', 'type' => 'rook');
        $data['pieces'] = $pieces;

        $this->setData($data);
    }

    public function getViewData(): array
    {
        return array_merge($this->getData(), array(
            'moves' => $this->getAllValidMoves()
        ));
    }

    public function isValidMove(int $from, int $to): bool
    {
        $moves = $this->getValidMoves($from);
        return in_array($to, $moves);
    }

    private function getAllValidMoves(): array
    {
        $moves = array();
        for ($p = 1; $p <= 64; $p++) {
            $moves[$p] = $this->getValidMoves($p);
        }
        return $moves;
    }

    private function getValidMoves(int $from): array
    {
        $data = $this->getData();
        $pieces = $data['pieces'];
        if (!isset($pieces[$from])) {
            return array();
        }

        switch ($pieces[$from]['type']) {
            case 'king':
                return array(); // TODO
            case 'queen':
                return array(); // TODO
            case 'rook':
                return array(); // TODO
            case 'bishop':
                return array(); // TODO
            case 'knight':
                return array(); // TODO
            case 'pawn':
                return PawnBehavior::getValidMoves($pieces, $from);
        }
    }
}
