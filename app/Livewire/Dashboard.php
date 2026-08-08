<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Receipt;
use App\Models\ScanLog;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $today = Carbon::today();
        
        $totalTarget = Receipt::where(function($q) use ($today) {
            $q->where('status', 'unscanned')
              ->orWhereDate('scanned_at', $today);
        })->count();

        $totalScanned = Receipt::whereDate('scanned_at', $today)->count();
        $totalUnscanned = Receipt::where('status', 'unscanned')->count();
        
        $totalDuplicates = ScanLog::whereDate('created_at', $today)
                                     ->where('status', 'duplicate')
                                     ->count();
                                     
        $totalUnknowns = ScanLog::whereDate('created_at', $today)
                                     ->where('status', 'unknown')
                                     ->count();

        // Metrics by Expedition
        $expeditionsData = \App\Models\Expedition::withCount([
            'receipts as total_target' => function($q) use ($today) {
                $q->where('status', 'unscanned')
                  ->orWhereDate('scanned_at', $today);
            },
            'receipts as total_scanned' => function($q) use ($today) {
                $q->whereDate('scanned_at', $today);
            }
        ])->whereHas('receipts', function($q) use ($today) {
            $q->where('status', 'unscanned')
              ->orWhereDate('scanned_at', $today);
        })->get();

        return view('livewire.dashboard', compact(
            'totalTarget', 
            'totalScanned', 
            'totalUnscanned', 
            'totalDuplicates',
            'totalUnknowns',
            'expeditionsData'
        ));
    }
}
