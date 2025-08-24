@extends('layouts.admin')

@section('title', 'Quản lý điểm người dùng')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-users text-info"></i> Quản Lý Users Điểm</h5>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Level VIP</th>
                                        <th>Tổng điểm</th>
                                        <th>Điểm đã tích</th>
                                        <th>Điểm đã dùng</th>
                                        <th>Điểm hết hạn</th>
                                        <th>Số đơn hàng</th>
                                        <th>Ngày tham gia</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </td>
                                            <td>
                                                @if($user->point)
                                                    <span class="label label-{{
                                                        $user->point->vip_level === 'Bronze' ? 'default' :
                                                        ($user->point->vip_level === 'Silver' ? 'info' :
                                                        ($user->point->vip_level === 'Gold' ? 'warning' :
                                                        ($user->point->vip_level === 'Platinum' ? 'primary' : 'danger')))
                                                    }}">
                                                        {{ $user->point->vip_level }}
                                                    </span>
                                                @else
                                                    <span class="label label-default">Bronze</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->point)
                                                    <strong class="text-primary">{{ number_format($user->point->total_points) }}</strong>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->point)
                                                    <span class="text-success">{{ number_format($user->point->earned_points) }}</span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->point)
                                                    <span class="text-warning">{{ number_format($user->point->used_points) }}</span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->point)
                                                    <span class="text-danger">{{ number_format($user->point->expired_points) }}</span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge">{{ $user->orders_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.points.user-detail', $user) }}"
                                                       class="btn btn-xs btn-info" title="Chi tiết">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-xs btn-success"
                                                            onclick="addBonusPoints({{ $user->id }})" title="Thêm điểm">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fa fa-users fa-3x text-muted"></i>
                            <p class="text-muted">Chưa có user nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm điểm thưởng -->
<div class="modal fade" id="addBonusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Thêm Điểm Thưởng</h4>
            </div>
            <form action="{{ route('admin.points.add-bonus') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="bonus_user_id">

                    <div class="form-group">
                        <label for="bonus_points">Số điểm thưởng <span class="text-danger">*</span></label>
                        <input type="number" name="points" id="bonus_points" class="form-control"
                               min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="bonus_description">Lý do thưởng <span class="text-danger">*</span></label>
                        <textarea name="description" id="bonus_description" class="form-control"
                                  rows="3" required placeholder="Ví dụ: Thưởng cho khách hàng VIP, Thưởng sinh nhật..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-plus"></i> Thêm Điểm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addBonusPoints(userId) {
    document.getElementById('bonus_user_id').value = userId;
    $('#addBonusModal').modal('show');
}
</script>
@endsection
