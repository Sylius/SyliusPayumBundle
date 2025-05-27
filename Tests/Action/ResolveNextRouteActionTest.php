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

namespace Tests\Sylius\Bundle\PayumBundle\Action;

use Payum\Core\Action\ActionInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Action\ResolveNextRouteAction;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRoute;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ResolveNextRouteActionTest extends TestCase
{
    private ResolveNextRouteAction $resolveNextRouteAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolveNextRouteAction = new ResolveNextRouteAction();
    }

    public function testAPayumAction(): void
    {
        self::assertInstanceOf(ActionInterface::class, $this->resolveNextRouteAction);
    }

    public function testResolvesNextRouteForCompletedPayment(): void
    {
        /** @var ResolveNextRoute&MockObject $resolveNextRouteRequest */
        $resolveNextRouteRequest = $this->createMock(ResolveNextRoute::class);
        /** @var PaymentInterface&MockObject $payment */
        $payment = $this->createMock(PaymentInterface::class);

        $resolveNextRouteRequest->expects(self::once())->method('getFirstModel')->willReturn($payment);

        $payment->expects(self::once())
            ->method('getState')
            ->willReturn(PaymentInterface::STATE_COMPLETED);

        $resolveNextRouteRequest->expects(self::once())
            ->method('setRouteName')
            ->with('sylius_shop_order_thank_you');

        $this->resolveNextRouteAction->execute($resolveNextRouteRequest);
    }

    public function testResolvesNextRouteForCancelledPayment(): void
    {
        /** @var ResolveNextRoute&MockObject $resolveNextRouteRequest */
        $resolveNextRouteRequest = $this->createMock(ResolveNextRoute::class);
        /** @var PaymentInterface&MockObject $payment */
        $payment = $this->createMock(PaymentInterface::class);
        /** @var OrderInterface&MockObject $order */
        $order = $this->createMock(OrderInterface::class);

        $resolveNextRouteRequest->expects(self::once())->method('getFirstModel')->willReturn($payment);

        $payment->method('getState')->willReturn(PaymentInterface::STATE_CANCELLED);

        $payment->expects(self::once())->method('getOrder')->willReturn($order);

        $order->expects(self::once())->method('getTokenValue')->willReturn('qwerty');

        $resolveNextRouteRequest->expects(self::once())
            ->method('setRouteName')
            ->with('sylius_shop_order_show');

        $resolveNextRouteRequest->expects(self::once())
            ->method('setRouteParameters')
            ->with(['tokenValue' => 'qwerty']);

        $this->resolveNextRouteAction->execute($resolveNextRouteRequest);
    }

    public function testResolvesNextRouteForPaymentInCartState(): void
    {
        /** @var ResolveNextRoute&MockObject $resolveNextRouteRequestMock */
        $resolveNextRouteRequestMock = $this->createMock(ResolveNextRoute::class);
        /** @var PaymentInterface&MockObject $paymentMock */
        $paymentMock = $this->createMock(PaymentInterface::class);
        /** @var OrderInterface&MockObject $orderMock */
        $orderMock = $this->createMock(OrderInterface::class);

        $resolveNextRouteRequestMock->expects(self::once())
            ->method('getFirstModel')
            ->willReturn($paymentMock);

        $paymentMock->method('getState')->willReturn(PaymentInterface::STATE_CART);

        $paymentMock->expects(self::once())->method('getOrder')->willReturn($orderMock);

        $orderMock->expects(self::once())->method('getTokenValue')->willReturn('qwerty');

        $resolveNextRouteRequestMock->expects(self::once())
            ->method('setRouteName')
            ->with('sylius_shop_order_show');

        $resolveNextRouteRequestMock->expects(self::once())
            ->method('setRouteParameters')
            ->with(['tokenValue' => 'qwerty']);

        $this->resolveNextRouteAction->execute($resolveNextRouteRequestMock);
    }

    public function testResolvesNextRouteForFaildPayment(): void
    {
        /** @var ResolveNextRoute&MockObject $resolveNextRouteRequest */
        $resolveNextRouteRequest = $this->createMock(ResolveNextRoute::class);
        /** @var PaymentInterface&MockObject $payment */
        $payment = $this->createMock(PaymentInterface::class);
        /** @var OrderInterface&MockObject $order */
        $order = $this->createMock(OrderInterface::class);

        $resolveNextRouteRequest->expects(self::once())->method('getFirstModel')->willReturn($payment);

        $payment->method('getState')->willReturn(PaymentInterface::STATE_FAILED);

        $payment->expects(self::once())->method('getOrder')->willReturn($order);

        $order->expects(self::once())->method('getTokenValue')->willReturn('qwerty');

        $resolveNextRouteRequest->expects(self::once())
            ->method('setRouteName')
            ->with('sylius_shop_order_show');

        $resolveNextRouteRequest->expects(self::once())
            ->method('setRouteParameters')
            ->with(['tokenValue' => 'qwerty']);

        $this->resolveNextRouteAction->execute($resolveNextRouteRequest);
    }

    public function testResolvesNextRouteForProcessingPayment(): void
    {
        /** @var ResolveNextRoute&MockObject $resolveNextRouteRequest */
        $resolveNextRouteRequest = $this->createMock(ResolveNextRoute::class);
        /** @var PaymentInterface&MockObject $payment */
        $payment = $this->createMock(PaymentInterface::class);
        /** @var OrderInterface&MockObject $order */
        $order = $this->createMock(OrderInterface::class);

        $resolveNextRouteRequest->expects(self::once())->method('getFirstModel')->willReturn($payment);

        $payment->method('getState')->willReturn(PaymentInterface::STATE_PROCESSING);

        $payment->expects(self::once())->method('getOrder')->willReturn($order);

        $order->expects(self::once())->method('getTokenValue')->willReturn('qwerty');

        $resolveNextRouteRequest->expects(self::once())
            ->method('setRouteName')
            ->with('sylius_shop_order_show');

        $resolveNextRouteRequest->expects(self::once())
            ->method('setRouteParameters')
            ->with(['tokenValue' => 'qwerty']);

        $this->resolveNextRouteAction->execute($resolveNextRouteRequest);
    }
}
