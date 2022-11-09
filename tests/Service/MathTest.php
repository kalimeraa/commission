<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kalimeraa\Commission\Tests\Service;

use Kalimeraa\Commission\Service\Math;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class MathTest extends TestCase
{
    private Math $math;

    protected function setUp(): void
    {
        $this->math = new Math(2);
    }

    /**
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(string $leftOperand, string $rightOperand, string $expectation): void
    {
        static::assertSame(
            $expectation,
            $this->math->add($leftOperand, $rightOperand)
        );
    }

    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', '3.00'],
            'add negative number to a positive' => ['-1', '2', '1.00'],
            'add natural number to a float' => ['1', '1.05123', '2.05'],
        ];
    }
}
