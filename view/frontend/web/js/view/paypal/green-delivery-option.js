
define(
    [
        'Magento_Ui/js/form/element/single-checkbox',
        'jquery',
        'ko',
        'underscore',
        'mage/translate'
    ],
    function (Component, $, ko, _, $t) {
        "use strict";

        return Component.extend({
            defaults: {
                visible: mwGreenDeliveryOptionConfig.enabled,
                label: mwGreenDeliveryOptionConfig.title,
                additionalInfo: mwGreenDeliveryOptionConfig.description,
                shipping_methods: mwGreenDeliveryOptionConfig.shipping_methods,
                shippingMethodAvailable: false,
                cost: mwGreenDeliveryOptionConfig.cost,
                formatedCost: mwGreenDeliveryOptionConfig.formatedCost,
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

                if (_.isEmpty(this.shipping_methods())) {
                    this.shippingMethodAvailable(true); // Any method
                } else {
                    let method = $('#shipping-method').find(":selected").val();

                    if (this.shipping_methods().indexOf(method) !== -1) {
                        this.shippingMethodAvailable(true);
                    } else {
                        this.shippingMethodAvailable(false);
                    }
                }

                this.initSubscription();

                this.valid = ko.computed(function () {return this.shippingMethodAvailable();}, this);

                return this;
            },

            initSubscription: function () {
                var self = this;

                // Validate shipping method
                $("#shipping-method").change(function() {
                    if (_.isEmpty(self.shipping_methods())) {
                        self.shippingMethodAvailable(true); // Any method
                        return;
                    }

                    let method = $(this).val();

                    if (self.shipping_methods().indexOf(method) !== -1) {
                        self.shippingMethodAvailable(true);
                    } else {
                        self.shippingMethodAvailable(false);
                    }
                });

                this.value.subscribe(function (val) {
                    this.saveChanges();
                }, this);
            },

            saveChanges: function () {
                var data = {
                        value: this.value() === '1' ? 1 : 0
                    },
                    widget = $("#order-review-form").data("mageOrderReview");

                widget._ajaxBeforeSend();

                $.ajax({
                    url: BASE_URL + 'mageworx_greendelivery/greenDeliveryOption/setValue',
                    type: 'POST',
                    isAjax: true,
                    dataType: 'json',
                    data: data,
                    success: function (xhr, status, errorThrown) {
                        location.reload();
                        //console.log(xhr);
                    },
                    error: function (xhr, status, errorThrown) {
                        widget._ajaxComplete();
                        console.log('There was an error saving quote.');
                    }
                });

                return true;
            },

            getLabelWithPrice: function () {
                if (this.cost < 0.001) {
                    return this.label + ' - ' + $t('Free');
                }

                return this.label + ' - ' + this.formatedCost;
            }
        });
    }
);
