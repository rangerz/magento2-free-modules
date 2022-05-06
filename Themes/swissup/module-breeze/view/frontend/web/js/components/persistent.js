(function () {
    'use strict';

    $.widget('persistent', {
        component: 'Magento_Persistent/js/view/additional-welcome',

        /** Initialize plugin */
        create: function () {
            var persistent = $.sections.get('persistent');

            if (persistent().fullname === undefined) {
                $.sections.get('persistent').subscribe(this.replacePersistentWelcome);
            } else {
                this.replacePersistentWelcome();
            }
        },

        /** Replace welcome message for customer with persistent cookie. */
        replacePersistentWelcome: function () {
            var persistent = $.sections.get('persistent'),
                welcomeElems;

            if (persistent().fullname !== undefined) {
                welcomeElems = $('li.greet.welcome > span.not-logged-in');

                if (welcomeElems.length) {
                    $(welcomeElems).each(function () {
                        var html = $.__('Welcome, %1!').replace('%1', persistent().fullname);

                        $(this).attr('data-bind', html);
                        $(this).html(html);
                    });
                    $(welcomeElems).append(' <span><a ' + window.notYouLink + '>' + $.__('Not you?') + '</a>');
                }
            }
        }
    });
})();
