<?php

declare(strict_types=1);

namespace Sylius\Bundle\PayumBundle\Api;

use Sylius\Bundle\ApiBundle\Payment\PaymentRequestCommandProviderInterface;
use Sylius\Bundle\PayumBundle\Command\StatusPaymentRequest;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final class PayumStatusPaymentRequestCommandProvider implements PaymentRequestCommandProviderInterface
{
    public function supports(PaymentRequestInterface $paymentRequest): bool
    {
        return $paymentRequest->getType() === PaymentRequestInterface::DATA_TYPE_STATUS;
    }

    public function handle(PaymentRequestInterface $paymentRequest): object
    {
        return new StatusPaymentRequest($paymentRequest->getHash());
    }
}
