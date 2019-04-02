<?php


class BreadcrumbsCest
{
    public function _before(AcceptanceTester $I)
    {
//        $I->loginAsAdmin();
//        $I->amOnPluginsPage();
//        $I->canSeePluginInstalled( 'Breadcrumbs' );
//		$I->activatePlugin( 'Breadcrumbs' );
//        $I->canSeePluginActivated( 'Breadcrumbs' );
    }

    public function _after(AcceptanceTester $I)
    {
    }

    /**
     * @test
     * it should render breadcrumbs
     */
    public function it_should_render_breadcrumbs(AcceptanceTester $I)
    {
//        $content = implode( ' ', array_fill( 0, 550, 'lorem' ) );
//        $page_id = $I->havePageInDatabase( [
//            'post_title'   => 'Home',
//            'post_content' => $content,
//        ] );
        $I->amOnPage( '/' );
        $I->seeElement( 'nav', ['aria-label' => 'breadcrumb'] );
        $I->seeElement( 'ol', ['itemtype' => 'https://schema.org/BreadcrumbList'] );

        $I->seeElement( 'a', ['itemprop' => 'item' ] );
    }

    /**
     * @test
     * it should render breadcrumbs_on_page
     */
//    public function it_should_render_breadcrumbs_on_page(AcceptanceTester $I)
//    {
//        $page_id = $I->havePageInDatabase( [
//            'post_title'   => 'Page',
//        ] );
//        $I->amOnPage( "/?p={$page_id}" );
////        $I->seeElement( '.breadcrumb' );
//		$I->seeElement( 'nav', ['aria-label' => 'breadcrumb'] );
//		$I->seeElement( 'ol', ['itemtype' => 'https://schema.org/BreadcrumbList'] );
//
//		$I->seeElement( 'a', ['itemprop' => 'item' ] );
//
////         $activated = $I->cli('plugin activate config');
//    }
}
