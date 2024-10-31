<?php
namespace RoloOptions;

Class Loader
{
	private $name;

    private $data;

	public function __construct($name)
	{
		$this->name = $name;

		add_action('init', array($this, 'hooks'));
	}

	public function hooks()
	{
		$name = $this->name;

		$this->includes();
		$this->instance($name);

        add_filter('rolo_sections_data', array($this, 'sections'), 10, 1);
	}

	/**
	* Instantiate core class
	*
	* @since 1.0.0
	*/
	function instance($name)
	{
		new \RoloOptions\Init($name);
	}

    /**
     * Add sections
     *
     * @since 1.0.0
     */
    function sections()
    {
        $data = $this->data;

        return $data;
    }

	/**
	* Include framework files
	*
	* Include coe file, sections and options
	*
	* @since 1.0.0
	*/
	function includes()
	{
        $namespace = '\RoloOptions\Section\\';
		$files     = plugin_dir_path(__FILE__). 'sections'.DIRECTORY_SEPARATOR."*.php";

		$sections = glob($files);

		# Framework Core
        require_once 'options.section.php';
        require_once 'options.fields.php';
		require_once 'options.core.php';

		# Get all sections
		foreach( $sections as $section ) {
			$file  = basename($section);
			$class = $this->section_class($namespace, $file);

			require_once "sections/{$file}";

			$this->data[] = new $class;
		}

        $data = $this->data;

        $this->data = apply_filters('rolo_sections_order', $data);
	}

    /**
     * Get Class
     *
     * @since 1.0.0
     */
    function section_class($namespace, $file)
    {
        $num   = substr($file, 0, 3);

        $class = str_replace('.php', '', $file);
        $class = str_replace($num, '', $class);
        $class = $namespace . ucfirst($class);

        return $class;
    }
}