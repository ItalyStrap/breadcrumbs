<?php
/**
 * Breadcrumbs_Factory Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

use ItalyStrap\Config\Config;

/**
 *
 */
class Container extends Config implements Container_Interface {

	/**
	 * Retrieves all of the runtime configuration parameters
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function all() {
		return apply_filters( 'italystrap_breadcrumbs_items_list', $this->items );
	}

	/**
	 * Add
	 *
	 * @param  string $title The item title.
	 * @param  string $url   The item url.
	 */
	public function push( $title, $url = false ) {
		$this->items[] = [
			'title'	=> $title,
			'url'	=> $url,
		];
	}
}
