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

            var $addressLookup = $('.address-lookup');
            var initialClass = 'address-lookup--initial';

            // Initial setup for Loqate.
            var loqateFields = [
                {element: "search", field: ""},
                {element: "address[address]", field: "Line1"},
                {
                    element: "address[address_2]",
                    field: "Line2",
                    mode: pca.fieldMode.POPULATE
                },
                {
                    element: "address[city]",
                    field: "City",
                    mode: pca.fieldMode.POPULATE
                },
                {
                    element: "address[state_province]",
                    field: "Province",
                    mode: pca.fieldMode.POPULATE
                },
                {element: "address[postal_code]", field: "PostalCode"},
                {
                    element: "address[country]",
                    field: "CountryName",
                    mode: pca.fieldMode.COUNTRY
                }
            ];

            var control = new pca.Address(loqateFields, loqateOptions);
            // Listener for when an address is populated.
            control.listen("populate", function (address) {
                $addressLookup.removeClass(initialClass);
            });
        }
    };
})(jQuery);