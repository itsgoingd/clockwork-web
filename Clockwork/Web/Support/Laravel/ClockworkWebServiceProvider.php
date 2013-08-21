<?php
namespace Clockwork\Web\Support\Laravel;

use Clockwork\Web\Web as ClockworkWeb;
use Clockwork\Clockwork;
use Illuminate\Support\ServiceProvider;

class ClockworkWebServiceProvider extends ServiceProvider
{
	public function boot()
	{
	}

	public function register()
	{
		$this->app['config']->package('itsgoingd/clockwork-web', __DIR__ . '/config');

		$isEnabled = $this->app['config']->get('clockwork-web::enable');
		if ($isEnabled === null) {
			$isEnabled = $this->app['config']->get('clockwork::enable');
		}
		if ($isEnabled === null) {
			$isEnabled = $this->app['config']->get('app.debug');
		}

		$this->app['clockwork.web'] = $this->app->share(function($app){
			return new ClockworkWeb();
		});

		if (!$isEnabled) {
			return; // Don't bother registering the routes and callbacks
		}

		$app = $this->app;
		$this->app->before(function($request) use($app){
			$app['clockwork.web']->setCurrentRequestId($app['clockwork']->getRequest()->id);

			$app['view']->share('clockwork_web', $app['clockwork.web']->getIframe());
		});

		$this->app['router']->get('/__clockwork/app', function() use($app){
			$app['clockwork.web']->render();
		});

		$this->app['router']->get('/__clockwork/{path}', function($path = null) use($app){
			$app['clockwork.web']->renderAsset($path);
		})->where('path', '.+');
	}

	public function provides()
	{
		return array('clockwork-web');
	}
}
