<?php

namespace Thopra\Styleguide\View;

class TwigPartial extends Partial {

    protected $fileExtension = 'html';

    public function render($render = true)
    {
        $view = $this->getView();

        $content = $view->render($this->vars);

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

    protected function getView()
    {
        $filename =  $this->source->getPath().DIRECTORY_SEPARATOR.$this->source->getPartialDir().DIRECTORY_SEPARATOR.$this->path.'.'.$this->fileExtension;

        if (!file_exists($filename)) {
            throw new \Exception("Partial not found: ".$this->path.'.'.$this->fileExtension);
        }

        $loader = new \Twig\Loader\FilesystemLoader($this->source->getPath().DIRECTORY_SEPARATOR.$this->source->getPartialDir());

        $twig = new \Twig\Environment($loader);

        $template = $twig->load($this->path.'.'.$this->fileExtension);

        return $template;
    }


}