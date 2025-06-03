<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(){

    }

    public function index()
    {
        $config = $this->config();
        $template = 'backend.dashborad.home.index'
        return view('backend.dashboard.layout', compact(
            'template',
            'config'
        ));
    }

    private function config(){
        return [
            'js' => [
                '{{ asset('js/plugins/flot/jquery.flot.js') }}',
                '{{ asset('js/plugins/flot/jquery.flot.tooltip.min.js') }}',
                '{{ asset('js/plugins/flot/jquery.flot.spline.js') }}',
                '{{ asset('js/plugins/flot/jquery.flot.resize.js') }}',
                '{{ asset('js/plugins/flot/jquery.flot.pie.js') }}',
                '{{ asset('js/plugins/flot/jquery.flot.symbol.js') }}',
                '{{ asset('js/plugins/flot/jquery.flot.time.js') }}',
                '{{ asset('js/plugins/peity/jquery.peity.min.js') }}',
                '{{ asset('js/demo/peity-demo.js') }}',
                '{{ asset('js/inspinia.js') }}',
                '{{ asset('js/plugins/pace/pace.min.js') }}',
                '{{ asset('js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}',
                '{{ asset('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}',
                '{{ asset('js/plugins/easypiechart/jquery.easypiechart.js') }}',
                '{{ asset('js/plugins/sparkline/jquery.sparkline.min.js') }}',
                '{{ asset('js/demo/sparkline-demo.js') }}'
            ]
            ];
    }

}
