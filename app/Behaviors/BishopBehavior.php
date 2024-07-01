<?php

namespace App\Behaviors;

class BishopBehavior extends PieceBehavior
{
    public const ID = 'b';

    private const DIRECTIONS = [[1, 1], [1, -1], [-1, -1], [-1, 1]];

    public static function getValidMoves(array $pieces, int $from): array
    {
        return self::getValidMovesInDirections($pieces, $from, self::DIRECTIONS, 8);
    }
}
