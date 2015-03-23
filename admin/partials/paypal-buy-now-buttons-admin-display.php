<?php

/**
 * @class       MBJ_PayPal_Payment_Admin_Display
 * @version	1.0.0
 * @package	paypal-buy-now-buttons
 * @category	Class
 * @author      plugingexperts <plugingexperts@gmail.com>
 */
class MBJ_PayPal_Payment_Admin_Display {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
        add_shortcode('paypal_buy_now_buttons', array(__CLASS__, 'paypal_button_shortcode'));
        add_filter('widget_text', 'do_shortcode');
    }

    /**
     * add_settings_menu helper function used for add menu for pluging setting
     * @since    1.0.0
     * @access   public
     */
    public static function add_settings_menu() {

        add_options_page('PayPal Buy Now Buttons Options', 'PayPal Buy Now Buttons', 'manage_options', 'paypal-buy-now-buttons', array(__CLASS__, 'paypal_buy_now_buttons_options'));
    }

    /**
     * paypal_ipn_for_wordpress_options helper will trigger hook and handle all the settings section 
     * @since    1.0.0
     * @access   public
     */
    public static function paypal_buy_now_buttons_options() {
        $setting_tabs = apply_filters('paypal_buy_now_buttons_options_setting_tab', array('general' => 'General', 'help' => 'Help'));
        $current_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
        ?>
        <h2 class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs as $name => $label)
                echo '<a href="' . admin_url('admin.php?page=paypal-buy-now-buttons&tab=' . $name) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
        </h2>
        <?php
        foreach ($setting_tabs as $setting_tabkey => $setting_tabvalue) {
            switch ($setting_tabkey) {
                case $current_tab:
                    do_action('paypal_buy_now_buttons_' . $setting_tabkey . '_setting_save_field');
                    do_action('paypal_buy_now_buttons_' . $setting_tabkey . '_setting');
                    break;
            }
        }
    }

    public static function paypal_button_shortcode($atts, $content = null) {

        $paypal_buy_now_buttons_custom_button = get_option('paypal_buy_now_buttons_custom_button');
        $paypal_buy_now_buttons_return_page = get_option('paypal_buy_now_buttons_return_page');
        $paypal_buy_now_buttons_currency = get_option('paypal_buy_now_buttons_currency');
        $paypal_buy_now_buttons_bussiness_email = get_option('paypal_buy_now_buttons_bussiness_email');
        $paypal_payment_PayPal_sandbox = get_option('paypal_buy_now_buttons_PayPal_sandbox');


        if (isset($paypal_payment_PayPal_sandbox) && $paypal_payment_PayPal_sandbox == 'yes') {
            $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        }

        ob_start();

        $output = '';
        $output = '<div class="page-sidebar widget">';

        $output .= '<form action="' . esc_url($paypal_url) . '" method="post" target="_blank">';

        if (isset($atts) && !empty($atts)) {
            foreach ($atts as $attskey => $attsvalue) {
                if ((isset($attskey) && !empty($attskey)) && (isset($attsvalue) && !empty($attsvalue) )) {
                    $output .= '<input type="hidden" name="' . esc_attr($attskey) . '" value="' . esc_attr($attsvalue) . '">';
                }
            }
        }

        $output .= '<input type="hidden" name="business" value="' . esc_attr($paypal_buy_now_buttons_bussiness_email) . '">';

        $output .= '<input type="hidden" name="bn" value="mbjtechnolabs_SP">';

        $output .= '<input type="hidden" name="cmd" value="_xclick">';


        if (isset($paypal_buy_now_buttons_currency) && !empty($paypal_buy_now_buttons_currency)) {
            $output .= '<input type="hidden" name="currency_code" value="' . esc_attr($paypal_buy_now_buttons_currency) . '">';
        }

        if (isset($paypal_payment_notify_url) && !empty($paypal_payment_notify_url)) {
            $output .= '<input type="hidden" name="notify_url" value="' . esc_url($paypal_payment_notify_url) . '">';
        }

        if (isset($paypal_buy_now_buttons_return_page) && !empty($paypal_buy_now_buttons_return_page)) {
            $paypal_buy_now_buttons_return_page = get_permalink($paypal_buy_now_buttons_return_page);
            $output .= '<input type="hidden" name="return" value="' . esc_url($paypal_buy_now_buttons_return_page) . '">';
        }

        $output .= '<input type="image" name="submit" border="0" src="' . esc_url($paypal_buy_now_buttons_custom_button) . '" alt="PayPal - The safer, easier way to pay online">';
        $output .= '</form></div>';

        return $output;
        return ob_get_clean();
    }

}

MBJ_PayPal_Payment_Admin_Display::init();
