<?php

namespace App\Behaviors;

abstract class PieceBehavior
{
    public const WHITE_ID = 'w';
    public const BLACK_ID = 'b';

    protected static function getXYFromPosition(int $from): array
    {
        $position = $from - 1;
        return array($position % 8, floor($position / 8));
    }

    protected static function getPositionFromXY(int $x, int $y): int
    {
        return $y * 8 + $x + 1;
    }

    protected static function getPositionsInDirection(int $fromX, int $fromY, int $dirX, int $dirY, int $maxCount): array
    {
        $positions = array();

        $x = $fromX + $dirX;
        $y = $fromY + $dirY;
        $count = 1;
        while ($x >= 0 && $x <= 7 && $y >= 0 && $y <= 7 && $count <= $maxCount) {
            $positions[] = self::getPositionFromXY($x, $y);
            $x += $dirX;
            $y += $dirY;
            $count++;
        }

        return $positions;
    }

    protected static function getValidMovesInDirections(array $pieces, int $from, array $directions, int $maxCount): array
    {
        $moves = array();

        list($fromX, $fromY) = self::getXYFromPosition($from);
        $color = $pieces[$from]['color'];
        foreach ($directions as $direction) {
            $dirX = $direction[0];
            $dirY = $direction[1];
            $positions = self::getPositionsInDirection($fromX, $fromY, $dirX, $dirY, $maxCount);
            foreach ($positions as $position) {
                if (isset($pieces[$position])) {
                    if ($color != $pieces[$position]['color']) {
                        $moves[] = $position;
                    }
                    break;
                }
                $moves[] = $position;
            }
        }

        return $moves;
    }

    abstract public static function getValidMoves(array $pieces, int $from): array;
}
