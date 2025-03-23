<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VisitorExport implements FromView
{
    protected $visitors;

    public function __construct($visitors)
    {
        $this->visitors = $visitors;
    }

    public function view(): View
    {
        return view('exports.visitor_export', [
            'visitors' => $this->visitors
        ]);
    }
}