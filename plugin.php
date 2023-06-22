<?php
/**
 * Plugin Name:       Event Tickets Extension: Test Data Generator
 * GitHub Plugin URI: https://github.com/mt-support/tec-ext-et-data-generator
 * Description:       This extension aims to provide an automated tool to generate high quality, life-like data for the Event Tickets suite of plugins.
 * Version:           1.1.0
 * Author:            The Events Calendar
 * Author URI:        http://evnt.is/1971
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       et-test-data-generator
 *
 *     This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
 */

/**
 * Define the base file that loaded the plugin for determining plugin path and other variables.
 *
 * @since 1.0.0
 *
 * @var string Base file that loaded the plugin.
 */
define( 'TEC_EXTENSION_ET_TEST_DATA_GENERATOR_FILE', __FILE__ );

/**
 * Register and load the service provider for loading the extension.
 *
 * @since 1.0.0
 */
function tec_extension_et_test_data_generator() {
	// When we dont have autoloader from common we bail.
	if  ( ! class_exists( 'Tribe__Autoloader' ) ) {
		return;
	}

	// Register the namespace so we can the plugin on the service provider registration.
	Tribe__Autoloader::instance()->register_prefix(
		'\\Tribe\\Extensions\\ET_Test_Data_Generator\\',
		__DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Tribe',
		'test-data-generator'
	);

    require_once __DIR__ . '/vendor/autoload.php';

	tribe_register_provider( '\Tribe\Extensions\ET_Test_Data_Generator\Plugin' );
}

// Loads after common is already properly loaded.
add_action( 'tribe_common_loaded', 'tec_extension_et_test_data_generator' );
