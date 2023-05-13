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
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Certificate_Search
 * @subpackage Certificate_Search/admin
 * @author     Web Tech Softs <info@webtechsofts.com>
 */
class Certificate_Search_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
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

    public function create_custom_post_type() {
        register_post_type( 'info_table',
            array(
                'labels' => array(
                    'name' => __( 'Info Table' ),
                    'singular_name' => __( 'Info Table' )
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array( 'title', '', '' ),
                'menu_icon' => 'dashicons-book',
                'rewrite' => array('slug' => 'info_table'),
            )
        );

        register_meta( 'serial_no', 'certificate_no', 'exporter', 'authorized_by', 'invoice_no', 'country', 'hs_code', 'issue_date', array(
            'type' => 'string',
            'description' => __( 'Custom Field', 'textdomain' ),
            'single' => true,
            'show_in_rest' => true,
        ) );
    }

    public function add_custom_metabox() {
        add_meta_box(
            'custom_metabox',
            __( 'Unsubscribe User', 'textdomain' ),
            array($this, 'render_custom_metabox'),
            'info_table',
            'normal',
            'default'
        );
    }

    public function render_custom_metabox( $post ) {
        wp_nonce_field( 'custom_metabox_nonce', 'custom_metabox_nonce' );
        $certificate_no = get_post_meta( $post->ID, 'certificate_no', true );
        $serial_no = get_post_meta( $post->ID, 'serial_no', true );
        $exporter = get_post_meta( $post->ID, 'exporter', true );
        $authorized_by = get_post_meta( $post->ID, 'authorized_by', true );
        $invoice_no = get_post_meta( $post->ID, 'invoice_no', true );
        $country = get_post_meta( $post->ID, 'country', true );
        $hs_code = get_post_meta( $post->ID, 'hs_code', true );
        $issue_date = get_post_meta( $post->ID, 'issue_date', true );
        ?>
        <div class="unsubscribe_data">
            <label for="certificate_no"><?php esc_html_e( 'Certificate No', 'textdomain' ); ?></label>
            <input type="text" name="certificate_no" id="certificate_no" value="<?php echo esc_attr( $certificate_no ); ?>">
        </div>
        <div class="unsubscribe_data">
            <label for="serial_no"><?php esc_html_e( 'Serial No', 'textdomain' ); ?></label>
            <input type="text" name="serial_no" id="serial_no" value="<?php echo esc_attr( $serial_no ); ?>">
        </div>
        <div class="unsubscribe_data">
            <label for="exporter"><?php esc_html_e( 'Exporter', 'textdomain' ); ?></label>
            <input type="text" name="exporter" id="exporter" value="<?php echo esc_attr( $exporter ); ?>">
        </div>
        <div class="unsubscribe_data">
            <label for="authorized_by"><?php esc_html_e( 'Authorized By', 'textdomain' ); ?></label>
            <input type="text" name="authorized_by" id="authorized_by" value="<?php echo esc_attr( $authorized_by ); ?>">
        </div>
        <div class="unsubscribe_data">
            <label for="invoice_no"><?php esc_html_e( 'Invoice No', 'textdomain' ); ?></label>
            <input type="text" name="invoice_no" id="invoice_no" value="<?php echo esc_attr( $invoice_no ); ?>">
        </div>
        <div class="unsubscribe_data">
            <label for="country"><?php esc_html_e( 'Country', 'textdomain' ); ?></label>
            <input type="text" name="country" id="country" value="<?php echo esc_attr( $country ); ?>">
        </div>
        <div class="unsubscribe_data">
            <label for="hs_code"><?php esc_html_e( 'H.S.Code', 'textdomain' ); ?></label>
            <input type="text" name="hs_code" id="hs_code" value="<?php echo esc_attr( $hs_code ); ?>">
        </div>
        <div class="unsubscribe_data">
            <label for="issue_date"><?php esc_html_e( 'Issue Date', 'textdomain' ); ?></label>
            <input type="text" name="issue_date" id="issue_date" value="<?php echo esc_attr( $issue_date ); ?>">
        </div>
        <?php
    }

    public function save_custom_fields( $post_id ) {
        if ( ! isset( $_POST['custom_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['custom_metabox_nonce'], 'custom_metabox_nonce' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['certificate_no'] ) ) {
                update_post_meta( $post_id, 'certificate_no', sanitize_text_field( $_POST['certificate_no'] ) );
            }
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['serial_no'] ) ) {
                update_post_meta( $post_id, 'serial_no', sanitize_text_field( $_POST['serial_no'] ) );
            }
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['exporter'] ) ) {
                update_post_meta( $post_id, 'exporter', sanitize_text_field( $_POST['exporter'] ) );
            }
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['invoice_no'] ) ) {
                update_post_meta( $post_id, 'invoice_no', sanitize_text_field( $_POST['invoice_no'] ) );
            }
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['authorized_by'] ) ) {
                update_post_meta( $post_id, 'authorized_by', sanitize_text_field( $_POST['authorized_by'] ) );
            }
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['country'] ) ) {
                update_post_meta( $post_id, 'country', sanitize_text_field( $_POST['country'] ) );
            }
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['hs_code'] ) ) {
                update_post_meta( $post_id, 'hs_code', sanitize_text_field( $_POST['hs_code'] ) );
            }
        }
        if ( isset( $_POST['post_type'] ) && 'info_table' == $_POST['post_type'] ) {
            if ( isset( $_POST['issue_date'] ) ) {
                update_post_meta( $post_id, 'issue_date', sanitize_text_field( $_POST['issue_date'] ) );
            }
        }
    }

    public function admin_menu_function(){
        add_menu_page(
            'Certification Setting',
            'Certification Setting',
            'manage_options',
            'certification-setting',
            array($this, 'admin_menu_function_callback'),
            'dashicons-admin-generic',
            6
        );
        register_setting('certificate_settings_group', 'product_id');
    }

    public function admin_menu_function_callback() {
        $_product = get_option('product_id');
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'product',
        );
        $products = get_posts($args);
        ?>
        <div class="wrap">
            <h1>Certification Setting</h1>
            <br />
            <form method="post" action="options.php">
                <?php
                settings_fields('certificate_settings_group');
                ?>
                <label for="custom-menu-input-field">Product</label>
                <select name="product_id">
                    <option>Select Product</option>
                    <?php
                    foreach ($products as $product) {
                        ?>
                        <option value="<?php echo esc_attr($product->ID); ?>" <?php echo ($product->ID == $_product) ? 'selected' : ''?>><?php echo esc_attr($product->post_title);?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function admin_init_function() {

    }

}
