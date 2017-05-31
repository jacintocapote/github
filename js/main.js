(function ($, Drupal) {
  Drupal.behaviors.github_inline_currency = {
    attach: function (context, settings) {
      $('.currency-convert').curry();
    }
  };
})(jQuery, Drupal);
