<?php

use \ProteusThemes\StickyMenu\StickyMenu;

/**
 * Register the Sticky Menu.
 *
 * The classes needed are auto-loaded via PHP composer.
 *
 * @see https://github.com/proteusthemes/pt-sticky-menu
 * @package auto-pt
 */

// Instantiate the sticky menu.
if ( class_exists( '\ProteusThemes\StickyMenu\StickyMenu' ) ) {
	$pt_sticky_menu = StickyMenu::get_instance();
}
