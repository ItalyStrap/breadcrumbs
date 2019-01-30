<?php
/*
Plugin Name: Breadcrumbs
Description: Description
Plugin URI: http://#
Author: Author
Author URI: http://#
Version: 1.0
License: GPL2
Text Domain: Text Domain
Domain Path: Domain Path
*/

/*

    Copyright (C) Year  Author  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require( __DIR__ . '/vendor/autoload.php' );

/**
 * Init
 *
 * @param  string $value [description]
 * @return string        [description]
 */
function init() {

    $args = [
        'bloginfo_name' => get_option( 'blogname' ),
        'home_url'      => get_home_url( null, '/' ),
    ];

    $breadcrumbs = \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( 'html', $args );

    $breadcrumbs->output();

    $json = \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( 'json', $args );

    /**
     * var_dump()
     */
    // d( $json );

    $obj = \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( 'object', $args );

    /**
     * var_dump()
     */
    // d( $obj );

    $array = \ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( 'array', $args );

    /**
     * var_dump()
     */
    // debug( $array );
    // d( $array );
	return true;
}

add_action( 'get_footer', 'init' );
