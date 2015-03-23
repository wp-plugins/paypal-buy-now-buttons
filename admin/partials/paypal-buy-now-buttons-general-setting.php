<?php

/**
 * @class       MBJ_PayPal_Buy_Now_Buttons_General_Setting
 * @version	1.0.0
 * @package	paypal-buy-now-buttons
 * @category	Class
 * @author      plugingexperts <plugingexperts@gmail.com>
 */
class MBJ_PayPal_Buy_Now_Buttons_General_Setting {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {

        add_action('paypal_buy_now_buttons_general_setting', array(__CLASS__, 'paypal_buy_now_buttons_general_setting_function'));
        add_action('paypal_buy_now_buttons_help_setting', array(__CLASS__, 'paypal_buy_now_buttons_help_setting'));
        add_action('paypal_buy_now_buttons_general_setting_save_field', array(__CLASS__, 'paypal_buy_now_buttons_general_setting_save_field'));
    }

    public static function help() {


        echo '<p>' . __('Some dynamic tags can be included in your email template :', 'wp-better-emails') . '</p>
					<ul>
						<li>' . __('<strong>%blog_url%</strong> : will be replaced with your blog URL.', 'wp-better-emails') . '</li>
						<li>' . __('<strong>%home_url%</strong> : will be replaced with your home URL.', 'wp-better-emails') . '</li>
						<li>' . __('<strong>%blog_name%</strong> : will be replaced with your blog name.', 'wp-better-emails') . '</li>
						<li>' . __('<strong>%blog_description%</strong> : will be replaced with your blog description.', 'wp-better-emails') . '</li>
						<li>' . __('<strong>%admin_email%</strong> : will be replaced with admin email.', 'wp-better-emails') . '</li>
						<li>' . __('<strong>%date%</strong> : will be replaced with current date, as formatted in <a href="options-general.php">general options</a>.', 'wp-better-emails') . '</li>
						<li>' . __('<strong>%time%</strong> : will be replaced with current time, as formatted in <a href="options-general.php">general options</a>.', 'wp-better-emails') . '</li>
                                                <li>' . __('<strong>%txn_id%</strong> : will be replaced with PayPal payment transaction ID.', 'wp-better-emails') . '</li>
                                                <li>' . __('<strong>%receiver_email%</strong> : will be replaced with PayPal payment receiver email address%.', 'wp-better-emails') . '</li>
                                                <li>' . __('<strong>%payment_date%</strong> : will be replaced with PayPal payment date%.', 'wp-better-emails') . '</li>
                                                <li>' . __('<strong>%first_name%</strong> : will be replaced with PayPal payment first name%.', 'wp-better-emails') . '</li>
                                                <li>' . __('<strong>%last_name%</strong> : will be replaced with PayPal payment last name%.', 'wp-better-emails') . '</li>
                                                <li>' . __('<strong>%mc_currency%</strong> : will be replaced with PayPal payment currency like USD', 'wp-better-emails') . '</li>
                                                <li>' . __('<strong>%mc_gross%</strong> : will be replaced with PayPal payment amount', 'wp-better-emails') . '</li>
                                          </ul>';
    }

    public static function paypal_buy_now_buttons_setting_fields() {

        $currency_code_options = self::get_paypal_buy_now_buttons_currencies();

        foreach ($currency_code_options as $code => $name) {
            $currency_code_options[$code] = $name . ' (' . self::get_paypal_buy_now_buttons_symbol($code) . ')';
        }

        $fields[] = array('title' => __('PayPal Account Setup', 'paypal-buy-now-buttons'), 'type' => 'title', 'desc' => '', 'id' => 'general_options');

        $fields[] = array(
            'title' => __('Enable PayPal sandbox', 'paypal-buy-now-buttons'),
            'type' => 'checkbox',
            'id' => 'paypal_buy_now_buttons_PayPal_sandbox',
            'label' => __('Enable PayPal sandbox', 'paypal-buy-now-buttons'),
            'default' => 'no',
            'css' => 'min-width:300px;',
            'desc' => sprintf(__('PayPal sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>.', 'paypal-buy-now-buttons'), 'https://developer.paypal.com/'),
        );



        $fields[] = array(
            'title' => __('PayPal Email address to receive payments', 'paypal-buy-now-buttons'),
            'type' => 'email',
            'id' => 'paypal_buy_now_buttons_bussiness_email',
            'desc' => __('This is the Paypal Email address where the payments will go.', 'paypal-buy-now-buttons'),
            'default' => '',
            'placeholder' => 'you@youremail.com',
            'css' => 'min-width:300px;',
            'class' => 'input-text regular-input'
        );


        $fields[] = array(
            'title' => __('Currency', 'paypal-buy-now-buttons'),
            'desc' => __('This is the currency for your visitors to make Payments or Payments in.', 'paypal-buy-now-buttons'),
            'id' => 'paypal_buy_now_buttons_currency',
            'css' => 'min-width:250px;',
            'default' => 'GBP',
            'type' => 'select',
            'class' => 'chosen_select',
            'options' => $currency_code_options
        );

        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');

        $fields[] = array('title' => __('Optional Settings', 'paypal-buy-now-buttons'), 'type' => 'title', 'desc' => '', 'id' => 'general_options');


        $fields[] = array(
            'title' => __('Select Thank you page', 'paypal-buy-now-buttons'),
            'id' => 'paypal_buy_now_buttons_return_page',
            'desc' => __('URL to which the Payer comes to after completing the payment; for example, a URL on your site that displays a "Thank you for your payment".', 'paypal-buy-now-buttons'),
            'type' => 'single_select_page',
            'default' => '',
            'class' => 'chosen_select_nostd',
            'css' => 'min-width:300px;',
        );



        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');

        $fields[] = array('title' => __('Payment Button', 'paypal-buy-now-buttons'), 'type' => 'title', 'desc' => '', 'id' => 'general_options');


        $fields[] = array(
            'title' => __('PayPal Custom Button URL', 'paypal-buy-now-buttons'),
            'type' => 'text',
            'id' => 'paypal_buy_now_buttons_custom_button',
            'desc' => __('Enter a URL to a custom payment button.', 'paypal-buy-now-buttons'),
            'default' => 'https://www.paypalobjects.com/en_AU/i/btn/btn_buynow_LG.gif',
            'css' => 'min-width:300px;',
            'class' => 'input-text regular-input'
        );



        $fields[] = array('type' => 'sectionend', 'id' => 'general_options');
        return $fields;
    }

    public static function paypal_buy_now_buttons_general_setting_save_field() {

        $paypal_buy_now_buttons_setting_fields = self::paypal_buy_now_buttons_setting_fields();
        $Html_output = new MBJ_PayPal_Buy_Now_Buttons_Html_output();
        $Html_output->save_fields($paypal_buy_now_buttons_setting_fields);
    }

    public static function paypal_buy_now_buttons_help_setting() {
        ?>
        <div class="postbox">
            <h2><label for="title">&nbsp;&nbsp;Plugin Usage</label></h2>
            <div class="inside">      
                <p>There are a few ways you can use this plugin:</p>
                <ol>
                    <li>Configure the options below and then add the shortcode <strong>[paypal_buy_now_buttons]</strong> to a post or page (where you want the payment button)</li>
                    <li>Call the function from a template file: <strong>&lt;?php echo do_shortcode( '[paypal_buy_now_buttons]' ); ?&gt;</strong></li>
                    <li>Use the <strong>PayPal Payment</strong> Widget from the Widgets menu</li>
                </ol>
                <p><h3>Archive of PayPal Buttons and Images</h3><br>
                The following reference pages list the localized PayPal buttons and images and their URLs.
                </p>
                <p><h4>English</h4></p>
                <ul>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/AU/">Australia</a></li>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/US-UK/">United Kingdom</a></li>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/US-UK/">United States</a></li>
                </ul>
                <p><h4>Asia-Pacific</h4></p>
                <ul>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/JP/">Japan</a></li>
                </ul>
                <p><h4>EU Non-English</h4></p>
                <ul>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/DE/">Germany</a></li>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/ES/">Spain</a></li>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/FR/">France</a></li>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/IT/">Italy</a></li>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/NL/">Netherlands</a></li>
                    <li><a target="_blank" href="https://developer.paypal.com/docs/classic/archive/buttons/PL/">Poland</a></li>
                </ul>
                <br>
                <h2> <label>Email dynamic tag list</label></h2>
        <?php self::help(); ?>
            </div></div>
        <?php
    }

    public static function paypal_buy_now_buttons_general_setting_function() {
        $paypal_buy_now_buttons_setting_fields = self::paypal_buy_now_buttons_setting_fields();
        $Html_output = new MBJ_PayPal_Buy_Now_Buttons_Html_output();
        ?>

        <form id="mailChimp_integration_form" enctype="multipart/form-data" action="" method="post">
        <?php $Html_output->init($paypal_buy_now_buttons_setting_fields); ?>
            <p class="submit">
                <input type="submit" name="mailChimp_integration" class="button-primary" value="<?php esc_attr_e('Save changes', 'Option'); ?>" />
            </p>
        </form>
        <?php
    }

    /**
     * Get full list of currency codes.
     * @return array
     */
    public static function get_paypal_buy_now_buttons_currencies() {
        return array_unique(
                apply_filters('paypal_buy_now_buttons_currencies', array(
            'AED' => __('United Arab Emirates Dirham', 'paypal-buy-now-buttons'),
            'AUD' => __('Australian Dollars', 'paypal-buy-now-buttons'),
            'BDT' => __('Bangladeshi Taka', 'paypal-buy-now-buttons'),
            'BRL' => __('Brazilian Real', 'paypal-buy-now-buttons'),
            'BGN' => __('Bulgarian Lev', 'paypal-buy-now-buttons'),
            'CAD' => __('Canadian Dollars', 'paypal-buy-now-buttons'),
            'CLP' => __('Chilean Peso', 'paypal-buy-now-buttons'),
            'CNY' => __('Chinese Yuan', 'paypal-buy-now-buttons'),
            'COP' => __('Colombian Peso', 'paypal-buy-now-buttons'),
            'CZK' => __('Czech Koruna', 'paypal-buy-now-buttons'),
            'DKK' => __('Danish Krone', 'paypal-buy-now-buttons'),
            'DOP' => __('Dominican Peso', 'paypal-buy-now-buttons'),
            'EUR' => __('Euros', 'paypal-buy-now-buttons'),
            'HKD' => __('Hong Kong Dollar', 'paypal-buy-now-buttons'),
            'HRK' => __('Croatia kuna', 'paypal-buy-now-buttons'),
            'HUF' => __('Hungarian Forint', 'paypal-buy-now-buttons'),
            'ISK' => __('Icelandic krona', 'paypal-buy-now-buttons'),
            'IDR' => __('Indonesia Rupiah', 'paypal-buy-now-buttons'),
            'INR' => __('Indian Rupee', 'paypal-buy-now-buttons'),
            'NPR' => __('Nepali Rupee', 'paypal-buy-now-buttons'),
            'ILS' => __('Israeli Shekel', 'paypal-buy-now-buttons'),
            'JPY' => __('Japanese Yen', 'paypal-buy-now-buttons'),
            'KIP' => __('Lao Kip', 'paypal-buy-now-buttons'),
            'KRW' => __('South Korean Won', 'paypal-buy-now-buttons'),
            'MYR' => __('Malaysian Ringgits', 'paypal-buy-now-buttons'),
            'MXN' => __('Mexican Peso', 'paypal-buy-now-buttons'),
            'NGN' => __('Nigerian Naira', 'paypal-buy-now-buttons'),
            'NOK' => __('Norwegian Krone', 'paypal-buy-now-buttons'),
            'NZD' => __('New Zealand Dollar', 'paypal-buy-now-buttons'),
            'PYG' => __('Paraguayan Guaraní', 'paypal-buy-now-buttons'),
            'PHP' => __('Philippine Pesos', 'paypal-buy-now-buttons'),
            'PLN' => __('Polish Zloty', 'paypal-buy-now-buttons'),
            'GBP' => __('Pounds Sterling', 'paypal-buy-now-buttons'),
            'RON' => __('Romanian Leu', 'paypal-buy-now-buttons'),
            'RUB' => __('Russian Ruble', 'paypal-buy-now-buttons'),
            'SGD' => __('Singapore Dollar', 'paypal-buy-now-buttons'),
            'ZAR' => __('South African rand', 'paypal-buy-now-buttons'),
            'SEK' => __('Swedish Krona', 'paypal-buy-now-buttons'),
            'CHF' => __('Swiss Franc', 'paypal-buy-now-buttons'),
            'TWD' => __('Taiwan New Dollars', 'paypal-buy-now-buttons'),
            'THB' => __('Thai Baht', 'paypal-buy-now-buttons'),
            'TRY' => __('Turkish Lira', 'paypal-buy-now-buttons'),
            'USD' => __('US Dollars', 'paypal-buy-now-buttons'),
            'VND' => __('Vietnamese Dong', 'paypal-buy-now-buttons'),
            'EGP' => __('Egyptian Pound', 'paypal-buy-now-buttons'),
                        )
                )
        );
    }

    /**
     * Get Currency symbol.
     * @param string $currency (default: '')
     * @return string
     */
    public static function get_paypal_buy_now_buttons_symbol($currency = '') {
        if (!$currency) {
            $currency = get_paypal_buy_now_buttons_currencies();
        }

        switch ($currency) {
            case 'AED' :
                $currency_symbol = 'د.إ';
                break;
            case 'BDT':
                $currency_symbol = '&#2547;&nbsp;';
                break;
            case 'BRL' :
                $currency_symbol = '&#82;&#36;';
                break;
            case 'BGN' :
                $currency_symbol = '&#1083;&#1074;.';
                break;
            case 'AUD' :
            case 'CAD' :
            case 'CLP' :
            case 'COP' :
            case 'MXN' :
            case 'NZD' :
            case 'HKD' :
            case 'SGD' :
            case 'USD' :
                $currency_symbol = '&#36;';
                break;
            case 'EUR' :
                $currency_symbol = '&euro;';
                break;
            case 'CNY' :
            case 'RMB' :
            case 'JPY' :
                $currency_symbol = '&yen;';
                break;
            case 'RUB' :
                $currency_symbol = '&#1088;&#1091;&#1073;.';
                break;
            case 'KRW' : $currency_symbol = '&#8361;';
                break;
            case 'PYG' : $currency_symbol = '&#8370;';
                break;
            case 'TRY' : $currency_symbol = '&#8378;';
                break;
            case 'NOK' : $currency_symbol = '&#107;&#114;';
                break;
            case 'ZAR' : $currency_symbol = '&#82;';
                break;
            case 'CZK' : $currency_symbol = '&#75;&#269;';
                break;
            case 'MYR' : $currency_symbol = '&#82;&#77;';
                break;
            case 'DKK' : $currency_symbol = 'kr.';
                break;
            case 'HUF' : $currency_symbol = '&#70;&#116;';
                break;
            case 'IDR' : $currency_symbol = 'Rp';
                break;
            case 'INR' : $currency_symbol = 'Rs.';
                break;
            case 'NPR' : $currency_symbol = 'Rs.';
                break;
            case 'ISK' : $currency_symbol = 'Kr.';
                break;
            case 'ILS' : $currency_symbol = '&#8362;';
                break;
            case 'PHP' : $currency_symbol = '&#8369;';
                break;
            case 'PLN' : $currency_symbol = '&#122;&#322;';
                break;
            case 'SEK' : $currency_symbol = '&#107;&#114;';
                break;
            case 'CHF' : $currency_symbol = '&#67;&#72;&#70;';
                break;
            case 'TWD' : $currency_symbol = '&#78;&#84;&#36;';
                break;
            case 'THB' : $currency_symbol = '&#3647;';
                break;
            case 'GBP' : $currency_symbol = '&pound;';
                break;
            case 'RON' : $currency_symbol = 'lei';
                break;
            case 'VND' : $currency_symbol = '&#8363;';
                break;
            case 'NGN' : $currency_symbol = '&#8358;';
                break;
            case 'HRK' : $currency_symbol = 'Kn';
                break;
            case 'EGP' : $currency_symbol = 'EGP';
                break;
            case 'DOP' : $currency_symbol = 'RD&#36;';
                break;
            case 'KIP' : $currency_symbol = '&#8365;';
                break;
            default : $currency_symbol = '';
                break;
        }

        return apply_filters('paypal_buy_now_buttons_currency_symbol', $currency_symbol, $currency);
    }

}

MBJ_PayPal_Buy_Now_Buttons_General_Setting::init();
