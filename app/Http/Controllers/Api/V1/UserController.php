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
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($this->includeRelation('tickets')){
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            $this->isAble('update', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (ModelNotFoundException $e) {
            return $this->responseError('User cannot be found', 404);
        } catch (AuthorizationException $e) {
            return $this->responseError('You are not authorized to update that resource', 401);
        }
    }

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
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            return $this->responseOk('User deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->responseError('User not found', 404);
        }
    }
}
