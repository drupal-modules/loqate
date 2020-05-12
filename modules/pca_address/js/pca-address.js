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
        let fields = null;
        let options = null;
        let addressWrapper = null;
        let showAddressFields = null;
        let allowManualInput = null;
        if (elements && typeof elements['#' + $(this).attr('id')] !== undefined) {
          fields = elements['#' + $(this).attr('id')].fields;
          options = elements['#' + $(this).attr('id')].options;
          addressWrapper = elements['#' + $(this).attr('id')].address_wrapper;
          showAddressFields = elements['#' + $(this).attr('id')].show_address_fields;
          allowManualInput = elements['#' + $(this).attr('id')].allow_manual_input;
        }
        const control = new pca.Address(fields, options);
        control.listen("load", function() {
          // control.setCountry("CAN");
        });
        control.listen("populate", function(address, variations) {
          // Double check if we allow manual input.
          if (allowManualInput === true) {
            doShowAddressFields(addressWrapper);
          }
          // Populate address label field.
          populateAddressLabelField(addressWrapper, address, fields);
          // document.getElementById("myCustomField").value = address.PostalCode;
        });
        // Double check if we allow manual input.
        if (allowManualInput === true) {
          // Manual entry toggle.
          $('.manual-address a', context).on('click', function () {
            doShowAddressFields(addressWrapper);
          });
        }
      });
    },
    detach: function detach(context, settings, trigger) {}
  };

  /**
   * Removes the .hidden class from the address wrapper.
   *
   * @param addressWrapper
   */
  function doShowAddressFields(addressWrapper) {
    // Remove the hidden class from the address wrapper.
    document.getElementById(addressWrapper).className = document.getElementById(addressWrapper)
      .className.replace(/\bhidden\b/,'');
    // Remove the manual entry toggle link if present.
    $('#' + addressWrapper).parent().find('.manual-address').remove();
  }

  /**
   * Populate and removes the .hidden class from the address label field.
   *
   * @param addressWrapper
   * @param address
   * @param fields
   */
  function populateAddressLabelField(addressWrapper, address, fields) {
    let $addressLabel = $('#' + addressWrapper).parent().find('.address-label');
    let $addressLabelEl = $addressLabel.find('.fieldset-wrapper span');
    $addressLabelEl.html('');
    $.each(fields, function (i, fieldObj) {
      // Check address key index values.
      if (address[fieldObj.field] !== undefined && address[fieldObj.field] !== '') {
        // Populate wih values.
        $addressLabelEl.append(address[fieldObj.field] + '</br>');
      }
    });
    $addressLabel.removeClass('hidden');
  }

})(jQuery, Drupal);
