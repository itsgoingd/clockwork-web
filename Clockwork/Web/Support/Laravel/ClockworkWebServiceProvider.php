<?php namespace Clockwork\Web\Support\Laravel;

use Clockwork\Clockwork;
use Clockwork\Web\Web as ClockworkWeb;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ClockworkWebServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if (!$this->app['clockwork.web.support']->isEnabled()) {
			return; // Clockwork Web is disabled, don't register the routes
		}

		if ($this->isLegacyLaravel()) {
			$this->app['router']->get('/__clockwork/app', 'Clockwork\Web\Support\Laravel\Controllers\LegacyController@render');
			$this->app['router']->get('/__clockwork/{path}', 'Clockwork\Web\Support\Laravel\Controllers\LegacyController@renderAsset')->where('path', '.+');
		} elseif ($this->isOldLaravel()) {
			$this->app['router']->get('/__clockwork/app', 'Clockwork\Web\Support\Laravel\Controllers\OldController@render');
			$this->app['router']->get('/__clockwork/{path}', 'Clockwork\Web\Support\Laravel\Controllers\OldController@renderAsset')->where('path', '.+');
		} else {
			$this->app['router']->get('/__clockwork/app', 'Clockwork\Web\Support\Laravel\Controllers\CurrentController@render');
			$this->app['router']->get('/__clockwork/{path}', 'Clockwork\Web\Support\Laravel\Controllers\CurrentController@renderAsset')->where('path', '.+');
		}

		$this->app['clockwork.web']->setCurrentRequestId($this->app['clockwork']->getRequest()->id);

		$this->app['view']->share('clockwork_web', $this->app['clockwork.web']->getIframe());
	}

	public function register()
	{
		if ($this->isLegacyLaravel() || $this->isOldLaravel()) {
			$this->package('itsgoingd/clockwork-web', 'clockwork-web', __DIR__);
		} else {
			$this->publishes(array(__DIR__ . '/config/clockwork-web.php' => config_path('clockwork-web.php')));
		}

		$legacy = $this->isLegacyLaravel() || $this->isOldLaravel();
		$this->app->singleton('clockwork.web.support', function($app) use($legacy)
		{
			return new ClockworkWebSupport($app, $legacy);
		});

		$this->app->singleton('clockwork.web', function($app)
		{
			return new ClockworkWeb();
		});

		if (!$this->app['clockwork.web.support']->isEnabled()) {
			return;
		}
	}

	public function provides()
	{
		return array('clockwork-web');
	}

	public function isLegacyLaravel()
	{
		return Str::startsWith(Application::VERSION, array('4.1.', '4.2.'));
	}
	public function isOldLaravel()
	{
		return Str::startsWith(Application::VERSION, '4.0.');
	}
}
