<?php

/**
 * @class       MBJ_PayPal_Buy_Now_Buttons_i18n
 * @version	1.0.0
 * @package	paypal-buy-now-buttons
 * @category	Class
 * @author      plugingexperts <plugingexperts@gmail.com>
 */
class MBJ_PayPal_Buy_Now_Buttons_i18n {

    /**
     * The domain specified for this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $domain    The domain identifier for this plugin.
     */
    private $domain;

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
                $this->domain, false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

    /**
     * Set the domain equal to that of the specified domain.
     *
     * @since    1.0.0
     * @param    string    $domain    The domain that represents the locale of this plugin.
     */
    public function set_domain($domain) {
        $this->domain = $domain;
    }

}
