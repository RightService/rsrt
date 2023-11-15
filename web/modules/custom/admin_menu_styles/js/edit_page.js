(function ($) {
  $(document).ready(function () {
    const $reachSelection = $('#edit-field-reach');
    const $checkboxes = $('#edit-field-counties input[type="checkbox"]');

    if ($reachSelection.get(0).value !== '_none') {
      hideClone();
      $checkboxes.prop('disabled', true);
    }

    $reachSelection.change(function () {
      if (this.value !== '_none') {
        $checkboxes.prop('checked', true);
        hideClone();
        $checkboxes.prop('disabled', true);
      }
      else {
        $('#county-clone').remove();
        $checkboxes.prop('disabled', false);
      }
    });

    function hideClone() {
      $('#county-clone').remove();
      $('#edit-field-counties').append('<div id="county-clone"></div>');
      $checkboxes.clone().prop('hidden', true).appendTo('#county-clone');
    }
  });
})(jQuery);
