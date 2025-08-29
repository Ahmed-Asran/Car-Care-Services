<?php

namespace App\Policies;
Use app\Models\Setting;
use app\Models\user;

class SettingPolicy
{
    public function viewAny(User $user): bool{
            return true ;
    }
    public function view(User $user, Setting $setting): bool{
            return true ;
    }
    public function create(User $user): bool{
            return $user->role === 'admin' ;
    }
    public function update(User $user, Setting $setting): bool{
            return $user->role === 'admin' ;
    }
    public function delete(User $user, Setting $setting): bool{
            return $user->role === 'admin' ;
    }
}
