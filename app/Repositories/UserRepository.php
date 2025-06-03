<?php

namespace App\Repositories;
use App\Repositories\Interfaces\UserRepositoryInterface;

use App\Models\User;

@package

class UserRepository implements UserRepositoryInterface{

    public function getAllPaginate(){
        return User::paginate(15);
    }

}

    
