<?php
namespace Clockwork\Web\Support\Slim;

use Clockwork\Clockwork;
use Clockwork\DataSource\PhpDataSource;
use Clockwork\DataSource\SlimDataSource;
use Clockwork\Storage\FileStorage;

use Slim\Middleware;

class ClockworkWebMiddleware extends Middleware
{
	private $clockwork;

	public function __construct()
	{
		$this->clockwork = $this->app->config('clockwork');
	}

	public function call()
	{
		$clockworkWeb = new ClockworkWeb();
		$clockworkWeb->setCurrentRequestId($this->clockwork->getRequest()->id);

		if ($this->app->config('debug')) {
			if (preg_match('#/__clockwork/app#', $this->app->request()->getPathInfo(), $matches))
				return $clockworkWeb->render();
			else if (preg_match('#/__clockwork/(?<path>.+)#', $this->app->request()->getPathInfo(), $matches))
				return $clockworkWeb->renderAsset($matches['path']);

			$this->app->view()->set('clockwork_web', $clockworkWeb->getIframe());
		}


		try {
			$this->next->call();
		} catch (Exception $e) {
			throw $e;
		}
	}
}
