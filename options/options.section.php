<?php
namespace RoloOptions;

abstract class Section
{
	/**
	 * The section slug
	 *
	 * @since 1.0.0
	 *
	 */
	public $slug;

	/**
	 * Main hooks that add options
	 *
	 * @since 1.0.0
	 *
	 */
	public function hooks()
	{
		add_filter( 'rolo_options_data', array( $this, 'options' ), 1, 1 );
	}

	/**
	 * Section options method
	 *
	 * @since 1.0.0
	 *
	 */
	public function options($options){}

	public function callback()
	{
		$slug = $this->slug;

		do_action("rolo_{$slug}_cb", $slug);
	}
}