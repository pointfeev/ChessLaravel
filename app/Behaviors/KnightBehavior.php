<?php

namespace App\Behaviors;

class KnightBehavior extends PieceBehavior
{
    public const ID = 'n';

    private const DIRECTIONS = [[2, 1], [1, 2], [-2, 1], [1, -2], [2, -1], [-1, 2], [-2, -1], [-1, -2]];

    public static function getValidMoves(array $pieces, int $from): array
    {
        return self::getValidMovesInDirections($pieces, $from, self::DIRECTIONS, 1);
    }
}
