<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace OnTap\Tns\Model\Ui\Adminhtml;

use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\Ui\TokenUiComponentInterface;
use Magento\Vault\Model\Ui\TokenUiComponentProviderInterface;
use Magento\Framework\View\Element\Template;
use Magento\Vault\Model\Ui\TokenUiComponentInterfaceFactory;
use Magento\Framework\UrlInterface;
use OnTap\Tns\Gateway\Config\ConfigFactory;
use OnTap\Tns\Gateway\Config\Config;

class TokenUiComponentProvider implements TokenUiComponentProviderInterface
{
    /**
     * @var TokenUiComponentInterfaceFactory
     */
    private $componentFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ConfigFactory $configFactory
     * @param TokenUiComponentInterfaceFactory $componentFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConfigFactory $configFactory,
        TokenUiComponentInterfaceFactory $componentFactory,
        UrlInterface $urlBuilder
    ) {
        $this->componentFactory = $componentFactory;
        $this->urlBuilder = $urlBuilder;
        $this->config = $configFactory->create();
    }

    /**
     * @param PaymentTokenInterface $paymentToken
     * @return TokenUiComponentInterface
     */
    public function getComponentForToken(PaymentTokenInterface $paymentToken)
    {
        $this->config->setMethodCode($paymentToken->getPaymentMethodCode());
        $jsonDetails = \Zend_Json_Decoder::decode($paymentToken->getTokenDetails() ?: '{}', true);

        // Check for merchant ID, if the token merchant ID does not match the payment extension merchant ID
        // then do not render the vault method in hand.
        // @todo: not the best way to decide if a token payment needs to be rendered, refactor it
        //
        if (!isset($jsonDetails['merchant_id']) || $this->config->getMerchantId() !== $jsonDetails['merchant_id']) {
            return $component = $this->componentFactory->create([
                'config' => [],
                'name' => Template::class
            ]);
        }

        $component = $this->componentFactory->create(
            [
                'config' => [
                    TokenUiComponentProviderInterface::COMPONENT_DETAILS => $jsonDetails,
                    TokenUiComponentProviderInterface::COMPONENT_PUBLIC_HASH => $paymentToken->getPublicHash(),
                    'template' => 'OnTap_Tns::form/vault.phtml'
                ],
                'name' => Template::class
            ]
        );

        return $component;
    }
}
