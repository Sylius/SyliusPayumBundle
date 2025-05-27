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

namespace Tests\Sylius\Bundle\PayumBundle\Provider;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProvider;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PaymentDescriptionProviderTest extends TestCase
{
    /** @var TranslatorInterface|MockObject */
    private MockObject $translatorMock;

    private PaymentDescriptionProvider $paymentDescriptionProvider;

    protected function setUp(): void
    {
        $this->translatorMock = $this->createMock(TranslatorInterface::class);
        $this->translatorMock->expects($this->once())->method('trans')->with('sylius.payum_action.payment.description', [
            '%items%' => 2,
            '%total%' => 100.00,
        ])->willReturn('Payment contains 2 items for a total of 100');
        $this->paymentDescriptionProvider = new PaymentDescriptionProvider($this->translatorMock);
    }

    public function testGenerateADescriptionString(): void
    {
        /** @var PaymentInterface|MockObject $paymentMock */
        $paymentMock = $this->createMock(PaymentInterface::class);
        /** @var OrderInterface|MockObject $orderMock */
        $orderMock = $this->createMock(OrderInterface::class);
        $orderMock->expects($this->once())->method('getItems')->willReturn(new ArrayCollection([new OrderItem(), new OrderItem()]));
        $paymentMock->expects($this->once())->method('getOrder')->willReturn($orderMock);
        $paymentMock->expects($this->once())->method('getAmount')->willReturn(10000);
        $this->assertSame('Payment contains 2 items for a total of 100', $this->paymentDescriptionProvider->getPaymentDescription($paymentMock));
    }
}
