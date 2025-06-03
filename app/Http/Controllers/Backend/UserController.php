<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface as UserService;
use App\Repositories\Interfaces\ProvinceServiceInterface as ProvinceService;

class UserController extends Controller
{
    protected $userService;
    protected $provinceRepository;
    public function __construct(UserService $userService, ProvinceService $provinceRepository){
        $this->userService = $userService;
        $this->provinceRepository = $provinceRepository;
    }

    public function index(){

        $users = $this->userService->paginate();

        $config['seo'] = config('apps.user');


        $config = $this->config();
        $template = 'backend.user.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'users'
        ));
    }

    public function create(){
        $location=[
            'province' => $this->provinceRepository->all()
        ];
        $config['seo'] = config('apps.user');
        $template = 'backend.user.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'location'
        ));
    }

    public function config() {
        return [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
            ];
    }
}
