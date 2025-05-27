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

use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Security\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Request\GetStatus;
use Sylius\Component\Core\Model\PaymentInterface;

final class GetStatusTest extends TestCase
{
    /** @var TokenInterface|MockObject */
    private MockObject $tokenMock;

    private GetStatus $getStatus;

    protected function setUp(): void
    {
        $this->tokenMock = $this->createMock(TokenInterface::class);
        $this->getStatus = new GetStatus($this->tokenMock);
    }

    public function testGetStatusRequest(): void
    {
        $this->assertInstanceOf(GetStatusInterface::class, $this->getStatus);
    }

    public function testHasUnknownStatusByDefault(): void
    {
        $this->assertTrue($this->getStatus->isUnknown());
        $this->assertSame(PaymentInterface::STATE_UNKNOWN, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsNew(): void
    {
        $this->getStatus->markNew();

        $this->assertTrue($this->getStatus->isNew());
        $this->assertSame(PaymentInterface::STATE_NEW, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsSuspended(): void
    {
        $this->getStatus->markSuspended();

        $this->assertTrue($this->getStatus->isSuspended());
        $this->assertSame(PaymentInterface::STATE_PROCESSING, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsExpired(): void
    {
        $this->getStatus->markExpired();

        $this->assertTrue($this->getStatus->isExpired());
        $this->assertSame(PaymentInterface::STATE_FAILED, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsCanceled(): void
    {
        $this->getStatus->markCanceled();

        $this->assertTrue($this->getStatus->isCanceled());
        $this->assertSame(PaymentInterface::STATE_CANCELLED, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsPending(): void
    {
        $this->getStatus->markPending();

        $this->assertTrue($this->getStatus->isPending());
        $this->assertSame(PaymentInterface::STATE_PROCESSING, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsFailed(): void
    {
        $this->getStatus->markFailed();

        $this->assertTrue($this->getStatus->isFailed());
        $this->assertSame(PaymentInterface::STATE_FAILED, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsUnknown(): void
    {
        $this->getStatus->markUnknown();

        $this->assertTrue($this->getStatus->isUnknown());
        $this->assertSame(PaymentInterface::STATE_UNKNOWN, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsCaptured(): void
    {
        $this->getStatus->markCaptured();

        $this->assertTrue($this->getStatus->isCaptured());
        $this->assertSame(PaymentInterface::STATE_COMPLETED, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsAuthorized(): void
    {
        $this->getStatus->markAuthorized();

        $this->assertTrue($this->getStatus->isAuthorized());
        $this->assertSame(PaymentInterface::STATE_AUTHORIZED, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsRefunded(): void
    {
        $this->getStatus->markRefunded();

        $this->assertTrue($this->getStatus->isRefunded());
        $this->assertSame(PaymentInterface::STATE_REFUNDED, $this->getStatus->getValue());
    }

    public function testCanBeMarkedAsPaydout(): void
    {
        $this->getStatus->markPayedout();

        $this->assertTrue($this->getStatus->isPayedout());
        $this->assertSame(PaymentInterface::STATE_REFUNDED, $this->getStatus->getValue());
    }
}
