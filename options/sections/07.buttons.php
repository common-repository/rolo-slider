<?php
namespace RoloOptions\Section;

use  \RoloOptions\Section as Section;

Class Buttons extends Section
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
		$this->set_slug('buttons');
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
		return esc_html__('Buttons', 'rolo-slider');
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
		
		# 1st button bg color
		$options[] = array(
            'section' => $section,
            'name' => '_rl_btn_1_bg',
            'title' => esc_html__('1st Button Background Color', 'rolo-slider'),
            'type' => 'color'
        );

        # 1st button text color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_btn_1_txt',
            'title' => esc_html__('1st Button Text Color', 'rolo-slider'),
            'type' => 'color'
        );

        # 1st button hover text color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_btn_1_htxt',
            'title' => esc_html__('1st Button Hover Text Color', 'rolo-slider'),
            'type' => 'color'
        );

        # 1st button hover bg color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_btn_1_hbg',
            'title' => esc_html__('1st Button Hover Background Color', 'rolo-slider'),
            'type' => 'color'
        );

        # 2nd button bg color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_btn_2_bg',
            'title' => esc_html__('2nd Button Background Color', 'rolo-slider'),
            'type' => 'color'
        );

        # 2nd button text color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_btn_2_txt',
            'title' => esc_html__('2nd Button Text Color', 'rolo-slider'),
            'type' => 'color'
        );

        # 2nd button hover text color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_btn_2_htxt',
            'title' => esc_html__('2nd Button Hover Text Color', 'rolo-slider'),
            'type' => 'color'
        );

        # 2nd button hover bg color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_btn_2_hbg',
            'title' => esc_html__('2nd Button Hover Background Color', 'rolo-slider'),
            'type' => 'color'
		);

        return apply_filters("rolo_{$section}_options", $options);
	}
}
