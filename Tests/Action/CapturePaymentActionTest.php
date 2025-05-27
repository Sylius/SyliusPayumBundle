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

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Capture;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Action\CapturePaymentAction;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class CapturePaymentActionTest extends TestCase
{
    /** @var PaymentDescriptionProviderInterface|MockObject */
    private MockObject $paymentDescriptionProviderMock;

    private CapturePaymentAction $capturePaymentAction;

    protected function setUp(): void
    {
        $this->paymentDescriptionProviderMock = $this->createMock(PaymentDescriptionProviderInterface::class);
        $this->capturePaymentAction = new CapturePaymentAction($this->paymentDescriptionProviderMock);
    }

    public function testThrowExceptionWhenUnsupportedRequest(): void
    {
        /** @var Capture|MockObject $captureMock */
        $captureMock = $this->createMock(Capture::class);
        $this->expectException(RequestNotSupportedException::class);
        $this->capturePaymentAction->execute($captureMock);
    }

    public function testPerformBasicCapture(): void
    {
        /** @var GatewayInterface|MockObject $gatewayMock */
        $gatewayMock = $this->createMock(GatewayInterface::class);
        /** @var Capture|MockObject $captureMock */
        $captureMock = $this->createMock(Capture::class);
        /** @var PaymentInterface|MockObject $paymentMock */
        $paymentMock = $this->createMock(PaymentInterface::class);
        /** @var OrderInterface|MockObject $orderMock */
        $orderMock = $this->createMock(OrderInterface::class);
        $this->capturePaymentAction->setGateway($gatewayMock);
        $paymentMock->expects($this->once())->method('getOrder')->willReturn($orderMock);
        $paymentMock->expects($this->once())->method('getDetails')->willReturn([]);
        $captureMock->expects($this->once())->method('getModel')->willReturn($paymentMock);
        $paymentMock->expects($this->once())->method('setDetails')->with([]);
        $captureMock->expects($this->once())->method('setModel')->with(new ArrayObject());
        $this->capturePaymentAction->execute($captureMock);
    }
}
