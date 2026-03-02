<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/ps-maintenaance
 * @since      1.0.0
 *
 * @package    ps_Maintenaance
 * @subpackage ps_Maintenaance/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ps_Maintenaance
 * @subpackage ps_Maintenaance/admin
 * @author     Parul Sharma <29parulsharma2001@gmail.com>
 */


if ( ! defined( 'ABSPATH' ) ) { exit; }

class PS_Maintenaance_Admin {

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
		 * defined in PS_Maintenaance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PS_Maintenaance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style($this->plugin_name . '-admin',plugin_dir_url( __FILE__ ) . 'css/ps-maintenaance-admin.css',[],filemtime( plugin_dir_path( __FILE__ ) . 'css/ps-maintenaance-admin.css' ),'all');
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
		 * defined in PS_Maintenaance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PS_Maintenaance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/ps-maintenaance-admin.js', [ 'jquery' ], filemtime( plugin_dir_path( __FILE__ ) . 'js/ps-maintenaance-admin.js' ),true);
		
	} 
	 public function ps_maintenaance_add_plugin_page() {
        add_menu_page(
            'ps maintenaance',
            'ps maintenaance',
            'manage_options',
            'ps-maintenaance',
            [ $this, 'ps_maintenaance_render_admin_page' ],
            'dashicons-hammer',
            80
        );
    }

	public function ps_maintenaance_admin_bar_toggle( $wp_admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) { return; }

		$ps_maintenance_opts = get_option( 'ps_maintenance_options', [] );
		$enabled = ! empty( $ps_maintenance_opts['enabled'] );

		// Toggle URL (admin-post handler with nonce)
		$toggleUrl = wp_nonce_url(
			admin_url( 'admin-post.php?action=ps_maintenaance_toggle' ),
			'ps_maintenaance_toggle_action',
			'ps_maintenaance_toggle_nonce'
		);

		$label = $enabled ? 'Disable Maintenance' : 'Enable Maintenance';
		$title = ($enabled ? '🟢 ' : '⚪ ') . $label;

		$wp_admin_bar->add_node( [
			'id'    => 'ps-maintenaance-toggle',
			'title' => $title,
			'href'  => $toggleUrl,
			'meta'  => [
				'title' => 'Toggle maintenance mode',
				'class' => 'ps-maint-toggle-node'
			],
		] );
    }

	public function ps_maintenaance_handle_toggle() {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Unauthorized' ); }
		check_admin_referer( 'ps_maintenaance_toggle_action', 'ps_maintenaance_toggle_nonce' );

		$ps_maintenance_opts = get_option( 'ps_maintenance_options', [] );
		$current = ! empty( $ps_maintenance_opts['enabled'] ) ? 1 : 0;
		$ps_maintenance_opts['enabled'] = $current ? 0 : 1;

		update_option( 'ps_maintenance_options', $ps_maintenance_opts );

		$redirect = wp_get_referer();
		if ( ! $redirect ) { $redirect = admin_url(); }
		wp_safe_redirect( $redirect );
		exit;
    }


    // Handle saving settings and file uploads
    public function ps_maintenaance_handle_form_submit() {
        if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Unauthorized' ); }
        check_admin_referer( 'ps_maintenaance_save_action', 'ps_maintenaance_nonce' );

        $ace_maintenance_opts = get_option( 'ace_maintenance_options', [] );

        // Basic fields
		$ps_maintenance_opts['enabled']     = isset( $_POST['enabled'] ) ? 1 : 0;
		$ps_maintenance_opts['title']       = isset( $_POST['title'] ) ? sanitize_text_field(wp_unslash( $_POST['title']) ) : '';

		$ps_maintenance_opts['description'] = isset( $_POST['description'] )
		? wp_kses_post( wp_unslash($_POST['description'] )) 
		: '';

        require_once ABSPATH . 'wp-admin/includes/file.php';

        // Logo upload
        if ( isset( $_FILES['logo_file'] ) && ! empty( $_FILES['logo_file']['name'] ) ) {
            $uploaded = wp_handle_upload( $_FILES['logo_file'], [
                'test_form' => false,
                'mimes'     => [
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'png'          => 'image/png',
                    'gif'          => 'image/gif',
                    'webp'         => 'image/webp',
                ]
            ] );
            if ( isset( $uploaded['url'] ) ) {
                $ps_maintenance_opts['logo'] = esc_url_raw( $uploaded['url'] );
            }
        } else {
            if ( isset( $_POST['logo_old'] ) ) {
                $ps_maintenance_opts['logo'] = esc_url_raw( wp_unslash($_POST['logo_old'] ));
            }
        }

        // Background upload
        if ( isset( $_FILES['background_file'] ) && ! empty( $_FILES['background_file']['name'] ) ) {
            $uploadedBg = wp_handle_upload( $_FILES['background_file'], [
                'test_form' => false,
                'mimes'     => [
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'png'          => 'image/png',
                    'gif'          => 'image/gif',
                    'webp'         => 'image/webp'
                ]
            ] );
            if ( isset( $uploadedBg['url'] ) ) {
                $ps_maintenance_opts['background'] = esc_url_raw( $uploadedBg['url'] );
            }
        } else {
            if ( isset( $_POST['background_old'] ) ) {
                $ps_maintenance_opts['background'] = esc_url_raw( wp_unslash($_POST['background_old'] ));
            }
        }

		// Background color
		$ps_maintenance_opts['background_color'] = isset($_POST['background_color'])
			? sanitize_text_field(wp_unslash($_POST['background_color']))
			: '';

		// Remove background image if requested
		if ( ! empty($_POST['remove_background']) ) {
			$ps_maintenance_opts['background'] = '';
		}

		//exclude filed
		$ps_maintenance_opts['exclude_pages'] = isset( $_POST['exclude_pages'] )
		? sanitize_text_field(wp_unslash($_POST['exclude_pages'] ))
		: '';

		//logo height and width
		$ps_maintenance_opts['logo_width']  = isset($_POST['logo_width']) ? intval($_POST['logo_width']) : '';
		$ps_maintenance_opts['logo_height'] = isset($_POST['logo_height']) ? intval($_POST['logo_height']) : '';

		//logo radius and box
		$ps_maintenance_opts['logo_shape'] = isset($_POST['logo_shape']) 
		? sanitize_text_field(wp_unslash($_POST['logo_shape'])) 
		: 'circle';
        update_option( 'ps_maintenance_options', $ps_maintenance_opts );
        wp_safe_redirect( admin_url( 'admin.php?page=ps-maintenaance&updated=1' ) );
        exit;
    }

	public function ps_maintenaance_render_admin_page() {
		$ps_maintenance_opts = get_option( 'ps_maintenance_options', [] );
		$ps_previewUrl = add_query_arg(
			[
				'ps_preview'       => '1',
				'ps_preview_nonce' => wp_create_nonce( 'ps_preview' ),
			],
			home_url( '/' )
		);

		$context = [
			'opts'        => $ps_maintenance_opts,
			'preview_url' => $ps_previewUrl,
		];

		// Instead of ACE_MAINT_PATH, use plugin_dir_path with __DIR__
		$ps_maintenance_partial = plugin_dir_path( __FILE__ ) . 'partials/ps-maintenaance-admin-display.php';

		if ( file_exists( $ps_maintenance_partial ) ) {
			require_once $ps_maintenance_partial;
		} else {
			echo '<div class="notice notice-error"><p>Admin partial missing.</p></div>';
		}
	}
}









