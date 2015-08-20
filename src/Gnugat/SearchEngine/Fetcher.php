<?php

namespace Gnugat\SearchEngine;

interface Fetcher
{

    public function fetchAll($argument1, $argument2);

    public function fetchFirst($argument1, $argument2);
}
