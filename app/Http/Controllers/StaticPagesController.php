<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersLikes;
use App\Models\FreePlanSettings;
use App\Models\Plan;
use App\Models\Pages;
use App\Models\Passion;
use DB;

class StaticPagesController extends Controller
{

    public function getPrivacyPolicy()
    {
        $privacyPolicy = Pages::where('page_type','privacy_policy')->first();
        return view('static_pages.privacy_policy', ['privacyPolicy' => $privacyPolicy]); 
    }

    public function updatePrivacyPolicy(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
        ]);

        $params = $request->all();

        Pages::where('page_type','privacy_policy')->update([
            'title' => $params['title'],
            'description' => $params['description'],
        ]);

        return redirect('privacy_policy')->withSuccess('Privacy Policy successfully updated.');
    }

    public function getTermsAndConditions()
    {
        $termsAndConditions = Pages::where('page_type','terms_and_conditions')->first();
        return view('static_pages.terms_and_conditions', ['termsAndConditions' => $termsAndConditions]); 
    }

    public function updateTermsAndConditions(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
        ]);

        $params = $request->all();

        Pages::where('page_type','terms_and_conditions')->update([
            'title' => $params['title'],
            'description' => $params['description'],
        ]);

        return redirect('terms_and_conditions')->withSuccess('Terms And Conditions successfully updated.');
    }

    public function privacyPolicy()
    {
        $privacyPolicy = Pages::where('page_type','privacy_policy')->first();
        return view('privacy_policy', ['privacyPolicy' => $privacyPolicy]);
    }

    public function termAndCondition()
    {
        $terms_and_conditions = Pages::where('page_type','terms_and_conditions')->first();
        return view('terms_and_conditions', ['terms_and_conditions' => $terms_and_conditions]);
    }

    public function disclaimer()
    {
        $terms_and_conditions = Pages::where('page_type','disclaimer')->first();
        return view('disclaimer', ['disclaimer' => $terms_and_conditions]);
    }
    public function privacyAndCookiePolicy()
    {
        $terms_and_conditions = Pages::where('page_type','privacy_and_cookie_policy')->first();
        return view('privacy_and_cookie_policy', ['privacy_and_cookie_policy' => $terms_and_conditions]);
    }
    public function howToGethingdProccessesYourData()
    {
        $terms_and_conditions = Pages::where('page_type','how_to_gethingd_proccesses_your_data')->first();
        return view('how_to_gethingd_proccesses_your_data', ['how_to_gethingd_proccesses_your_data' => $terms_and_conditions]);
    }

    public function passion()
    {
        $passion = Passion::all();
        return view('passion.index', ['passions' => $passion]);
    }
}
