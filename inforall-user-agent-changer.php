<?php
/*
 * Plugin Name:       Default Useragent Changer
 * Plugin URI:        https://ilanfirsov.me/wordpress/useragent-changer
 * Description:       Allows to set a custom useragent and override the default one WordPress is using for HTTP requests.
 * Version:           1.0.0
 * Author:            Ilan Firsov
 * Author URI:        https://ilanfirsov.me/
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       useragent-changer
 * Domain Path:       /languages
 */

/*
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Inforall_User_Agent_Changer
 */
final class Inforall_User_Agent_Changer {

	/**
	 * Inforall_User_Agent_Changer constructor
	 */
	public function __construct() {
		$this->load_textdomain();
		$this->register_hooks();
	}

	/**
	 * Load plugin textdomain
	 */
	protected function load_textdomain() {
		$locale    = apply_filters( 'plugin_locale', get_locale(), 'useragent-changer' );

		$global_mo = WP_LANG_DIR . '/useragent-changer/useragent-changer-' . $locale . '.mo';
		$local_mo  = __DIR__ . '/languages/' . $locale . '.mo';

		load_textdomain( 'useragent-changer', file_exists( $global_mo ) ? $global_mo : $local_mo );
	}

	/**
	 * Register WordPress hooks
	 */
	protected function register_hooks() {
		add_filter( 'http_headers_useragent', array( $this, 'http_headers_useragent' ) );

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Filter WordPress default HTTP useragent
	 * @param string $useragent Default useragent
	 * @return string
	 */
	public function http_headers_useragent( $useragent ) {
		$custom_ua = get_option( 'custom_default_http_useragent', false );

		if ( $custom_ua !== false && ! empty( $custom_ua ) ) {
			return $custom_ua;
		}

		return $useragent;
	}

	/**
	 * Register settings fields
	 */
	public function register_settings() {
    	add_settings_section(
	        'inforall_user_agent_changer',
	        '',
	        '__return_false',
	        'general'
	    );
		add_settings_field(
	        'custom_default_http_useragent',
	        __( 'Default Useragent', 'useragent-changer' ),
	        array( $this, 'textbox_callback' ),
	        'general',
	        'inforall_user_agent_changer',
	        array( 'custom_default_http_useragent' )
	    );
		register_setting('general','custom_default_http_useragent', 'esc_attr');
	}

	/**
	 * Render textbox field
	 */
	public function textbox_callback() {
		global $wp_version;

		$key = 'custom_default_http_useragent';
		$useragent_str = 'WordPress/' . $wp_version . '; ' . home_url();
    	$option = get_option( $key );
    	printf( '<input type="text" class="regular-text code" id="%1$s" name="%1$s" value="%2$s" placeholder="%3$s" />',
    		$key,
    		$option,
    		$useragent_str
    	);
    	printf( '<p class="description">%s</p>', __( 'Override WordPress default useragent for HTTP requests.', 'useragent-changer' ) );
    }
}
new Inforall_User_Agent_Changer();
