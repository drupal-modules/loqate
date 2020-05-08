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
      const options = settings.pca_address.options;
      const elements = settings.pca_address && settings.pca_address.elements ? settings.pca_address.elements : null;
      $(context).find('.pca-address').once('pcaAddress').each(function () {
        // Get field mapping.
        let fields = false;
        if (elements && typeof elements['#' + $(this).attr('id')] !== 'undefined') {
          fields = elements['#' + $(this).attr('id')].fields;
        }

        // let options = {
        //   countries: { codesList:"USA,CAN"},
        //   setCountryByIP: false,
        // };

        console.log([fields, options]);

        const control = new pca.Address(fields, options);

        control.listen("load", function() {
          // control.setCountry("CAN");
        });
        control.listen("populate", function(address, variations) {
          // document.getElementById("myCustomField").value = address.PostalCode;
        });
      });
    },
    detach: function detach(context, settings, trigger) {}
  };

})(jQuery, Drupal);
