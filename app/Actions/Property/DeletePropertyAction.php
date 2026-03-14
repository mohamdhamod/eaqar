<?php

namespace App\Actions\Property;

use App\Models\Property;
use App\Models\User;
use App\Traits\FileHandler;

class DeletePropertyAction
{
    use FileHandler;

    public function execute(Property $property): bool
    {
        $userId = $property->user_id;

        // Delete all images and directory
        if ($property->images()->exists()) {
            foreach ($property->images as $image) {
                $this->deleteFile($image->image);
            }
            $this->deleteDirectory("properties/{$property->id}");
        }

        // Delete related data
        $property->favorites()->delete();
        $property->images()->delete();

        // Delete property
        $deleted = $property->delete();

        return $deleted;
    }
}
