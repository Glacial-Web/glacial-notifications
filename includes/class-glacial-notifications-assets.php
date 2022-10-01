<?php
/**
 * Loads all static assets for the plugin.
 *
 * @since         1.0.0
 * @package       Glacial_Notifications
 * @subpackage    Glacial_Notifications/includes
 * @author        Glacial Multimedia
 */

if ( ! class_exists( 'Glacial_Notifications_Assets' ) ) {

	class Glacial_Notifications_Assets {
		/**
		 * Static property to hold our singleton instance
		 *
		 */
		static $instance = false;

		/**
		 * @var false|mixed|null
		 */
		private $options;

		/**
		 * @var false|mixed
		 */

		private $front_page_only;
		/**
		 * @var false|mixed
		 */
		private $active;

		private function __construct() {

			$this->options         = get_option( 'glacial_notifications' );
			$this->front_page_only = $this->options['front_page_only'] ?? false;
			$this->active          = $this->options['status'] ?? false;


			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 10 );
			add_action( 'admin_init', array( $this, 'add_editor_styles' ) );
		}

		public static function getInstance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Admin assets
		 *
		 * @return void
		 */
		public function admin_scripts() {

			wp_enqueue_style( 'glacial-notifications-admin-style', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'admin/css/glacial-notifications-admin.css', array(), GLACIAL_NOTIFICATIONS_VERSION, 'all' );
			wp_enqueue_script( 'glacial-notifications-admin-script', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'admin/js/glacial-notifications-admin.js', array( 'jquery', 'wp-color-picker' ), GLACIAL_NOTIFICATIONS_VERSION, false );
			wp_enqueue_style( 'wp-color-picker' );
		}

		/**
		 * Frontend assets
		 * @return void
		 */
		public function front_scripts() {

			if ( $this->active ) {

				if ( ( is_front_page() && $this->front_page_only ) || ! $this->front_page_only ) {

					if ( $this->options['placement'] === 'sticky-header' ) {
						wp_enqueue_style( 'glacial-notifications-style', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'public/css/glacial-notifications-top.css', array(), GLACIAL_NOTIFICATIONS_VERSION, 'all' );
						wp_enqueue_script( 'glacial-notifications-script', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'public/js/glacial-notifications-top.js', array( 'jquery' ), GLACIAL_NOTIFICATIONS_VERSION, true );
					} else {
						wp_enqueue_script( 'glacial-notification-popup', 'https://unpkg.com/micromodal/dist/micromodal.min.js', array(), GLACIAL_NOTIFICATIONS_VERSION, true );
						wp_enqueue_style( 'glacial-notifications-style', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'public/css/glacial-notifications-popup.css', array(), GLACIAL_NOTIFICATIONS_VERSION, 'all' );
						wp_enqueue_script( 'glacial-notifications-script', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'public/js/glacial-notifications-popup.js', array(
							'jquery',
							'glacial-notification-popup'
						), GLACIAL_NOTIFICATIONS_VERSION, true );
					}
				}
			}
		}

		/**
		 * Add editor styles
		 *
		 * @return void
		 */
		function add_editor_styles() {
			add_editor_style( GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'admin/css/glacial-notifications-editor-styles.css' );
		}
	}
}

$glacial_notifications_assets = Glacial_Notifications_Assets::getInstance();