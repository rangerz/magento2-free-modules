/* global ko _ focusTrap */
(function () {
    'use strict';

    var methods = [
        'click',
        'select',
        'submit',
        'blur',
        'focus'
    ];

    $.each(methods, function () {
        var method = this;

        /** Native methods proxy */
        $.fn[method] = function (callback) {
            if (callback) {
                return this.on(method, callback);
            }

            return this.each(function () {
                var event = document.createEvent('Event');

                event.initEvent(method, true, true);

                $(this).trigger(event);

                if (!event.defaultPrevented && method !== 'click' && this[method]) {
                    this[method]();
                }
            });
        };
    });

    /** [isVisible description] */
    function isVisible(i, el) {
        return el.offsetWidth || el.offsetHeight || el.getClientRects().length;
    }

    /** [isVisible description] */
    function isHidden(i, el) {
        return !isVisible(i, el);
    }

    /** Return visible elements */
    $.fn.visible = function () {
        return this.filter(isVisible);
    };

    /** Checks if element is visible */
    $.fn.isVisible = function () {
        return this.visible().length > 0;
    };

    /** Return hidden elements */
    $.fn.hidden = function () {
        return this.filter(isHidden);
    };

    /** Checks if element is hidden */
    $.fn.isHidden = function () {
        return this.hidden().length > 0;
    };

    /** [inViewport description] */
    function inViewport(i, el) {
        var rect = el.getBoundingClientRect();

        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= $(window).height() &&
            rect.right <= $(window).width()
        );
    }

    /** Return elements that are inside viewport */
    $.fn.inViewport = function () {
        return this.filter(inViewport);
    };

    /** Checks if element is inside viewport */
    $.fn.isInViewport = function () {
        return this.inViewport().length > 0;
    };

    /** Toggle block loader on the element */
    $.fn.spinner = function (flag, settings) {
        return this.each(function () {
            if (flag) {
                $.fn.blockLoader().show($(this), settings);
            } else {
                $.fn.blockLoader().hide($(this));
            }
        });
    };

    $.fn.fadeIn = $.fn.show;
    $.fn.fadeOut = $.fn.hide;

    /** Get/Set scroll top position */
    $.fn.scrollTop = function (val) {
        var el = this.get(0);

        if (val === undefined) {
            return el ? el.scrollTop : null;
        }

        if (el) {
            el.scrollTop = val;
        }

        return this;
    };

    /** Evaluates bindings specified in each DOM element of collection. */
    $.fn.applyBindings = function () {
        return this.each(function () {
            ko.applyBindings(ko.contextFor(this), this);
        });
    };

    $.fn.is = _.wrap($.fn.is, function (original, selector) {
        switch (selector) {
            case ':visible':
                return this.isVisible();

            case ':hidden':
                return this.isHidden();

            case ':selected':
                return this.filter(function () {
                    return this.selected;
                }).length > 0;

            case ':checked':
                return this.filter(function () {
                    return this.checked;
                }).length > 0;
        }

        return original.bind(this)(selector);
    });

    $.fn.data = _.wrap($.fn.data, function (original, key, value) {
        var collection = this,
            result,
            cleanKey,
            keys = [];

        if (value === undefined) {
            this.each(function () {
                if (this.__breeze && this.__breeze[key]) {
                    result = this.__breeze[key];

                    return false;
                }
            });

            if (result !== undefined) {
                return result;
            }
        } else if (value instanceof Node || value instanceof $) {
            return this.each(function () {
                this.__breeze = this.__breeze || {};
                this.__breeze[key] = value;
            });
        }

        result = original.apply(
            this,
            Array.prototype.slice.call(arguments, 1)
        );

        if (result === undefined) {
            cleanKey = key.replace(/^[^A-Z]+/, ''); // mageSwatchRenderer => SwatchRenderer
            keys = [
                key,
                cleanKey,
                cleanKey.charAt(0).toLowerCase() + cleanKey.slice(1)
            ];

            $.each(keys, function (i, widgetName) {
                if (typeof collection[widgetName] !== 'function') {
                    return;
                }

                result = collection[widgetName]('instance');

                return false;
            });
        }

        return result;
    });

    /** Hover implementation */
    $.fn.hover = function (mouseenter, mouseleave) {
        this.on('mouseenter', mouseenter).on('mouseleave', mouseleave);
    };

    /** Copy of magento's zIndex function */
    $.fn.zIndex = function (zIndex) {
        var elem = $(this[0]),
            position,
            value;

        if (zIndex !== undefined) {
            return this.css('zIndex', zIndex);
        }

        if (!this.length) {
            return 0;
        }

        while (elem.length && elem[0] !== document) {
            position = elem.css('position');
            value = parseInt(elem.css('zIndex'), 10);
            elem = elem.parent();

            if (position === 'static') {
                continue;
            }

            if (!isNaN(value) && value !== 0) {
                return value;
            }
        }

        return 0;
    };

    /**
     * Constraint element inside visible viewport
     * @return {Cash}
     */
    $.fn.contstraint = function () {
        var viewportWidth = $(window).width(),
            width = this.outerWidth(),
            left,
            right;

        if (!this.length) {
            return this;
        }

        left = Math.round(this.offset().left);
        right = left + width;

        if (left < 0) {
            this.css({
                left: 'auto',
                right: parseFloat(this.css('right')) + (left - 10)
            });
        } else if (left > 0 && right > viewportWidth) {
            this.css({
                left: 'auto',
                right: Math.min(parseFloat(this.css('right')) + left, 0)
            });
        }

        return this;
    };

    $.fn.var = function (name, value) {
        var el = this[0];

        if (value !== undefined) {
            el.style.setProperty(name, value);

            return this;
        }

        return getComputedStyle(el).getPropertyValue(name);
    };

    /** Proxy implementation */
    $.proxy = _.bind;

    /** Serialize object to query string */
    $.params = function (object) {
        return Object.keys(object).map(function (key) {
            return key + '=' + object[key];
        }).join('&');
    };

    /** Parse url query params */
    $.parseQuery = function (query) {
        var result = {};

        query = query || window.location.search;
        query = query.replace(/^\?/, '');

        $.each(query.split('&'), function (i, param) {
            var pair = param.split('='),
                key = decodeURIComponent(pair.shift().replace('+', ' ')).toString(),
                value = decodeURIComponent(pair.length ? pair.join('=').replace('+', ' ') : null);

            result[key] = value;
        });

        return result;
    };

    /** The main difference with .extend is that arrays are overriden instead of inherited */
    $.extendProps = function (own, inherited) {
        var destination = {};

        $.each(own, function (key, value) {
            if ($.isPlainObject(value)) {
                destination[key] = $.extendProps(own[key], inherited[key] || {});
            } else {
                destination[key] = value;
            }
        });

        $.each(inherited, function (key, value) {
            if (typeof own[key] !== 'undefined') {
                return;
            }

            if ($.isPlainObject(value)) {
                destination[key] = $.extendProps(own[key] || {}, inherited[key]);
            } else {
                destination[key] = value;
            }
        });

        return destination;
    };

    $.focusTrap = focusTrap;
})();
