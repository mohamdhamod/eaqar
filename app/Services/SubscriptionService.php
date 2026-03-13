<?php

namespace App\Services;

use App\DTO\SubscriptionDTO;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function __construct(
        protected SubscriptionRepository $repository
    ) {}

    /**
     * Get all subscriptions
     */
    public function getAll()
    {
        return $this->repository->all();
    }

    /**
     * Get subscription by ID
     */
    public function getById(int $id): ?Subscription
    {
        return $this->repository->getById($id);
    }

    /**
     * Get subscription by key
     */
    public function getByKey(string $key): ?Subscription
    {
        return $this->repository->getByKey($key);
    }

    /**
     * Get active subscriptions
     */
    public function getActive()
    {
        return $this->repository->getActive();
    }

    /**
     * Get paginated subscriptions for dashboard
     */
    public function getPaginated(int $perPage = 12)
    {
        return $this->repository->getPaginated($perPage);
    }

    /**
     * Create new subscription
     */
    public function create(SubscriptionDTO $dto): Subscription
    {
        try {
            DB::beginTransaction();

            // Create subscription
            $subscription = $this->repository->create($dto->toCreateArray());

            // Save translations
            $subscription->translateOrNew('en')->fill([
                'name' => $dto->name,
                'description' => $dto->description,
            ])->save();

            $subscription->translateOrNew('ar')->fill([
                'name' => $dto->name,
                'description' => $dto->description,
            ])->save();

            DB::commit();

            return $subscription;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update subscription
     */
    public function update(int $id, SubscriptionDTO $dto): Subscription
    {
        try {
            DB::beginTransaction();

            $subscription = $this->repository->update($id, $dto->toUpdateArray());

            // Update translation
            $subscription->translateOrNew('en')->fill([
                'name' => $dto->name,
                'description' => $dto->description,
            ])->save();

            $subscription->translateOrNew('ar')->fill([
                'name' => $dto->name,
                'description' => $dto->description,
            ])->save();

            DB::commit();

            return $subscription;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete subscription
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): Subscription
    {
        return $this->repository->toggleActive($id);
    }

    /**
     * Get subscription with all relations
     */
    public function getWithRelations(int $id): Subscription
    {
        return $this->repository->getById($id);
    }
}
