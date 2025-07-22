@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Người dùng sử dụng mã giảm giá</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Email</th>
                                    <th>Mã coupon</th>
                                    <th>Giá trị giảm</th>
                                    <th>Ngày sử dụng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($couponUsers as $item)
                                    <tr>
                                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                                        <td>{{ $item->user->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="label label-info">{{ $item->coupon->code ?? 'Không có' }}</span>
                                        </td>
                                        <td>
                                            @if($item->coupon)
                                                {{ $item->coupon->discount_type === 'percentage' 
                                                    ? $item->coupon->discount_value . '%' 
                                                    : number_format($item->coupon->discount_value) . '₫' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-center">
                            {{ $couponUsers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
