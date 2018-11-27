<?php


class BreadcrumbsCest
{
    public function _before(AcceptanceTester $I)
    {
        // $I->loginAsAdmin();
        // $I->amOnPluginsPage();
        // $I->seePluginInstalled( 'breadcrumbs' );
        // $I->seePluginActivated( 'breadcrumbs/index.php' );
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    // public function tryToTest(AcceptanceTester $I)
    // {
    //     $content = implode( ' ', array_fill( 0, 550, 'lorem' ) );
    //     $page_id = $I->havePageInDatabase( [
    //         'post_title'   => 'Home',
    //         'post_content' => $content,
    //     ] );
    //     $I->amOnPage( "/?p={$page_id}" );
    //     $I->see( "Home" );
    // }

    // tests
    public function tryToTest2(AcceptanceTester $I)
    {
        // $content = implode( ' ', array_fill( 0, 550, 'lorem' ) );
        // $page_id = $I->havePageInDatabase( [
        //     'post_title'   => 'Home',
        //     'post_content' => $content,
        // ] );
        // $I->amOnPage( '/' );
        // $I->seeElement( 'breadcrumb' );
    }
}
