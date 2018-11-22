<?php
/**
 * Json Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

/**
 *
 */
class Json extends View {

	/**
	 * Schema for Schema.org
	 *
	 * @var array
	 */
	private $schema;

	/**
	 * Render
	 *
	 * The $schema schema is taken from WP_SEO by Yoast
	 */
	protected function set_output() {

		$this->schema = [
			'@context'			=> 'https://schema.org',
			'@type'				=> 'BreadcrumbList',
			'itemListElement'	=> [],
		];

		foreach ( $this->list as $position => $crumb ) {

			$this->schema['itemListElement'][] = [
				'@type'		=> 'ListItem',
				'position'	=> $position + 1,
				'item'		=> [
					'@id'		=> $crumb['url'],
					'name'		=> $crumb['title'],
				],
			];

		}

		$this->output = "<script type='application/ld+json'>" . wp_json_encode( $this->schema ) . '</script>' . "\n";
	}
}

