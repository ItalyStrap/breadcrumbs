<?php

/**
 * Collection of notes on WP_UnitTestCase:
 * https://gist.github.com/benlk/d1ac0240ec7c44abd393
 *
 * https://www.theaveragedev.com/the-power-of-go_to-and-wordpress-kinda-functional-tests/
 *
 * https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/testcase.php
 */
class BreadcrumbsTest extends \Codeception\TestCase\WPTestCase
{
    protected $args;
    protected $site_title;


    public function setUp(): void
    {
        // before
        parent::setUp();

        $this->args = [
            'bloginfo_name' => get_option( 'blogname' ),
            'home_url'      => get_home_url( null, '/' ),
        ];

        $this->site_title = 'Test';

        // global $wp_rewrite;
        // $wp_rewrite->init();

        $this->author_id = $this->factory()->user->create();

        $this->post_ids = $this->factory->post->create_many( 15, [ 'post_author' => $this->author_id ] );

        // $postObj = $this->factory()->post->create_and_get();

        $this->page_id = $this->factory()->post->create([ 'post_type' => 'page', 'post_title' => 'Page', 'post_author' => $this->author_id ]);

        $this->front_page_id = $this->factory->post->create( [ 'post_type' => 'page', 'post_title' => 'HOME' ] );

        $this->blog_page_id  = $this->factory->post->create( [ 'post_type' => 'page', 'post_title' => 'BLOG' ] );

        $this->tag_id = $this->factory->term->create( [ 'taxonomy' => 'post_tag' ] );

        $this->post_type     = rand_str( 12 );
        $this->taxonomy      = rand_str( 12 );

        register_post_type(
            $this->post_type,
            [
                'public'      => true ,
                'taxonomies'  => ['category'],
                'has_archive' => true
            ]
        );

        register_taxonomy(
            $this->taxonomy,
            $this->post_type,
            [
                'public' => true,
            ]
        );
    }

    public function tearDown(): void
    {
        update_option( 'show_on_front', 'posts' );
        update_option( 'page_on_front', 0 );
        update_option( 'page_for_posts', 0 );
        _unregister_post_type( $this->post_type );
        _unregister_taxonomy( $this->taxonomy, $this->post_type );

        // then
        parent::tearDown();
    }

    private function make_instance( $type = 'html', $args = [] ) {

        $args = array_merge( $this->args, $args );

        $sut = \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( $type, $args, true );

        return $sut;
    }

    /**
     * @test
     * it should be front_page
     */
    public function it_should_be_front_page()
    {
        $this->go_to( home_url() );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be static page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_static_page()
    {

        $this->go_to( get_permalink( $this->page_id ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'Page',
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be attachment
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_attachment()
    {

        // TODO
    }

    /**
     * @test
     * it should be single
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_single()
    {

        // TODO
    }

    /**
     * @test
     * it should be static page_with_ancestor
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_static_page_with_ancestor()
    {

        // TODO
    }

    /**
     * @test
     * it should be blog page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_blog_page()
    {

        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $this->front_page_id );
        update_option( 'page_for_posts', $this->blog_page_id );

        $this->go_to( get_permalink( $this->blog_page_id ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'BLOG',
                    'url'   => get_permalink( $this->blog_page_id )
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be blog page_paged
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_blog_page_paged()
    {

        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $this->front_page_id );
        update_option( 'page_for_posts', $this->blog_page_id );

        $this->set_permalink_structure('%postname%');

        $this->go_to( get_permalink( $this->blog_page_id ) . '/page/2/' );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'BLOG',
                    'url'   => get_permalink( $this->blog_page_id )
                ],
                [
                    'title' => 'Page 2',
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be tax page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_tax_page()
    {
        // TODO
    }

    /**
     * @test
     * it should be category page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_category_page()
    {
        // TODO: hierarchy of the category

        $category = $this->factory->category->create();
        $term = get_term( $category, 'category' );
        $this->go_to( get_term_link( $category ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => $term->name,
                    'url'   => get_term_link( $category )
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be tag page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_tag_page()
    {
        $tag = $this->factory->tag->create();
        $term = get_term( $tag, 'post_tag' );
        $this->go_to( get_term_link( $tag ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'Tag: ' . $term->name,
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be post_type_archive page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_post_type_archive_page()
    {
        // TODO
    }

    /**
     * @test
     * it should be year page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_year_page()
    {
        $newest_post = get_post( $this->post_ids[0] );
        $year = date( 'Y', strtotime( $newest_post->post_date ) );

        $this->go_to( get_year_link( $year ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'Yearly archive: ' . $year,
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be month page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_month_page()
    {
        $newest_post = get_post( $this->post_ids[0] );
        $year = date( 'Y', strtotime( $newest_post->post_date ) );
        $month = date( 'm', strtotime( $newest_post->post_date ) );

        $this->go_to( get_month_link( $year, $month ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => $year,
                    'url'   => get_year_link( $year )
                ],
                [
                    'title' => 'Monthly archive: ' . date( 'F', strtotime( $newest_post->post_date ) ),
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be day page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_day_page()
    {
        $newest_post = get_post( $this->post_ids[0] );
        $year = date( 'Y', strtotime( $newest_post->post_date ) );
        $month = date( 'm', strtotime( $newest_post->post_date ) );
        $day   = date( 'j', strtotime( $newest_post->post_date ) );

        $this->go_to( get_day_link( $year, $month, $day ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => $year,
                    'url'   => get_year_link( $year )
                ],
                [
                    'title' => $month,
                    'url'   => get_month_link( $year, $month )
                ],
                [
                    'title' => 'Daily archive: ' . date( 'd', strtotime( $newest_post->post_date ) ),
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be author page
     * $this->go_to( '/?p=' . $this->page_id );
     */
    public function it_should_be_author_page()
    {

        $this->go_to( get_author_posts_url( $this->author_id ) );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'Author Archives: ' . get_the_author_meta( 'user_login', $this->author_id ),
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be search_page
     */
    public function it_should_be_search_page()
    {
        // $this->set_permalink_structure('%postname%');
        $this->go_to( home_url() . '/?s=cerca' );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'Search Results for: cerca',
                    'url'   => false
                ],
            ],
            $sut
        );
    }

    /**
     * @test
     * it should be 404_page
     */
    public function it_should_be_404_page()
    {
        $this->set_permalink_structure('%postname%');
        $this->go_to( home_url() . '/404' );

        /**
         * Array
         */
        $sut = $this->make_instance( 'array' );

        $this->assertEquals(
            [
                [
                    'title' => $this->site_title,
                    'url'   => $this->args['home_url']
                ],
                [
                    'title' => 'Not Found',
                    'url'   => false
                ],
            ],
            $sut
        );
    }
}



