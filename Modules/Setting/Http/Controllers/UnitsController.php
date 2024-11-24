<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Unit;
use Illuminate\Support\Facades\Auth;
class   UnitsController extends Controller
{

    public function index() {
        $units = Unit::where('business_id', Auth::user()->business_id)->get();

        return view('setting::units.index', [
            'units' => $units
        ]);
    }

    public function create() {
        return view('setting::units.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'       => 'required|string|max:255',
            'short_name' => 'required|string|max:255'
        ]);

        Unit::create([
            'name'            => $request->name,
            'short_name'      => $request->short_name,
            'operator'        => $request->operator,
            'operation_value' => $request->operation_value,
            'business_id' => Auth::user()->business_id,
        ]);

        toast(__('controller.created'), 'success');

        return redirect()->route('units.index');
    }

    public function edit(Unit $unit) {
        return view('setting::units.edit', [
            'unit' => $unit
        ]);
    }

    public function update(Request $request, Unit $unit) {
        $request->validate([
            'name'       => 'required|string|max:255',
            'short_name' => 'required|string|max:255'
        ]);

        if ($unit->is_default = true){
            toast(__('controller.is_default_error'), 'info');
        }
        else{
            $unit->update([
                'name'            => $request->name,
                'short_name'      => $request->short_name,
                'operator'        => $request->operator,
                'operation_value' => $request->operation_value,
            ]);

            toast(__('controller.updated'), 'info');

            return redirect()->route('units.index');
        }
    }

    public function destroy(Unit $unit) {
        if ($unit->is_default){
            toast(__('controller.is_default_error'), 'info');
            return back();
        }
        else{

        $unit->delete();

        toast(__('controller.deleted'), 'warning');
        return redirect()->route('units.index');
        }
    }
}
