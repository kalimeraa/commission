<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kalimeraa\Commission\Service;

class Client
{
    public const PRIVATE = 'private';

    public const BUSINESS = 'business';

    private string $id;

    private string $type;

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setType(string $type): self
    {
        $type = strtolower($type);

        if (!\in_array($type, [self::PRIVATE, self::BUSINESS], true)) {
            throw new \InvalidArgumentException('type is invalid');
        }

        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return strtolower($this->type);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isPrivate(): bool
    {
        return self::PRIVATE === $this->getType();
    }

    public function isBusiness(): bool
    {
        return self::BUSINESS === $this->getType();
    }
}
