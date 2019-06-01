(function ($) {
  /**
   * Address autocomplete, powered by Loqate.
   *
   * @type {{attach: Drupal.behaviors.addressAutocomplete.attach}}
   */
  Drupal.behaviors.addressAutocomplete = {
    attach: function (context, settings) {
      var loqateOptions = settings.loqate.loqate;
      // Check the loqate options have been defined before continuing.
      if (typeof loqateOptions !== 'object') {
        return;
      }

      var fieldClass = 'address-lookup__field';
      var fieldInitialClass = 'address-lookup__field--initial';
      var fieldHiddenClass = 'address-lookup__field--hidden';

      $('.address-lookup', context).each(function () {
        var $lookupWrapper = $(this);
        var $lookupFields = $lookupWrapper.find('.' + fieldClass);
        var dataKey = $lookupFields.data('key');
        var $provinceField = $lookupWrapper.find('[name="' + dataKey + '[state_province]"]');
        var $regionField = $lookupWrapper.find('[name="' + dataKey + '[region]"]');
        var provinceType = $provinceField.data('option-type') === 'state_province_codes' ? 'Province' : 'ProvinceName';

        // Initial setup for Loqate.
        var loqateFields = [
          { element: dataKey + '[search]', field: '' },
          { element: dataKey + '[address]', field: 'Line1' },
          { element: dataKey + '[address_2]', field: 'Line2', mode: pca.fieldMode.POPULATE },
          { element: dataKey + '[city]', field: 'City', mode: pca.fieldMode.POPULATE },
          { element: dataKey + '[region]', field: 'ProvinceName', mode: pca.fieldMode.POPULATE },
          { element: dataKey + '[state_province]', field: provinceType, mode: pca.fieldMode.POPULATE },
          { element: dataKey + '[postal_code]', field: 'PostalCode' },
          { element: dataKey + '[country]', field: 'CountryName', mode: pca.fieldMode.COUNTRY }
        ];

        var control = new pca.Address(loqateFields, loqateOptions);

        // Listener for when an address is populated.
        control.listen('populate', function (address) {
          if (address.ProvinceName !== '' && $provinceField.find('option[value="' + address[provinceType] + '"]').length > 0) {
            // ProvinceName exists in the State/Province drop-down.
            $provinceField.closest('.' + fieldClass).show().removeClass(fieldHiddenClass);
            $regionField.closest('.' + fieldClass).hide().addClass(fieldHiddenClass);
          }
          else {
            // ProvinceName does not exist in the State/Province
            // drop-down, so use the region field instead.
            $provinceField.closest('.' + fieldClass).hide().addClass(fieldHiddenClass);
            $regionField.closest('.' + fieldClass).show().removeClass(fieldHiddenClass);
          }
          $lookupFields.removeClass(fieldInitialClass);
        });
      });
    }
  };
})(jQuery);
