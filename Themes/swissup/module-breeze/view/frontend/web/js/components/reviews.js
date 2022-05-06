(function () {
    'use strict';

    // Review List
    $.widget('ajaxReviews', {
        component: 'Magento_Review/js/process-reviews',

        /** Create widget */
        create: function () {
            var self = this,
                reviewTab = $(this.options.reviewsTabSelector);

            if (!reviewTab.length) {
                this.loadReviews();
            } else if (reviewTab.attr('role') === 'tab' && reviewTab.hasClass('active')) {
                this.loadReviews();
            } else {
                reviewTab.one('collapsible:beforeOpen', function () {
                    self.loadReviews();
                });
            }

            $(document).on('click.ajaxReviews', '[data-role="product-review"] .pages a', function (event) {
                event.preventDefault();
                self.loadReviews(this.href).then(function () {
                    $('#reviews').get(0).scrollIntoView();
                });
            });
        },

        destroy: function () {
            $(document).off('click.ajaxReviews');
            this._super();
        },

        /** [loadReviews description] */
        loadReviews: function (href) {
            return $.request.get({
                url: href || this.options.productReviewUrl,
                type: 'html'
            }).then(function (response) {
                $('#product-review-container').html('').append(response.text).trigger('contentUpdated');
            });
        }
    });

    // Review Form
    $.view('reviewForm', {
        component: 'Magento_Review/js/view/review',

        /** Init component */
        create: function () {
            this.review = $.sections.get('review');
        },

        /** Get nickname for the customer */
        nickname: function () {
            return this.review().nickname || $.sections.get('customer')().firstname;
        }
    });

    $.validator.validators['rating-required'] = [
        function (value) {
            return value !== undefined;
        },
        $.__('Please select one of each of the ratings above.')
    ];

    $(document).on('breeze:mount:Magento_Review/js/validate-review', function (event, data) {
        $(data.el).validator({
            /** Disable button to prevent multiple submits */
            onValid: function () {
                this.form.find('.submit').prop('disabled', true);
            },

            /** Override error placement */
            addErrorNodes: function (el, nodes) {
                var reviewTable = $(el).parents('#product-review-table');

                if (reviewTable.length) {
                    reviewTable.after(nodes);
                } else {
                    this.addErrorNodes(el, nodes);
                }
            },

            /** Override error removal */
            removeErrorNodes: function (el) {
                var reviewTable = $(el).parents('#product-review-table');

                if (reviewTable.length) {
                    reviewTable.siblings('.error-text[generated]').remove();
                } else {
                    this.removeErrorNodes(el);
                }
            }
        });
    });
})();
