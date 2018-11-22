# ItalyStrap Breadcrumbs

Breadcrumbs Class API for WordPress

This package create an HTML or Json Breadcrumbs elements to display on your WordPress site

## Installation

### Install with Composer

Add the package to your projects `composer.json` file. Visit [getcomposer.org](http://getcomposer.org/) more information.

```json
{
    "require": {
        "italystrap/breadcrumbs": "dev-master"
    }
}
```

### Install Manually

Download and include the class file into your theme/plugin:

```php
include_once 'path/to/ItalyStrap/Breadcrumbs.php';
```

## Usage

### Basic usage

Use `\ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( $args, $type )` to display the breadcrumbs in your template.

```php
use ItalyStrap\Breadcrumbs;

echo Breadcrumbs_Factory::make( $args, 'html' );

// Or

echo Breadcrumbs_Factory::make( $args, 'json' );

```

The second parameter is the type of breadcrumbs you want to display, "HTML" or "Json".

## Options

An optional array of arguments can be passed to modify the breadcrumb output.
The defaults for each option @see `Breadcrumbs/config/breadcrumbs.php`

```php
/**
 * Default configuration for Breadcrumbs
 */
return [

	/**
	 * This is the container of the breadcrumbs
	 * @example <nav aria-label="breadcrumb">...</nav>
	 */
	'container_tag'				=> 'nav',
	'container_attr'			=> [
		'aria-label'	=> 'breadcrumb',
	],

	/**
	 * This is the list tag of the breadcrumbs
	 * @example <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">...</ol>
	 */
	'list_tag'					=> 'ol',
	'list_attr'					=> [
		'class'			=> 'breadcrumb',
		'itemscope'		=> true,
		'itemtype'		=> 'https://schema.org/BreadcrumbList',
	],

	/**
	 * This is the item tag of the breadcrumbs
	 * @example <li class="breadcrumb-item [...active]" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">...</li>
	 */
	'item_tag'					=> 'li',
	'item_attr'					=> [
		'class'			=> "breadcrumb-item",
		'itemprop'		=> 'itemListElement',
		'itemscope'		=> true,
		'itemtype'		=> 'https://schema.org/ListItem',
	],
	/**
	 * Css class for active element
	 */
	'item_attr_class_active'	=> ' active',

	/**
	 * It could be passed an HTML icon to show instead of the firt element (home)
	 * @example <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
	 */
	'home_icon'					=> false,

	/**
	 * Separator for the items
	 * @example ' /'
	 */
	'separator'					=> false,

	/**
	 * Show on front
	 * @default true
	 */
	'show_on_front'				=> true,
];
```

### Default output

```html
<nav aria-label="breadcrumb">
	<ol class="breadcrumb" itemscope="" itemtype="https://schema.org/BreadcrumbList">
		<meta name="numberOfItems" content="2">
		<meta name="itemListOrder" content="Ascending">
		<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
			<a itemprop="item" href="http://192.168.1.10/italystrap/">
				<span itemprop="name">ItalyStrap</span></a>
				<meta itemprop="position" content="1">
		</li>
		<li class="breadcrumb-item active" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem" aria-current="page">
			<a itemprop="item" href="http://192.168.1.10/italystrap/blog/">
				<span itemprop="name">Blog</span></a>
				<meta itemprop="position" content="2">
		</li>
	</ol>
</nav>
```

## Advanced usage

### Example for HTML version

```php
/**
 * Get the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function get_breadcrumbs( array $args = array() ) {

	$args['bloginfo_name'] = GET_BLOGINFO_NAME;
	$args['home_url'] = HOME_URL;
	$args['separator'] = false;

	$args['show_on_front'] = false;

	try {

		return apply_filters(
			'italystrap_get_the_breadcrumbs',
			\ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( $args, 'html' ),
			$args
		);

	} catch ( Exception $e ) {
		echo $e->getMessage();
	}
}

/**
 * Print the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function breadcrumbs( array $args = array() ) {

	echo get_breadcrumbs( $args );
}

/**
 * Do breadcrumbs
 *
 * @since 2.2.0
 *
 * @param  array  $args The breadcrumbs arguments.
 */
function do_breadcrumbs( array $args = array() ) {

	breadcrumbs( $args );
}
add_action( 'do_breadcrumbs', __NAMESPACE__ . '\do_breadcrumbs' );
```

### Example for Json version

```php
/**
 * Get the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function get_breadcrumbs( array $args = array() ) {

	$args['bloginfo_name'] = GET_BLOGINFO_NAME;
	$args['home_url'] = HOME_URL;

	$args['show_on_front'] = false;

	try {

		return apply_filters(
			'italystrap_get_the_breadcrumbs',
			\ItalyStrap\Breadcrumbs\Breadcrumbs_Factory::make( $args, 'json' ),
			$args
		);

	} catch ( Exception $e ) {
		echo $e->getMessage();
	}
}

/**
 * Print the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function breadcrumbs( array $args = array() ) {

	echo get_breadcrumbs( $args );
}

/**
 * Do breadcrumbs
 *
 * @since 2.2.0
 *
 * @param  array  $args The breadcrumbs arguments.
 */
function do_breadcrumbs( array $args = array() ) {

	breadcrumbs( $args );
}
add_action( 'wp_footer', __NAMESPACE__ . '\do_breadcrumbs' );
```

## Filters

> TODO

## Other Example

> TODO

## array_insert()

`array_insert()` is a function that allows you to insert a new element into an array at a specific index.

### Example array_insert()

```php
/**
 * Modify breadcrums list
 *
 * @param  {array} $list
 *
 * @return {array}
 */
function modify_breadcrumbs_list( array $list ) {

    // if on the events category archive page
    if( is_tax( 'event-categories' ) ) {

        // create a new element
        $element = [
            'title'	=> "Shows",
            'url'	=> site_url( '/shows' )
        ];

        // add the new element at the index of 1
        $list = array_insert( $list, $element, 1 );
    }

    return $list;
}

add_filter( 'ItalyStrap\Breadcrumbs\Container\Items', 'modify_breadcrumbs_list' );
```

## Notes

*  Licensed under the [GNU General Public License v2.0](https://github.com/ItalyStrap/breadcrumbs/blob/master/LICENSE)
*  Maintained under the [Semantic Versioning Guide](http://semver.org)

## Author

**Enea Overclokk**
* [https://italystrap.com/](https://italystrap.com/)
