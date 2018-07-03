<?php
/**
 * Register automatic updates for this theme.
 */

class AdrenalineAutomaticUpdates {
	function __construct() {
		// Enable EDD updates.
		add_action( 'after_setup_theme', array( $this, 'enable_edd_theme_updater' ) );
	}

	/**
	 * Load EDD theme updater.
	 */
	public function enable_edd_theme_updater() {
		$config = array(
			'remote_api_url' => 'https://www.proteusthemes.com', // Site where EDD is hosted.
			'item_name'      => 'Adrenaline', // Name of theme.
			'theme_slug'     => 'adrenaline-pt', // Theme slug.
			'author'         => 'ProteusThemes', // The author of this theme.
			'download_id'    => '4099', // Optional, used for generating a license renewal link.
			'renew_url'      => '', // Optional, allows for a custom license renewal link.
		);
		$edd_updater = new ProteusThemes\EDDThemeUpdater\EDDThemeUpdaterConfig( $config );
	}
}

$adrenaline_automatic_updates = new AdrenalineAutomaticUpdates();
