<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{

    protected $policyClass = UserPolicy::class;

    /**
     * Get all users
     *
     * @group Managing Users
     *
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=name
     * @queryParam filter[name] Filter by status name. Wildcards are supported. No-example
     * @queryParam filter[email] Filter by email. Wildcards are supported. No-example
     *
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::filter($filters)->paginate());
    }
//
//    {
//        if($this->includeRelation('tickets')){
//            return UserResource::collection(User::with('tickets')->paginate());
//        }
//        return UserResource::collection(User::paginate());
//    }

    /**
     * Create a user
     *
     * @group Managing Users
     *
     * @response 200 {"data":{"type":"user","id":16,"attributes":{"name":"My User","email":"user@user.com","isManager":false},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/16"}}}
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->isAble('store', User::class);

            return new UserResource(User::create($request->mappedAttributes()));

        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to create that resource', 401);
        }
    }

    /**
     * Display a user
     *
     * @group Managing Users
     *
     *
     */
    public function show(User $user)
    {
        if ($this->includeRelation('tickets')){
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }


    /**
     * Update a user
     *
     * @group Managing Users
     *
     * @response 200 {"data":{"type":"user","id":16,"attributes":{"name":"My User","email":"user@user.com","isManager":false},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/16"}}}
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($this->isAble('update', $user)) {
            $user->update($request->mappedAttributes());

            return new UserResource($user);
        }
        return $this->responseError('You are not authorized to update that resource', 401);

    }

    /**
     * Replace a user
     *
     * @group Managing Users
     *
     * @response 200 {"data":{"type":"user","id":16,"attributes":{"name":"My User","email":"user@user.com","isManager":false},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/16"}}}
     */

    public function replace(UpdateUserRequest $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            $this->isAble('update', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('Ticket cannot be found', 404);
        }
    }

    /**
     * Delete a user
     *
     * @group Managing Users
     *
     * @response 200 {}
     */
    public function destroy(User $user)
    {
        if ($this->isAble('delete', $user)) {
            $user->delete();

            return $this->responseOk('User successfully deleted');
        }

        return $this->responseError('You are not authorized to delete that resource', 401);
    }
}
