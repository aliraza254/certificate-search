<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://webtechsofts.co.uk/
 * @since      1.0.0
 *
 * @package    Certificate_Search
 * @subpackage Certificate_Search/admin
 */

/**
 * The short-code-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Certificate_Search
 * @subpackage Certificate_Search/admin
 * @author     Web Tech Softs <info@webtechsofts.com>
 */

class Certificate_Search_ShortCode {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Certificate_Search_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Certificate_Search_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/certificate-search-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Certificate_Search_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Certificate_Search_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/certificate-search-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function certificate_search_function(){
        $certificate_img = plugin_dir_url( dirname( __FILE__ ) ) . 'public/images/shang.png';
        $serial_img = plugin_dir_url( dirname( __FILE__ ) ) . 'public/images/xia.png';
        $html = <<<EOT
             <form method="post">
                <div class="certificate_search_container">
                    <div class="certificate_search_row">
                        <div class="certificate_search_content_text">Certificate No:</div>
                        <div class="certificate_content">
                            <input type="text" class="certificate_field"  name="certificate" required>
                            <img src="$certificate_img" class="certificate_field_img">
                        </div>
                    </div>
                    <div class="certificate_search_row">
                        <div class="certificate_search_content_text">Serial  No:</div>
                        <div class="certificate_content">
                            <input type="text" class="serial_field" name="serial" required>
                            <img src="$serial_img" class="serial_field_img">
                        </div>
                    </div>
                    <div class="certificate_search_row_btn">
                        <div class="certificate_search_row_btn_search"> <button type="submit" >Search</button></div>
                        <div><button id="certificate_search_row_btn_rest" type="reset">Reset</button></div>
                    </div>
                </div>
            </form>
            <div class="loading_screen_container">
                <div class="loader"></div>
            </div>
EOT;
        return $html;

    }

    public function certificate_search_result()
    {
        $certificate = isset($_POST['certificate_no']) ? $_POST['certificate_no'] : '';
        $serial = isset($_POST['serial_no']) ? $_POST['serial_no'] : '';


        $user_id = get_current_user_id();

        $_product = get_option('product_id');

        $args = array(
            'numberposts' => -1,
            'post_type' => wc_get_order_types(),
            'meta_key' => '_customer_user',
            'meta_value' => $user_id,
            'post_status' => array_keys(wc_get_order_statuses())
        );

        $orders = get_posts( $args );
        $user_has_product = false;
        foreach ($orders as $order) {
            $order = wc_get_order($order->ID);
            foreach ($order->get_items() as $item) {
                $product = $item->get_product();
                $date1 = $order->get_date_created()->date_i18n('Y-m-d');
                $date2 = date('Y-m-d');
                $diff = $this->isWithinOneYear($date1, $date2);
                if ($diff) {
                    if ($_product == $product->get_id()) {
                        if ($order->get_status('completed')){
                            $user_has_product = true;
                            break 2;
                        }
                    }
                }
            }
        }

        $args = array(
            'post_type' => 'info_table',
            'posts_per_page' => -1,
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $repeater_field = get_field('certificate_&_details_', get_the_ID());
                $serial_no = get_post_meta(get_the_ID(), 'serial_no', true);
                $certificate_no = get_post_meta(get_the_ID(), 'certificate_no', true);
                $exporter = get_post_meta( get_the_ID(), 'exporter', true );
                $authorized_by = get_post_meta( get_the_ID(), 'authorized_by', true );
                $invoice_no = get_post_meta( get_the_ID(), 'invoice_no', true );
                $country = get_post_meta( get_the_ID(), 'country', true );
                $hs_code = get_post_meta( get_the_ID(), 'hs_code', true );
                $issue_date = get_post_meta( get_the_ID(), 'issue_date', true );
                $verify_data = false;
                if ($certificate_no === $certificate && $serial_no === $serial) {
                    $verify_data = true;
                    ?>
                    <table class="customers">
                        <tbody class="to_append">
                        <tr>
                            <td>Certificate Number</td>
                            <td><?php echo $certificate_no?></td>
                        </tr>
                        <tr>
                            <td>Serial Number</td>
                            <td><?php echo $serial_no?></td>
                        </tr>
                        <tr>
                            <td>Exporter</td>
                            <td><?php echo $exporter?></td>
                        </tr>
                        <tr>
                            <td>Authorized by</td>
                            <td><?php echo $authorized_by?></td>
                        </tr>
                        <tr>
                            <td>Invoice No</td>
                            <td><?php echo $invoice_no?></td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td><?php echo $country?></td>
                        </tr>
                        <tr>
                            <td>H.S.Code</td>
                            <td><?php echo $hs_code?></td>
                        </tr>
                        <tr>
                            <td>Issue Date</td>
                            <td><?php echo $issue_date?></td>
                        </tr>
                        <?php
                        if (is_user_logged_in()) {
                            if ($user_has_product === true) {
                                if ($repeater_field) {
                                    foreach ($repeater_field as $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['lable'] ?></td>
                                            <td><?php echo $value['value']?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                    break;
                }
            }
            wp_reset_postdata();
        }
        if ($user_has_product == false) {
            ?>
            <div>
                <p class="more_subscription">Want To See More Info</p>
            </div>
            <?php
        }
        if ($verify_data === false){
            echo 'No Data Found!';
        }
        die();
    }

    public function isWithinOneYear($date1, $date2) {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        $differenceInYears = $interval->y;

        return $differenceInYears <= 1;
    }

    public function redirect_to_checkout_after_registration()
    {
        $info = isset($_COOKIE['more_info']) ? $_COOKIE['more_info'] : '';

        if ($info == '100') {
            $_product = get_option('product_id');
            $quantity = 1;
            WC()->cart->add_to_cart($_product, $quantity);
            $checkout_url = wc_get_checkout_url();
            wp_safe_redirect($checkout_url);
            setcookie('more_info', '', time() + (86400 * 30), "/");
            exit;
        }else{
            $myaccount_url = wc_get_account_endpoint_url('dashboard');
            wp_safe_redirect($myaccount_url);
            exit;
        }
    }

    public function certificate_search_subscription_result()
    {

        $certificate = isset($_POST['certificate_no']) ? $_POST['certificate_no'] : '';
        $serial = isset($_POST['serial_no']) ? $_POST['serial_no'] : '';

        if (!is_user_logged_in()) {
            setcookie('more_info', 100, time() + (86400 * 30), "/");
            ?>
            <script>
                window.location.replace("<?php echo esc_url( get_home_url().'/my-account' ); ?>");
            </script>
            <?php
            exit();
        }

        $user_id = get_current_user_id();

        $_product = get_option('product_id');

        $args = array(
            'numberposts' => -1,
            'post_type' => wc_get_order_types(),
            'meta_key' => '_customer_user',
            'meta_value' => $user_id,
            'post_status' => array_keys(wc_get_order_statuses())
        );

        $orders = get_posts( $args );

        $user_has_product = false;
        foreach ($orders as $order) {
            $order = wc_get_order($order->ID);
            foreach ($order->get_items() as $item) {
                $product = $item->get_product();
                $date1 = $order->get_date_created()->date_i18n('Y-m-d');
                $date2 = date('Y-m-d');
                $diff = $this->isWithinOneYear($date1, $date2);
                if ($diff) {
                    if ($_product == $product->get_id()) {
                        $user_has_product = true;
                        break 2;
                    }
                }
            }
        }
        if (!$user_has_product) {
            ?>
            <script>
                window.location.replace("<?php echo esc_url(get_permalink($_product)); ?>");
            </script>
            <?php
            exit();
        }

        $args = array(
            'post_type' => 'info_table',
            'posts_per_page' => -1,
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $repeater_field = get_field('certificate_&_details_', get_the_ID());
                $serial_no = get_post_meta(get_the_ID(), 'serial_no', true);
                $certificate_no = get_post_meta(get_the_ID(), 'certificate_no', true);
                if ($certificate_no === $certificate && $serial_no === $serial) {
                    ?>
                    <?php
                    if (is_user_logged_in()) {
                        if ($user_has_product === true){
                            if ($repeater_field) {
                                foreach ($repeater_field as $value) {
                                    ?>
                                    <tr>
                                        <td><?php echo $value['lable'] ?></td>
                                        <td><?php echo $value['value']?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    }
                    break;
                }
            }
            wp_reset_postdata();
        }
        die();
    }

    public function certificate_search_table_function()
    {
        $html = <<<EOT
        <div id="certificate_search_table">

        </div>
EOT;
        return $html;
    }
}