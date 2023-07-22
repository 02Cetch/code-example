<?php

namespace App\Job;

interface JobInterface
{
    public function run(array $data): void;
}
