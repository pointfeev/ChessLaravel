<?php

namespace App\Behaviors;

class QueenBehavior extends PieceBehavior
{
    public const ID = 'q';

    private const DIRECTIONS = [[1, 1], [1, -1], [-1, -1], [-1, 1], [0, 1], [1, 0], [0, -1], [-1, 0]];

    public static function getValidMoves(array $pieces, int $from): array
    {
        return self::getValidMovesInDirections($pieces, $from, self::DIRECTIONS, 8);
    }
}
