<?php

namespace Thopra\Styleguide\View;

class TwigPartial extends Partial {

    protected $fileExtension = 'twig';

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
        $dir = realpath($this->source->getPartialDir());
        if (!is_dir($dir)) {
            $dir = $this->source->getPath() . DIRECTORY_SEPARATOR . $this->source->getPartialDir();
        }
        $filename =  $dir.DIRECTORY_SEPARATOR.$this->path.'.'.$this->fileExtension;

        if (!is_dir($dir) || !file_exists($filename)) {
            throw new \Exception("Partial not found: ".$this->path.'.'.$this->fileExtension);
        }

        $loader = new \Twig\Loader\FilesystemLoader($dir);

        $twig = new \Twig\Environment($loader);

        $template = $twig->load($this->path.'.'.$this->fileExtension);

        return $template;
    }


}