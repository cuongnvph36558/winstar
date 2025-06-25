@extends('layouts.admin')

@section('title', 'Thống kê bình luận theo sản phẩm')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <h2>Bình luận cho sản phẩm: {{ $product->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Người bình luận</th>
                <th>Nội dung</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product->comments as $comment)
                <tr>
                    <td>{{ $comment->guest_name ?? 'User #' . $comment->user_id }}</td>
                    <td>{{ $comment->content }}</td>
                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.comment.toggle-status', $comment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            @if ($comment->status == 1)
                                <button class="btn btn-warning btn-sm"><i class="fa fa-eye-slash"></i> Ẩn</button>
                            @else
                                <button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> Hiện</button>
                            @endif
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
        </div>
    </div>
</div>
@endsection
