<?php

class BreadcrumbsFactoryTest extends \Codeception\TestCase\WPTestCase
{
    protected $args;
    protected $site_title;


    public function setUp()
    {
        // before
        parent::setUp();

        $this->args = [
            'bloginfo_name' => get_option( 'blogname' ),
            'home_url'      => get_home_url( null, '/' ),
        ];

        $this->site_title = 'Test';
    }

    public function tearDown()
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    private function make_instance( $type = 'html', $args = [] ) {

        $args = array_merge( $this->args, $args );

        $sut = \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( $type, $args );

        return $sut;

    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $type = 'html';

        $sut = $this->make_instance( $type );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\View_Interface', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\View', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\\' . $type, $sut );

        $type = 'json';

        $sut = $this->make_instance( $type );
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

        $this->make_instance( 'incorrect_type' );
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
        echo $this->make_instance( 'json' );
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
        echo $this->make_instance( 'json' );
        $sut = ob_get_clean();

        $this->assertStringStartsWith( "<script type='application/ld+json'>", $sut, 'message');

        //
        // assertOutputRegex($pattern) // PHPUnit docs
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
        $sut = $this->make_instance( 'array' );

        $this->assertTrue( is_array( $sut ) );
        $this->assertTrue( isset( $sut[0]['title'] ));
    }

    /**
     * @test
     * it should be return_an_object
     */
    public function it_should_be_return_an_object()
    {
        /**
         * Object
         */
        $sut = $this->make_instance( 'object' );

        $this->assertTrue( is_object( $sut ) );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\Container_Interface', $sut );
        $this->assertInstanceOf( '\ItalyStrap\Breadcrumbs\Container', $sut );
    }
}
