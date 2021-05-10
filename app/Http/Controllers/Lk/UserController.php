<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    public UserService $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * Get all users
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function index(UserRequest $request): JsonResponse
    {
        $data = $request->all();

        $limit = $data['limit'] ?? 10;

        $users = $this
            ->userService
            ->getUsers($limit);

        return response()->json([
            'data' => UserResource::collection($users),
            'count' => $users->total(),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Create new user
     * @param UserCreateRequest $request
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $data = $request->all();

        $user = $this
            ->userService
            ->createUser($data);

        return response()->json(new UserResource($user), JsonResponse::HTTP_CREATED);
    }

    /**
     * Get user by id
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this
            ->userService
            ->getOneUser($id);

        return response()->json(new UserResource($user), JsonResponse::HTTP_OK);
    }

    /**
     * Update current user by id
     * @param UserUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $data = $request->all();

        $user = $this
            ->userService
            ->updateUser($data, $id);

        return response()->json(new UserResource($user), JsonResponse::HTTP_OK);
    }

    /**
     * Delete user by id
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $model = $this
            ->userService
            ->deleteUser($id);

        return response()->json($model, JsonResponse::HTTP_OK);
    }

    public function users()
    {
        return view('lk.users');
    }
}
