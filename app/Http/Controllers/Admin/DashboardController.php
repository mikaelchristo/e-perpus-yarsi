<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\EbookRead;
use App\Services\LoanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(LoanService $loanService)
    {
        // Auto-update overdue loans
        $loanService->updateOverdueLoans();

        // ── Summary Stats ──
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $adminUsers = User::where('role', 'admin')->count();
        $totalBooks = Book::count();
        $totalPhysical = Book::physicals()->count();
        $totalEbooks = Book::ebooks()->count();
        $booksBorrowed = Loan::where('status', 'borrowed')->count();
        $lateReturns = Loan::where('status', 'late')->count();
        $totalLoans = Loan::count();
        $totalReturned = Loan::where('status', 'returned')->count();
        $totalEbookReads = EbookRead::count();

        // ── Loan Trend (last 12 months) ──
        $loanTrend = Loan::select(
                DB::raw("DATE_FORMAT(loan_date, '%Y-%m') as month"),
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned"),
                DB::raw("SUM(CASE WHEN status = 'borrowed' THEN 1 ELSE 0 END) as borrowed"),
                DB::raw("SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late")
            )
            ->where('loan_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fill missing months
        $loanTrendData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $monthKey = Carbon::now()->subMonths($i)->format('Y-m');
            $monthLabel = Carbon::now()->subMonths($i)->translatedFormat('M Y');
            $found = $loanTrend->firstWhere('month', $monthKey);
            $loanTrendData->push([
                'month' => $monthLabel,
                'total' => $found ? $found->total : 0,
                'returned' => $found ? $found->returned : 0,
                'borrowed' => $found ? $found->borrowed : 0,
                'late' => $found ? $found->late : 0,
            ]);
        }

        // ── Ebook Read Trend (last 30 days) ──
        $ebookReadTrend = EbookRead::select(
                DB::raw("DATE(created_at) as read_date"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
            ->groupBy('read_date')
            ->orderBy('read_date')
            ->get();

        $ebookReadData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dateKey = Carbon::now()->subDays($i)->format('Y-m-d');
            $dateLabel = Carbon::now()->subDays($i)->format('d M');
            $found = $ebookReadTrend->firstWhere('read_date', $dateKey);
            $ebookReadData->push([
                'date' => $dateLabel,
                'total' => $found ? $found->total : 0,
            ]);
        }

        // ── Category Distribution (pie chart) ──
        $categoryDistribution = Book::select('category_id', DB::raw('COUNT(*) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get()
            ->map(fn($item) => [
                'name' => $item->category->name ?? 'Tanpa Kategori',
                'total' => $item->total,
            ]);

        // ── Top Borrowed Books ──
        $topBorrowedBooks = Book::withCount('loans')
            ->having('loans_count', '>', 0)
            ->orderByDesc('loans_count')
            ->take(5)
            ->get();

        // ── Top Read Ebooks ──
        $topReadEbooks = Book::ebooks()
            ->withCount('ebookReads')
            ->having('ebook_reads_count', '>', 0)
            ->orderByDesc('ebook_reads_count')
            ->take(5)
            ->get();

        // ── Weekly Activity (last 7 days) ──
        $weeklyActivity = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $loans = Loan::whereDate('created_at', $dateKey)->count();
            $reads = EbookRead::whereDate('created_at', $dateKey)->count();
            $weeklyActivity->push([
                'day' => $date->translatedFormat('D'),
                'loans' => $loans,
                'reads' => $reads,
            ]);
        }

        // ── Recent Data ──
        $recentLoans = Loan::with('book')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'adminUsers',
            'totalBooks',
            'totalPhysical',
            'totalEbooks',
            'booksBorrowed',
            'lateReturns',
            'totalLoans',
            'totalReturned',
            'totalEbookReads',
            'loanTrendData',
            'ebookReadData',
            'categoryDistribution',
            'topBorrowedBooks',
            'topReadEbooks',
            'weeklyActivity',
            'recentLoans',
            'recentUsers'
        ));
    }
}
