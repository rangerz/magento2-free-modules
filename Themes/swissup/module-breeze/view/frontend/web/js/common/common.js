/* global _ ko */
(function () {
    'use strict';

    var staticUrl = window.require.baseUrl;

    $('form[data-auto-submit="true"]').submit();

    $(document).on('submit', function (event) {
        var input,
            form = $(event.target),
            formKey = $('input[name="form_key"]').val();

        if (!formKey ||
            form.prop('method') !== 'post' ||
            form.prop('action').indexOf(window.BASE_URL) !== 0 ||
            form.children('input[name="form_key"]').length
        ) {
            return;
        }

        input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'form_key');
        input.setAttribute('value', formKey);
        input.setAttribute('auto-added-form-key', '1');
        form.get(0).appendChild(input);
    });

    /** [getScopeId description] */
    $.breeze.getScopeId = function (scope) {
        var mapping = {
            'store': window.checkout ? window.checkout.storeId : '',
            'group': window.checkout ? window.checkout.storeGroupId : '',
            'website': window.checkout ? window.checkout.websiteId : ''
        };

        if (!mapping[scope]) {
            scope = 'website';
        }

        return mapping[scope];
    };

    /** [visit description] */
    $.breeze.visit = function (url) {
        if (typeof Turbo !== 'undefined') {
            // eslint-disable-next-line no-undef
            Turbo.visit(url);
        } else if (typeof Turbolinks !== 'undefined') {
            // eslint-disable-next-line no-undef
            Turbolinks.visit(url);
        } else {
            location.href = url;
        }
    };

    /** [visit description] */
    $.breeze.scrollbar = {
        /** [hide description] */
        hide: function () {
            var padding = parseFloat($('body').css('padding-right')),
                scrollbar = Math.abs(window.innerWidth - document.documentElement.clientWidth);

            $('body')
                .css('box-sizing', 'border-box')
                .css('padding-right', padding + scrollbar)
                .css('overflow', 'hidden');
        },

        /** [show description] */
        reset: function () {
            $('body')
                .css('box-sizing', '')
                .css('padding-right', '')
                .css('overflow', '');
        }
    };

    /** Prevent js error in case some module calls for requirejs */
    window.require = function () {};
    window.require.toUrl = function (path) {
        return staticUrl + '/' + path.trim('/');
    };

    /**
     * Emulate requirejs's define for some integrations
     *
     * @param {Array} deps
     * @param {Function} callback
     */
    window.define = function (deps, callback) {
        var args = [],
            mapping = {
                'jquery': $,
                'ko': ko,
                'mage/cookies': $.cookies,
                'mage/translate': $.__,
                'Magento_Customer/js/customer-data': $.sections,
                'Magento_Ui/js/lib/view/utils/async': $,
                'underscore': _
            };

        if (!_.isArray(deps)) {
            return;
        }

        deps.forEach(function (alias) {
            args.push(mapping[alias] || undefined);
        });

        callback.apply(this, args);
    };
})();
