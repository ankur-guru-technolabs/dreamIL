<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Questions;
use App\Models\Categories;
use App\Models\QuestionAnswer;
use App\Models\AdvertisementCategory;

class AdvertisementCategoryController extends Controller
{
	public function index()
    {
        $advertisementCategory   = AdvertisementCategory::all();
        return view('advertisement_category.index', ['advertisementCategory' => $advertisementCategory]); 
    }

    public function create()
    {
        $categories = Categories::all();
        return view('advertisement_category.create', ['categories'  => $categories]);
    }

    public function store(Request $request)
    {
        $messages = array(
            'category.required'  => 'Category field is required.',
        );

        $request->validate([
            'category'=> 'required',
        ],$messages);

        $params = $request->all();
        $result = AdvertisementCategory::addUpdateAdvertisementCategory($params);

        if($result) {
            return redirect()->route('advertise_category.index')->withSuccess('Advertisements category successfully added.');
        }

        return redirect('advertise_category')->withErrors(__('Something went wrong!'));
    }

    public  function edit($id)
    {
         $category   = AdvertisementCategory::where('id', $id)->first();
         return view('advertisement_category.edit', ['category' => $category]);
    }

    public function update($id, Request $request)
    {
        $messages = array(
            'category.required'  => 'Question field is required.',
        );

        $request->validate([
            'category'=> 'required',
        ],$messages);

        $params       = $request->all();
        $params['id'] = $id;
        $result       = AdvertisementCategory::addUpdateAdvertisementCategory($params);

        if($result) {
            return redirect()->route('advertise_category.index')->withSuccess('Advertisements category successfully updated.');
        }

        return redirect('advertise_category')->withErrors(__('Something went wrong!'));
    }

    public function deleteCategory($id)
    {
        AdvertisementCategory::where('id', $id)->delete();
        return redirect()->route('advertise_category.index')->withSuccess('Advertisements category successfully deleted');
    }
}