<?php
/**
 * Displays the admin settings page.
 *
 * @since         1.0.0
 * @package       Glacial_Notifications
 * @subpackage    Glacial_Notifications/includes
 * @author        Glacial Multimedia
 */

if ( ! class_exists( 'Glacial_Notifications_Settings' ) ) {

	class Glacial_Notifications_Settings {

		/**
		 * Static property to hold our singleton instance
		 *
		 */
		static $instance = false;

		/**
		 * @var false|mixed|null
		 */
		private $glacial_notifications;

		private function __construct() {

			$this->glacial_notifications = $this->set_defaults();
//			$this->glacial_notifications = get_option('glacial_notifications');

			add_action( 'admin_menu', array( $this, 'glacial_notifications_add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'glacial_notifications_page_init' ) );
		}

		public static function getInstance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function set_defaults() {
			$defaults = array(
				'placement'       => 'top_of_page',
				'cookie'          => 'yes',
				'closed_duration' => '0',
			);

			return wp_parse_args( get_option( 'glacial_notifications' ), $defaults );
		}

		public function glacial_notifications_add_plugin_page() {
			add_options_page(
				'Glacial Notifications Settings', // page_title
				'Glacial Notifications Settings', // menu_title
				'manage_options', // capability
				'glacial-notifications-settings', // menu_slug
				array( $this, 'glacial_notifications_create_admin_page' ) // function
			);
		}

		public function glacial_notifications_create_admin_page() { ?>

            <div class="wrap">
                <h2>Glacial Notifications Settings</h2>

                <form method="post" action="options.php">

					<?php
					//					var_dump( $this->glacial_notifications );
					settings_fields( 'glacial_notifications_option_group' );
					do_settings_sections( 'glacial-notifications-settings-admin' );
					submit_button();
					?>

                </form>
            </div>
		<?php }

		public function glacial_notifications_page_init() {
			register_setting(
				'glacial_notifications_option_group', // option_group
				'glacial_notifications', //
				array( $this, 'glacial_notifications_sanitize' ) // sanitize_callback
			);

			add_settings_section(
				'glacial_notifications_setting_section', // id
				'', // title
				array( $this, 'glacial_notifications_section_info' ), // callback
				'glacial-notifications-settings-admin' // page
			);

			add_settings_field(
				'status', // id
				'Enable Notification', // title
				array( $this, 'status' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section' // section
			);

			add_settings_field(
				'placement', // id
				'Placement', // title
				array( $this, 'placement_callback' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section',
			);

			add_settings_field(
				'cookie', // id
				'Cookie', // title
				array( $this, 'cookie' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section' // section
			);

			add_settings_field(
				'closed_duration', // id
				'Closed Duration', // title
				array( $this, 'cookie_length' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section'// section
			);

			add_settings_field(
				'content', // id
				'Content', // title
				array( $this, 'content_callback' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section' // section
			);
		}

		public function glacial_notifications_sanitize( $input ) {
			$sanitary_values = array();

			if ( isset( $input['status'] ) ) {
				$sanitary_values['status'] = $input['status'];
			}

			if ( isset( $input['placement'] ) ) {
				$sanitary_values['placement'] = $input['placement'];
			}

			if ( isset( $input['cookie'] ) ) {
				$sanitary_values['cookie'] = $input['cookie'];
			}

			if ( isset( $input['closed_duration'] ) ) {
				$sanitary_values['closed_duration'] = filter_var( $input['closed_duration'], FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $input['content'] ) ) {
				$sanitary_values['content'] = $input['content'];
			}

			return $sanitary_values;
		}

		public function glacial_notifications_section_info() {
		}

		public function status() {
			printf(
				'<input type="checkbox" name="glacial_notifications[status]" id="status" value="active" %s>',
				( isset( $this->glacial_notifications['status'] ) && $this->glacial_notifications['status'] === 'active' ) ? 'checked' : ''
			);

		}

		public function placement_callback() {
			$checked = ( isset( $this->glacial_notifications['placement'] ) && $this->glacial_notifications['placement'] === 'sticky-header' ) ? 'checked' : 'sticky-header';
			?>

            <fieldset>
                <label for="placement-0">
                    <input type="radio"
                           name="glacial_notifications[placement]"
                           id="placement-0" value="sticky-header" <?php echo $checked; ?>>Top of
                    page</label>
                <br>

				<?php $checked = ( isset( $this->glacial_notifications['placement'] ) && $this->glacial_notifications['placement'] === 'popup' ) ? 'checked' : ''; ?>

                <label for="placement-1">
                    <input type="radio"
                           name="glacial_notifications[placement]"
                           id="placement-1" value="popup" <?php echo $checked; ?>>Popup/Modal</label>
            </fieldset>
		<?php }

		public function cookie() {
			$checked = ( isset( $this->glacial_notifications['cookie'] ) && $this->glacial_notifications['cookie'] === 'yes' ) ? 'checked' : 'yes';
			?>

            <fieldset id="cookie-radios">
                <label for="cookie-0">
                    <input type="radio"
                           name="glacial_notifications[cookie]"
                           id="cookie-0" value="yes" <?php echo $checked; ?>>Use cookie</label>
                <br>

				<?php $checked = ( isset( $this->glacial_notifications['cookie'] ) && $this->glacial_notifications['cookie'] === 'no' ) ? 'checked' : ''; ?>

                <label for="cookie-1">
                    <input type="radio"
                           name="glacial_notifications[cookie]"
                           id="cookie-1" value="no" <?php echo $checked; ?>>No cookie</label>
            </fieldset>
		<?php }

		public function cookie_length() {
			printf(
				'<input style="display: none" type="number" name="glacial_notifications[closed_duration]" id="cookie-length" value="%s" min="0" max="99">',
				isset( $this->glacial_notifications['closed_duration'] ) ? esc_attr( $this->glacial_notifications['closed_duration'] ) : '0'
			);
			echo '<p>Number of days to keep the notification closed. 0 for session.</p>';
		}


		public function content_callback() {

			wp_editor( $this->glacial_notifications['content'], 'content', array(
				'wpautop'       => true,
				'media_buttons' => true,
				'textarea_name' => 'glacial_notifications[content]',
				'editor_class'  => 'content',
				'textarea_rows' => 10
			) );


		}
	}
}

$glacial_notifications_settings = Glacial_Notifications_Settings::getInstance();