<?php

namespace App\Actions\PropertyImage;

use App\Models\PropertyImage;
use App\Repositories\PropertyImageRepository;

class SetMainImageAction
{
    public function __construct(
        private readonly PropertyImageRepository $repository,
    ) {}

    public function execute(PropertyImage $image): bool
    {
        return $this->repository->setMain($image);
    }
}
