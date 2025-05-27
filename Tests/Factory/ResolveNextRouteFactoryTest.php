<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\Bundle\PayumBundle\Factory;

use Payum\Core\Security\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Factory\ResolveNextRouteFactory;
use Sylius\Bundle\PayumBundle\Factory\ResolveNextRouteFactoryInterface;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRoute;

final class ResolveNextRouteFactoryTest extends TestCase
{
    private ResolveNextRouteFactory $resolveNextRouteFactory;

    protected function setUp(): void
    {
        $this->resolveNextRouteFactory = new ResolveNextRouteFactory();
    }

    public function testResolveNextRouteFactory(): void
    {
        $this->assertInstanceOf(ResolveNextRouteFactoryInterface::class, $this->resolveNextRouteFactory);
    }

    public function testCreatesResolveNextRouteRequest(): void
    {
        /** @var TokenInterface|MockObject $tokenMock */
        $tokenMock = $this->createMock(TokenInterface::class);
        $this->resolveNextRouteFactory->expects($this->once())->method('createNewWithModel')->with($tokenMock)->shouldBeLike(new ResolveNextRoute($tokenMock));
    }
}
