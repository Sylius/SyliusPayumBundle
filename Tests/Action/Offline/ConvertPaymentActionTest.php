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

namespace Tests\Sylius\Bundle\PayumBundle\Action\Offline;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Convert;
use Payum\Offline\Constants;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Action\Offline\ConvertPaymentAction;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

final class ConvertPaymentActionTest extends TestCase
{
    private ConvertPaymentAction $convertPaymentAction;

    protected function setUp(): void
    {
        $this->convertPaymentAction = new ConvertPaymentAction();
    }

    public function testPayumAction(): void
    {
        $this->assertInstanceOf(ActionInterface::class, $this->convertPaymentAction);
    }

    public function testConvertsPaymentToOfflineAction(): void
    {
        /** @var Convert|MockObject $requestMock */
        $requestMock = $this->createMock(Convert::class);
        /** @var PaymentInterface|MockObject $paymentMock */
        $paymentMock = $this->createMock(PaymentInterface::class);
        $requestMock->expects($this->once())->method('getTo')->willReturn('array');
        $requestMock->expects($this->once())->method('getSource')->willReturn($paymentMock);
        $requestMock->expects($this->once())->method('setResult')->with([
            Constants::FIELD_PAID => false,
        ]);
        $this->convertPaymentAction->execute($requestMock);
    }

    public function testSupportsOnlyConvertRequest(): void
    {
        /** @var Convert|MockObject $convertRequestMock */
        $convertRequestMock = $this->createMock(Convert::class);
        /** @var Capture|MockObject $captureRequestMock */
        $captureRequestMock = $this->createMock(Capture::class);
        /** @var PaymentInterface|MockObject $paymentMock */
        $paymentMock = $this->createMock(PaymentInterface::class);
        $convertRequestMock->expects($this->once())->method('getTo')->willReturn('array');
        $convertRequestMock->expects($this->once())->method('getSource')->willReturn($paymentMock);
        $this->assertTrue($this->convertPaymentAction->supports($convertRequestMock));
        $this->assertFalse($this->convertPaymentAction->supports($captureRequestMock));
    }

    public function testSupportsOnlyConvertingToArrayFromPayment(): void
    {
        /** @var Convert|MockObject $fromSomethingElseToSomethingElseRequestMock */
        $fromSomethingElseToSomethingElseRequestMock = $this->createMock(Convert::class);
        /** @var Convert|MockObject $fromPaymentToArrayRequestMock */
        $fromPaymentToArrayRequestMock = $this->createMock(Convert::class);
        /** @var PaymentInterface|MockObject $paymentMock */
        $paymentMock = $this->createMock(PaymentInterface::class);
        /** @var PaymentMethodInterface|MockObject $methodMock */
        $methodMock = $this->createMock(PaymentMethodInterface::class);
        $fromPaymentToArrayRequestMock->expects($this->once())->method('getTo')->willReturn('array');
        $fromPaymentToArrayRequestMock->expects($this->once())->method('getSource')->willReturn($paymentMock);
        $fromSomethingElseToSomethingElseRequestMock->expects($this->once())->method('getTo')->willReturn('json');
        $fromSomethingElseToSomethingElseRequestMock->expects($this->once())->method('getSource')->willReturn($methodMock);
        $this->assertTrue($this->convertPaymentAction->supports($fromPaymentToArrayRequestMock));
        $this->assertFalse($this->convertPaymentAction->supports($fromSomethingElseToSomethingElseRequestMock));
    }
}
