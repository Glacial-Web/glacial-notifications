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
				'placement' => 'sticky-header',
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

            <div id="glacial-notifications-wrapper" class="meta-box-sortables ui-sortable">
                <div style="display: flex; align-items: center">
                    <img style="display: inline-block; margin-right: 2em;" src="<?php echo self::get_icon_svg(); ?>"
                         alt="Glacial Multimedia logo">
                    <h1>Glacial Notifications Settings</h1>
                </div>

                <form method="post" action="options.php">

					<?php
					//					var_dump( $this->glacial_notifications );
					settings_fields( 'glacial_notifications_option_group' );
					do_settings_sections( 'glacial-notifications-settings-admin' );
					submit_button('Save Settings');
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
				'front_page_only', // id
				'Front Page Only', // title
				array( $this, 'front_page_only' ), // callback
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
				'content', // id
				'Content', // title
				array( $this, 'content_callback' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section' // section
			);

			add_settings_field(
				'colors', // id
				'Colors', // title
				array( $this, 'colors_callback' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section' // section
			);

			add_settings_field(
				'button', // id
				'Button', // title
				array( $this, 'button_callback' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section',
				array(
					'class' => 'glacial-notifications-group'
				)
			);

			add_settings_field(
				'cookie', // id
				'Cookie', // title
				array( $this, 'cookie' ), // callback
				'glacial-notifications-settings-admin', // page
				'glacial_notifications_setting_section' // section
			);

		}

		public function glacial_notifications_sanitize( $input ) {
			$sanitary_values = array();

			if ( isset( $input['status'] ) ) {
				$sanitary_values['status'] = $input['status'];
			}

			if ( isset( $input['front_page_only'] ) ) {
				$sanitary_values['front_page_only'] = $input['front_page_only'];
			}

			if ( isset( $input['placement'] ) ) {
				$sanitary_values['placement'] = $input['placement'];
			}

			if ( isset( $input['content'] ) ) {
				$sanitary_values['content'] = $input['content'];
			}

			if ( isset( $input['colors'] ) ) {
				$sanitary_values['colors'] = $input['colors'];
			}

			if ( isset( $input['button'] ) ) {
				$sanitary_values['button'] = $input['button'];
			}


			if ( isset( $input['cookie'] ) ) {
				$sanitary_values['cookie'] = $input['cookie'];
			}

			return $sanitary_values;
		}

		public function glacial_notifications_section_info() {
		}

		public function status() { ?>
            <label for="status">
                <input type="checkbox" name="glacial_notifications[status]" id="status"
                       value="active" <?php checked( 'active', $this->glacial_notifications['status'], true ); ?>>
                Enable Notification</label>


		<?php }

		public function front_page_only() { ?>
            <label for="front_page_only">
                <input type="checkbox" name="glacial_notifications[front_page_only]"
                       id="front_page_only"
                       value="1" <?php checked( '1', $this->glacial_notifications['front_page_only'], true ); ?>>
                Show only on the front page / homepage</label>
		<?php }

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

		public function content_callback() {

			wp_editor( $this->glacial_notifications['content'], 'content', array(
				'wpautop'       => true,
				'media_buttons' => true,
				'textarea_name' => 'glacial_notifications[content]',
				'editor_class'  => 'content',
				'textarea_rows' => 10
			) );
		}

		public function colors_callback() {
			$background_color = isset( $this->glacial_notifications['colors']['background'] ) ? esc_attr( $this->glacial_notifications['colors']['background'] ) : '#fff';
			$text_color       = isset( $this->glacial_notifications['colors']['text'] ) ? esc_attr( $this->glacial_notifications['colors']['text'] ) : '#000'; ?>

            <div class="glacial-admin-flex">
                <div>
                    <label for="background-color">Background Color</label>
                    <br>
                    <input type="text" name="glacial_notifications[colors][background]" class="glacial-color-field"
                           id="background-color"
                           value="<?php echo $background_color; ?>">
                </div>
                <div>
                    <label for="background-color">Text Color</label>
                    <br>
                    <input type="text" name="glacial_notifications[colors][text]" class="glacial-color-field"
                           id="background-color"
                           value="<?php echo $text_color; ?>">
                </div>
            </div>

		<?php }


		public function button_callback() { ?>
            <div class="button-container group">
                <label for="button-text">Button Text</label>
                <br>
                <input type="text" name="glacial_notifications[button][text]" id="button-text"
                       value="<?php echo $this->glacial_notifications['button']['text']; ?>">
                <br>
                <br>
                <div class="glacial-admin-flex align-end">
                    <div>
                        <label for="button-url">Button URL</label>
                        <br>
                        <input type="url" size="60" name="glacial_notifications[button][url]" id="button-url"
                               value="<?php echo $this->glacial_notifications['button']['url']; ?>">
                    </div>
                    <div>
                        <label for="new-tab">
                            <input type="checkbox" name="glacial_notifications[button][target]"
                                   id="new-tab"
                                   value="1" <?php checked( '1', $this->glacial_notifications['button']['target'], true ) ?>>Open
                            in New Tab</label>
                    </div>
                </div>
            </div>

		<?php }

		public function cookie() {
			$checked = isset( $this->glacial_notifications['cookie']['cookie'] ) ? 'checked' : '';
			?>

            <label for="cookie-0">
                <input type="checkbox" name="glacial_notifications[cookie][cookie]" id="cookie-0"
                       value="1" <?php checked( '1', $this->glacial_notifications['cookie']['cookie'], true ); ?>>Use
                cookie</label>
            <br>
            <div id="cookie-length">
				<?php printf(

					'<br><input type="number" name="glacial_notifications[cookie][closed_duration]" value="%s" min="0" max="99">',
					isset( $this->glacial_notifications['cookie']['closed_duration'] ) ? esc_attr( $this->glacial_notifications['cookie']['closed_duration'] ) : '0'
				);
				echo '<p>Number of days to keep the notification closed. 0 for session.</p>'; ?>
            </div>
		<?php }

		/*public function cookie_length() {
			printf(
				'<input type="number" name="glacial_notifications[closed_duration]" id="cookie-length" value="%s" min="0" max="99">',
				isset( $this->glacial_notifications['closed_duration'] ) ? esc_attr( $this->glacial_notifications['closed_duration'] ) : '0'
			);
			echo '<p>Number of days to keep the notification closed. 0 for session.</p>';
		}*/

		/**
		 * @param bool $base64
		 *
		 * @return string
		 *
		 * Return Glacial logo SVG
		 *
		 */
		private static function get_icon_svg(
			bool $base64 = true
		): string {
			$svg = '<svg height="100" width="100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 277.7 189.7" style="enable-background:new 0 0 277.7 189.7;" xml:space="preserve">
<style type="text/css">
	.st0{fill:url(#SVGID_1_);}
	.st1{fill:#FFFFFF;}
	.st2{fill:#0085BF;}
	.st3{fill:#F26522;}
</style>
<g>
	<linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="137.7557" y1="110.8416" x2="137.7557" y2="2.5126">
		<stop offset="0.2343" style="stop-color:#87D6F8"/>
		<stop offset="1" style="stop-color:#0065A3"/>
	</linearGradient>
	<path class="st0" d="M122.9,91.8c15.5-12,60.4-25.1,112.3,18.6l-44.5-81.9L164.1,38L145,7.6l-3.8,4.4l-9.5-9.5L97.4,46.3l-12.1-7   L65.7,79.9l-11,6.4l-14.4,24.4c0,0,22.9,2,63.7-8.6c43.4-10.8,61.7-13.6,82.4-3.1C158,76.6,122.9,91.8,122.9,91.8z"/>
</g>
<path class="st1" d="M88.6,50L72,82.6l-13.4,8.3l-8.3,14.7c0,0,22.7,0,52.7-10L99.5,74l-8.7-6.5L94.3,53L88.6,50z"/>
<path class="st1" d="M131.9,11.7l-31.2,38.8l3.5,15.9c0,0,7.2-13.3,10.1-0.3s3.2,20.2,3.2,20.2l9.8-33c0,0-7.2-11.5-7.2-13.8  S131.9,11.7,131.9,11.7z"/>
<polygon class="st1" points="144.7,17.3 131.9,37.1 135.9,78.3 163.5,47.4 "/>
<g>
	<path class="st2" d="M31,146.8h6.6v13.3c-3.7,4-8.9,6.1-15.8,6.1c-5.8,0-10.6-1.9-14.5-5.7c-3.9-3.8-5.8-8.6-5.8-14.4   c0-5.8,2-10.6,5.9-14.5c4-3.9,8.7-5.8,14.3-5.8c5.6,0,10.3,1.6,14,4.9l-3.5,5c-1.5-1.3-3.1-2.2-4.6-2.7c-1.5-0.5-3.3-0.8-5.4-0.8   c-3.9,0-7.2,1.3-9.9,3.8c-2.7,2.5-4,5.9-4,10c0,4.1,1.3,7.5,3.9,10c2.6,2.5,5.7,3.8,9.4,3.8c3.7,0,6.8-0.8,9.3-2.4V146.8z"/>
	<path class="st2" d="M51.8,165.7v-38.9h6.6v32.7h17.8v6.2H51.8z"/>
	<path class="st2" d="M94.4,156.9l-3.9,8.9h-7l17.2-38.9h7l17.2,38.9h-7l-3.9-8.9H94.4z M111.2,150.8l-7.1-16l-7.1,16H111.2z"/>
	<path class="st2" d="M153.5,159.8c2.3,0,4.2-0.4,5.8-1.1c1.6-0.8,3.3-2,5.1-3.6l4.2,4.3c-4.1,4.6-9.1,6.9-15,6.9   c-5.9,0-10.8-1.9-14.6-5.7c-3.9-3.8-5.8-8.6-5.8-14.4c0-5.8,2-10.6,5.9-14.5c4-3.9,8.9-5.8,15-5.8c6,0,11.1,2.2,15.1,6.7l-4.2,4.6   c-1.9-1.8-3.6-3-5.3-3.7c-1.7-0.7-3.6-1.1-5.8-1.1c-3.9,0-7.2,1.3-9.9,3.8c-2.7,2.5-4,5.8-4,9.7c0,4,1.3,7.3,4,9.9   C146.7,158.4,149.9,159.8,153.5,159.8z"/>
	<path class="st2" d="M182.3,126.8h6.6v38.9h-6.6V126.8z"/>
	<path class="st2" d="M210.8,156.9l-3.9,8.9h-7l17.2-38.9h7l17.2,38.9h-7l-3.9-8.9H210.8z M227.6,150.8l-7.1-16l-7.1,16H227.6z"/>
	<path class="st2" d="M252.2,165.7v-38.9h6.6v32.7h17.8v6.2H252.2z"/>
</g>
<g>
	<path class="st3" d="M6.8,187.1H5.3V178h2.4l2.7,5.7l2.7-5.7h2.4v9.1H14V180l-3.2,6.3H10L6.8,180V187.1z"/>
	<path class="st3" d="M40.5,185c0.4,0.5,1,0.7,1.7,0.7c0.7,0,1.3-0.2,1.7-0.7c0.4-0.5,0.6-1.1,0.6-2v-5h1.5v5.1c0,1.3-0.4,2.3-1.1,3   c-0.7,0.7-1.6,1.1-2.8,1.1c-1.1,0-2-0.4-2.8-1.1c-0.7-0.7-1.1-1.7-1.1-3V178h1.5v5C39.9,183.9,40.1,184.5,40.5,185z"/>
	<path class="st3" d="M68.9,187.1V178h1.5v7.6h4.1v1.5H68.9z"/>
	<path class="st3" d="M99.3,179.4v7.7h-1.5v-7.7H95V178h7v1.4H99.3z"/>
	<path class="st3" d="M124.1,178h1.5v9.1h-1.5V178z"/>
	<path class="st3" d="M150.2,187.1h-1.5V178h2.4l2.7,5.7l2.7-5.7h2.4v9.1h-1.5V180l-3.2,6.3h-0.9l-3.2-6.3V187.1z"/>
	<path class="st3" d="M188.3,178v1.4h-4.8v2.4h4.4v1.4h-4.4v2.4h5v1.4h-6.5V178H188.3z"/>
	<path class="st3" d="M217.7,179.2c0.9,0.8,1.3,1.9,1.3,3.3s-0.4,2.5-1.3,3.4c-0.8,0.8-2.1,1.2-3.9,1.2h-3V178h3.1   C215.6,178,216.9,178.4,217.7,179.2z M217.5,182.6c0-2.1-1.2-3.1-3.6-3.1h-1.5v6.2h1.7c1.1,0,1.9-0.3,2.5-0.8   C217.2,184.4,217.5,183.6,217.5,182.6z"/>
	<path class="st3" d="M241.5,178h1.5v9.1h-1.5V178z"/>
	<path class="st3" d="M267.3,185l-0.9,2.1h-1.6l4-9.1h1.6l4,9.1h-1.6l-0.9-2.1H267.3z M271.2,183.6l-1.7-3.7l-1.7,3.7H271.2z"/>
</g>
</svg>';

			if ( $base64 ) {

				return 'data:image/svg+xml;base64,' . base64_encode( $svg );
			}

			return $svg;
		}
	}
}

$glacial_notifications_settings = Glacial_Notifications_Settings::getInstance();