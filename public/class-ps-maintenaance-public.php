<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/ps-maintenaance
 * @since      1.0.0
 *
 * @package    ps_Maintenaance
 * @subpackage ps_Maintenaance/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    ps_Maintenaance
 * @subpackage ps_Maintenaance/public
 * @author     Parul Sharma <29parulsharma2001@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class PS_Maintenaance_Public {

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
	 * Register the stylesheets for the public-facing side of the site.
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

		
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ace-maintenance-page-public.css', array(), $this->version, 'all' );

		$ps_maintenance_opts    = get_option( 'ps_maintenance_options', [] );
		$enabled = ! empty( $ps_maintenance_opts['enabled'] );
		$ps_maintenance_Preview = isset($_GET['ps_preview']) && current_user_can('manage_options') && isset($_GET['ps_preview_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['ps_preview_nonce'])), 'ps_preview'); 
	
		if ( $enabled || $ps_maintenance_Preview ) {
			$css_file = plugin_dir_path( __FILE__ ) . 'css/ps-maintenaance-public.css';
			wp_enqueue_style($this->plugin_name . '-public',plugin_dir_url( __FILE__ ) . 'css/ps-maintenaance-public.css',[],file_exists( $css_file ) ? filemtime( $css_file ) : $this->version,'all');
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ace-maintenance-page-public.js', array( 'jquery' ), $this->version, false );

		$ps_maintenance_opts    = get_option( 'ps_maintenance_options', [] );
		$enabled = ! empty( $ps_maintenance_opts['enabled'] );
		$ps_maintenance_Preview = isset( $_GET['ps_preview'] )
			&& current_user_can( 'manage_options' )
			&& isset($_GET['ps_preview_nonce'])
			&& wp_verify_nonce( sanitize_text_field(wp_unslash($_GET['ps_preview_nonce'] )), 'ps_preview' );
		if ( $enabled || $ps_maintenance_Preview ) {
			wp_enqueue_script(
				$this->plugin_name . '-public',
				plugin_dir_url( __FILE__ ) . 'js/ps-maintenaance-public.js',
				[ 'jquery' ],
				$this->version,
				true
			);
		}
	}
	public function ps_maintenaance_page_display() {
		$ps_maintenance_opts    = get_option( 'ps_maintenance_options', [] );
		$enabled = ! empty( $ps_maintenance_opts['enabled'] );
		$ps_maintenance_Preview = isset( $_GET['ps_preview'] )
			&& current_user_can( 'manage_options' )
			&& isset( $_GET['ps_preview_nonce'] )
			&& wp_verify_nonce( sanitize_text_field(wp_unslash($_GET['ps_preview_nonce'] )), 'ps_preview' );
		if ( ! $enabled && ! $ps_maintenance_Preview ) {
			return;
		}
		if ( $enabled && current_user_can( 'manage_options' ) && ! $ps_maintenance_Preview ) {
			return;
		}
		if ( $enabled && ! current_user_can( 'manage_options' ) ) {
			$excluded = array_map( 'trim', explode( ',', $ps_maintenance_opts['exclude_pages'] ?? '' ));
			global $post;
			if ( $post ) {
				$current_slug = $post->post_name;
				if ( in_array( $current_slug, $excluded, true ) ) {
					return; 
				}
			}
		}
		nocache_headers();
		$context = [
			'title'       => esc_html( $ps_maintenance_opts['title'] ?? 'Maintenance Mode' ),
			'description' => wp_kses_post( $ps_maintenance_opts['description'] ?? 'We’ll be back soon.' ),
			'logo'        => ! empty( $ps_maintenance_opts['logo'] )
                     ? esc_url( $ps_maintenance_opts['logo'] )
                     : '',
			'background'  => ! empty( $ps_maintenance_opts['background'] )
                     ? esc_url( $ps_maintenance_opts['background'] )
                     : '',

			'background_color' => ! empty( $ps_maintenance_opts['background_color'] )
			? esc_attr( $ps_maintenance_opts['background_color'] )
			: '',
			'logo_width' => isset($ps_maintenance_opts['logo_width']) ? intval($ps_maintenance_opts['logo_width']) : '',
			'logo_height' => isset($ps_maintenance_opts['logo_height']) ? intval($ps_maintenance_opts['logo_height']) : '',
			'logo_shape' => isset($ps_maintenance_opts['logo_shape']) ? $ps_maintenance_opts['logo_shape'] : 'circle',
			'is_preview'  => $ps_maintenance_Preview,
		];
	$partial = plugin_dir_path(__FILE__ ) . 'partials/ps-maintenaance-public-display.php';

		if ( file_exists( $partial ) ) {
			ob_start();
			wp_head();
			require_once $partial; 
			wp_footer();
			$html = ob_get_clean();
			wp_die(
				wp_kses_post( $html ),
				esc_html__( 'Maintenance Mode', 'ps-maintenaance' ),
				[ 'response' => $ps_maintenance_Preview ? 200 : 503 ]
			);
		} else {
		wp_die(
			esc_html__( 'Maintenance page template missing.', 'ps-maintenaance' ),
			esc_html__( 'Maintenance Mode', 'ps-maintenaance' ),
			[ 'response' => 503 ]
		);
		

	}

    } 
}

