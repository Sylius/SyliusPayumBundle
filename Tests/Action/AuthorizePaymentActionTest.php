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
use Payum\Core\Request\Authorize;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Action\AuthorizePaymentAction;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class AuthorizePaymentActionTest extends TestCase
{
    /** @var PaymentDescriptionProviderInterface|MockObject */
    private MockObject $paymentDescriptionProviderMock;

    private AuthorizePaymentAction $authorizePaymentAction;

    protected function setUp(): void
    {
        $this->paymentDescriptionProviderMock = $this->createMock(PaymentDescriptionProviderInterface::class);
        $this->authorizePaymentAction = new AuthorizePaymentAction($this->paymentDescriptionProviderMock);
    }

    public function testThrowExceptionWhenUnsupportedRequest(): void
    {
        /** @var Authorize|MockObject $authorizeMock */
        $authorizeMock = $this->createMock(Authorize::class);
        $this->expectException(RequestNotSupportedException::class);
        $this->authorizePaymentAction->execute($authorizeMock);
    }

    public function testPerformBasicAuthorize(): void
    {
        /** @var GatewayInterface|MockObject $gatewayMock */
        $gatewayMock = $this->createMock(GatewayInterface::class);
        /** @var Authorize|MockObject $authorizeMock */
        $authorizeMock = $this->createMock(Authorize::class);
        /** @var PaymentInterface|MockObject $paymentMock */
        $paymentMock = $this->createMock(PaymentInterface::class);
        /** @var OrderInterface|MockObject $orderMock */
        $orderMock = $this->createMock(OrderInterface::class);
        $this->authorizePaymentAction->setGateway($gatewayMock);
        $paymentMock->expects($this->once())->method('getOrder')->willReturn($orderMock);
        $paymentMock->expects($this->once())->method('getDetails')->willReturn([]);
        $authorizeMock->expects($this->once())->method('getModel')->willReturn($paymentMock);
        $paymentMock->expects($this->once())->method('setDetails')->with([]);
        $authorizeMock->expects($this->once())->method('setModel')->with(new ArrayObject());
        $this->authorizePaymentAction->execute($authorizeMock);
    }
}
