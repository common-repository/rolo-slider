<?php
namespace RoloOptions\Section;

use  \RoloOptions\Section as Section;

Class Pagination extends Section
{

	public function __construct()
	{
		$this->set_slug('pagination');
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
		return esc_html__('Pagination', 'rolo-slider');
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

		# Slide Circle Pagination
		$options[] = array(
				'section' => $section,
				'name' => '_rl_bullets',
				'title' => esc_html__('Circle Pagination', 'rolo-slider'),
				'desc' => esc_html__('Display or hide circle pagination located at the bottom of the slider.', 'rolo-slider'),
				'type' => 'yes_no',
				'data' => array(
					'ops' => array(
						0 => array(
							'val'    => 'on',
							'option' => esc_html__('On', 'rolo-slider'),
							'id'     => 'bulon',
							'show'   => '.pagination-data'
						),
						1 => array(
							'val'    => 'off',
							'option' => esc_html__('Off', 'rolo-slider'),
							'id'     => 'buloff',
							'hide'   => '.pagination-data'
						)
					),
					'default' => 'no',
				)
		);

		# Bullets color
		$options[] = array(
				'section' => $section,
				'name' => '_rl_bulletsc',
				'title' => esc_html__('Cirles Color', 'rolo-slider'),
				'rowclass' => 'pagination-data',
				'type' => 'color'
		);

		# Active Bullet Color
		$options[] = array(
				'section' => $section,
				'name' => '_rl_bulletsac',
				'title' => esc_html__('Active Circle Color', 'rolo-slider'),
				'desc' => esc_html__('Color of the currently active circle of slider pagination.', 'rolo-slider'),
				'rowclass' => 'pagination-data',
				'type' => 'color'
		);

		return apply_filters("rolo_{$section}_options", $options);
	}
}
