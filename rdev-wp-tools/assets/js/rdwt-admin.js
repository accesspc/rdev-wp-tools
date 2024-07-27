(function ( $ ) {
    'use strict';

    // RDWT::Settings: range slider value
    $(
        function () {
            $('.rdwt-range input').on(
                'input', function () {
                    $(this).next('.sub-desc').html(this.value);
                }
            );
        }
    );

})(jQuery);
