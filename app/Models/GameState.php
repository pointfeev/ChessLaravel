<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    use HasUuids;

    protected $table = 'state';
    protected $primaryKey = 'uuid';

    public function getData(): mixed
    {
        return json_decode($this->getAttribute('data'), true);
    }

    public function setData($data): void
    {
        $this->setAttribute('data', json_encode($data))->save();
    }

    public static function new(): GameState
    {
        $gameState = new GameState();
        $gameState->reset();
        return $gameState;
    }

    public function reset(): void
    {
        $data = array();

        $pieces = array();
        $pieces[1] = ['white', 'rook'];
        $pieces[2] = ['white', 'knight'];
        $pieces[3] = ['white', 'bishop'];
        $pieces[4] = ['white', 'queen'];
        $pieces[5] = ['white', 'king'];
        $pieces[6] = ['white', 'bishop'];
        $pieces[7] = ['white', 'knight'];
        $pieces[8] = ['white', 'rook'];
        for ($n = 9; $n <= 16; $n++) {
            $pieces[$n] = ['white', 'pawn'];
        }
        for ($n = 49; $n <= 56; $n++) {
            $pieces[$n] = ['black', 'pawn'];
        }
        $pieces[57] = ['black', 'rook'];
        $pieces[58] = ['black', 'knight'];
        $pieces[59] = ['black', 'bishop'];
        $pieces[60] = ['black', 'queen'];
        $pieces[61] = ['black', 'king'];
        $pieces[62] = ['black', 'bishop'];
        $pieces[63] = ['black', 'knight'];
        $pieces[64] = ['black', 'rook'];
        $data['pieces'] = $pieces;

        $this->setData($data);
    }

    public function isValidMove(int $from, int $to): bool
    {
        if ($from == $to) {
            return false;
        }
        $data = $this->getData();
        $pieces = $data['pieces'];
        if (!isset($pieces[$from])) {
            return false;
        }
        // TODO: return false if the specific piece type doesn't permit the move here.
        //       may want to create a method to get all possible moves for a piece type
        //       and send that to the front-end, or request it each time? idk yet.
        //       may also want to perform the rest of the logic following this comment
        //       from where we check the specific piece type movement as well.
        if (!isset($pieces[$to])) {
            return true;
        }
        if ($pieces[$from][0] == $pieces[$to][0]) {
            return false; // same color
        }
        return true;
    }
}
