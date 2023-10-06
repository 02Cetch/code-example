<?php

namespace App\Tests\Mock;

use Doctrine\ORM\AbstractQuery;

class MockableQuery extends AbstractQuery
{
    public function __construct()
    {
    }

    public function getSQL()
    {
        // TODO: Implement getSQL() method.
    }

    protected function _doExecute()
    {
        // TODO: Implement _doExecute() method.
    }
}
