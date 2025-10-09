<?php

namespace RbacAuth\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use RbacAuth\Services\CrudService;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\Access\AuthorizationException;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected ?CrudService $service = null;
    protected array $with = [];
    protected ?string $orderBy = null;
    protected ?string $resourceName = null;
    protected ?string $resourceClass = null;

    public function __construct()
    {
        $controller = class_basename(static::class);
        $modelName = Str::replaceLast('Controller', '', $controller);

        $modelClass = "RbacAuth\\Models\\{$modelName}";
        if (!class_exists($modelClass)) {
            if ($this->shouldInitializeCrud()) {
                throw new \Exception("Model {$modelClass} not found for {$controller}.");
            }
            return;
        }

        $possibleResource = "RbacAuth\\Http\\Resources\\{$modelName}Resource";
        $this->resourceClass = class_exists($possibleResource) ? $possibleResource : null;

        $model = new $modelClass;
        $rules = property_exists($modelClass, 'rules') ? $modelClass::$rules : [];
        $this->service = new CrudService($model, $rules);
        $this->resourceName = $this->resourceName ?? Str::camel($modelName);
    }

    protected function shouldInitializeCrud(): bool
    {
        return !in_array(class_basename(static::class), ['Controller', 'AuthController']);
    }

    protected function transform($data)
    {
        if ($this->resourceClass) {
            $resource = $this->resourceClass;
            return $data instanceof \Illuminate\Support\Collection
                ? $resource::collection($data)
                : new $resource($data);
        }
        return $data;
    }

    public function index()
    {
        if (!$this->service) {
            return sendApiError('CRUD service not initialized', 500);
        }

        try {
            $items = $this->service->all($this->with, $this->orderBy);

            return sendApiResponse(
                [$this->resourceName => $this->transform($items)],
                ucfirst($this->resourceName) . ' fetched successfully.'
            );
        } catch (AuthorizationException $e) {
            return sendApiError($e->getMessage(), 403);
        }
    }

    public function show($id)
    {
        if (!$this->service) {
            return sendApiError('CRUD service not initialized', 500);
        }

        try {
            $item = $this->service->find($id, $this->with);
            if (!$item) {
                return sendApiError('Not found', 404);
            }

            return sendApiResponse(
                [$this->resourceName => $this->transform($item)],
                ucfirst($this->resourceName) . ' retrieved successfully.'
            );
        } catch (AuthorizationException $e) {
            return sendApiError($e->getMessage(), 403);
        }
    }

    public function store(Request $request)
    {
        if (!$this->service) {
            return sendApiError('CRUD service not initialized', 500);
        }

        try {
            $item = $this->service->create($request->all());

            return sendApiResponse(
                [$this->resourceName => $this->transform($item)],
                ucfirst($this->resourceName) . ' created successfully.',
                201
            );
        } catch (ValidationException $e) {
            return sendApiError('Validation failed', 422, $e->errors());
        } catch (QueryException $e) {
            return sendApiError('Failed to create record', 500, $e->getMessage());
        } catch (AuthorizationException $e) {
            return sendApiError($e->getMessage(), 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (!$this->service) {
            return sendApiError('CRUD service not initialized', 500);
        }

        try {
            $item = $this->service->update($id, $request->all());
            if (!$item) {
                return sendApiError('Not found', 404);
            }

            return sendApiResponse(
                [$this->resourceName => $this->transform($item)],
                ucfirst($this->resourceName) . ' updated successfully.'
            );
        } catch (ValidationException $e) {
            return sendApiError('Validation failed', 422, $e->errors());
        } catch (QueryException $e) {
            return sendApiError('Failed to update record', 500, $e->getMessage());
        } catch (AuthorizationException $e) {
            return sendApiError($e->getMessage(), 403);
        }
    }

    public function destroy($id)
    {
        if (!$this->service) {
            return sendApiError('CRUD service not initialized', 500);
        }

        try {
            $deleted = $this->service->delete($id);
            if (!$deleted) {
                return sendApiError('Not found', 404);
            }

            return sendApiResponse(null, ucfirst($this->resourceName) . ' deleted successfully.');
        } catch (QueryException $e) {
            return sendApiError('Failed to delete record', 500, $e->getMessage());
        } catch (AuthorizationException $e) {
            return sendApiError($e->getMessage(), 403);
        }
    }
}
