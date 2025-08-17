<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VnpayTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VnpayTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = VnpayTransaction::with(['order']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order code or transaction reference
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vnp_TxnRef', 'like', "%{$search}%")
                  ->orWhere('vnp_TransactionNo', 'like', "%{$search}%")
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('code_order', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => VnpayTransaction::count(),
            'success' => VnpayTransaction::where('status', 'success')->count(),
            'failed' => VnpayTransaction::where('status', 'failed')->count(),
            'pending' => VnpayTransaction::where('status', 'pending')->count(),
        ];

        return view('admin.vnpay-transactions.index', compact('transactions', 'stats'));
    }

    public function show(VnpayTransaction $transaction)
    {
        $transaction->load(['order']);
        return view('admin.vnpay-transactions.show', compact('transaction'));
    }

    public function destroy(VnpayTransaction $transaction)
    {
        try {
            $transaction->delete();
            return redirect()->route('admin.vnpay-transactions.index')
                ->with('success', 'Xóa giao dịch VNPay thành công!');
        } catch (\Exception $e) {
            Log::error('Failed to delete VNPay transaction: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Không thể xóa giao dịch VNPay: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = VnpayTransaction::with(['order']);

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $filename = 'vnpay_transactions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Mã đơn hàng',
                'Mã giao dịch VNPay',
                'Số tiền',
                'Mã phản hồi',
                'Mã giao dịch ngân hàng',
                'Ngày thanh toán',
                'Mã ngân hàng',
                'Loại thẻ',
                'Trạng thái',
                'Thông báo',
                'Ngày tạo'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->order ? $transaction->order->code_order : 'N/A',
                    $transaction->vnp_TxnRef,
                    number_format($transaction->vnp_Amount / 100, 0, ',', '.') . ' VNĐ',
                    $transaction->vnp_ResponseCode,
                    $transaction->vnp_TransactionNo,
                    $transaction->vnp_PayDate,
                    $transaction->vnp_BankCode,
                    $transaction->vnp_CardType,
                    $transaction->status,
                    $transaction->message,
                    $transaction->created_at->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

