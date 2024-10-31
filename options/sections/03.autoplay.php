<?php
namespace RoloOptions\Section;

use  \RoloOptions\Section as Section;

Class Autoplay extends Section
{

	public function __construct()
	{
		$this->set_slug('autoplay');
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
		return esc_html__('Autoplay', 'rolo-slider');
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

		# Autoplay
		$options[] = array(
				'section' => $section,
				'name' => '_rl_autoplay',
				'title' => esc_html__('Autoplay', 'rolo-slider'),
				'type' => 'yes_no',
				'data' => array(
					'ops' => array(
						0 => array(
							'val' => 'on',
							'option' => esc_html__('On', 'rolo-slider'),
							'id'     => 'auton',
							'show'   => '.autoplay-data'
						),
						1 => array(
							'val' => 'off',
							'option' => esc_html__('Off', 'rolo-slider'),
							'id'     => 'autoff',
							'hide'   => '.autoplay-data'
						)
					),
					'default' => 'on',
				)
		);

		# Autoplay Delay
		$options[] = array(
				'section'  => $section,
				'name'     => '_rl_delay',
				'title'    => esc_html__('Autoplay Delay', 'rolo-slider'),
				'desc'     => esc_html__('Delay between slides (in ms).', 'rolo-slider'),
				'rowclass' => 'autoplay-data',
				'type'     => 'slide',
				'data'     => array(
						'step'    => 100,
						'min'	  => 1000,
						'max'	  => 15000,
						'default' => 3500
				)
		);

		# Autoplay Stop
		$options[] = array(
				'section' => $section,
				'name' => '_rl_autst',
				'title' => esc_html__('Autoplay Stop', 'rolo-slider'),
				'desc'    => esc_html__('Stop the autoplay when mouse enters the screen/phone area.', 'rolo-slider'),
				'rowclass' => 'autoplay-data',
				'type' => 'yes_no',
				'data' => array(
					'ops' => array(
						0 => array(
							'val' => 'on',
							'option' => esc_html__('On', 'rolo-slider'),
							'id'     => 'aston'
						),
						1 => array(
							'val' => 'off',
							'option' => esc_html__('Off', 'rolo-slider'),
							'id'     => 'astoff'
						)
					),
					'default' => 'on'
				)
		);

		return apply_filters("rolo_{$section}_options", $options);
	}
}
