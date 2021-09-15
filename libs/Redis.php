<?php declare( strict_types=1 );

namespace Mertowitch\Phpneeds
{

	class Redis extends \Redis
	{
		private static ?Redis $instance = null;
		private static object $config;

		private function __construct()
		{
			parent::__construct();
			self::_getConfig();
		}

		private static function _getConfig(): void
		{
			self::$config = include( __DIR__ . '/../confs/conf.redis.php' );
		}

		public static function getInstance(): Redis
		{
			if ( self::$instance === null )
			{
				self::$instance = ( new self() )->_getNewInstance();
			}

			return self::$instance;
		}

		private function _getNewInstance(): Redis
		{
			try
			{
				$newRedisInstance = new Redis();

				$newRedisInstance->connect( self::$config->HOST, self::$config->PORT, 1, null, 0, 0 );

				if ( str_contains( session_save_path(), 'auth=' ) )
				{
					$newRedisInstance->auth( self::$config->PASS );
				}
			}
			catch ( \Exception $e )
			{
				exit( $e->getMessage() );
			}

			return $newRedisInstance;
		}
	}
}
