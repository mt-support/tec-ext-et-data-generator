<?php
/**
 * Handles registering all Assets for the extension.
 *
 * To remove a Asset you can use the global assets handler:
 *
 * ```php
 *  tribe( 'assets' )->remove( 'asset-name' );
 * ```
 *
 * @since 1.0.0
 *
 * @package Tribe\Extensions\ET_Test_Data_Generator
 */
namespace Tribe\Extensions\ET_Test_Data_Generator;


use TEC\Common\Contracts\Service_Provider;
/**
 * Register Assets.
 *
 * @since 1.0.0
 *
 * @package Tribe\Extensions\ET_Test_Data_Generator
 */
class Assets extends Service_Provider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'extension.et_test_data_generator.assets', $this );

		$plugin = tribe( Plugin::class );

	}
}
