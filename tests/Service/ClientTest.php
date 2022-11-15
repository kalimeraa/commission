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

use Kalimeraa\Commission\Service\Client;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @covers ::isBusiness
     */
    public function testClientShouldBeBusiness(): void
    {
        $this->client->setType('business');

        static::assertTrue($this->client->isBusiness());
    }

    /**
     * @covers ::isPrivate
     */
    public function testClientShouldBePrivate(): void
    {
        $this->client->setType('private');

        static::assertTrue($this->client->isPrivate());
    }

    /**
     * @covers ::setType
     */
    public function testItShouldBeThrownInvalidArgumentExceptionForClientType(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->client->setType('dsfds');
    }

    /**
     * @covers ::getId
     */
    public function testShouldBeGotClientId(): void
    {
        $this->client->setId('2');

        static::assertSame('2', $this->client->getId());
    }

    /**
     * @covers ::getType
     */
    public function testShouldGotClientType(): void
    {
        $this->client->setType('private');

        static::assertSame('private', $this->client->getType());
    }
}
