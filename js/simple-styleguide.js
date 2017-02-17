/**
 * @file
 * Primary application javascript.
 *
 */

(function ($) {
    Drupal.behaviors.app = {
        attach: function (context, settings) {
            jQuery('.simple-styleguide--view-sourecode').click(function() {
                jQuery(this).next('pre').toggle();
            });
        }
    }
})(jQuery);
