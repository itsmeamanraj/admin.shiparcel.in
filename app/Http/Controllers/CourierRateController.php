<?php

namespace App\Http\Controllers;

use App\Models\CourierCompany;
use App\Models\CourierWeightSlab;
use App\Models\UserAIRCourierRate;
use App\Models\UserCourierWeightSlab;
use App\Models\UserSurfaceCourierRate;
use Illuminate\Http\Request;

class CourierRateController extends Controller
{

    /**
     * Store User wise it's rate
     */
    public function storeAIRRates(Request $request)
    {
        $userId = $request->user_id;
        $companyId = $request->courier_company_id;
        $weightSlabs = $request->input('weight_slab', []);

        foreach ($request->input('rates', []) as $index => $rateSlab) {
            $weightSlabId = $weightSlabs[$index] ?? null;
            if (!$weightSlabId) continue;

            foreach (['Forward', 'RTO'] as $mode) {
                if (!isset($rateSlab[$mode])) continue;

                foreach ($rateSlab[$mode] as $zone => $rates) {
                    // Check if record exists

                    $existingRate = UserAIRCourierRate::where([
                        'user_id' => $userId,
                        'courier_company_id' => $companyId,
                        'courier_weight_slab_id' => $weightSlabId,
                        'zone' => $zone,
                        'mode' => $mode
                    ])->first();

                    // Prepare data
                    $forwardFwd = $rates['forward_fwd'] ?? null;
                    $additionalFwd = $rates['additional_fwd'] ?? null;

                    if ($existingRate) {
                        // Update existing record
                        $existingRate->update([
                            'forward_fwd' => $forwardFwd,
                            'additional_fwd' => $additionalFwd,
                        ]);
                    } else {
                        // Create only if values are not null
                        if (!is_null($forwardFwd) || !is_null($additionalFwd)) {
                            UserAIRCourierRate::create([
                                'user_id' => $userId,
                                'courier_company_id' => $companyId,
                                'courier_weight_slab_id' => $weightSlabId,
                                'zone' => $zone,
                                'mode' => $mode,
                                'forward_fwd' => $forwardFwd,
                                'additional_fwd' => $additionalFwd,
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Courier rates saved successfully!']);
    }

    /**save surface data */

    public function storeSurfaceRates(Request $request)
    {
        $userId = $request->user_id;
        $companyId = $request->courier_company_id;
        $weightSlabs = $request->input('weight_slab', []);

        foreach ($request->input('rates', []) as $index => $rateSlab) {
            $weightSlabId = $weightSlabs[$index] ?? null;
            if (!$weightSlabId) continue;

            foreach (['Forward', 'RTO'] as $mode) {
                if (!isset($rateSlab[$mode])) continue;

                foreach ($rateSlab[$mode] as $zone => $rates) {
                    // Check if record exists

                    $existingRate = UserSurfaceCourierRate::where([
                        'user_id' => $userId,
                        'courier_company_id' => $companyId,
                        'courier_weight_slab_id' => $weightSlabId,
                        'zone' => $zone,
                        'mode' => $mode
                    ])->first();

                    // Prepare data
                    $forwardFwd = $rates['forward_fwd'] ?? null;
                    $additionalFwd = $rates['additional_fwd'] ?? null;

                    if ($existingRate) {
                        // Update existing record
                        $existingRate->update([
                            'forward_fwd' => $forwardFwd,
                            'additional_fwd' => $additionalFwd,
                        ]);
                    } else {
                        // Create only if values are not null
                        if (!is_null($forwardFwd) || !is_null($additionalFwd)) {
                            UserSurfaceCourierRate::create([
                                'user_id' => $userId,
                                'courier_company_id' => $companyId,
                                'courier_weight_slab_id' => $weightSlabId,
                                'zone' => $zone,
                                'mode' => $mode,
                                'forward_fwd' => $forwardFwd,
                                'additional_fwd' => $additionalFwd,
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Courier rates saved successfully!']);
    }

    /**
     * User Weight Slab  
     * 
     */
    public function userWeightSlab(Request $request)
    {
        $userId = $request->user_id;

        $courierCompanies = CourierCompany::where('status', 1)
            ->with(['userSelections' => function ($query) use ($userId) {
                $query->where(['user_id' => $userId]);
            }])
            ->get();

        $weightSlabs = CourierWeightSlab::where('status', 1)->get();

        foreach ($courierCompanies as $company) {
            $userSelection = $company->userSelections->first();

            $company->isChecked = $userSelection ? (bool) $userSelection->courier_status : false;
            $company->expressTypeAir = $userSelection ? (bool) $userSelection->express_type_air : false;
            $company->expressTypeSurface = $userSelection ? (bool) $userSelection->express_type_surface : false;
            $company->selectedAirSlabs = $userSelection ? json_decode($userSelection->air_weight_slab_ids, true) : [];
            $company->selectedSurfaceSlabs = $userSelection ? json_decode($userSelection->surface_weight_slab_ids, true) : [];
        }

        return view('admin.users.courier_weight_slab', compact('courierCompanies', 'weightSlabs', 'userId'));
    }

    /**
     * Save Weight Slabs
     */
    public function saveUserWeightSlab(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:courier_companies,id',
            'courier_status' => 'required',
            'express_type_air' => 'nullable',
            'express_type_surface' => 'nullable',
            'air_weight_slab_ids' => 'nullable|array',
            'air_weight_slab_ids.*' => 'exists:courier_weight_slabs,id',
            'surface_weight_slab_ids' => 'nullable|array',
            'surface_weight_slab_ids.*' => 'exists:courier_weight_slabs,id'
        ]);

        $userId = $validated['user_id'];
        $companyId = $validated['company_id'];


        // Find existing record
        $existingRecord = UserCourierWeightSlab::where('user_id', $userId)
            ->where('courier_company_id', $companyId)
            ->first();

        // Prepare data for update or insert
        $data = [
            'user_id' => $userId,
            'courier_company_id' => $companyId,
            'courier_status' => $validated['courier_status'] ? true : false,
            'express_type_air' => $request->express_type_air ? true : false,
            'express_type_surface' => $request->express_type_surface ? true : false,
            'air_weight_slab_ids' => json_encode($validated['air_weight_slab_ids'] ?? []),
            'surface_weight_slab_ids' => json_encode($validated['surface_weight_slab_ids'] ?? []),
            'is_enabled' => 1,
        ];

        if ($existingRecord) {
            $existingRecord->update($data);
        } else {
            UserCourierWeightSlab::create($data);
        }

        return redirect()->back()->with('success', 'Weight slabs updated successfully.');
    }

    /*
     *   AIR Courier Rate Slab 
     */
    public function airCourierRateSlab($company_id, $user_id)
    {
        // Fetch weight slabs for the user and courier company
        $courierWeightSlabs = UserCourierWeightSlab::where('courier_company_id', $company_id)
            ->where('user_id', $user_id)
            ->with('company')
            ->first();

        // Fetch existing rates for the user and company
        $existingRates = UserAIRCourierRate::where('user_id', $user_id)
            ->where('courier_company_id', $company_id)
            ->get()
            ->keyBy(function ($rate) {
                return "{$rate->courier_weight_slab_id}_{$rate->mode}_{$rate->zone}";
            });

        $formattedSlabs = [];

        if ($courierWeightSlabs) {
            $airWeightSlabs = json_decode($courierWeightSlabs->air_weight_slab_ids, true) ?? [];
            if (!empty($airWeightSlabs)) {
                $weightSlabs = CourierWeightSlab::whereIn('id', $airWeightSlabs)->pluck('weight', 'id');

                foreach ($airWeightSlabs as $slabId) {
                    $formattedSlabs[] = [
                        'company_name' => $courierWeightSlabs->company->name ?? 'Unknown Company',
                        'weight_slab_id' => $slabId,
                        'weight_slab' => $weightSlabs[$slabId] ?? 'Unknown Weight',
                    ];
                }
            }
        }

        return view('admin.users.air_courier_rate_slab', compact('formattedSlabs', 'existingRates'));
    }


    /*
     *   Surface Courier Rate Slab 
     */
    public function surfaceCourierRateSlab($company_id, $user_id)
    {
        // Fetch weight slabs for the user and courier company
        $courierWeightSlabs = UserCourierWeightSlab::where('courier_company_id', $company_id)
            ->where('user_id', $user_id)
            ->with('company')
            ->first();

        // Fetch existing rates for the user and company
        // $existingRates = UserSurfaceCourierRate::where('user_id', $user_id)
        //     ->where('courier_company_id', $company_id)
        //     ->first(); // Assuming one record per user & company

        $existingRates = UserSurfaceCourierRate::where('user_id', $user_id)
            ->where('courier_company_id', $company_id)
            ->get()
            ->keyBy(function ($rate) {
                return "{$rate->courier_weight_slab_id}_{$rate->mode}_{$rate->zone}";
            });

        $formattedSlabs = [];

        if ($courierWeightSlabs) {
            $airWeightSlabs = json_decode($courierWeightSlabs->surface_weight_slab_ids, true) ?? [];
            if (!empty($airWeightSlabs)) {
                $weightSlabs = CourierWeightSlab::whereIn('id', $airWeightSlabs)->pluck('weight', 'id');

                foreach ($airWeightSlabs as $slabId) {
                    $formattedSlabs[] = [
                        'company_name' => $courierWeightSlabs->company->name ?? 'Unknown Company',
                        'weight_slab_id' => $slabId,
                        'weight_slab' => $weightSlabs[$slabId] ?? 'Unknown Weight',
                    ];
                }
            }
        }

        return view('admin.users.surface_courier_rate_slab', compact('formattedSlabs', 'existingRates'));
    }
}
