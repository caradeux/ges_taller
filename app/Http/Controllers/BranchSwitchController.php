<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BranchSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $branchId = $request->input('branch_id');
        session(['active_branch_id' => $branchId ?: null]);

        return back();
    }
}
