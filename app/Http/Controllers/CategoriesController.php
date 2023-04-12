<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Categories;

class CategoriesController extends Controller
{
	public function index()
    {
        $categories = Categories::all();		//return $categories;
        return view('passion.index', ['categories' => $categories]); 
    }

    public function create()
    {
        return view('passion.create', []);
    }

    public function store(Request $request)
    {
        $messages = array(
            'category.required'        => 'Category field is required.',
            'is_hide_details.required' => 'is_hide_details field is required.',
        );

        $request->validate([
            'category'        => 'required',
            'is_hide_details' => 'required',
        ],$messages);

        $params = $request->all();
        $result = Categories::addUpdateCategory($params);

        if($result) {
            return redirect()->route('categories.index')->withSuccess('Category successfully Added.');
        }

        return redirect('user')->withErrors(__('Something went wrong!'));
    }

    public  function edit($id)
    {
    	$categories = Categories::find($id);
        return view('passion.edit', ['categories' => $categories]);
    }

    public function update($id, Request $request)
    {
    	$messages = array(
            'category.required'        => 'Category field is required.',
            'is_hide_details.required' => 'is_hide_details field is required.',
        );

        $request->validate([
            'category'        => 'required',
            'is_hide_details' => 'required',
        ],$messages);

        $params       = $request->all();
        $params['id'] = $id;
        $result       = Categories::addUpdateCategory($params);

        if($result) {
            return redirect()->route('categories.index')->withSuccess('Category successfully updated.');
        }

        return redirect('categories')->withErrors(__('Something went wrong!'));
    }

    public function deleteCategory($id)
    {
    	$Passion = Categories::where('id', $id)->delete();
        return redirect()->route('categories.index')->withSuccess('Category successfully deleted.');
    }
}