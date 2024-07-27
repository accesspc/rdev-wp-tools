(function ( $ ) {
    'use strict';

    // Password Generator
    $(
        function () {
            $('.rdwt-pwdgen-generate').on(
                'click', function () {
                    // Get options from hidden inputs
                    const count = $('input.pwdgen-count').val();
                    const length = $('input.pwdgen-length').val();
                    const inc_numbers = $('input.pwdgen-inc_numbers').val();
                    const inc_lower = $('input.pwdgen-inc_lower').val();
                    const inc_upper = $('input.pwdgen-inc_upper').val();
                    const inc_symbols = $('input.pwdgen-inc_symbols').val();
                    const symbols = '!@#$%^&*(){}[]=<>/,.';

                    // Init pwd, clear pwdgen-list
                    let pwd;
                    $('.pwdgen-list').html('');

                    // Generate pwd
                    for ( let i = 0; i < count; i ++ ) {
                        pwd = generatePassword(inc_lower, inc_upper, inc_numbers, inc_symbols, length);
                        $('.pwdgen-list').append(
                            '<div class="pwdgen-item"><code>' + $('<div/>').text(pwd) .html() + '</code></div>'
                        );
                    }

                    // Defined functions
                    function generatePassword(lower, upper, number, symbol, length)
                    {
                        let generatedPassword = '';

                        for ( let i = 0; i < length; i ++ ) {
                            if (lower) {
                                const val = Math.random();
                                  generatedPassword += String.fromCharCode(Math.floor(val * 26) + 97);
                            }
                            if (upper) {
                                const val = Math.random();
                                generatedPassword += String.fromCharCode(Math.floor(val * 26) + 65);
                            }
                            if (number) {
                                const val = Math.random();
                                 generatedPassword += String.fromCharCode(Math.floor(val * 10) + 48);
                            }
                            if (symbol) {
                                const val = Math.random();
                                generatedPassword += symbols[Math.floor(val * symbols.length)];
                            }
                        }

                        const finalPassword = generatedPassword.slice(0, length);
                        return finalPassword;
                    }
                }
            )
        }
    );
    // End: Password Generator
})(jQuery);
