<?php

namespace App\Models;

use App\Behaviors\BishopBehavior;
use App\Behaviors\KingBehavior;
use App\Behaviors\KnightBehavior;
use App\Behaviors\PawnBehavior;
use App\Behaviors\PieceBehavior;
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
        $pieces[1] = array('color' => PieceBehavior::BLACK_ID, 'type' => RookBehavior::ID);
        $pieces[2] = array('color' => PieceBehavior::BLACK_ID, 'type' => KnightBehavior::ID);
        $pieces[3] = array('color' => PieceBehavior::BLACK_ID, 'type' => BishopBehavior::ID);
        $pieces[4] = array('color' => PieceBehavior::BLACK_ID, 'type' => QueenBehavior::ID);
        $pieces[5] = array('color' => PieceBehavior::BLACK_ID, 'type' => KingBehavior::ID);
        $pieces[6] = array('color' => PieceBehavior::BLACK_ID, 'type' => BishopBehavior::ID);
        $pieces[7] = array('color' => PieceBehavior::BLACK_ID, 'type' => KnightBehavior::ID);
        $pieces[8] = array('color' => PieceBehavior::BLACK_ID, 'type' => RookBehavior::ID);
        for ($n = 9; $n <= 16; $n++) {
            $pieces[$n] = array('color' => PieceBehavior::BLACK_ID, 'type' => PawnBehavior::ID);
        }
        for ($n = 49; $n <= 56; $n++) {
            $pieces[$n] = array('color' => PieceBehavior::WHITE_ID, 'type' => PawnBehavior::ID);
        }
        $pieces[57] = array('color' => PieceBehavior::WHITE_ID, 'type' => RookBehavior::ID);
        $pieces[58] = array('color' => PieceBehavior::WHITE_ID, 'type' => KnightBehavior::ID);
        $pieces[59] = array('color' => PieceBehavior::WHITE_ID, 'type' => BishopBehavior::ID);
        $pieces[60] = array('color' => PieceBehavior::WHITE_ID, 'type' => QueenBehavior::ID);
        $pieces[61] = array('color' => PieceBehavior::WHITE_ID, 'type' => KingBehavior::ID);
        $pieces[62] = array('color' => PieceBehavior::WHITE_ID, 'type' => BishopBehavior::ID);
        $pieces[63] = array('color' => PieceBehavior::WHITE_ID, 'type' => KnightBehavior::ID);
        $pieces[64] = array('color' => PieceBehavior::WHITE_ID, 'type' => RookBehavior::ID);
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
        $turn = $data['turn'] % 2 == 0 ? PieceBehavior::WHITE_ID : PieceBehavior::BLACK_ID;
        if ($turn != $piece['color']) {
            return array();
        }

        return match ($piece['type']) {
            KingBehavior::ID => KingBehavior::getValidMoves($pieces, $from),
            QueenBehavior::ID => QueenBehavior::getValidMoves($pieces, $from),
            RookBehavior::ID => RookBehavior::getValidMoves($pieces, $from),
            BishopBehavior::ID => BishopBehavior::getValidMoves($pieces, $from),
            KnightBehavior::ID => KnightBehavior::getValidMoves($pieces, $from),
            PawnBehavior::ID => PawnBehavior::getValidMoves($pieces, $from),
            default => array()
        };
    }
}
