<?php
namespace Clockwork\Web\Support\Laravel;

use Clockwork\Web\Web as ClockworkWeb;
use Clockwork\Clockwork;
use Illuminate\Support\ServiceProvider;

class ClockworkWebServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->package('itsgoingd/clockwork-web', 'clockwork-web', __DIR__);

		if (!$this->isEnabled()) {
			return; // Clockwork web is disabled, don't register the routes
		}

		$app = $this->app;
		$this->app['router']->get('/__clockwork/app', function() use($app)
		{
			$app['clockwork.web']->render();
		});

		$this->app['router']->get('/__clockwork/{path}', function($path = null) use($app)
		{
			$app['clockwork.web']->renderAsset($path);
		})->where('path', '.+');
	}

	public function register()
	{
		$this->app->singleton('clockwork.web', function($app)
		{
			return new ClockworkWeb();			
		});

		$app = $this->app;
		$service = $this;
		$this->app->before(function($request) use($app, $service)
		{
			if (!$service->isEnabled()) {
				return;
			}

			$app['clockwork.web']->setCurrentRequestId($app['clockwork']->getRequest()->id);

			$app['view']->share('clockwork_web', $app['clockwork.web']->getIframe());
		});
	}

	public function provides()
	{
		return array('clockwork-web');
	}

	private function isEnabled()
	{
		$is_enabled = $this->app['config']->get('clockwork-web::config.enable');

		if ($is_enabled === null) {
			$is_enabled = $this->app['config']->get('clockwork::config.enable');
		}

		if ($is_enabled === null) {
			$is_enabled = $this->app['config']->get('app.debug');
		}

		return $is_enabled;
	}
}
