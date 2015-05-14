<?php

namespace Site\Common\Controller;

class BaseController extends \Flame\Abstracts\BaseController
{
	use \Site\Common\Traits\User;
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
        $varible['isAuth'] = $this->getUser($this->fabric('user.dao'))->isAuth() ? 'true' : 'false';
	}
	
	protected function initMenuCommon()
	{
	
	}
}
