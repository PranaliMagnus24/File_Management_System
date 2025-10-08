<?php

namespace App\Http\Controllers\Backend\MasterSettings\GeneralSetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Http\RedirectResponse;

class GeneralSettingController extends Controller
{
    public function index(Request $request)
    {
        $data['getRecord'] = GeneralSetting::find(1);
        return view('admin.Settings.GeneralSetting.index', $data);
    }
    public function store(Request $request): RedirectResponse
    {
        $save = GeneralSetting::find(1);
        $save->website_name = $request->website_name;
         $save->website_url = $request->website_url;
        $save->email = $request->email;
        $save->phone = $request->phone;
        $save->address = $request->address;
        $save->description = $request->description;
        $save->location_url = $request->location_url;
        $save->gst_number = $request->gst_number;


        if(!empty($request->file('favicon_logo')))
        {
            if(!empty($save->favicon_logo) && file_exists('upload/general_setting/' .$save->favicon_logo))
            {
                unlink('upload/general_setting/' .$save->favicon_logo);
            }
            $file = $request->file('favicon_logo');
            $randomStr = Str::random(30);
            $filename = $randomStr . '.' .$file->getClientOriginalExtension();
            $file->move('upload/general_setting/',$filename);
            $save->favicon_logo = $filename;
        }

        if(!empty($request->file('header_logo')))
        {
            if(!empty($save->header_logo) && file_exists('upload/general_setting/' .$save->header_logo))
            {
                unlink('upload/general_setting/' .$save->header_logo);
            }
            $file = $request->file('header_logo');
            $randomStr = Str::random(30);
            $filename = $randomStr . '.' .$file->getClientOriginalExtension();
            $file->move('upload/general_setting/',$filename);
            $save->header_logo = $filename;
        }

        if(!empty($request->file('footer_logo')))
        {
            if(!empty($save->footer_logo) && file_exists('upload/general_setting/' .$save->footer_logo))
            {
                unlink('upload/general_setting/' .$save->footer_logo);
            }
            $file = $request->file('footer_logo');
            $randomStr = Str::random(30);
            $filename = $randomStr . '.' .$file->getClientOriginalExtension();
            $file->move('upload/general_setting/',$filename);
            $save->footer_logo = $filename;
        }

        $save->save();
        return redirect()->route('create.generalSetting')->with('success', 'General Settings created successfully!');
    }
}
