<?php
/**
 * Default configuration for Breadcrumbs
 *
 * @since 1.0.0
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
