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
        if ($pieces[$from]['color'] == $pieces[$to]['color']) {
            return false;
        }
        return true;
    }
}
