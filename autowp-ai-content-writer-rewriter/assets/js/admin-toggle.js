jQuery(document).on('click', '.toggle-status', function(e) {
    e.preventDefault();
    var btn = jQuery(this);
    var websiteId = btn.data('id');
    jQuery.post(ajaxurl, { 
        action: 'autowp_toggle_website', 
        id: websiteId, 
        security: '<?php echo wp_create_nonce("autowp_toggle_nonce"); ?>' 
    }, function(response) {
        if(response.success) {
            // response.data.new_status: 1 (aktif) veya 0 (pasif)
            var newStatus = response.data.new_status;
            btn.text(newStatus ? "<?php echo esc_js(__('Stop', 'autowp')); ?>" : "<?php echo esc_js(__('Start', 'autowp')); ?>");
            if(newStatus) {
                btn.removeClass('btn-success').addClass('btn-danger');
            } else {
                btn.removeClass('btn-danger').addClass('btn-success');
            }
        }
    });
});
