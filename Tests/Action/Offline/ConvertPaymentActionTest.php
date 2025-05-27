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
        parent::setUp();
        $this->convertPaymentAction = new ConvertPaymentAction();
    }

    public function testPayumAction(): void
    {
        self::assertInstanceOf(ActionInterface::class, $this->convertPaymentAction);
    }

    public function testConvertsPaymentToOfflineAction(): void
    {
        /** @var Convert&MockObject $request */
        $request = $this->createMock(Convert::class);
        /** @var PaymentInterface&MockObject $payment */
        $payment = $this->createMock(PaymentInterface::class);

        $request->expects(self::once())->method('getTo')->willReturn('array');
        $request->expects(self::once())->method('getSource')->willReturn($payment);
        $request->expects(self::once())->method('setResult')->with([
            Constants::FIELD_PAID => false,
        ]);
        $this->convertPaymentAction->execute($request);
    }

    public function testSupportsOnlyConvertRequest(): void
    {
        /** @var Convert&MockObject $convertRequest */
        $convertRequest = $this->createMock(Convert::class);
        /** @var Capture&MockObject $captureRequest */
        $captureRequest = $this->createMock(Capture::class);
        /** @var PaymentInterface&MockObject $payment */
        $payment = $this->createMock(PaymentInterface::class);

        $convertRequest->expects(self::once())->method('getTo')->willReturn('array');
        $convertRequest->expects(self::once())->method('getSource')->willReturn($payment);

        self::assertTrue($this->convertPaymentAction->supports($convertRequest));
        self::assertFalse($this->convertPaymentAction->supports($captureRequest));
    }

    public function testSupportsOnlyConvertingToArrayFromPayment(): void
    {
        /** @var Convert&MockObject $fromSomethingElseToSomethingElseRequest */
        $fromSomethingElseToSomethingElseRequest = $this->createMock(Convert::class);
        /** @var Convert&MockObject $fromPaymentToArrayRequest */
        $fromPaymentToArrayRequest = $this->createMock(Convert::class);
        /** @var PaymentInterface&MockObject $payment */
        $payment = $this->createMock(PaymentInterface::class);
        /** @var PaymentMethodInterface&MockObject $method */
        $method = $this->createMock(PaymentMethodInterface::class);

        $fromPaymentToArrayRequest
            ->method('getTo')
            ->willReturn('array');
        $fromPaymentToArrayRequest
            ->method('getSource')
            ->willReturn($payment);

        $fromSomethingElseToSomethingElseRequest
            ->method('getTo')
            ->willReturn('json');
        $fromSomethingElseToSomethingElseRequest
            ->method('getSource')
            ->willReturn($method);

        self::assertTrue($this->convertPaymentAction->supports($fromPaymentToArrayRequest));
        self::assertFalse($this->convertPaymentAction->supports($fromSomethingElseToSomethingElseRequest));
    }
}
