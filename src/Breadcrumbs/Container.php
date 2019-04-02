<?php
/**
 * Container Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

use \ArrayObject;

/**
 *
 */
class Container extends ArrayObject implements Container_Interface {

	protected $items = [];

	/**
	 * Retrieves all of the runtime configuration parameters
	 *
	 * @since 1.0.0
	 *
	 * @filter  ItalyStrap\Breadcrumbs\Container\Items
	 *
	 * @return array
	 */
	public function all() : array {
		return apply_filters( get_class( $this ) . '\Items', $this->getArrayCopy() );
	}

	/**
	 * Add
	 *
	 * @param  string $title The item title.
	 * @param bool $url The item url.
	 *
	 * @return self
	 */
	public function push( $title, $url = false ) : self {
		$this->append( [
			'title'	=> $title,
			'url'	=> $url,
		] );

		return $this;
	}
}
