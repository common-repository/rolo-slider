<?php
namespace RoloOptions;

Class Fields
{

	/**
	 * Text field
	 */
	public function text($args)
	{
        $value = $this->get_value($args);
        $name  = $this->name_attr($args);
        $id = !empty($args['name']) ? $args['name'] : 'field_id_'.rand(0,60);

		$html = $this->before($args);

        $html .= '<input id="'.$id.'" type="text" '.$name.' value="'.$value.'" />';

		$html .= $this->after();

        return $html;
	}

	/**
	 * Color field
	 */
	public function color($args)
	{
        $value = $this->get_value($args);
        $name  = $this->name_attr($args);
        $id = !empty($args['name']) ? $args['name'] : 'field_id_'.rand(0,60);

		$html = $this->before($args);

        $html .= '<input id="'.$id.'" type="text" class="color" '.$name.' value="'.$value.'" />';

		$html .= $this->after();

        return $html;
	}

	/**
	 * Slide field
	 */
	public function slide($args)
	{
		$value = $this->get_value($args);
		$name  = $this->name_attr($args);
        $id = !empty($args['name']) ? $args['name'] : 'field_id_'.rand(0,60);
        $data = isset($args['data']) ? $args['data'] : '';

		$attr = '';
		if( isset($data['step']) ){
			$attr .= 'step="'.$data['step'].'"';
		}

		if( isset($data['min']) ){
			$attr .= ' min="'.$data['min'].'"';
		}

		if( isset($data['max']) ){
			$attr .= ' max="'.$data['max'].'"';
		}

		$html = $this->before($args);

		$html .= '<span class="rng-val"></span><input id="'.$id.'" type="range" '. $attr . ' ' .$name.' value="'.$value.'" />';

		$html .= $this->after();

		return $html;
	}

	/**
	 * Select field
	 */
	public function select($args)
	{
		$data = isset($args['data']) ? $args['data'] : false;
		$value = $this->get_value($args);
		$id = !empty($args['name']) ? $args['name'] : 'field_id_'.rand(0,60);

		$html = $this->before($args);

		$html .= '<select id="'.esc_attr($id).'"';
	    $html .= $this->name_attr($args).'>';

		if( !empty($data) && isset($data['ops'] ) ) {
			foreach( $data['ops'] as $key => $val )	{
                $atts = $this->data_atts($val);

				$html .= '<option value="'.$val['val'].'" '.$atts. ' ' .('' != $value ? (selected($value, $val['val'], false)) : '').'>'.$val['option'].'</option>';
			}
		}

		$html .= '</select>';

		$html .= $this->after();

		return $html;
	}

	/**
	 * Yes/No field
	 */
	public function yes_no($args)
	{
        $value = $this->get_value($args);
        $name = $this->name_attr($args);
		$data = isset($args['data']) ? $args['data'] : array();

		$html = $this->before($args);

        if( !empty($data) && isset($data['ops'] ) ) {
            foreach( $data['ops'] as $key => $val ) {
                $id = isset($val['id']) ? esc_attr($val['id']) : '';
                $atts = $this->data_atts($val);

                $html .= '<label class="switch">';
                $html .= isset($val['option']) ? esc_html($val['option']) : '';
                $html .= '<input id="'.$id.'" type="radio" '.$atts.' class="toggle" value="'.$val['val'].'" '.('' != $value ? (checked($value, $val['val'], false)) : ''). ' ' . $name. ' />';
                $html .= '</label>';
            }
        }

		$html .= $this->after();

        return $html;
	}

	/**
	 * Image Select
	 */
	public function image_select($args)
	{
		$value = $this->get_value($args);
		$name = $this->name_attr($args);
		$data = isset($args['data']) ? $args['data'] : array();

        $html = $this->before($args);

        if( !empty($data) && isset($data['ops'] ) ) {
            foreach( $data['ops'] as $key => $val ) {
                $css = array();
                $id = isset($val['id']) ? esc_attr($val['id']) : '';
                $width = isset($val['width']) ? esc_attr($val['width']) : '';
                $height = isset($val['height']) ? esc_attr($val['height']) : '';
                $atts = $this->data_atts($val);
                $css[] = isset($val['src']) ? 'background-image: url('.$val['src'].')' : '';
                $css[] = !empty($width) ? 'width: '.$width : '';
                $css[] = !empty($height) ? 'height: '.$height : '';

                $css = implode(';', $css);

                $html .= '<label class="img_icon" style="'.esc_attr($css).'">';
                $html .= '<input id="'.$id.'" type="radio" '.$atts.' class="toggle" value="'.$val['val'].'"' .$name. ' ' .('' != $value ? (checked($value, $val['val'], false)) : '').' />';
                $html .= '</label>';
            }
        }

        $html .= $this->after();

		return $html;
	}

    /**
     * Fallback field for layout select
     * for users prior to version 1.0.0
     *
     */
    public function layout_select($args)
    {
        $data = isset($args['data']) ? $args['data'] : array();
        $id = get_the_ID();
        $name = isset($args['name']) ? esc_attr($args['name']) : 'op_id_'.rand(0,60);

        $option = get_post_meta($id, $name, true );
        $default = isset( $args['data'] ) && isset($args['data']['default']) ? $args['data']['default'] : false;
        $value = '';

        if( isset( $option[0] ) && !empty($option[0]) ) {
            $value = $option[0];
        }

        if( $default && '' == $value ) {
            $value = $default;
        }

        $html = $this->before($args);

        if( !empty($data) && isset($data['ops'] ) ) {
            foreach( $data['ops'] as $key => $val ) {
                $css = array();
                $id = isset($val['id']) ? esc_attr($val['id']) : '';
                $width = isset($val['width']) ? esc_attr($val['width']) : '';
                $height = isset($val['height']) ? esc_attr($val['height']) : '';
                $atts = $this->data_atts($val);
                $css[] = isset($val['src']) ? 'background-image: url('.$val['src'].')' : '';
                $css[] = !empty($width) ? 'width: '.$width : '';
                $css[] = !empty($height) ? 'height: '.$height : '';

                $css = implode(';', $css);

                $html .= '<label class="img_icon" style="'.esc_attr($css).'">';
                $html .= '<input id="'.$id.'" type="radio" class="toggle" '.$atts.' value="'.$val['val'].'" name="'.$name. '[]" ' .('' != $value ? (checked($value, $val['val'], false)) : '').' />';
                $html .= '</label>';
            }
        }

        $html .= $this->after();

        return $html;
    }

	/**
	 * Image Select
	 */
	public function banner($args)
	{
		$html = $this->before($args);

		$css = array();
		$width = isset($args['width']) ? esc_attr($args['width']) : '';
		$height = isset($args['height']) ? esc_attr($args['height']) : '';
		$css[] = isset($args['src']) ? 'background-image: url('.esc_url($args['src']).')' : '';
		$css[] = !empty($width) ? 'width: '.esc_attr($width) : '100%';
		$css[] = !empty($height) ? 'height: '.esc_attr($height) : '320px';

		$css = implode(';', $css);

		$html .= '<a target="_blank" href="' . ( ! empty($args['url']) ? esc_url($args['url']) : '#') . '" class="banner" style="'.esc_attr($css).'"></a>';

		$html .= $this->after();

		return $html;
	}

	/**
	 * Get field value
	 */
	public function get_value($args)
	{
		$id = get_the_ID();
		$name = isset($args['name']) ? esc_attr($args['name']) : 'op_id_'.rand(0,60);

		$option = get_post_meta($id, $name, true );
		$default = isset( $args['data'] ) && isset($args['data']['default']) ? $args['data']['default'] : false;
		$value = '';

		if( !empty( $option ) ) {
			$value = $option;
		}

		if( $default && '' == $value ) {
			$value = $default;
		}

		return $value;
	}

	/**
	 * Get field name
	 */
	public function name_attr($args)
	{
		$name = 'name="'.(isset($args['name']) ? esc_attr($args['name']) : 'name_id_'.rand(0,60)).'"';

		return $name;
	}

    /**
     * Get data attributes
     */
    public function data_atts($args)
    {
        $atts = array();

        if( isset( $args['show'] ) && !empty( $args['show'] ) ) {
            $atts[] = 'data-show="'.$args['show'].'"';
        }

        if( isset( $args['hide'] ) && !empty( $args['hide'] ) ) {
            $atts[] = 'data-hide="'.$args['hide'].'"';
        }

        return implode(' ', $atts);
    }

	/**
	 * Before field output
	 */
	public function before($args)
	{
		$rowClass = isset( $args['rowclass'] ) ? $args['rowclass'] : '';
		$title = isset($args['title']) ? esc_html($args['title']) : '';

		$html  = '<div class="_rl_row '.esc_attr($rowClass).'">';
		$html .= '<div>';

		if( $title ) {
			$html .= '<label>'.$title.'</label>';
		}

		if( isset($args['desc']) && !empty($args['desc']) ) {
			$html .= '<span class="dashicons dashicons-info rolo-question"></span>';
			$html .= '<span class="question-bulb">'. esc_attr($args['desc']).'</span>';
		}

		$html .= '</div>';
		$html .= '<div>';

		return $html;
	}

	/**
	 * After field output
	 */
	public function after()
	{
		$html  = '</div></div>';

		return $html;
	}
}