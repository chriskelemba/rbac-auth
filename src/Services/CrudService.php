<?php

namespace RbacAuth\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

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
        $query = $this->model->with($with);
        if ($orderBy) {
            $query->orderBy($orderBy, 'desc');
        }
        return $query->get();
    }

    public function find($id, array $with = []): ?Model
    {
        return $this->model->with($with)->find($id);
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

    public function create(array $data): Model
    {
        $validated = $this->validate($data);
        return $this->model->create($validated);
    }

    public function update($id, array $data): ?Model
    {
        $record = $this->model->find($id);
        if (!$record) {
            return null;
        }

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
        return (bool) $record->delete();
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
