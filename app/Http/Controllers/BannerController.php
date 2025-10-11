<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        $return = [];

        foreach($banners as $banner) {
            $return[] = [
                "uri" => asset('storage/'.$banner->uri),
                "link" => $banner->link
            ];
        }

        return response()->json([
            "error" => null,
            "banners" => $return
        ]);
    }
}
