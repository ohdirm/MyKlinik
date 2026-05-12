<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class WilayahController extends Controller
{
    public function provinces()
    {
        $provinces = Province::orderBy('name')
            ->get(['code as id', 'name']);

        return response()->json($provinces);
    }

    public function districts(Request $request)
    {
        $districts = City::where('province_code', $request->province_id)
            ->orderBy('name')
            ->get(['code as id', 'name']);

        return response()->json($districts);
    }

    public function subDistricts(Request $request)
    {
        $subDistricts = District::where('city_code', $request->district_id)
            ->orderBy('name')
            ->get(['code as id', 'name']);

        return response()->json($subDistricts);
    }

    public function villages(Request $request)
    {
        $villages = Village::where('district_code', $request->sub_district_id)
            ->orderBy('name')
            ->get(['code as id', 'name']);

        return response()->json($villages);
    }
}
