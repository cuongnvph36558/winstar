<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositoties\Interfaces\UserRepositoryInterface as UserRepository;


@package

class UserService implements UserServiceInterface {

    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository
    }

    public function paginate(){
        $users = $this->userRepository->getAllPaginate();
        return $users;
    }
}