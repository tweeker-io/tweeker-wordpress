<?php
/**
 * Plugin Name:	Tweeker - Simple a/b and multivariate testing
 * Plugin URI:	https://tweeker.io
 * Description:	Add Tweeker to your wordpress site. Simple a/b and multivariate testing on your site.
 * Version:		1.1
 * Author:		tweekerio
 * License: GPLv2 or later
 */

/**
 * Restrict direct access to the file, for security purpose.
 */
defined('ABSPATH') or die('You can not access it directly');

/**
 * class Tweeker
 * Handles plugin functionality
 */
class Tweeker {

	public static $group = 'tweeker';

	/**
	 * Class Constructor
	 * Initiates the plugin functionality
	 * by attaching callbacks to WordPress hooks
	 */
	function __construct() {
		$plugin = plugin_basename(__FILE__);
		register_activation_hook(__FILE__, [$this, 'activate']);
		add_action('admin_init', [$this, 'redirect']);
		add_action('admin_init', [$this, 'register_settings']);
		add_action('admin_menu', [$this, 'admin_menu']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue']);
		add_filter('plugin_action_links_'.$plugin, [$this, 'settings_link']);
	}

	/**
	 * Function to register settings fields
	 **/
	function register_settings() {
		register_setting( static::$group, 'tweekerio_wp_business_id' );
		register_setting( static::$group, 'tweekerio_wp_embed_version' );
	}

	/**
	 * Function adds a new menu item
	 * "Tweeker" in WP admin menu
	 * as a child of Settings menu
	 **/
	function admin_menu() {
		add_options_page('Tweeker', 'Tweeker', 'administrator', 'tweeker', [$this, 'admin_page']);
	}

	/**
	 * Function for Displaying Settings Fields
	 * In Plugin Admin Page
	 **/
	function admin_page() {
		$biz = get_option('tweekerio_wp_business_id', '');
		$ver = get_option('tweekerio_wp_embed_version', '');
		?>
			<div class="wrap">
				<h1 class="admin-page-title">Tweeker - Settings</h1>
				<br><br>
				<form method="POST" action="options.php">
					<?php
						settings_fields( static::$group );
						do_settings_sections( static::$group );
					?>
					<div class="container">
						<table class="form-table">
							<tr valign="top">
								<th scope="row">Business ID</th>
								<td>
									<input type="text" name="tweekerio_wp_business_id" id="tweekerio_wp_business_id" placeholder="Enter your Tweeker business ID here..." value="<?php echo $biz; ?>" style="min-width: 40%;" required>
								</td>
							</tr valign="top">
							<tr>
								<th scope="row">Embed Version</th>
								<td>
									<input type="text" name="tweekerio_wp_embed_version" id="tweekerio_wp_embed_version" placeholder="Enter embed version here..." value="<?php echo $ver; ?>" style="min-width: 40%;">
								</td>
							</tr>
						</table>
					</div>
					<?php
						submit_button();
					?>
				</form>
			</div>
		<?php
	}

	/**
	 * Embeds Plugin JS Script into website
	 * before Head closing tag
	 **/
	function enqueue() {
		$biz = get_option('tweekerio_wp_business_id', '');
		$ver = get_option('tweekerio_wp_embed_version', '');
		if (empty($ver)) {
			$ver = 'latest';
		}
		if (!empty($biz) && !empty($ver)) {
			$src = 'https://embed.tweeker.io/external.js?businessId='.$biz.'&embedVersion='.$ver;
			wp_enqueue_script( 'tweeker-io-embed', $src, array(), null, false);
		}
	}

	/**
	 * The function adds settings link
	 * to plugins page
	 **/
	function settings_link( $links ) {
	  $new = '<a href="options-general.php?page=tweeker">Settings</a>';
	  array_unshift($links, $new);
	  return $links;
	}

	/**
	 * Function to be called when plugin
	 * is activated to do necessary things
	 **/
	function activate() {
		add_option('tweekerioforwp_activation_redirect', true);
	}

	/**
	 * Redirect to plugin settings page
	 * when plugin is activated
	 **/
	function redirect() {
		if (get_option('tweekerioforwp_activation_redirect', false)) {
			delete_option('tweekerioforwp_activation_redirect');
			wp_redirect("options-general.php?page=tweeker");
			exit;
		}
	}

}

/**
 * Check if Class Exists
 * Initiate an object of the class
 **/
if( class_exists('Tweeker')){
	$tweekerforwp = new Tweeker();
}