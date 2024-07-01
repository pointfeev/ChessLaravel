<?php

namespace App\Models;

use App\Behaviors\BishopBehavior;
use App\Behaviors\KingBehavior;
use App\Behaviors\KnightBehavior;
use App\Behaviors\PawnBehavior;
use App\Behaviors\QueenBehavior;
use App\Behaviors\RookBehavior;
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

        $data['turn'] = 0;

        $pieces = array();
        $pieces[1] = array('color' => 'black', 'type' => 'rook');
        $pieces[2] = array('color' => 'black', 'type' => 'knight');
        $pieces[3] = array('color' => 'black', 'type' => 'bishop');
        $pieces[4] = array('color' => 'black', 'type' => 'queen');
        $pieces[5] = array('color' => 'black', 'type' => 'king');
        $pieces[6] = array('color' => 'black', 'type' => 'bishop');
        $pieces[7] = array('color' => 'black', 'type' => 'knight');
        $pieces[8] = array('color' => 'black', 'type' => 'rook');
        for ($n = 9; $n <= 16; $n++) {
            $pieces[$n] = array('color' => 'black', 'type' => 'pawn');
        }
        for ($n = 49; $n <= 56; $n++) {
            $pieces[$n] = array('color' => 'white', 'type' => 'pawn');
        }
        $pieces[57] = array('color' => 'white', 'type' => 'rook');
        $pieces[58] = array('color' => 'white', 'type' => 'knight');
        $pieces[59] = array('color' => 'white', 'type' => 'bishop');
        $pieces[60] = array('color' => 'white', 'type' => 'queen');
        $pieces[61] = array('color' => 'white', 'type' => 'king');
        $pieces[62] = array('color' => 'white', 'type' => 'bishop');
        $pieces[63] = array('color' => 'white', 'type' => 'knight');
        $pieces[64] = array('color' => 'white', 'type' => 'rook');
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

        $piece = $pieces[$from];
        $turn = $data['turn'] % 2 == 0 ? 'white' : 'black';
        if ($turn != $piece['color']) {
            return array();
        }

        return match ($piece['type']) {
            'king' => KingBehavior::getValidMoves($pieces, $from),
            'queen' => QueenBehavior::getValidMoves($pieces, $from),
            'rook' => RookBehavior::getValidMoves($pieces, $from),
            'bishop' => BishopBehavior::getValidMoves($pieces, $from),
            'knight' => KnightBehavior::getValidMoves($pieces, $from),
            'pawn' => PawnBehavior::getValidMoves($pieces, $from),
            default => array()
        };
    }
}
