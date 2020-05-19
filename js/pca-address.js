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
      const elements = settings.pca_address.elements ? settings.pca_address.elements : null;

      $.each(elements, function (elementId, value) {

        $(context).find(elementId).once('pcaAddress').each(function () {
          // Init vars.
          let pcaElementId = $(this).attr('id'),
              fields = null,
              options = null,
              addressWrapper = null;
          // Get field mapping and options.
          if (elements && typeof elements['#' + pcaElementId] !== undefined) {
            fields = elements['#' + pcaElementId].fields;
            options = elements['#' + pcaElementId].options;
            addressWrapper = elements['#' + pcaElementId].address_wrapper;
          }
          // Init label field values.
          initPopulateAddressLabelField(addressWrapper, fields);
          const control = new pca.Address(fields, options);
          control.listen('populate', function (address, variations) {
            // Remove the manual entry toggle link if present.
            doHideManualEntryLink(addressWrapper);
            // Populate address label field.
            doPopulateAddressLabelField(addressWrapper, fields, address);
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
   * @param fields
   * @param address
   */
  function doPopulateAddressLabelField(addressWrapper, fields, address) {
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

  /**
   * Initialise and removes the .hidden class from the address label field.
   *
   * @param addressWrapper
   * @param fields
   */
  function initPopulateAddressLabelField(addressWrapper, fields) {
    let $addressLabelWrapper = $('#' + addressWrapper).parent().find('.address-label-wrapper');
    let $addressLabel = $addressLabelWrapper.find('.address-label');
    let hasInputValues = false;
    $addressLabel.html('');
    $.each(fields, function (i, fieldObj) {
      // Lookup values from the input elements.
      let inputValue = $('input[name="' + fieldObj.element + '"]').val();
      if (inputValue !== '') {
        // Populate wih values.
        $addressLabel.append(inputValue + '</br>');
        hasInputValues = true;
      }
    });
    // Act upon successfully retrieving pre-existing values.
    if (hasInputValues) {
      $addressLabelWrapper.removeClass('hidden');
      doHideManualEntryLink(addressWrapper);
    }
  }

})(jQuery, Drupal);
