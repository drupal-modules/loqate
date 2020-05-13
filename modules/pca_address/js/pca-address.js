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
        control.listen("load", function () {
          // control.setCountry("CAN");
        });
        control.listen("populate", function (address, variations) {
          // Remove the manual entry toggle link if present.
          doHideManualEntryLink(addressWrapper);
          // Populate address label field.
          doPopulateAddressLabelField(addressWrapper, address, fields);
          // document.getElementById("myCustomField").value = address.PostalCode;
        });
        // Address manual input events.
        $('.manual-address a', context).on('click', function () {
          // Remove the manual entry toggle link if present.
          doHideManualEntryLink(addressWrapper);
          // Remove the address label wrapper as we don't need this is not
          // needed when showing the address fields.
          doRemoveAddressLabelWrapper(addressWrapper);
          // Show address fields.
          doShowAddressFields(addressWrapper);
        });
        // Edit address input event.
        $('.fieldset-wrapper a', context).on('click', function () {
          // Remove the address label wrapper.
          doRemoveAddressLabelWrapper(addressWrapper);
          // Show address fields.
          doShowAddressFields(addressWrapper);
        });
      });
    },
    detach: function detach(context, settings, trigger) {}
  };

  /**
   * Removes the address label wrapper.
   *
   * @param addressWrapper
   */
  function doRemoveAddressLabelWrapper(addressWrapper) {
    $('#' + addressWrapper).parent().find('.address-label-wrapper').remove();
  }

  /**
   * Hides the manual entry link.
   *
   * @param addressWrapper
   */
  function doHideManualEntryLink(addressWrapper) {
    $('#' + addressWrapper).parent().find('.manual-address').remove();
  }

  /**
   * Removes the .hidden class from the address wrapper.
   *
   * @param addressWrapper
   */
  function doShowAddressFields(addressWrapper) {
    // Remove the hidden class from the address wrapper.
    document.getElementById(addressWrapper).className = document.getElementById(addressWrapper)
      .className.replace(/\bhidden\b/,'');
  }

  /**
   * Populate and removes the .hidden class from the address label field.
   *
   * @param addressWrapper
   * @param address
   * @param fields
   */
  function doPopulateAddressLabelField(addressWrapper, address, fields) {
    let $addressLabelWrapper = $('#' + addressWrapper).parent().find('.address-label-wrapper');
    let $addressLabel = $addressLabelWrapper.find('.address-label');
    $addressLabel.html('');
    $.each(fields, function (i, fieldObj) {
      // Check address key index values.
      if (address[fieldObj.field] !== undefined && address[fieldObj.field] !== '') {
        // Populate wih values.
        $addressLabel.append(address[fieldObj.field] + '</br>');
      }
    });
    $addressLabelWrapper.removeClass('hidden');
  }

})(jQuery, Drupal);
