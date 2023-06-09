<?php

namespace Dainsys\Evaluate\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Gate::allows('view-dashboards')
            ? redirect()->route('evaluate.dashboard')
            : redirect()->route('evaluate.my_tickets');
    }
}
