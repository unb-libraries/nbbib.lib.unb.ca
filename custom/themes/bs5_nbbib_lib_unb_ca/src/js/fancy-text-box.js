(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.fancyTextBox = {
    attach: function (context) {
      const exposedTextBoxes = once('fancyTextBox', ".views-exposed-form input[type='text']", context);
      
      // On document ready (handled inside the attach function).
      exposedTextBoxes.forEach((textBox) => {
        // Append empty class if empty.
        if (!textBox.value) {
          textBox.classList.add('empty');
        } else {
          textBox.classList.remove('empty');
        }

        // Set empty class when leaving the box (blur event).
        textBox.addEventListener('blur', function () {
          if (!this.value) {
            this.classList.add('empty');
          } else {
            this.classList.remove('empty');
          }
        });
      });
    },
  };
})(Drupal, once);