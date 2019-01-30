<?php
/**
 * View Class API
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
abstract class View implements View_Interface {

	/**
	 * container object
	 *
	 * @var container_Interface
	 */
	protected $container;

	/**
	 * Configuration object
	 *
	 * @var Config_Interface
	 */
	protected $config;

	/**
	 * List of items
	 *
	 * @var array
	 */
	protected $list = [];

	/**
	 * Elements number
	 *
	 * @var int
	 */
	protected $count;

	/**
	 * Breadcrumbs string output
	 *
	 * @var string
	 */
	protected $output = '';

	/**
	 * The context the instance is
	 *
	 * @var string
	 */
	protected $context = '';

	/**
	 * Constructor
	 *
	 * @param Config_Interface    $config
	 * @param Container_Interface $container
	 */
	public function __construct( Config_Interface $config, Container_Interface $container ) {

		$this->container = $container;
		$this->config = $config;

		/**
		 * Get the list of items
		 *
		 * @var array
		 */
		$this->list = (array) $this->container->all();
		$this->count = count( $this->list );
		$this->context = get_class( $this ); // (new \ReflectionClass($this))->getShortName();
	}

	/**
	 * Abstract maybe_render()
	 */
	abstract protected function maybe_render();

	/**
	 * Render the output
	 */
	public function render() {

		/**
		 * Prevent the output in case there is any item in the list
		 */
		if ( 0 === $this->count ) {
			return;
		}

		$this->maybe_render();

		return apply_filters( $this->context, $this->output );
	}

	/**
	 * Print the output
	 */
	public function output() {
		echo $this->render();
	}
}
