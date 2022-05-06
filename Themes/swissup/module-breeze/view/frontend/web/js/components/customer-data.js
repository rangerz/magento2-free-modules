/* global _ */
(function () {
    'use strict';

    var customerData,
        storage = $.storage.ns('mage-cache-storage'),
        storageInvalidation = $.storage.ns('mage-cache-storage-section-invalidation');

    /**
     * @param {Object} settings
     */
    function invalidateCacheBySessionTimeOut(settings) {
        var date = new Date(Date.now() + parseInt(settings.cookieLifeTime, 10) * 1000);

        if (new Date(storage.get('mage-cache-timeout')) < new Date()) {
            storage.removeAll();
        }

        storage.set('mage-cache-timeout', date);
    }

    /**
     * Invalidate Cache By Close Cookie Session
     */
    function invalidateCacheByCloseCookieSession() {
        if (!$.cookies.get('mage-cache-sessid')) {
            $.cookies.set('mage-cache-sessid', true, {
                domain: false
            });
            storage.removeAll();
        }
    }

    customerData = {
        /**
         * @param {Object} settings
         */
        initialize: function (settings) {
            this.options = settings;
            invalidateCacheBySessionTimeOut(this.options);
            invalidateCacheByCloseCookieSession();
            this.create();
        },

        /** Init component */
        create: function () {
            var sectionNames;

            // store switcher
            if ($.cookies.get('section_data_clean')) {
                $.cookies.set('section_data_clean', '');

                return this.reload([], true);
            }

            // magento bugfix for deprecated/removed cookie
            if (this.options.expirableSectionNames &&
                _.isEmpty($.cookies.getJson('section_data_ids') || {})
            ) {
                return this.reload([], true);
            }

            sectionNames = this.getExpiredSectionNames();

            if (sectionNames.length > 0) {
                this.reload(sectionNames);
            }
        },

        /**
         * @return {Array}
         */
        getExpiredSectionNames: function () {
            var expiredSectionNames = storageInvalidation.keys(),
                cookieSectionTimestamps = $.cookies.getJson('section_data_ids') || {},
                sectionLifetime = this.options.expirableSectionLifetime * 60,
                currentTimestamp = Math.floor(Date.now() / 1000),
                sectionData;

            // process sections that can expire due to lifetime constraints
            _.each(this.options.expirableSectionNames, function (sectionName) {
                sectionData = storage.get(sectionName);

                if (sectionData && sectionData.data_id + sectionLifetime <= currentTimestamp) {
                    expiredSectionNames.push(sectionName);
                }
            });

            // process sections that can expire due to storage information inconsistency
            _.each(cookieSectionTimestamps, function (cookieSectionTimestamp, sectionName) {
                sectionData = storage.get(sectionName);

                if (!sectionData || sectionData.data_id != cookieSectionTimestamp) { //eslint-disable-line
                    expiredSectionNames.push(sectionName);
                }
            });

            // remove expired section names of previously installed/enable modules
            expiredSectionNames = _.intersection(expiredSectionNames, $.sections.getSectionNames());

            return _.uniq(expiredSectionNames);
        },

        /**
         * @param {Array} sections
         * @param {Boolean} forceNewSectionTimestamp
         */
        reload: function (sections, forceNewSectionTimestamp) {
            var params = {};

            if (!this.options) {
                return this.invalidate(sections);
            }

            sections = sections || [];

            if (sections.length) {
                params.sections = sections.join(',');
            }

            if (forceNewSectionTimestamp) {
                params.force_new_section_timestamp = true;
            }

            $.request.get({
                url: this.options.sectionLoadUrl,
                data: params,
                accept: 'json',

                /** Success callback */
                success: function (data) {
                    var sectionDataIds = $.cookies.getJson('section_data_ids') || {};

                    $.each(data, function (sectionName, sectionData) {
                        // No need to store messages, but data_id must be
                        // in storage otherwise it will expire.
                        if (sectionName === 'messages') {
                            sectionData = {
                                data_id: sectionData.data_id,
                                messages: []
                            };
                        }

                        sectionDataIds[sectionName] = sectionData.data_id;
                        storage.set(sectionName, sectionData);
                        storageInvalidation.remove(sectionName);
                        $.sections.set(sectionName, sectionData);
                    });

                    $(document).trigger('customer-data-reload', {
                        sections: sections,
                        response: data
                    });

                    $.cookies.setJson('section_data_ids', sectionDataIds, {
                        domain: false
                    });
                }
            });
        },

        /**
         * @param {Array} sections
         */
        invalidate: function (sections) {
            var sectionDataIds = $.cookies.getJson('section_data_ids') || {};

            sections = _.contains(sections, '*') ?
                $.sections.getSectionNames() : sections;

            $(document).trigger('customer-data-invalidate', {
                sections: sections
            });

            storage.remove(sections);

            // Invalidate section in cookie (increase version of section with 1000)
            $(sections)
                .filter(function () {
                    return !$.sections.isClientSideSection(this);
                })
                .each(function () {
                    sectionDataIds[this] += 1000;
                    storageInvalidation.set(this, true);
                });

            $.cookies.setJson('section_data_ids', sectionDataIds, {
                domain: false
            });
        }
    };

    $(document).on('customerData:reload', function (event, data) {
        customerData.reload(data.sections, data.forceNewSectionTimestamp);
    });

    $(document).on('customerData:invalidate', function (event, data) {
        customerData.invalidate(data.sections);
    });

    $(document).on('breeze:load', function () {
        customerData.initialize(window.customerDataConfig);
    });
})();
