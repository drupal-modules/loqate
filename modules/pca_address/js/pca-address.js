/**
 * @file
 * PCA address behavior.
 */
(function ($, Drupal) {

  'use strict';

  /**
   * Process pca_address elements.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.pcaAddress = {
    attach: function attach(context, settings) {
      const elements = settings.pca_address && settings.pca_address.elements ? settings.pca_address.elements : null;
      $(context).find('.pca-address').once('pcaAddress').each(function () {
        // Get field mapping and options.
        let fields = false;
        let options = false;
        let addressWrapper = null;
        if (elements && typeof elements['#' + $(this).attr('id')] !== 'undefined') {
          fields = elements['#' + $(this).attr('id')].fields;
          options = elements['#' + $(this).attr('id')].options;
          addressWrapper = elements['#' + $(this).attr('id')].address_wrapper;
        }
        const control = new pca.Address(fields, options);
        control.listen("load", function() {
          // control.setCountry("CAN");
        });
        control.listen("populate", function(address, variations) {
          // Remove the hidden class from the address wrapper.
          document.getElementById(addressWrapper).className = document.getElementById(addressWrapper).className.replace(/\bhidden\b/,'');
          // document.getElementById("myCustomField").value = address.PostalCode;
        });
      });
    },
    detach: function detach(context, settings, trigger) {}
  };

})(jQuery, Drupal);
