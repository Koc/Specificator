<?php

namespace Brouzie\Specificator\SortOrder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SupportsInverse
{
    public static function create(?bool $inverse);

    public function isInverse(): bool;
}
