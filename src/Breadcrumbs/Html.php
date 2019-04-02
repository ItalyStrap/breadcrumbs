<?php
/**
 * Html Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

use function \ItalyStrap\HTML\get_attr;

/**
 *
 */
class Html extends View{

	/**
	 * Render
	 */
	protected function maybe_render() {

		/**
		 * Back compat for icon on first lement
		 *
		 * @var string|array
		 */
		$home_icon = $this->config->get( 'home_icon', false );

		/**
		 * Just in case to prevent error if you have old version of Config::
		 * with the array_merge_recursive();
		 */
		if ( \is_array( $home_icon ) ) {
			$home_icon = $home_icon[ 1 ];
		}

		$label = '';
		$title = '';

		$this->output = \sprintf(
			'<meta name="numberOfItems" content="%d" /><meta name="itemListOrder" content="Ascending" />',
			\absint( $this->count )
		);

		foreach ( $this->container as $position => $crumb ) {

			if ( 0 === $position && $home_icon ) {
				$title = \sprintf(
					'%s<meta itemprop="name" content="%s">',
					\wp_kses_post( $home_icon ),
					\wp_strip_all_tags( $crumb['title'] )
				);
			} else {
				$title = \sprintf(
					'<span itemprop="name">%s</span>',
					\wp_strip_all_tags( $crumb['title'] )
				);
			}

			if ( $crumb['url'] ) {
				$label = \sprintf(
					'<a itemprop="item" href="%s">%s</a>',
					\esc_url( $crumb['url'] ),
					$title
				);
			} else {
				$label = $title;
			}

			$attr = $this->config->get( 'item_attr', [] );

			if ( $position === $this->count - 1 ) {
				$attr['class'] .= $this->config->get( 'item_attr_class_active', ' active' );
				$attr['aria-current'] = 'page';
			}

			/**
			 * Build the breadcrumbs item
			 *
			 * @var string
			 */
			$this->output .= \sprintf(
				'<%1$s%2$s>%3$s<meta itemprop="position" content="%4$d" /></%1$s>%5$s',
				$this->config->get( 'item_tag', 'li' ),
				get_attr(
					'breadcrumbs_item_attr',
					$attr
				),
				$label,
				\absint( $position + 1 ),
				$this->count !== $position + 1 ? \wp_strip_all_tags( $this->config->get( 'separator', '' ) ) : ''
			);
		}

		/**
		 * Build the breadcrumbs list
		 *
		 * @var string
		 */
		$this->output = \sprintf(
			'<%1$s%2$s>%3$s</%1$s>',
			$this->config->get( 'list_tag', 'ol' ),
			get_attr(
				'breadcrumbs_list_attr',
				$this->config->get( 'list_attr', [] )
			),
			$this->output
		);

		/**
		 * Build the container with the breadcrumbs list
		 *
		 * @TODO Chose from _navigation_markup() or custom wrapper
		 * @see \ItalyStrap\Components\Navigations\Pagination
		 *
		 * @var string
		 */
		$this->output = \sprintf(
			'<%1$s%2$s>%3$s</%1$s>',
			$this->config->get( 'container_tag', 'nav' ),
			get_attr(
				'breadcrumbs_container_attr',
				$this->config->get( 'container_attr', [] )
			),
			$this->output
		);
	}
}

