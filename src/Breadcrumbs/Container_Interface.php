<?php
/**
 * Container Interface
 *
 * @since 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Breadcrumbs;

interface Container_Interface {

	/**
	 * Retrieves all of the runtime configuration parameters
	 *
	 * @since 1.0.0
	 *
	 * @filter  ItalyStrap\Breadcrumbs\Container\Items
	 *
	 * @return array
	 */
	public function all() : array;

	/**
	 * Add
	 *
	 * @param  string $title The item title.
	 * @param bool $url The item url.
	 * @return self
	 */
	public function push( $title, $url = false );

	/**
	 * Retunr the number of the items
	 *
	 * @return mixed
	 */
	public function count();
}
