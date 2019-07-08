/**
 * StackOverflow Catalog.
 *
 * @category  Mage
 *
 * @author    Toan Nguyen <me@nntoan.com>
 * @copyright 2018 Toan Nguyen (https://nntoan.com)
 */
define([
    'jquery',
    'underscore',
    'mageUtils',
    'Magento_Ui/js/form/element/abstract',
], function ($, _, utils, Element) {
    'use strict';

    return Element.extend({
        defaults: {
            list: ([]),
            valueUpdate: 'afterkeydown',
            listens: {
                'valueArea': 'onUpdateArea'
            }
        },

        initialize: function () {
            this._super();
            this.on('value', this.onUpdateArea.bind(this));
            var self = this;
            var list = this.value().split(',');
            _.each(list, function (value, index) {
                if (value.length > 0) {
                    self.list.push(value.trim());
                }
            });

            return this;
        },

        initObservable: function () {
            this._super();
            this.observe(['valueArea']);
            this.observe('list', this.list);
            return this;
        },

        onUpdateArea: function (value) {
            if (value.length > 0) {
                setMarker(new google.maps.LatLng($('input[name="position[lat]"]').val(), $('input[name="position[lng]"]').val()));
            }
        }
    });
});