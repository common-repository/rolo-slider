<?php
namespace RoloOptions\Section;

use  \RoloOptions\Section as Section;

Class Captions extends Section
{

	public function __construct()
	{
		$this->set_slug('captions');
		$this->hooks();
	}

	/**
	 * The section slug
	 *
	 * @since 1.0.0
	 *
	 */
	public function set_slug($slug)
	{
		$this->slug = $slug;
	}

	/**
	 * The section Title
	 *
	 * @since 1.0.0
	 *
	 */
	public function section()
	{
		return esc_html__('Captions', 'rolo-slider');
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

		# Captions bg color
		$options[] = array(
			'section' => $section,
			'name' => '_rl_captions_bg',
			'title' => esc_html__('Captions Background Color', 'rolo-slider'),
			'desc' => esc_html__('Background color for layer captions - title, subtitle and description.', 'rolo-slider'),
			'type' => 'color'
		);

		# Captions text color
		$options[] = array(
			'section' => $section,
			'name' => '_rl_captions_txt',
			'title' => esc_html__('Captions Text Color', 'rolo-slider'),
			'desc' => esc_html__('Text color for layer  captions -  title, subtitle and description.', 'rolo-slider'),
			'type' => 'color'
		);

		return apply_filters("rolo_{$section}_options", $options);
	}
}
