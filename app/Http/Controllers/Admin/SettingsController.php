<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function view_of_settings() {
        $settings = Settings::where('setting_name', 'BlackLine')->get();
        return view('admin.layouts.settings', ['settings' => $settings]);
    }
    public function send_black_line_to_data_base(Request $request) {
        Settings::where('setting_name', 'BlackLine')->update(['setting_content' => $request->black_line]);
        $new_black_line = Settings::where('setting_name', 'BlackLine')->first();
        return $new_black_line;
    }
    public function view_of_edition_black_header() {
        $black_line = Settings::where('setting_name', 'BlackLine')->first();
        return view('admin.layouts.change_header_content', ['black_line' => $black_line['setting_content']]);
    }
}
