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
use ItalyStrap\Config\Config;
use InvalidArgumentException;

/**
 *
 */
class Breadcrumbs_Factory {

	/**
	 * Makers
	 *
	 * @param  array  $args
	 * @param  string $type
	 *
	 * @return ItalyStrap\Breadcrumbs\View
	 */
	public static function make( array $args = [], $type = 'html' ) {

		static $container = null;

		$config_default = require( 'config/breadcrumbs.php' );

		/**
		 * Breadcrumbs configuration
		 *
		 * @var Config
		 */
		$config = new Config( $args, $config_default );

		if ( is_null( $container ) ) {

			/**
			 * Breadcrumbs items container
			 * First instantiate the container
			 *
			 * @var Container
			 */
			$container = new Container();

			/**
			 * Breadcrumbs generator
			 * Pass the container to the generator
			 *
			 * @var Generator
			 */
			$generator = new Generator( $config, $container );
			// Build the items list
			$generator->build();
		}

		/**
		 * And then pass the container with all items to the viewer
		 */
		switch ( $type ) {
			case 'html':
				return new Html( $config, $container );
				// break;
			case 'json':
				return new Json( $config, $container );	
				// break;
			case 'object':
				return (object) $container;
				// break;
			case 'array':
				return (array) $container->all();
				// break;
			
			default:
				throw new InvalidArgumentException( 'Unknown $type format given' );
				break;
		}
	}
}
