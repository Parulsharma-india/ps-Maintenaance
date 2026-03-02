(function($) {
    'use strict';

    // File input preview
    $('input[type="file"]').on('change', function() {
        const file = this.files?.[0];
        if (!file) return;

        const $preview = $(this).closest('td').find('.ps-preview');
        const reader = new FileReader();

        reader.onload = e => {
            $preview.html('<img src="' + e.target.result + '" style="max-width:160px;">');
        };

        reader.readAsDataURL(file);
    });

    // Maintenance toggle preview link
    $(document).ready(function() {
        const toggle = $('#ps-enabled-toggle')[0];
        const previewLink = $('#ps-preview-link')[0];

        if (toggle && previewLink) {
            toggle.addEventListener('change', function() {
                previewLink.style.display = this.checked ? '' : 'none';
            });
        }
    });

})(jQuery);
