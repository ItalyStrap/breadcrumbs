<?php
/**
 * Breadcrumbs_Factory Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs\Controllers;

/**
 *
 */
class Controller {

	private $items = [];

	/**
	 * Add
	 *
	 * @param  string $title The item title.
	 * @param  string $url   The item url.
	 */
	public function add( $title, $url = false ) {
	
		$this->items[] = [
			'title'	=> $title,
			'url'	=> $url,
		];

	}

	/**
	 * Get the breadcrumbs list
	 *
	 * @param  string $value [description]
	 * @return array
	 */
	public function get() {
	
		return $this->items;
	
	}
}
