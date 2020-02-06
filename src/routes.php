<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Exception\ApiException;

/*
 * Routes available for:
 *
 * [GET] /  > for all endpoints
 *
 * [GET] /api/v1/todos
 * [POST] /api/v1/todos
 * [GET] /api/v1/todos/{task_id}
 * [PUT] /api/v1/todos/{task_id}
 * [DELETE] /api/v1/todos/{task_id}
 *
 * [GET] /api/v1/todos/{task_id}/subtasks
 * [POST] /api/v1/todos/{task_id}/subtasks
 * [GET] /api/v1/todos/{task_id}/subtasks/{subtask_id}
 * [PUT] /api/v1/todos/{task_id}/subtasks/{subtask_id}
 * [DELETE] /api/v1/todos/{task_id}/subtasks/{subtask_id}
 *
*/

$app->get('/', function (Request $request, Response $response, array $args) {
    $endpoints =  [
        'all tasks' => $this->api['api_url'] . '/todos',
        'single task' => $this->api['api_url'] . '/todos/{task_id}',
        'subtasks by task' => $this->api['api_url'] . '/todos/{task_id}/subtasks',
        'single subtask' => $this->api['api_url'] . '/todos/{task_id}/subtasks/{subtask_id}',
        'help' => $this->api['base_url']. '/',
    ];
    $result = [
        'endpoints' => $endpoints,
        'version' => $this->api['version'],
        'timestamp' => time(),
    ];
    return $response->withJson($result,200,JSON_PRETTY_PRINT);
});

$app->group('/api/v1/todos',function() use($app) {
    $app->get('', function (Request $request, Response $response, array $args) {
        $result = $this->task->orderBy('id','asc')->get();
        if(empty($result)) {
            throw new ApiException(ApiException::TASK_NOT_FOUND,404);
        }
        return $response->withJson($result,200,JSON_PRETTY_PRINT);
    });
    $app->get('/{task_id}', function (Request $request, Response $response, array $args) {
        if(empty($args['task_id'])) {
            throw new ApiException(ApiException::TASK_INFO_REQUIRED);
        }
        $result = $this->task->find($args['task_id']);
        if(empty($result)) {
            throw new ApiException(ApiException::TASK_NOT_FOUND,404);
        }
        return $response->withJson($result,200,JSON_PRETTY_PRINT);
    });
    $app->post('', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if(empty($data['task']) || !isset($data['status'])) {
            throw new ApiException(ApiException::TASK_INFO_REQUIRED);
        }
        $result = $this->task->create($data);
        if(empty($result)) {
            throw new ApiException(ApiException::TASK_CREATION_FAILED);
        }
        return $response->withJson($result,201,JSON_PRETTY_PRINT);
    });
    $app->put('/{task_id}', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        if(empty($args['task_id']) || empty($data['task']) || !isset($data['status'])) {
            throw new ApiException(ApiException::TASK_INFO_REQUIRED);
        }
        $result_find = $this->task->find($args['task_id']);
        if(empty($result_find)) {
            throw new ApiException(ApiException::TASK_NOT_FOUND,404);
        }
        $row_count_update = $result_find->update($data);
        if($row_count_update < 1) {
            throw new ApiException(ApiException::TASK_UPDATE_FAILED);
        }
        $result = $this->task->find($args['task_id']);
        return $response->withJson($result,201,JSON_PRETTY_PRINT);
    });
    $app->delete('/{task_id}', function (Request $request, Response $response, array $args) {
        if(empty($args['task_id'])) {
            throw new ApiException(ApiException::TASK_INFO_REQUIRED);
        }
        $result_find = $this->task->find($args['task_id']);
        if(empty($result_find)) {
            throw new ApiException(ApiException::TASK_NOT_FOUND,404);
        }
        $row_count_delete = $result_find->delete();
        if($row_count_delete < 1) {
            throw new ApiException(ApiException::TASK_DELETE_FAILED);
        }
        $result = ["message" => "The task & its subtasks were deleted"];
        return $response->withJson($result,200,JSON_PRETTY_PRINT);
    });
    $app->group('/{task_id}/subtasks', function() use ($app) {
        $app->get('', function (Request $request, Response $response, array $args) {
            $task = $this->task->find($args['task_id']);
            if(empty($task)) {
                throw new ApiException(ApiException::TASK_NOT_FOUND,404);
            }
            $result = $this->task->find($args['task_id'])->subtasks()->get();
            if(count($result) == 0) {
                throw new ApiException(ApiException::SUBTASK_NOT_FOUND,404);
            }
            return $response->withJson($result,200,JSON_PRETTY_PRINT);
        });
        $app->get('/{subtask_id}', function (Request $request, Response $response, array $args) {
            if(empty($args['subtask_id'])) {
                throw new ApiException(ApiException::SUBTASK_INFO_REQUIRED);
            }
            $task = $this->task->find($args['task_id']);
            if(empty($task)) {
                throw new ApiException(ApiException::TASK_NOT_FOUND,404);
            }
            $result = $this->task->find($args['task_id'])->subtasks()->find($args['subtask_id']);
            if(count($result) == 0) {
                throw new ApiException(ApiException::SUBTASK_NOT_FOUND,404);
            }
            return $response->withJson($result,200,JSON_PRETTY_PRINT);
        });
        $app->post('', function (Request $request, Response $response, array $args) {
            $task = $this->task->find($args['task_id']);
            if(empty($task)) {
                throw new ApiException(ApiException::TASK_NOT_FOUND,404);
            }
            $data = $request->getParsedBody();
            if(empty($data['name']) || !isset($data['status'])) {
                throw new ApiException(ApiException::SUBTASK_INFO_REQUIRED);
            }
            $result = $this->task->find($args['task_id'])->subtasks()->create($data);
            if(empty($result)) {
                throw new ApiException(ApiException::SUBTASK_CREATION_FAILED);
            }
            return $response->withJson($result,201,JSON_PRETTY_PRINT);

        });
        $app->put('/{subtask_id}', function (Request $request, Response $response, array $args) {
            $task = $this->task->find($args['task_id']);
            if(empty($task)) {
                throw new ApiException(ApiException::TASK_NOT_FOUND,404);
            }
            $result_find = $this->task->find($args['task_id'])->subtasks()->find($args['subtask_id']);
            if(empty($result_find)) {
                throw new ApiException(ApiException::SUBTASK_NOT_FOUND,404);
            }
            $data = $request->getParsedBody();
            if(empty($args['subtask_id']) || empty($data['name']) || !isset($data['status'])) {
                throw new ApiException(ApiException::SUBTASK_INFO_REQUIRED);
            }
            $row_count_update = $result_find->update($data);
            if($row_count_update < 1) {
                throw new ApiException(ApiException::SUBTASK_UPDATE_FAILED);
            }
            $result = $this->task->find($args['task_id'])->subtasks()->find($args['subtask_id']);
            return $response->withJson($result,201,JSON_PRETTY_PRINT);

        });
        $app->delete('/{subtask_id}', function (Request $request, Response $response, array $args) {
            $task = $this->task->find($args['task_id']);
            if(empty($task)) {
                throw new ApiException(ApiException::TASK_NOT_FOUND,404);
            }
            if(empty($args['subtask_id'])) {
                throw new ApiException(ApiException::SUBTASK_INFO_REQUIRED);
            }
            $result_find = $this->task->find($args['task_id'])->subtasks()->find($args['subtask_id']);
            if(empty($result_find)) {
                throw new ApiException(ApiException::SUBTASK_NOT_FOUND,404);
            }
            $row_count_delete = $result_find->delete();
            if($row_count_delete < 1) {
                throw new ApiException(ApiException::SUBTASK_DELETE_FAILED);
            }
            $result = ["message" => "The subtask is deleted"];
            return $response->withJson($result,200,JSON_PRETTY_PRINT);

        });
    });
});
