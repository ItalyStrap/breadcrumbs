<?php
/**
 * Breadcrumbs_Factory Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

use ItalyStrap\Config\Config_Interface;

/**
 *
 */
class Breadcrumbs_Factory {

	/**
	 * Constructor
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public static function make() {

		/**
		 * Get the first element of breadcrumbs
		 */
		if ( ( is_home() && is_front_page() ) || is_front_page() ) {

		} else if ( is_home() && ! is_front_page() ) {

		} else {

		}

		/**
		 * Get the rest of breadcrumbs for every content type
		 */
		if ( is_attachment() ) {

		} elseif ( is_single() && ! is_attachment() ) {

			$breadcrumb = new Controllers\Single();

		} elseif ( is_page() && ( ! is_front_page() ) ) {

		} elseif ( is_tax() ) {

		} elseif ( is_category() ) {

		} elseif ( is_tag() ) {

		} elseif ( is_post_type_archive() ) {

		} elseif ( is_year() ) {

		} elseif ( is_month() ) {

		} elseif ( is_day() ) {

		} elseif ( is_author() ) {

		} elseif ( is_search() ) {

		} elseif ( is_404() ) {

		}

		/**
		 * If is paginated page add (Page n°) at the end of breadcrumb
		 * This has <small> tag
		 */
		if ( get_query_var( 'paged' ) ) {
		}

		return apply_filters( 'italystrap_breadcrumbs', $this->breadcrumbs );
	}
}
