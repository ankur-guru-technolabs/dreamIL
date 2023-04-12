<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ArticalCategory;
use App\Models\Artical;
use App\Models\User;

class ArticalManagementController extends Controller
{
	public function index()
    {
        $artical  = Artical::with('articleCategory')->orderBy('id', 'DESC')->get();
        $articals = [];
        if($artical) {
            $articals = $artical->toArray();
        }
        return view('artical.index', ['articals' => $articals]); 
    }

    public function create()
    {
        $articalCategory = ArticalCategory::all();
        return view('artical.create', ['articalCategory' => $articalCategory]);
    }

    public function store(Request $request)
    { 
        $messages = array(
            'name.required'        => 'Name field is required.',
            'description.required' => 'Description field is required.',
            'image.required'       => 'Image field is required.',
            'author_name.required' => 'Author Name field is required.',
        );

        $request->validate([
            'name'            => 'required',
            'description'     => 'required',
            'image'           => 'required',
            'author_name'     => 'required',
        ],$messages);

        $params          = $request->all();
        $imageName       = time().'.'.$request->image->extension();
        $request->image->move(public_path('artical_images'), $imageName);
        $params['image'] = $imageName;
        $result          = Artical::addUpdateArtical($params);

        if($params['status'] == 'Active') {
            $users  = User::all();
            $pushData = [
                'custom' => ['article_id' => $result->id]
            ];

            $description = $params['description'];
            if( strlen( $description) > 50) {
                $description = explode( "\n", wordwrap( $description, 50));
                $description = $description[0] . '...';
            }

            $title = 'New Article Published '.$params['name'];
            if(!empty($users)) {
                foreach ($users as $key => $value) {
                    if(!empty($value->fcm_token)) {
                        $noticationStatus = $this->sendPushNotifcation($value->fcm_token,$title, $description, $value->id, 0, $pushData, 0, 'artical');
                    }
                }
            }
        }

        if($result) {
            return redirect()->route('artical.index')->withSuccess('Artical Successfully Added.');
        }

        return redirect('artical')->withErrors(__('Something went wrong!'));
    }

    public  function edit($id)
    {
    	$artical         = Artical::find($id);
        $articalCategory = ArticalCategory::all();
        return view('artical.edit', ['artical' => $artical,'articalCategory' => $articalCategory]);
    }

    public function update($id, Request $request)
    {
        $messages = array(
            'name.required'        => 'Name field is required.',
            'description.required' => 'Description field is required.',
            'author_name.required' => 'Author Name field is required.',
        );

        $request->validate([
            'name'            => 'required',
            'description'     => 'required',
            'author_name'     => 'required',
        ],$messages);

        $artical             = Artical::find($id);
        $params              = $request->all();
        if(isset($params['image']) && !empty($params['image'])) {
            unlink(public_path('artical_images')."/".$artical->image);
            $imageName       = time().'.'.$request->image->extension();
            $request->image->move(public_path('artical_images'), $imageName);
            $params['image'] = $imageName;
        }
        $params['id']   = $id;
        $result         = Artical::addUpdateArtical($params);
        //print_r($params);exit;
        if($params['status'] == 'Active') {
            $users  = User::all();
            $pushData = [
                'custom' => ['article_id' => $id]
            ];

            $description = $params['description'];
            if( strlen( $description) > 50) {
                $description = explode( "\n", wordwrap( $description, 50));
                $description = $description[0] . '...';
            }

            //$title = 'Hey there! We have published a new article on '.$params['name'];
            $title = 'New Article Published '.$params['name'];
            if(!empty($users)) {
                foreach ($users as $key => $value) {
                    if(!empty($value->fcm_token)) {
                        $noticationStatus     = $this->sendPushNotifcation($value->fcm_token,$title, $description, $value->id, 0, $pushData, 0, 'artical');
                    }
                }
            }
        }

        if($result) {
            return redirect()->route('artical.index')->withSuccess('Artical successfully updated.');
        }

        return redirect('artical')->withErrors(__('Something went wrong!'));
    }

    public function deleteArtical($id)
    {
        Artical::where('id', $id)->delete();
        return redirect()->route('artical.index')->withSuccess('Artical successfully deleted.');
    }
}