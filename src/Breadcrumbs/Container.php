<?php
/**
 * Container Class API
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
	 * @filter  ItalyStrap\Breadcrumbs\Container\Items
	 *
	 * @return array
	 */
	public function all() {
		return apply_filters( get_class( $this ) . '\Items', $this->items );
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
