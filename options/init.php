<?php
/**
 * Init The Options Loader
 */

# Loader
require_once 'options.loader.php';

use \RoloOptions\Loader as Loader;

$name = 'rolo';

new Loader($name);

