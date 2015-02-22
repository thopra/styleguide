<?php

namespace Thopra\Styleguide\Source;

interface SourceInterface {

	public function getKey();
	public function getName();
	public function getPath();
	public function getParser();

}