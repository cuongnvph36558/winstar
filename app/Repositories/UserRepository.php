<?php

namespace App\Repositories;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;

use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface{

    protected $model;

    public function __construct(
        User $model
    ){
        $this->model = $model;
    }

}

    
