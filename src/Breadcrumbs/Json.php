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
 * Json view for Breadcrumbs
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
	protected function maybe_render() {

		$this->schema = [
			'@context'			=> 'https://schema.org',
			'@type'				=> 'BreadcrumbList',
			'itemListElement'	=> [],
		];

		foreach ( $this->container as $position => $crumb ) {

			$this->schema['itemListElement'][] = [
				'@type'		=> 'ListItem',
				'position'	=> $position + 1,
				'item'		=> [
					'@id'		=> \esc_url( $crumb['url'] ),
					'name'		=> \wp_strip_all_tags( $crumb['title'] ),
				],
			];

		}

		$this->output = "<script type='application/ld+json'>" . \wp_json_encode( $this->schema ) . '</script>' . "\n";
	}
}

