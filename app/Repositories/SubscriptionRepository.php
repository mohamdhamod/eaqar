<?php

namespace App\Repositories;

use App\Models\Subscription;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionRepository
{
    protected $model = Subscription::class;

    /**
     * Get all subscriptions
     */
    public function all()
    {
        return $this->model::orderBy('sort_order')->get();
    }

    /**
     * Get subscription by ID
     */
    public function getById(int $id): ?Subscription
    {
        return $this->model::findOrFail($id);
    }

    /**
     * Get subscription by key
     */
    public function getByKey(string $key): ?Subscription
    {
        return $this->model::where('key', $key)->first();
    }

    /**
     * Get active subscriptions
     */
    public function getActive()
    {
        return $this->model::where('active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get paginated subscriptions
     */
    public function getPaginated(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model::with(['translations', 'currency'])
            ->orderBy('sort_order')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create subscription
     */
    public function create(array $data): Subscription
    {
        return $this->model::create($data);
    }

    /**
     * Update subscription
     */
    public function update(int $id, array $data): Subscription
    {
        $subscription = $this->getById($id);
        $subscription->update($data);
        return $subscription;
    }

    /**
     * Delete subscription
     */
    public function delete(int $id): bool
    {
        $subscription = $this->getById($id);
        $subscription->deleteTranslations();
        return $subscription->delete();
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): Subscription
    {
        $subscription = $this->getById($id);
        $subscription->update(['active' => !$subscription->active]);
        return $subscription;
    }

    /**
     * Get subscriptions with translations
     */
    public function withTranslations()
    {
        return $this->model::with('translations')->get();
    }
}
