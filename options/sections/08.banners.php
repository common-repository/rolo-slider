<?php
namespace RoloOptions\Section;

use  \RoloOptions\Section as Section;

Class Banners extends Section
{
	/**
	* The section slug
	*
	* @since 1.0.0
	*
	*/
	public $slug;

	public function __construct()
	{
		$this->set_slug('banners');
		$this->hooks();
	}

	public function set_slug($slug)
	{
		$this->slug = $slug;
	}

	/**
	* The section markup
	*
	* @since 1.0.0
	*
	*/
	public function section()
	{
		return esc_html__('Check Our Themes', 'rolo-slider');
	}

	/**
	* Main plugin options
	*
	* @since 1.0.0
	*
	*/
	public function options($options)
	{
		$section = $this->slug;

        $options[] = array(
            'section' => $section,
            'name' => '',
            'title' => esc_html__('Get Our Best Multipurpose Theme For Startups And Creatives', 'rolo-slider'),
            'type' => 'banner',
	        'src' => ROLO_ASSETS_URL.'images/banner-green-ink-vertical.jpg',
	        'url' => 'http://pressfore.com/item/greenink-pro/',
	        'height' => '400px'
		);

        return apply_filters("rolo_{$section}_options", $options);
	}
}
