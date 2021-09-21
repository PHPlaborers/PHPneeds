<?php declare( strict_types=1 );

/*
 * This file is part of PHPneeds.
 *
 * (c) Mertcan Ayhan <mertowitch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mertowitch\Phpneeds
{

	use PDO;
	use PDOException;

	/**
	 * Class Database
	 */
	class Database extends PDO
	{
		/**
		 * @var Database|null
		 */
		private static ?Database $instance = null;
		/**
		 * @var object
		 */
		private static object $config;
		/**
		 * @var string
		 */
		private static string $configName;

		/**
		 * @param string $configName
		 */
		private function __construct( string $configName = 'default' )
		{
			self::$configName = $configName;
			self::_getConfig();

			parent::__construct( self::$config->TYPE . ':host=' . self::$config->HOST . ';port=' . self::$config->PORT . ';dbname=' . self::$config->NAME . ';charset=utf8', self::$config->USER, self::$config->PASS );

			$this->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
			$this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}

		private static function _getConfig(): void
		{
			self::$config = include( __DIR__ . '/../confs/conf.db.' . self::$configName . '.php' );
		}

		/**
		 * @param string $configName
		 *
		 * @return Database
		 */
		public static function getInstance( string $configName = 'default' ): Database
		{
			if ( self::$instance === null )
			{
				self::$instance = ( new self( $configName ) )->_getNewInstance();
			}

			return self::$instance;
		}

		/**
		 * @return Database
		 */
		private function _getNewInstance(): Database
		{
			try
			{
				$newDbInstance = new self( self::$configName );
			}
			catch ( PDOException $e )
			{
				exit( $e->getMessage() );
			}

			return $newDbInstance;
		}

		/**
		 * @param string $configName
		 *
		 * @return Database
		 */
		public static function getNewInstance( string $configName = 'default' ): Database
		{
			return ( new self( $configName ) )->_getNewInstance();
		}
	}
}
