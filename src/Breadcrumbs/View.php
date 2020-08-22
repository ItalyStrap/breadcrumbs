<?php
/**
 * View Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

use ItalyStrap\Config\ConfigInterface;

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
	 * @var ConfigInterface
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
	 * @param ConfigInterface    $config
	 * @param Container_Interface $container
	 */
	public function __construct( ConfigInterface $config, Container_Interface $container ) {

		$this->container = $container;
		$this->config = $config;

		$this->count = $this->container->count();
		$this->context = \get_class( $this ); // (new \ReflectionClass($this))->getShortName();
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
			return '';
		}

		$this->maybe_render();

		/**
		 * Filter name: ItalyStrap\Breadcrumbs\Html|Json
		 */
		return \apply_filters( $this->context, $this->output );
	}

	/**
	 * Print the output
	 */
	public function output() {
		echo $this->render();
	}

	/**
	 * Render the output
	 */
	public function __toString() {
		return $this->render();
	}
}
