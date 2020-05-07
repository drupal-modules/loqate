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

      let options;
      const fields = [
        {element: "search", field: ""},
        {element: "company", field: "Company", mode: pca.fieldMode.DEFAULT | pca.fieldMode.PRESERVE},
        {element: "line1", field: "Line1"},
        {element: "line2", field: "Line2", mode: pca.fieldMode.POPULATE},
        {element: "city", field: "City", mode: pca.fieldMode.POPULATE},
        {element: "state", field: "Province", mode: pca.fieldMode.POPULATE},
        {element: "postcode", field: "PostalCode"},
        {element: "country", field: "CountryName", mode: pca.fieldMode.COUNTRY}
      ];

      const control = new pca.Address(fields, options);

      control.listen("load", function() {
        control.setCountry("CAN");
      });

      control.listen("populate", function(address, variations) {
        document.getElementById("myCustomField").value = address.PostalCode;
      });

    },
    detach: function detach(context, settings, trigger) {}
  };

})(jQuery, Drupal);
