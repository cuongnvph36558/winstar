@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Dashboard</h2>
        <ol class="breadcrumb">
            <li class="active">
                <strong>Dashboard</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
            @csrf
            <button type="submit" class="btn btn-primary">Đăng xuất</button>
        </form>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="row">
        @if(session('success'))
            <div class="col-lg-12">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Hôm nay</span>
                    <h5>Người dùng</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ App\Models\User::count() }}</h1>
                    <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                    <small>Tổng số người dùng</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">Hàng tháng</span>
                    <h5>Danh mục</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ App\Models\Category::count() }}</h1>
                    <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                    <small>Tổng số danh mục</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">Hôm nay</span>
                    <h5>Lượt truy cập</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">2,346</h1>
                    <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div>
                    <small>Lượt truy cập mới</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">Thấp</span>
                    <h5>Doanh thu</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">9,356 VND</h1>
                    <div class="stat-percent font-bold text-danger">-13% <i class="fa fa-level-down"></i></div>
                    <small>Doanh thu tháng này</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chào mừng đến với Winstar Admin</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Xin chào, {{ Auth::user()->name }}!</h4>
                            <p>Bạn đã đăng nhập thành công vào hệ thống quản trị Winstar. Từ đây bạn có thể:</p>
                            <ul>
                                <li>Quản lý danh mục sản phẩm</li>
                                <li>Quản lý người dùng</li>
                                <li>Xem báo cáo thống kê</li>
                                <li>Cấu hình hệ thống</li>
                            </ul>
                            
                            <div class="m-t-lg">
                                <a href="" class="btn btn-primary">Quản lý danh mục</a>
                                <a href="#" class="btn btn-success">Quản lý sản phẩm</a>
    
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4>Thông tin hệ thống</h4>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Laravel Version:</strong> {{ app()->version() }}
                                </li>
                                <li class="list-group-item">
                                    <strong>PHP Version:</strong> {{ phpversion() }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Thời gian đăng nhập:</strong> {{ now()->format('d/m/Y H:i:s') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Email:</strong> {{ Auth::user()->email }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 