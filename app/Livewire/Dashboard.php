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
        
        $totalTarget = Receipt::whereDate('created_at', $today)->count();
        $totalScanned = Receipt::whereDate('created_at', $today)
                              ->where('status', 'scanned')
                              ->count();
        $totalUnscanned = $totalTarget - $totalScanned;
        
        $totalDuplicates = ScanLog::whereDate('created_at', $today)
                                     ->where('status', 'duplicate')
                                     ->count();
                                     
        $totalUnknowns = ScanLog::whereDate('created_at', $today)
                                     ->where('status', 'unknown')
                                     ->count();

        // Metrics by Expedition
        $expeditionsData = \App\Models\Expedition::withCount([
            'receipts as total_target' => function($q) use ($today) {
                $q->whereDate('created_at', $today);
            },
            'receipts as total_scanned' => function($q) use ($today) {
                $q->whereDate('created_at', $today)->where('status', 'scanned');
            }
        ])->having('total_target', '>', 0)->get();

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
