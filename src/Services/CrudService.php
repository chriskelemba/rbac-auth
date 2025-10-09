<?php

namespace RbacAuth\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class CrudService
{
    protected Model $model;
    protected array $rules;

    public function __construct(Model $model, array $rules = [])
    {
        $this->model = $model;
        $this->rules = $rules;
    }

    public function all(array $with = [], string $orderBy = null): Collection
    {
        $this->authorize('viewAny', $this->model, true);

        $query = $this->model->with($with);
        if ($orderBy) {
            $query->orderBy($orderBy, 'desc');
        }

        return $query->get();
    }

    public function find($id, array $with = []): ?Model
    {
        $record = $this->model->with($with)->find($id);

        if ($record) {
            $this->authorize('view', $record);
        }

        return $record;
    }

    protected function validate(array $data): array
    {
        if (empty($this->rules)) {
            return $data;
        }

        $validator = Validator::make($data, $this->rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    protected function authorize(string $action, $modelOrClass = null, bool $mapViewAny = false): void
    {
        $modelOrClass = $modelOrClass ?? $this->model;

        $user = Auth::user();
        if (!$user) {
            throw new AuthorizationException("Unauthorized.");
        }

        $modelName = $modelOrClass instanceof Model
            ? class_basename($modelOrClass)
            : class_basename($modelOrClass);

        $permissionName = strtolower($action) . '.' . strtolower($modelName);
        if ($mapViewAny && $action === 'viewAny') {
            $permissionName = 'view.' . strtolower($modelName);
        }

        if (!$user->hasPermission($permissionName)) {
            throw new AuthorizationException(
                "You do not have permission to {$action} {$modelName}."
            );
        }
    }

    public function create(array $data): Model
    {
        $this->authorize('create', $this->model);
        $validated = $this->validate($data);
        return $this->model->create($validated);
    }

    public function update($id, array $data): ?Model
    {
        $record = $this->model->find($id);
        if (!$record) {
            return null;
        }

        $this->authorize('update', $record);
        $validated = $this->validate($data);
        $record->update($validated);

        return $record;
    }

    public function delete($id): bool
    {
        $record = $this->model->find($id);
        if (!$record) {
            return false;
        }

        $this->authorize('delete', $record);
        return (bool) $record->delete();
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
