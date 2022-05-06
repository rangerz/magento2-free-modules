/* global _ */
(function () {
    'use strict';

    $.widget('productConfigure', {
        /** [create description] */
        create: function () {
            this.cartSubscription = $.sections.get('cart').subscribe(function (data) {
                this.syncQuantity(data);
            }.bind(this));

            this.syncQuantity($.sections.get('cart')());
        },

        /** [destroy description] */
        destroy: function () {
            this.cartSubscription.dispose();
            this._super();
        },

        /** [syncQuantity description] */
        syncQuantity: function (data) {
            var product,
                itemId = $('#product_addtocart_form [name="item"]').val(),
                productId = $('#product_addtocart_form [name="product"]').val();

            if (!data || !data.items || !data.items.length || !productId) {
                return;
            }

            product = _.find(data.items, function (item) {
                return item.item_id === itemId && item.product_id === productId;
            });

            $('#product_addtocart_form [name="qty"]').val(product.qty);
        }
    });

    $(document).on('breeze:load', function () {
        if (window.location.href.indexOf('checkout/cart/configure') === -1) {
            return;
        }

        $.fn.productConfigure();
    });
})();
