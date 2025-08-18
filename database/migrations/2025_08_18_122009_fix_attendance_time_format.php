<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sửa format thời gian cho các bản ghi hiện có
        $attendanceRecords = DB::table('attendance')->get();
        
        foreach ($attendanceRecords as $record) {
            $updates = [];
            
            // Sửa check_in_time nếu cần
            if ($record->check_in_time) {
                $checkInTime = $this->formatTime($record->check_in_time);
                if ($checkInTime !== $record->check_in_time) {
                    $updates['check_in_time'] = $checkInTime;
                }
            }
            
            // Sửa check_out_time nếu cần
            if ($record->check_out_time) {
                $checkOutTime = $this->formatTime($record->check_out_time);
                if ($checkOutTime !== $record->check_out_time) {
                    $updates['check_out_time'] = $checkOutTime;
                }
            }
            
            // Cập nhật nếu có thay đổi
            if (!empty($updates)) {
                DB::table('attendance')
                    ->where('id', $record->id)
                    ->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì đây là sửa lỗi format
    }

    /**
     * Format thời gian về dạng H:i:s
     */
    private function formatTime($time): string
    {
        // Nếu đã là format H:i:s thì giữ nguyên
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            return $time;
        }
        
        // Nếu là format H:i thì thêm :00
        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time . ':00';
        }
        
        // Nếu là datetime, lấy phần time
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $time)) {
            return substr($time, 11);
        }
        
        // Trường hợp khác, trả về 00:00:00
        return '00:00:00';
    }
};
