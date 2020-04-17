<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // 关注的id不能是自己的id
    public function follow(User $currentUser, User $user)
    {
        return $currentUser->id !== $user->id;
    }

    // 验证登录用户id和传递id是否一致
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    // 验证用户为管理员并且不是本人
    public function destroy(User $currentUser, User $user)
    {
        return ($currentUser->id !== $user->id) && ($currentUser->is_admin);
    }

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
