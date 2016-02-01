<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace OnTap\Tns\Gateway\Http\Hosted;

use OnTap\Tns\Gateway\Http\TransferFactory;
use OnTap\Tns\Gateway\Http\Client\Rest;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;

class TransferFactoryOrder extends TransferFactory
{
    /**
     * @var string
     */
    protected $httpMethod = Rest::GET;

    /**
     * @param PaymentDataObjectInterface $payment
     * @return string
     */
    protected function getUri(PaymentDataObjectInterface $payment)
    {
        $orderId = $payment->getOrder()->getOrderIncrementId();
        return $this->getGatewayUri() . 'order/'.$orderId;
    }
}
