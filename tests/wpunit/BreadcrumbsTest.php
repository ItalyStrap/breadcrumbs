<?php

class BreadcrumbsTest extends \Codeception\Test\Unit
{
    /**
     * @var \WpunitTester
     */
    protected $tester;
    protected $args;
    
    protected function _before()
    {
        $this->args = [
            'bloginfo_name' => get_option( 'blogname' ),
            'home_url'      => get_home_url( null, '/' ),
        ];

        global $wp_rewrite;
        $wp_rewrite->init();

        // $this->author_id     = $this->factory()->user->create();
        // $postId= $this->factory->post->create();

    }

    protected function _after()
    {
    }

    private function make_instance( $args = [], $type = 'html' ) {

        $args = array_merge( $this->args, $args );

        $sut = \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( $args, $type );

        return $sut;

    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $type = 'html';

        $sut = $this->make_instance( [], $type );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\View_Interface', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\View', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\\' . $type, $sut );

        $type = 'json';

        $sut = $this->make_instance( [], $type );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\View_Interface', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\View', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\\' . $type, $sut );
    }

    /**
     * @test
     * it should throw_if_is_not_the_correct_type
     */
    public function it_should_throw_if_is_not_the_correct_type() {
        $this->setExpectedException( 'InvalidArgumentException' );

        $this->make_instance( [], 'incorrect_type' );
    }

    /**
     * @test
     * it should be return_a_string
     */
    public function it_should_be_return_a_string()
    {
        /**
         * HTML
         */
        ob_start();
        echo $this->make_instance();
        $sut = ob_get_clean();

        $this->assertTrue( is_string( $sut ) );

        /**
         * Json
         */
        ob_start();
        echo $this->make_instance( [], 'json' );
        $sut = ob_get_clean();

        $this->assertTrue( is_string( $sut ) );
    }

    /**
     * @test
     * it should be string_start_with
     */
    public function it_should_be_string_start_with()
    {
        ob_start();
        echo $this->make_instance();
        $sut = ob_get_clean();

        $this->assertStringStartsWith( '<nav aria-label="breadcrumb">', $sut, 'message');

        ob_start();
        echo $this->make_instance( [], 'json' );
        $sut = ob_get_clean();

        $this->assertStringStartsWith( "<script type='application/ld+json'>", $sut, 'message');
    }

    /**
     * @test
     * it should be return_an_array_of_items
     */
    public function it_should_be_return_an_array_of_items()
    {
        /**
         * Array
         */
        $sut = $this->make_instance( [], 'array' );

        $this->assertTrue( is_array( $sut ) );
    }

    /**
     * @test
     * it should be return_an_object
     */
    public function it_should_be_return_an_object()
    {
        /**
         * Array
         */
        $sut = $this->make_instance( [], 'object' );

        $this->assertTrue( is_object( $sut ) );
    }
}



