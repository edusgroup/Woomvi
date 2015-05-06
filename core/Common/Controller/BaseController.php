<?php

namespace Site\Common\Controller;

class BaseController extends \Flame\Abstracts\BaseController
{
	use \Flame\Traits\User;
    use \Flame\Traits\Twig;

    public function preCallAction($methodName)
    {

    }

	public function preInitCommon($methodName, $matches)
	{
		$this->initMenuCommon($methodName, $matches);
	}
	
	public function preRenderCommon(\Twig_Environment $twig, &$tplName, &$varible)
    {
		$this->extendsTwig($twig);
	}
	
	protected function initMenuCommon()
	{
	
	}
}
