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

namespace Tests\Sylius\Bundle\PayumBundle\Request;

use Payum\Core\Security\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRoute;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRouteInterface;

final class ResolveNextRouteTest extends TestCase
{
    /** @var TokenInterface|MockObject */
    private MockObject $tokenMock;

    private ResolveNextRoute $resolveNextRoute;

    protected function setUp(): void
    {
        $this->tokenMock = $this->createMock(TokenInterface::class);
        $this->resolveNextRoute = new ResolveNextRoute($this->tokenMock);
    }

    public function testResolveNextRouteRequest(): void
    {
        $this->assertInstanceOf(ResolveNextRouteInterface::class, $this->resolveNextRoute);
    }

    public function testHasNextRouteName(): void
    {
        $this->resolveNextRoute->setRouteName('route_name');

        $this->assertSame('route_name', $this->resolveNextRoute->getRouteName());
    }

    public function testHasNextRouteParameters(): void
    {
        $this->resolveNextRoute->setRouteParameters(['id' => 1]);

        $this->assertSame(['id' => 1], $this->resolveNextRoute->getRouteParameters());
    }

    public function testDoesNotHaveRouteNameByDefault(): void
    {
        $this->assertNull($this->resolveNextRoute->getRouteName());
    }

    public function testDoesNotHaveRouteParametersByDefault(): void
    {
        $this->assertSame([], $this->resolveNextRoute->getRouteParameters());
    }
}
