<?php namespace Clockwork\Web\Support\Laravel\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Controllers\Controller;

class OldController extends Controller
{
	protected $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function render()
	{
		return $this->app['clockwork.web']->render();
	}

	public function renderAsset($path)
	{
		return $this->app['clockwork.web']->renderAsset($path);
	}
}