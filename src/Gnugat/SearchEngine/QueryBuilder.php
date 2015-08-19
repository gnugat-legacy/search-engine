<?php

namespace Gnugat\SearchEngine;

interface QueryBuilder
{

    public function from($argument1);

    public function select($argument1);

    public function execute();
}
