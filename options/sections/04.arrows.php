<?php
namespace RoloOptions\Section;

use  \RoloOptions\Section as Section;

Class Arrows extends Section
{

    public function __construct()
    {
        $this->set_slug('arrows');
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
        return esc_html__('Arrows', 'rolo-slider');
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

        # Icon Style
        $options[] = array(
            'section' => $section,
            'name' => '_rl_icstyle',
            'title' => esc_html__('Icon Style', 'rolo-slider'),
            'type' => 'image_select',
            'data' => array(
                'ops' => array(
                    0 => array(
                        'val' => 'circles',
                        'src' => ROLO_ASSETS_URL.'images/circles.png'
                    ),
                    1 => array(
                        'val' => '',
                        'src' => ROLO_ASSETS_URL.'images/square.png'
                    )
                ),
                'default' => ''
            )
        );

        # Icon color
        $options[] = array(
            'section' => $section,
            'name'    => '_rl_iclr',
            'title'   => esc_html__('Icon Color', 'rolo-slider'),
            'type'    => 'color'
        );

        # Icon bg color
        $options[] = array(
            'section'  => $section,
            'name'     => '_rl_ibck',
            'title'    => esc_html__('Icon Background Color', 'rolo-slider'),
            'desc'     => esc_html__('Background color of the circle/square that holds the icon.', 'rolo-slider'),
            'type'     => 'color',
            'data'     => array(
                'default' => 'rgba(183, 180, 180, 0.74)'
            )
        );

        # Hover Icon color
        $options[] = array(
            'section' => $section,
            'name'    => '_rl_iconac',
            'title'   => esc_html__('Hover Icon Color', 'rolo-slider'),
            'desc'     => esc_html__('Arrow color when it is hovered with a mouse.', 'rolo-slider'),
            'type'    => 'color',
            'data'    => array(
                'default' => 'rgba(183, 180, 180, 0.74)'
            )
        );

        # Hover Icon bg color
        $options[] = array(
            'section' => $section,
            'name' => '_rl_iconab',
            'title' => esc_html__('Hover Icon Background', 'rolo-slider'),
            'desc'     => esc_html__('Arrow holder background when it is hovered with a mouse.', 'rolo-slider'),
            'type' => 'color',
            'data' => array(
                'default' => 'rgba(183, 180, 180, 0.74)'
            )
        );

        return apply_filters("rolo_{$section}_options", $options);
    }
}
