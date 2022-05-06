/* global google */
(function () {
    'use strict';

    var loading = false,

        /**
         * Generates a google map usable latitude and longitude object
         *
         * @param {Object} position
         * @return {google.maps.LatLng}
         */
        getGoogleLatitudeLongitude = function (position) {
            return new google.maps.LatLng(position.latitude, position.longitude);
        },
        gmAuthFailure = false;

    // jscs:disable requireCamelCaseOrUpperCaseIdentifiers
    window.gm_authFailure = function () {
        gmAuthFailure = true;
    };
    // jscs:enable requireCamelCaseOrUpperCaseIdentifiers

    window.GoogleMap = function (element, markers, additionalOptions) {
        var options,
            style;

        if (gmAuthFailure) {
            return;
        }

        try {
            style = googleMapsConfig.style ? JSON.parse(googleMapsConfig.style) : [];
        } catch (error) {
            style = [];
        }

        options = _.extend({
            zoom: 8,
            center: getGoogleLatitudeLongitude({
                latitude: 30.2672,
                longitude: -97.7431
            }),
            scrollwheel: false,
            disableDoubleClickZoom: false,
            disableDefaultUI: false,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DEFAULT
            },
            styles: style
        }, additionalOptions);

        /* Create the map */
        this.map = new google.maps.Map(element, options);
        this.markers = [];

        /**
         * Callback function on map config update
         * @param {Array} newMarkers
         * @param {Object} updateOptions
         */
        this.onUpdate = function (newMarkers, updateOptions) {
            this.map.setOptions(updateOptions);
            this.setMarkers(newMarkers);
        };

        /**
         * Sets the markers to selected map
         * @param {Object} newMarkers
         */
        this.setMarkers = function (newMarkers) {
            var activeInfoWindow,
                latitudeLongitudeBounds = new google.maps.LatLngBounds();

            this.markers.forEach(function (marker) {
                marker.setMap(null);
            }, this);

            this.markers = [];
            this.bounds = [];

            /**
             * Creates and set listener for markers
             */
            if (newMarkers && newMarkers.length) {
                newMarkers.forEach(function (newMarker) {
                    var location = _.escape(newMarker['location_name']) || '',
                    comment = newMarker.comment ?
                        '<p>' + _.escape(newMarker.comment).replace(/(?:\r\n|\r|\n)/g, '<br/>') + '</p>'
                        : '',
                    phone = newMarker.phone ? '<p>Phone: ' + _.escape(newMarker.phone) + '</p>' : '',
                    address = newMarker.address ? _.escape(newMarker.address) + '<br/>' : '',
                    city = _.escape(newMarker.city) || '',
                    country = newMarker.country ? _.escape(newMarker.country) : '',
                    state = newMarker.state ? _.escape(newMarker.state) + ' ' : '',
                    zipCode = newMarker.zipcode ? _.escape(newMarker.zipcode) : '',
                    cityComma = city !== '' && (zipCode !== '' || state !== '') ? ', ' : '',
                    lineBreak = city !== '' || zipCode !== '' ? '<br/>' : '',
                    contentString =
                        '<div>' +
                        '<h3><b>' + location + '</b></h3>' +
                        comment +
                        phone +
                        '<p><span>' + address +
                        city + cityComma + state + zipCode + lineBreak +
                        country + '</span></p>' +
                        '</div>',
                    infowindow = new google.maps.InfoWindow({
                        content: contentString,
                        maxWidth: 350
                    }),
                    newCreatedMarker = new google.maps.Marker({
                        map: this.map,
                        position: getGoogleLatitudeLongitude(newMarker.position),
                        title: location
                    });

                    if (location) {
                        newCreatedMarker.addListener('click', function () {
                            if (activeInfoWindow) {
                                activeInfoWindow.close();
                            }

                            infowindow.open(this.map, newCreatedMarker);
                            activeInfoWindow = infowindow;
                        }, this);
                    }

                    this.markers.push(newCreatedMarker);
                    this.bounds.push(getGoogleLatitudeLongitude(newMarker.position));
                }, this);
            }

            /**
             * This sets the bounds of the map for multiple locations
             */
            if (this.bounds.length > 1) {
                this.bounds.forEach(function (bound) {
                    latitudeLongitudeBounds.extend(bound);
                });
                this.map.fitBounds(latitudeLongitudeBounds);
            }

            /**
             * Zoom to 8 if there is only a single location
             */
            if (this.bounds.length === 1) {
                this.map.setCenter(this.bounds[0]);
                this.map.setZoom(8);
            }
        };

        this.setMarkers(markers);
    };

    window.pagebuilderMapsLoad = function () {
        if (loading) {
            return;
        }

        loading = true;

        $.loadScript(googleMapsConfig.src + '&callback=pagebuilderMapsLoaded');
    };

    window.pagebuilderMapsLoaded = function () {
        var maps = $.registry.get('pagebuilderMap');

        if (!maps) {
            return;
        }

        $.each(maps, function () {
            this.initMap();
        });
    };

    $.widget('pagebuilderMap', {
        component: 'Magento_PageBuilder/js/content-type/map/appearance/default/widget',

        /** [create description] */
        create: function () {
            var locations = this.element.attr('data-locations'),
                controls,
                mapOptions = {};

            if (!locations || locations === '[]') {
                return $(element).hide();
            }

            locations = JSON.parse(locations);
            locations.forEach(function (location) {
                location.position.latitude = parseFloat(location.position.latitude);
                location.position.longitude = parseFloat(location.position.longitude);
            });
            this.locations = locations;

            controls = this.element.attr('data-show-controls');
            mapOptions.disableDefaultUI = controls !== 'true';
            mapOptions.mapTypeControl = controls === 'true';
            this.mapOptions = mapOptions;

            if (typeof google === 'undefined') {
                pagebuilderMapsLoad();
            } else {
                this.initMap();
            }
        },

        initMap: function () {
            if (!this.locations) {
                return;
            }

            new GoogleMap(this.element[0], this.locations, this.mapOptions);
        }
    });
})();
