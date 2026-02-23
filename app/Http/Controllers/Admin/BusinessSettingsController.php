<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\Page;
use App\Models\PageTranslation;
use Artisan;
use DB;
// use CoreComponentRepository;

class BusinessSettingsController extends Controller
{
    public function general_setting(Request $request)
    {
        // CoreComponentRepository::instantiateShopRepository();
        //CoreComponentRepository::initializeCache();
    	return view('backend.setup_configurations.general_settings');
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        Page::updateOrCreate(
            ['type' => 'home'],
            ['slug' => 'home', 'status' => 1, 'data' => json_encode($data)]
        );

        flash(trans("messages.settings").' '.trans("messages.updated_msg"))->success();
        return back();
    }

    

    public function shipping_configuration(Request $request){
        return view('backend.general_settings.settings');
    }

    public function shipping_configuration_update(Request $request){
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        $business_settings->value = $request[$request->type];
        $business_settings->save();

        Artisan::call('cache:clear');
        return back();
    }

    public function freeshipping_settings(Request $request)
    {
       
        BusinessSetting::updateOrCreate([
            'type' => 'free_shipping_status'
        ], [
            'value' => $request->free_shipping_status ? 1 : 0
        ]);

        BusinessSetting::updateOrCreate([
            'type' => 'free_shipping_min_amount'
        ], [
            'value' => $request->free_shipping_min_amount ?? 0
        ]);

        BusinessSetting::updateOrCreate([
            'type' => 'default_shipping_amount'
        ], [
            'value' => $request->default_shipping_amount ?? 0
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    public function return_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_return_time'
        ], [
            'value' => $request->default_return_time ?? 0
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    public function service_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_service_whatsapp'
        ], [
            'value' => $request->default_service_whatsapp ?? NULL
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }
}
