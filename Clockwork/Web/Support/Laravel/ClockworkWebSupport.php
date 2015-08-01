<?php namespace Clockwork\Web\Support\Laravel;

use Clockwork\Clockwork;
use Clockwork\Storage\FileStorage;
use Clockwork\Storage\SqlStorage;

use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;

class ClockworkWebSupport
{
	protected $app;
	protected $legacy;

	public function __construct(Application $app, $legacy)
	{
		$this->app = $app;
		$this->legacy = $legacy;
	}

	public function getConfig($key, $default = null)
	{
		if ($this->legacy) {
			if ($this->app['config']->has("clockwork-web::clockwork.{$key}")) {
				// try to look for a value from clockwork-web.php configuration file first
				return $this->app['config']->get("clockwork-web::clockwork.{$key}");
			} else {
				// try to look for a value from config.php (pre 1.3) or return the default value
				return $this->app['config']->get("clockwork-web::config.{$key}", $default);
			}
		} else {
			return $this->app['config']->get("clockwork-web.{$key}", $default);
		}
	}

	public function isEnabled()
	{
		$is_enabled = $this->getConfig('enable', null);

		if ($is_enabled === null) {
			$is_enabled = $this->app['clockwork.support']->isEnabled();
		}

		return $is_enabled;
	}
}
