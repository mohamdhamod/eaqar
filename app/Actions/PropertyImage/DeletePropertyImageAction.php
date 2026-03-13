<?php

namespace App\Actions\PropertyImage;

use App\Models\PropertyImage;
use App\Repositories\PropertyImageRepository;
use App\Traits\FileHandler;

class DeletePropertyImageAction
{
    use FileHandler;

    public function __construct(
        private readonly PropertyImageRepository $repository,
    ) {}

    public function execute(PropertyImage $image): bool
    {
        $this->deleteFile($image->image);

        return $this->repository->delete($image);
    }
}
