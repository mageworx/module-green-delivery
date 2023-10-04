
define(
    [
        'Magento_Ui/js/form/element/single-checkbox',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'jquery',
        'ko',
        'underscore',
        'mage/translate'
    ],
    function (Component, quote, priceUtils, $, ko, _, $t) {
        "use strict";

        return Component.extend({
            defaults: {
                visible: mwGreenDeliveryOptionConfig.enabled,
                label: mwGreenDeliveryOptionConfig.title,
                additionalInfo: mwGreenDeliveryOptionConfig.description,
                shipping_methods: mwGreenDeliveryOptionConfig.shipping_methods,
                shippingMethodAvailable: false,
                cost: mwGreenDeliveryOptionConfig.cost,
                checked: mwGreenDeliveryOptionConfig.checked,
                value: mwGreenDeliveryOptionConfig.value,
                valueMap: {true: '1', false: '0'}
            },

            observableProperties: ['shipping_methods', 'shippingMethodAvailable'],

            /**
             * Invokes initialize method of parent class,
             * contains initialization logic
             */
            initialize: function () {
                this._super();

                return this;
            },

            /** @inheritdoc */
            initObservable: function () {
                this._super()
                    .observe(this.observableProperties);

                this.initSubscription();

                this.valid = ko.computed(function () {return this.shippingMethodAvailable();}, this);

                return this;
            },

            initSubscription: function () {
                if (_.isEmpty(this.shipping_methods())) {
                    this.shippingMethodAvailable(true); // Any method
                }

                // Validate shipping method
                quote.shippingMethod.subscribe(function (method) {
                    if (_.isEmpty(this.shipping_methods())) {
                        this.shippingMethodAvailable(true); // Any method
                        return;
                    }

                    var methodCode = method.carrier_code + '_' + method.method_code;
                    if (this.shipping_methods().indexOf(methodCode) !== -1) {
                        this.shippingMethodAvailable(true);
                    } else {
                        this.shippingMethodAvailable(false);
                    }
                }, this);

                this.value.subscribe(function (val) {
                    this.saveChanges();
                }, this);
            },

            saveChanges: function () {
                var data = {
                    value: this.value() === '1' ? 1 : 0
                };

                $.ajax({
                    url: BASE_URL + 'mageworx_greendelivery/greenDeliveryOption/setValue',
                    type: 'POST',
                    isAjax: true,
                    dataType: 'json',
                    data: data,
                    success: function (xhr, status, errorThrown) {
                        //console.log(xhr);
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('There was an error saving quote.');
                    }
                });

                return true;
            },

            getLabelWithPrice: function () {
                if (this.cost < 0.001) {
                    return this.label + ' - ' + $t('Free');
                }

                return this.label + ' - ' + priceUtils.formatPrice(this.cost, quote.getPriceFormat());
            }
        });
    }
);
