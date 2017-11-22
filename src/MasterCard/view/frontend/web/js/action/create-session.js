/*
 * Copyright (c) 2016. On Tap Networks Limited.
 */
define(
    [
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'mage/url',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/model/customer'
    ],
    function (quote, urlBuilder, storage, url, errorProcessor, customer) {
        'use strict';

        return function (api, paymentData, messageContainer) {
            var serviceUrl,
                payload;

            if (customer.isLoggedIn()) {
                serviceUrl = urlBuilder.createUrl('/:api/session/create', {
                    api: api
                });
                payload = {
                    cartId: quote.getQuoteId(),
                    paymentMethod: paymentData,
                    billingAddress: quote.billingAddress()
                };
            } else {
                serviceUrl = urlBuilder.createUrl('/:api/session/:quoteId/create', {
                    quoteId: quote.getQuoteId(),
                    api: api
                });
                payload = {
                    cartId: quote.getQuoteId(),
                    email: quote.guestEmail,
                    paymentMethod: paymentData,
                    billingAddress: quote.billingAddress()
                };
            }

            return storage.post(
                serviceUrl, JSON.stringify(payload)
            ).fail(
                function (response) {
                    errorProcessor.process(response, messageContainer);
                }
            );
        };
    }
);
