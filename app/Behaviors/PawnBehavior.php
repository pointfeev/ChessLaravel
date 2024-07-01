<?php

namespace App\Behaviors;

class PawnBehavior extends PieceBehavior
{
    public static function getValidMoves(array $pieces, int $from): array
    {
        $moves = array();

        list($fromX, $fromY) = self::getXYFromPosition($from);
        $color = $pieces[$from]['color'];
        $dirY = $color == 'white' ? 1 : -1;

        $startY = $color == 'white' ? 1 : 6;
        $maxCount = $fromY == $startY ? 2 : 1;
        $positions = self::getPositionsInDirection($fromX, $fromY, 0, $dirY, $maxCount);
        foreach ($positions as $position) {
            if (isset($pieces[$position])) {
                break;
            }
            $moves[] = $position;
        }

        $directions = array(-1, 1);
        foreach ($directions as $dirX) {
            $positions = self::getPositionsInDirection($fromX, $fromY, $dirX, $dirY, 1);
            if (!empty($positions)) {
                $position = $positions[0];
                if (isset($pieces[$position])) {
                    $pieceLeft = $pieces[$position];
                    if ($color != $pieceLeft['color']) {
                        $moves[] = $position;
                    }
                }
            }
        }

        return $moves;
    }
}
