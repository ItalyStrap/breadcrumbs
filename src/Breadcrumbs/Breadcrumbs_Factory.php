<?php
/**
 * Breadcrumbs_Factory Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

use \ItalyStrap\Config\Config_Factory as Config;
use \InvalidArgumentException;

/**
 * Class Breadcrumbs_Factory
 *
 * @package ItalyStrap\Breadcrumbs
 */
class Breadcrumbs_Factory {

	/**
	 * Makers
	 *
	 * @param  string $type
	 * @param  array $args
	 * @param  bool $reload
	 *
	 * @return \ItalyStrap\Breadcrumbs\View|object|array
	 * @throws \Exception
	 */
	public static function make( $type = 'html', array $args = [], $reload = false ) {

		static $container = null;

		$config_default = (array) require( 'config/breadcrumbs.php' );

		/**
		 * Breadcrumbs configuration
		 *
		 * @var Config
		 */
		$config = Config::make( $args, $config_default );

		if ( is_null( $container ) || $reload ) {

			/**
			 * Breadcrumbs items container
			 * First we need to instantiate the container
			 *
			 * @var Container
			 */
			$container = new Container();

			/**
			 * Breadcrumbs generator
			 * Pass the container to the generator
			 * And Build the items list
			 *
			 * @var Generator
			 */
			( new Generator( $config, $container ) )->build();
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
				throw new InvalidArgumentException( sprintf(
					'Unknown %s format given',
					$type
				) );
				break;
		}
	}
}
