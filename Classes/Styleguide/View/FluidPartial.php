<?php

namespace Thopra\Styleguide\View;

class FluidPartial extends Partial {

	protected $fileExtension = 'html';

	public function render($render = true)
	{
		$view = $this->getStandaloneView();
		
		$view->assignMultiple($this->vars);
		$content = $view->render();

		if ($render) {
			echo $content;
		}
		
		return $content;
	}

	public function setVars($data)
	{
		$this->vars = $data;
	}

	public function getVars()
	{
		return $this->vars;
	}

	protected function getStandaloneView()
	{
		$view = new \TYPO3Fluid\Fluid\View\TemplateView();
		$paths = $view->getTemplatePaths();

		$paths->setTemplateRootPaths(array(
			$this->source->getPartialDir().'/'
		));
		$paths->setPartialRootPaths(array(
			$this->source->getPartialDir().'/'
		));
		$paths->setLayoutRootPaths(array(
			$this->source->getPartialDir().'/'
		));

		$view->getTemplatePaths()->setTemplatePathAndFilename($this->file);

		return $view;

	}


}