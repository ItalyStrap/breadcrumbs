<?php

class BreadcrumbsFactoryTest extends \Codeception\TestCase\WPTestCase
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
    }

    public function tearDown() : void
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
     * it should throw_an_exception_if_is_not_the_correct_type
     */
    public function it_should_throw_an_exception_if_is_not_the_correct_type() {
        $this->expectException( InvalidArgumentException::class );

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
        $sut = $this->make_instance()->render();

        $this->assertTrue( is_string( $sut ) );

        /**
         * Json
         */
        $sut = $this->make_instance( 'json' )->render();

        $this->assertTrue( is_string( $sut ) );
    }

    /**
     * @test
     * it should be string_start_with
     */
    public function it_should_be_string_start_with()
    {
        $sut = $this->make_instance()->render();
        $this->assertStringStartsWith( '<nav aria-label="breadcrumb">', $sut, 'message');

        $sut = $this->make_instance( 'json' )->render();
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
        $this->assertInstanceOf( \ItalyStrap\Breadcrumbs\Container::class, $sut );
    }
}
