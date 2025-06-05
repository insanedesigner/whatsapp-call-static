<?php
/**
 * Plugin Name: Static Contact Buttons
 * Plugin URI: https://www.sangamamtech.com/plugins/static-contact-buttons
 * Description: A lightweight WordPress plugin that adds always-visible WhatsApp and Call buttons on all pages. Includes an admin settings panel for easy number updates.
 * Version: 1.1
 * Author: Sudeep S
 * Author URI: https://www.sudeep.co.in
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: static-contact-buttons
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Enqueue CSS for buttons
function scb_enqueue_styles() {
    wp_enqueue_style( 'scb-style', plugins_url( 'style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'scb_enqueue_styles' );

// Output the static buttons
function scb_output_buttons() {
    $whatsapp_number = get_option( 'scb_whatsapp_number', '971501234567' );
    $call_number = get_option( 'scb_call_number', '971501234567' );
    ?>
    <div class="scb-buttons">
        <a href="https://wa.me/<?php echo esc_attr( $whatsapp_number ); ?>" class="scb-whatsapp" target="_blank" title="WhatsApp">
            📱 WhatsApp
        </a>
        <a href="tel:<?php echo esc_attr( $call_number ); ?>" class="scb-call" title="Call">
            📞 Call
        </a>
    </div>
    <?php
}
add_action( 'wp_footer', 'scb_output_buttons' );

// Add settings page
function scb_add_admin_menu() {
    add_options_page(
        'Static Contact Buttons Settings',
        'Contact Buttons',
        'manage_options',
        'static-contact-buttons',
        'scb_settings_page'
    );
}
add_action( 'admin_menu', 'scb_add_admin_menu' );

// Register settings
function scb_settings_init() {
    register_setting( 'scb_settings', 'scb_whatsapp_number' );
    register_setting( 'scb_settings', 'scb_call_number' );

    add_settings_section(
        'scb_settings_section',
        'Contact Numbers',
        '__return_false',
        'static-contact-buttons'
    );

    add_settings_field(
        'scb_whatsapp_number',
        'WhatsApp Number (no +)',
        'scb_whatsapp_field_render',
        'static-contact-buttons',
        'scb_settings_section'
    );

    add_settings_field(
        'scb_call_number',
        'Call Number (no +)',
        'scb_call_field_render',
        'static-contact-buttons',
        'scb_settings_section'
    );
}
add_action( 'admin_init', 'scb_settings_init' );

function scb_whatsapp_field_render() {
    $value = get_option( 'scb_whatsapp_number', '' );
    echo '<input type="text" name="scb_whatsapp_number" value="' . esc_attr( $value ) . '" placeholder="971501234567">';
}

function scb_call_field_render() {
    $value = get_option( 'scb_call_number', '' );
    echo '<input type="text" name="scb_call_number" value="' . esc_attr( $value ) . '" placeholder="971501234567">';
}

function scb_settings_page() {
    ?>
    <div class="wrap">
        <h1>Static Contact Buttons Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'scb_settings' );
            do_settings_sections( 'static-contact-buttons' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
