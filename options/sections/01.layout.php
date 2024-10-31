<?php
namespace RoloOptions\Section;

use  \RoloOptions\Section as Section;

Class Layout extends Section
{

    public function __construct()
    {
        $this->set_slug('layout');
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
        return esc_html__('Layout', 'rolo-slider');
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

        $layouts = array(
            0 => array(
                'id'    => 'rl_highlights_box',
                'val'   => 'default',
                'src'   => ROLO_DIR . 'assets/images/default-layout.png',
                'width' => '96px',
                'height' => '96px'
            ),
            1 => array(
                'id'    => 'rl_responsive_box',
                'val' => 'images',
                'src' => ROLO_DIR . 'assets/images/responsive-layout.png',
                'width' => '96px',
                'height' => '96px'
            )
        );

        # Layouts
        $options[] = array(
            'section' => $section,
            'name' => '_rl_layout',
            'title' => esc_html__('Select Slider Layout', 'rolo-slider'),
            'type' => 'layout_select',
            'rowclass' => 'layout-select',
            'data' => array(
                'ops' => apply_filters('rolo_slider_layouts_list', $layouts),
                'default' => 'default'
            )
        );

        return apply_filters("rolo_{$section}_options", $options);
    }
}
