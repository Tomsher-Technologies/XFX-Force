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
            ['type' => $request->type],
            ['data' => json_encode($data)]
        );

        flash(trans("messages.settings").' '.trans("messages.updated_msg"))->success();
        return back();
    }

     public function updateSettings(Request $request)
    {
        $lang = $request->lang ?? 'en';
        if(!empty($request->types)){
            foreach ($request->types as $key => $type) {
                
                if(gettype($type) == 'array'){
                    $lang = array_key_first($type);
                    $type = $type[$lang];
                    $business_settings = BusinessSetting::where('type', $type)->where('lang',$lang)->first();
                }else{
                    $business_settings = BusinessSetting::where('type', $type)->first();
                }

                
                if($business_settings!=null){
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
                else{
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
            }
        }

        Artisan::call('cache:clear');

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

    public function delivery_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_delivery_days'
        ], [
            'value' => $request->default_delivery_days ?? 0
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    public function vat_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_vat'
        ], [
            'value' => $request->default_vat ?? 0
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    public function pickup_settings(Request $request)
    {
        BusinessSetting::updateOrCreate(
            ['type' => 'pickup_address'],
            ['value' => $request->pickup_address]
        );

        BusinessSetting::updateOrCreate(
            ['type' => 'pickup_latitude'],
            ['value' => $request->pickup_latitude]
        );

        BusinessSetting::updateOrCreate(
            ['type' => 'pickup_longitude'],
            ['value' => $request->pickup_longitude]
        );

        flash('Pickup location updated successfully')->success();

        Artisan::call('cache:clear');

        return back();
    }

    public function invoice_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_invoice_logo'
        ], [
            'value' => $request->default_invoice_logo ?? ''
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }

    public function seo_og_image_settings(Request $request)
    {
        BusinessSetting::updateOrCreate([
            'type' => 'default_seo_og_image'
        ], [
            'value' => $request->default_seo_og_image ?? ''
        ]);

        flash('Settings updated successfully')->success();

        Artisan::call('cache:clear');
        return back();
    }
}
