<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ArticalCategory;
use App\Models\Artical;

class ArticalCategoryController extends Controller
{
	public function index()
    {
        $articalCategory = ArticalCategory::get();
        $articalCategories = [];
        if($articalCategory) {
            $Categories = $articalCategory->toArray();
        }
        return view('artical_category.index', ['categories' => $Categories]); 
    }

    public function create()
    {
        $articalCategory = ArticalCategory::all();
        return view('artical_category.create', ['articalCategory' => $articalCategory]);
    }

    public function store(Request $request)
    {
        $messages = array(
            'category.required' => 'Name field is required.',
        );

        $request->validate([
            'category' => 'required',
        ],$messages);

        $params = $request->all();
        $result = ArticalCategory::addUpdateCategory($params);

        if($result) {
            return redirect()->route('artical_category.index')->withSuccess('Artical Category Successfully Added.');
        }

        return redirect('artical_category')->withErrors(__('Something went wrong!'));
    }

    public  function edit($id)
    {
    	$articalCategory = ArticalCategory::find($id);
        return view('artical_category.edit', ['articalCategory' => $articalCategory]);
    }

    public function update($id, Request $request)
    {
        $messages = array(
            'category.required' => 'Name field is required.',
        );

        $request->validate([
            'category' => 'required',
        ],$messages);

        $params       = $request->all();
        $params['id'] = $id;
        $result       = ArticalCategory::addUpdateCategory($params);

        if($result) {
            return redirect()->route('artical_category.index')->withSuccess('Artical Category Successfully Updated.');
        }

        return redirect('artical_category')->withErrors(__('Something went wrong!'));
    }

    public function deleteCategory($id)
    {
    	$Passion = Categories::where('id', $id)->delete();
        return redirect()->route('categories.index')->withSuccess('Category successfully deleted.');
    }
}