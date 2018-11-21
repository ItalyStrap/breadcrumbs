<?php

/**
 * Config Class that handle the classes configuration
 *
 * [Long Description.]
 *
 * @link [URL]
 * @since 2.4.2
 * @author      hellofromTonya
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Breadcrumbs;

interface Generator_Interface {

	/**
	 * Get the crumbs array
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function build();
}
