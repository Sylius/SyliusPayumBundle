<?php

namespace Sylius\Bundle\PayumBundle\Checker;

use Sylius\Component\Payment\Model\GatewayConfigInterface;

/**
 * @experimental
 */
interface PayumGatewayConfigEncryptionCheckerInterface
{
    public function isPayumEncryptionEnabled(GatewayConfigInterface $gatewayConfig): bool;
}
