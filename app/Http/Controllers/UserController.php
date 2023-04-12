<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersReport;
use App\Models\Categories;

class UserController extends Controller
{
    public function usersList()
    {
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "User"], ['name' => "Users List"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-users-list', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }
    public function usersView()
    {
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "User"], ['name' => "Users View"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-users-view', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }
    public function usersEdit()
    {
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "User"], ['name' => "Users Edit"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        return view('pages.page-users-edit', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }

    public function index()
    {
        $user       = User::whereNotIn('user_type', ['admin'])->get();
        $categories = Categories::all();
        if($categories) {
            $categories = $categories->toArray();
            $categories = array_column($categories, 'category', 'id');
        }
        return view('users.index', ['users' => $user,'categories' => $categories]); 
    }


    public function create()
    {
        return view('users.create', []); 
    }

    public function store(Request $request)
    {
        $messages = array(
            'email.required'    => 'Email field is required.',
            'name.required'     => 'Name field is required.',
            'email.unique'      => 'Email is already registered',
            'phone.unique'      => 'Phone is already registered',
            'email.email'       => 'Please enter valid email.',
            'phone.required'    => 'Phone field is required.',
            'password.required' => 'password field is required.',
        );

        $request->validate([
            'email'           => 'required|email|unique:users,email',
            'phone'           => 'required|numeric|unique:users,phone',
            'name'            => 'required',
            'password'        => 'required',
        ],$messages);

        $params             = $request->all();
        $params['password'] = isset($params['password']) ? md5($params['password']) : '';
        $result              = User::addUpdateUser($params);

        if($result) {
            $result->email = $params['email'];
            $result->save();
        }

        if($result) {
            return redirect()->route('user.index')->withSuccess('User successfully Added.');
        }

        return redirect('user')->withErrors(__('Something went wrong!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {		//$user =	User::with('userQuestion','categories')->where('id', $id)->first();		//return $user;
       $user = User::find($id);				
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request,$id)
    {
        $messages = array(
            'email.required'    => 'Email field is required.',
            'first_name.required'     => 'First Name field is required.',
            'last_name.required'     => 'Last Name field is required.',
            'email.unique'      => 'Email is already registered',
            //'phone.unique'      => 'Phone is already registered',
            'email.email'       => 'Please enter valid email.',
            'phone.required'    => 'Phone field is required.',
        );

        $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|numeric|unique:users,phone,'.$id,
            'first_name'  => 'required',
            'last_name'  => 'required',
        ], $messages);

        $params       = $request->all();
        $params['id'] = $id;
        $result       = User::addUpdateUser($params);

        if($result) {
            return redirect()->route('user.index')->withSuccess('User successfully Updated.');
        }

        return redirect('user')->withErrors(__('Something went wrong!'));
    }

    public function userReports(Request $request)
    {
        $usersReport = UsersReport::all();
        foreach ($usersReport as $key => $value) {
            $userId       = User::find($value->user_id);
            $reportUserId = User::find($value->reporter_id);
            $value->username = isset($userId->first_name) ? $userId->first_name : '';
            $value->report_by = isset($reportUserId->first_name) ? $reportUserId->first_name : '';
            # code...
        }
        return view('report.index', ['report' => $usersReport]); 
        //echo "<pre>";print_r($usersReport);exit;
    }

    public function userView($id)
    {
        $user = User::find($id);
        return view('users.view', ['user' => $user]);
    }

}
