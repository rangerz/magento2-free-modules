(function () {
    'use strict';

    $.widget('tabs', {
        component: 'tabs',
        options: {
            active: 0,
            collapsible: false,
            collapsibleElement: '[data-role=collapsible]',
            header: '[data-role=title]',
            content: '[data-role=content]',
            trigger: '[data-role=trigger]'
        },

        /** init widget */
        create: function () {
            var self = this,
                activeIndex;

            this.collapsibles = this.element.find(this.options.collapsibleElement);
            this.headers = this.element.find(this.options.header);
            this.triggers = this.element.find(this.options.trigger);
            this.contents = this.element.find(this.options.content);

            if (this.headers.length === 0) {
                this.headers = this.collapsibles;
            }

            if (this.triggers.length === 0) {
                this.triggers = this.headers;
            }

            this.collapsibles
                .attr('role', 'presentation')
                .parent()
                .attr('role', 'tablist');

            this.triggers.attr('role', 'tab');

            activeIndex = this.findActiveTabIndex(location.hash);

            if (activeIndex > -1) {
                this.options.active = activeIndex;
            }

            this.collapsibles.each(function (index, el) {
                var isActive;

                if (_.isArray(self.options.active)) {
                    isActive = self.options.active.indexOf(index) !== -1;
                } else {
                    isActive = index === self.options.active;
                }

                $(el).collapsible($.extend({}, self.options, {
                    active: isActive,
                    header: self.headers.eq(index),
                    content: self.contents.eq(index),
                    trigger: self.triggers.eq(index)
                }));
            });

            $(this.element).on('collapsible:beforeOpen', function (event, data) {
                var activeTab = self.getActiveTab(),
                    prevContent;

                if (self.collapsibles.index(data.instance.element.get(0)) === -1) {
                    return; // nested tabs
                }

                if (activeTab) {
                    prevContent = activeTab.collapsible('instance').content;
                }

                if (data.instance.options.multipleCollapsible) {
                    return;
                }

                self.prevHeight = prevContent ? $(prevContent).outerHeight() : false;
                self.collapsibles.not(data.instance.element).collapsible('close');
            });

            $(this.element).on('collapsible:beforeLoad', function (event, data) {
                if (self.prevHeight && $(window).width() > 767) {
                    data.instance.content.css('height', self.prevHeight);
                }
            });

            $(this.element).on('collapsible:afterLoad', function (event, data) {
                data.instance.content.css('height', 'auto');
            });

            // Reviews and other third-party links
            $(document).on('click.tabs', 'a[href*="#"]', function (event) {
                var anchor = $(this).attr('href').split('#')[1],
                    element,
                    index;

                if (!anchor || self.element.has(this).length) {
                    return;
                }

                index = self.findActiveTabIndex('#' + anchor);

                if (index === -1) {
                    return;
                }

                event.preventDefault();
                self.collapsibles.eq(index).collapsible('open');
                element = self.contents.find('#' + anchor);

                if (!element.length) {
                    element = self.triggers.eq(index);
                }

                element.get(0).scrollIntoView();
            });
        },

        destroy: function () {
            $(document).off('click.tabs');
        },

        /** [getActiveTab description] */
        getActiveTab: function () {
            var tab;

            this.collapsibles.each(function (index, el) {
                if ($(el).collapsible('isActive')) {
                    tab = $(el);

                    return false;
                }
            });

            return tab;
        },

        /** Find active tab index */
        findActiveTabIndex: function (hash) {
            var index = -1,
                activeTrigger,
                activeContent;

            if (!hash || hash.length <= 1) {
                return index;
            }

            activeTrigger = this.triggers.has('[href*="' + hash + '"');

            if (activeTrigger.length) {
                index = this.triggers.index(activeTrigger);
            } else {
                try {
                    activeContent = this.contents.has(hash);
                } catch (e) {
                    return -1;
                }

                if (activeContent.length) {
                    index = this.contents.index(activeContent);
                }
            }

            return index;
        }
    });
})();
