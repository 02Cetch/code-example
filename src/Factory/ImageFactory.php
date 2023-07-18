<?php

namespace App\Factory;

use App\Entity\Image;

class ImageFactory
{
    public function create(string $path, string $host = null, bool $isDeleted = false): Image
    {
        $image = new Image();

        $image->setPath($path);
        $image->setHost($host);
        $image->setIsDeleted($isDeleted);

        return $image;
    }
}
