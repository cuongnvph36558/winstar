@extends('layouts.admin')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Danh sách liên hệ chưa phản hồi</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('contacts.replied') }}" class="btn btn-success btn-xs">
                            <i class="fa fa-check"></i> Đã phản hồi
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('contacts.index') }}" class="form-inline">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Tìm kiếm theo tiêu đề, nội dung hoặc người gửi..." 
                                           value="{{ request('search') }}">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="text-muted">Tổng: {{ $contacts->total() }} liên hệ</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th width="5%">STT</th>
                                    <th width="15%">Người gửi</th>
                                    <th width="25%">Chủ đề</th>
                                    <th width="15%">Trạng thái</th>
                                    <th width="15%">Ngày gửi</th>
                                    <th width="20%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $index => $contact)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="contacts[]" value="{{ $contact->id }}" class="contact-checkbox">
                                    </td>
                                    <td>{{ $index + 1 + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                                    <td>
                                        @if($contact->user)
                                            <strong>{{ $contact->user->name }}</strong>
                                            <br><small class="text-muted">{{ $contact->user->email }}</small>
                                        @else
                                            <span class="text-muted">Khách</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($contact->subject, 50) }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($contact->message, 80) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Chưa phản hồi</span>
                                    </td>
                                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('contacts.show', $contact->id) }}" 
                                           class="btn btn-info btn-xs" title="Xem chi tiết">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('contacts.edit', $contact->id) }}" 
                                           class="btn btn-success btn-xs" title="Phản hồi">
                                            <i class="fa fa-reply"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> Không có liên hệ nào chưa phản hồi.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <!-- Bulk Actions -->
                        @if($contacts->count() > 0)
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('contacts.bulk-action') }}" id="bulk-action-form">
                                    @csrf
                                    <input type="hidden" name="contacts" id="selected-contacts">
                                    <div class="btn-group">
                                        <button type="submit" name="action" value="mark_resolved" 
                                                class="btn btn-success btn-sm" id="bulk-resolve-btn" disabled>
                                            <i class="fa fa-check"></i> Đánh dấu đã phản hồi
                                        </button>
                                        <button type="submit" name="action" value="delete" 
                                                class="btn btn-danger btn-sm" id="bulk-delete-btn" disabled
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa các liên hệ đã chọn?')">
                                            <i class="fa fa-trash"></i> Xóa
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-right">
                                {{ $contacts->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#select-all').change(function() {
        $('.contact-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkButtons();
    });

    // Individual checkbox change
    $('.contact-checkbox').change(function() {
        updateBulkButtons();
        
        // Update select all checkbox
        var totalCheckboxes = $('.contact-checkbox').length;
        var checkedCheckboxes = $('.contact-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#select-all').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#select-all').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#select-all').prop('indeterminate', true);
        }
    });

    function updateBulkButtons() {
        var checkedCount = $('.contact-checkbox:checked').length;
        var selectedIds = [];
        
        $('.contact-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        $('#selected-contacts').val(JSON.stringify(selectedIds));
        
        if (checkedCount > 0) {
            $('#bulk-resolve-btn, #bulk-delete-btn').prop('disabled', false);
        } else {
            $('#bulk-resolve-btn, #bulk-delete-btn').prop('disabled', true);
        }
    }
});
</script>
@endpush
@endsection
