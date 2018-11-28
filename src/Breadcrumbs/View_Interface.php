<?php
/**
 * View interface
 *
 * @since 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Breadcrumbs;

interface View_Interface {

	/**
	 * Render the crumbs array
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function render();
}