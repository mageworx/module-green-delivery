
define(
    [
        'uiElement',
        'jquery',
        'underscore',
        'mageUtils'
    ],
    function (Component, $, _, utils) {
        "use strict";

        return Component.extend({
            defaults: {
                imageSrc: mwGreenDeliveryMethodConfig.image_src,
                description: mwGreenDeliveryMethodConfig.description,
                titleColor: mwGreenDeliveryMethodConfig.color,
                methodName: mwGreenDeliveryMethodConfig.name
            },

            updateMethod: function () {
                let shippingMethodListContainer = document.getElementById("checkout-shipping-method-load");

                if (!shippingMethodListContainer) {
                    return;
                }

                let observer = new MutationObserver(function (mutationList, observer) {
                    const self = this;

                    _.each(mutationList, function (mutation) {
                        if (mutation['addedNodes'] && mutation['addedNodes'].length > 0) {
                            _.each(mutation['addedNodes'], function (addedNode) {
                                if (addedNode.classList && addedNode.classList.contains('row')) {
                                    let element = $(addedNode),
                                        mageworxGreenDeliveryMethod = element.find('#label_method_mageworxgreendelivery_mageworxgreendelivery');

                                    if (mageworxGreenDeliveryMethod.length) {
                                        self.addImage();
                                        self.addDescription();
                                        self.changeColor();
                                    }
                                }
                            });
                        }
                    });
                }.bind(this));

                observer.observe(shippingMethodListContainer, {
                        attributes: false,
                        childList: true,
                        subtree: true
                    }
                );
            },

            addImage: function () {
                if (!this.imageSrc) {
                    return;
                }

                let imageTemplate = '<div class="mageworx-green-delivery-method__image-wrapper"><img class="mageworx-green-delivery-method__image" src="${ $.imageSrc }" alt="${ $.title }"/></div>',
                    imageData = {'imageSrc': '', 'title': ''};

                imageData.imageSrc = this.imageSrc;
                imageData.title = this.methodName;

                let imageDOMObject = utils.template(imageTemplate, imageData),
                    existingImage = $('.mageworx-green-delivery-method__image-wrapper'),
                    greenDeliveryMethod = $('#label_method_mageworxgreendelivery_mageworxgreendelivery');

                if (greenDeliveryMethod.length > 0 && existingImage.length === 0) {
                    this.wrapMethodLabel(greenDeliveryMethod);
                    greenDeliveryMethod.prepend(imageDOMObject);
                }
            },

            addDescription: function () {
                if (!this.description) {
                    return;
                }

                let descriptionTemplate = '<tr class="row mageworx-green-delivery-method__description-wrapper"><td class="col" colSpan="5">${ $.description }</td></tr>',
                    descriptionData = {'description': ''};

                descriptionData.description = this.description;

                let descriptionDOMObject = utils.template(descriptionTemplate, descriptionData),
                    existingDescription = $('.mageworx-green-delivery-method__description-wrapper'),
                    greenDeliveryMethod = $('#label_method_mageworxgreendelivery_mageworxgreendelivery');

                if (greenDeliveryMethod.length > 0) {
                    if (existingDescription.length === 0) {
                        $(descriptionDOMObject).insertAfter(greenDeliveryMethod.parent());
                    }
                }
            },

            changeColor: function () {
                if (!this.titleColor) {
                    return;
                }

                $('#label_method_mageworxgreendelivery_mageworxgreendelivery').css('color', this.titleColor);
                $('#label_carrier_mageworxgreendelivery_mageworxgreendelivery').css('color', this.titleColor);
            },

            wrapMethodLabel: function (greenDeliveryMethodElem) {
                let methodLabel = greenDeliveryMethodElem.text(),
                    newMethodLabelElem = document.createElement('div');

                newMethodLabelElem.innerHTML = methodLabel;
                newMethodLabelElem.className = 'mageworx-green-delivery-method__label';

                greenDeliveryMethodElem.contents().filter(function () {
                    return this.nodeType === 3;
                }).remove();

                greenDeliveryMethodElem.prepend(newMethodLabelElem);
            }
        });
    }
);
