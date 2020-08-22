<?php
/**
 * Generator Class API
 *
 * @since 1.0.0
 *
 * @package ItalyStrap\Breadcrumbs
 */

namespace ItalyStrap\Breadcrumbs;

use \InvalidArgumentException;
use \ItalyStrap\Config\ConfigInterface;

/**
 * Generator
 */
class Generator implements Generator_Interface {

	/**
	 * Config object
	 *
	 * @var ConfigInterface
	 */
	private $config = null;

	/**
	 * Container object
	 *
	 * @var Container
	 */
	private $container = null;

	/**
	 * Constructor
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct( ConfigInterface $config, Container_Interface $container ) {

		$this->config = $config;
		$this->container = $container;

		if ( ! $this->config->has( 'bloginfo_name' ) ) {
			throw new InvalidArgumentException( "'bloginfo_name' Not set", 1 );
		}

		if ( ! $this->config->has( 'home_url' ) ) {
			throw new InvalidArgumentException( "'home_url' Not set", 1 );
		}
	}

	/**
	 * Build the items list
	 */
	public function build() {

		if ( ! $this->config->get( 'show_on_front' ) && \is_front_page() ) {
			return;
		}

		$bloginfo_name = $this->config->get( 'bloginfo_name' );
		$home_url = $this->config->get( 'home_url' );

		/**
		 * Get the first element of breadcrumbs
		 * This is the static front page or the blog page if is a new installation
		 */
		if ( ( \is_home() && \is_front_page() ) || \is_front_page() ) {

			$this->container->push( $bloginfo_name, $home_url );

		} else if ( \is_home() && ! \is_front_page() ) {
		// The page with the list of article.
			$page_for_posts = \get_option( 'page_for_posts' );

			/**
			 * Displayed only on blog page if exists
			 */
			$this->container->push( $bloginfo_name, $home_url );
			$this->container->push( \get_the_title( $page_for_posts ), \get_permalink( $page_for_posts ) );

		} else {

			$this->container->push( $bloginfo_name, $home_url );
		}

		switch ( true ) {

			case \is_attachment():

				/**
				 * ID of attachemnt's parent
				 *
				 * @var int
				 */
				$parent_id = \wp_get_post_parent_id( \get_the_ID() );

				/**
				 * If parent post id $parent_id exist return parent post item
				 */
				if ( $parent_id ) {

					/**
					 * A WP_Post object from parent ID
					 *
					 * @var Object
					 */
					$get_post = \get_post( $parent_id );

					$this->container->push( $get_post->post_title, \get_permalink( $parent_id ) );
				}

				$this->container->push( \get_the_title() );

				break;

			case \is_single() && ! \is_attachment():

				$post_type = \get_post_type();

				/**
				 * Array with WP_Term in it
				 *
				 * @var array
				 */
				$category = \get_the_category();

				/**
				 * If article has category and has parents category too
				 */
				if ( $category && 'post' === $post_type ) {

					$this->generate_tax_hierarchy( $category[0] );
				}

				/**
				 * Da fare, se non ha la categoria inserire i post format se ci sono
				 * Volendo posso aggiungere un parametro alla classe per abilitare
				 * questa funzione direttamente nello snippet
				 * !is_singular() è da togliere, non serve al massimo testarla prima
				 */
				// elseif ( has_post_format() && !is_singular() ) {
				// echo get_post_format_string( get_post_format() );
				// }

				if ( 'post' === $post_type ) {

					$this->container->push( \get_the_title() );

				} else {

					/**
					 * Da fare:
					 * Creare la gerarchie se ci sono pagine genitore per
					 * i custom post type
					 */

					/**
					 * Get post type object from CPT files
					 *
					 * @var Object
					 */
					$post_type = \get_post_type_object( $post_type );

					/**
					 * Slug of post
					 *
					 * @var string
					 */
					$slug = $post_type->rewrite;

					$this->container->push(
						$post_type->labels->singular_name,
						$home_url . $slug['slug'] . '/'
					);

					/**
					 * Get array of all anchestor ID
					 *
					 * @var array
					 */
					$anchestor = \array_reverse( \get_post_ancestors( \get_the_ID() ) );

					/**
					 * If there is a hierarchy of page then add post anchestor
					 */
					if ( $anchestor ) {

						foreach ( $anchestor as $anchestor_id ) {

							/**
							 * Single anchestor ID
							 *
							 * @var int
							 */
							$post_anchestor_id = \get_post( $anchestor_id );

							$this->container->push( $post_anchestor_id->post_title, \get_permalink( $post_anchestor_id ) );
						}

					} else {

						/**
						 * Ritorna i nomi delle tassonomie associate al CPT
						 * Esempio:
						 * [
						 * 	"download_category",
						 * 	"download_tag",
						 * ]
						 *
						 * @var array
						 */
						$object_taxonomies = \get_object_taxonomies( \get_post() );

						/**
						 * Ritorna tutte le tassonomie associate al post partendo
						 * dalla prima trovata sopra
						 * Esempio da:
						 * "download_category" posso otenere:
						 * EDD Category
						 * Nuova categoria
						 * ecc
						 * https://developer.wordpress.org/reference/functions/get_the_terms/
						 */
						$get_the_terms = false;

						/**
						 * @TODO Test
						 * Problema sorto con un custom post type dove il post non aveva
						 * nessuna tassonomia associata e dava undefine index
						 */
						if ( isset( $object_taxonomies[0] ) ) {
							$get_the_terms = \get_the_terms( \get_the_ID(), $object_taxonomies[0] );
						}

						if ( $get_the_terms && ! \is_wp_error( $get_the_terms ) ) {

							$sorted_terms = [];
							$this->sort_terms_hierarchically( $get_the_terms, $sorted_terms );

							$this->sorted_term_html_output( $sorted_terms, $object_taxonomies );
						}
					}

					$this->container->push( \get_the_title() );

				}

				break;

			case  \is_page() && ! \is_front_page() :

				/**
				 * Get array of all anchestor ID
				 *
				 * @var array
				 */
				$anchestor = \array_reverse( \get_post_ancestors( \get_the_ID() ) );

				foreach ( $anchestor as $anchestor_id ) {

					/**
					 * Single anchestor ID
					 *
					 * @var int
					 */
					$post_anchestor_id = \get_post( $anchestor_id );

					$this->container->push( $post_anchestor_id->post_title, \get_permalink( $post_anchestor_id ) );
				}

				/**
				 * If is page and not front page add page title
				 */
				$this->container->push( \get_the_title() );

				break;

			case \is_tax():

				$queried_object = \get_queried_object();

				if ( $queried_object instanceof \WP_Term ) {

					/**
					 * If is category (default archive.php) add Category name
					 * If category has child add category child too
					 * Nota per me: togliere solo link su categoria nipote
					 * e aggiungere &before_element_active
					 */
					// $breadcrumb .= $this->get_tax_parents( $queried_object->term_id, $before_element, $after_element, array(), $queried_object->taxonomy );

					$this->generate_tax_hierarchy( $queried_object->term_id, [], $queried_object->taxonomy );
				}

				break;

			case \is_category():

				/**
				 * If is category (default archive.php) add Category name
				 * If category has child add category child too
				 * Nota per me: togliere solo link su categoria nipote
				 * e aggiungere &before_element_active
				 */
				$this->generate_tax_hierarchy( \get_query_var( 'cat' ) );

				break;

			case \is_tag():

				/**
				 * If is tag (default archive.php) add tag title
				 */
				$this->container->push( __( 'Tag: ', 'italystrap' ) . \single_tag_title( '', false ) );

				break;

			case \is_post_type_archive():

				/**
				 * If is Custom Post Type's archive (default archive.php)
				 * add Post Type Archive Title
				 */
				$this->container->push( \post_type_archive_title( '', false ) );

				break;

			case \is_year():

				/**
				 * If is year (default archive.php) add year
				 * @hook https://developer.wordpress.org/reference/hooks/get_the_time/
				 */
				$this->container->push( __( 'Yearly archive: ', 'italystrap' ) . \get_the_time( 'Y' ) );

				break;

			case \is_month():

				/**
				 * If is month (default archive.php) add year with link and month name
				 * @hook https://developer.wordpress.org/reference/hooks/get_the_time/
				 *
				 * Get the year time
				 *
				 * @var int
				 */
				$year = \get_the_time( 'Y' );

				$this->container->push( $year, \get_year_link( $year ) );

				$this->container->push( __( 'Monthly archive: ', 'italystrap' ) . \get_the_time( 'F' ) );

				break;

			case \is_day():

				/**
				 * If is day (default archive.php) add year with link,
				 * month with link and day number
				 * @hook https://developer.wordpress.org/reference/hooks/get_the_time/
				 *
				 * Get the year time
				 *
				 * @var int
				 */
				$year = \get_the_time( 'Y' );

				/**
				 * Get the month time
				 * @hook https://developer.wordpress.org/reference/hooks/get_the_time/
				 *
				 * @var int
				 */
				$month = \get_the_time( 'm' );

				$this->container->push( $year, \get_year_link( $year ) );
				$this->container->push( $month, \get_month_link( $year, $month ) );

				$this->container->push( __( 'Daily archive: ', 'italystrap' ) . \get_the_time( 'd' ) );

				break;

			case \is_author():

				/**
				 * If is author (default archive.php) add author name
				 */
				$this->container->push( __( 'Author Archives: ', 'italystrap' ) . \get_the_author() );

				break;

			case \is_search():

				/**
				 * If is search (default search.php) add search query
				 */
				$this->container->push( __( 'Search Results for: ', 'italystrap' ) . \get_search_query() );

				break;

			case \is_404():

				/**
				 * If is 404
				 */
				$this->container->push( __( 'Not Found', 'italystrap' ) );

				break;

			default:
				// Do nothing
				break;
		}

		/**
		 * If is paginated page add (Page n°) at the end of breadcrumb
		 */
		$paged = \get_query_var( 'paged' );

		if ( $paged ) {

			$label = \sprintf(
				'%s %s',
				__( 'Page', 'italystrap' ),
				$paged
			);

			$this->container->push( $label );
		}
	}

	/**
	 * Retrieve category parents.
	 *
	 * Da fare per le performance:
	 * Modificare il metodo italystrap_generate_tax_hierarchy e creare un loop normale
	 * la ricorsione è una martellata sui maroni
	 * ma è comunque abbastanza veloce :-P
	 *
	 * @since 2.1.0 (From WP core 1.2.0)
	 *
	 * @see generate_tax_hierarchy
	 * @link https://core.trac.wordpress.org/browser/tags/4.1/src/wp-includes/category-template.php#L42 Original function
	 *
	 * @param  int|object          $id        Category ID.
	 * @param  array               $visited   Optional. Already linked to categories to
	 *                                        prevent duplicates
	 * @param  string              $tax
	 *
	 * @return string|\WP_Error A list of category parents on success, WP_Error on failure.
	 */
	private function generate_tax_hierarchy( $id, $visited = [], $tax = 'category' ) {

		/**
		 * WP_term object
		 *
		 * @var \WP_Term
		 */
		$parent = \get_term( $id, $tax );

		if ( \is_wp_error( $parent ) ) {
			return $parent;
		}

		if ( $parent->parent && ( $parent->parent !== $parent->term_id ) && ! \in_array( $parent->parent, $visited, true ) ) {

			$visited[] = $parent->parent;

			// http://devzone.zend.com/283/recursion-in-php-tapping-unharnessed-power/
			$this->generate_tax_hierarchy( $parent->parent, $visited, $tax );
		}

		$this->container->push( $parent->name, \get_category_link( $parent->term_id ) );
	}

	/**
	 * sort_terms_hierarchically
	 * https://wordpress.stackexchange.com/a/239935
	 *
	 * @param  array  &$terms
	 * @param  array  &$into
	 * @param  int    $parent_id
	 */
	private function sort_terms_hierarchically( array &$terms, array &$into, $parent_id = 0 ) {
		foreach ( $terms as $i => $term ) {
			if ( $term->parent === $parent_id ) {
				$into[ $term->term_id ] = $term;
				unset( $terms[ $i ] );
			}
		}

		foreach ( $into as $top_term ) {
			$top_term->children = array();
			$this->sort_terms_hierarchically( $terms, $top_term->children, $top_term->term_id );
		}
	}

	/**
	 * Get_term_flat
	 *
	 * @param  array  $terms
	 *
	 * @return array
	 */
	private function get_terms_flat( array $terms ) {

		static $new_terms = [];

		foreach ( $terms as $term ) {
			$this->get_terms_flat( $term->children );
			$new_terms[ $term->term_id ] = $term;
		}

		return $new_terms;
	}

	/**
	 * Sorted term html
	 *
	 * @param  array               $terms
	 * @param  array               $object_taxonomies
	 */
	private function sorted_term_html_output( array $terms = [], $object_taxonomies = null ) {

		$terms = $this->get_terms_flat( $terms );
		$terms = \array_reverse( $terms );

		foreach ( $terms as $term ) {

			$term_link = \get_term_link( $term, $object_taxonomies[0] );

			$this->container->push( $term->name, $term_link );
		}
	}
}
