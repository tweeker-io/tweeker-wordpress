<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/**
 * The fie is executed when plugin is removed
 * Deletes setting field values saved in DB
 **/
delete_option( 'tweekerio_wp_business_id' );
delete_option( 'tweekerio_wp_embed_version' );