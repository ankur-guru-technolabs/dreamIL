<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Categories;
use App\Models\Artical;
use App\Models\ArticalCategory;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Notifcation;


class ArticalController extends Controller
{
	public function getArticals(Request $request)
	{
	    $messages = array(
            'category_id.required'  => 'Category id field is required.',
        );

        $validator = Validator::make($request->all(),[
            'category_id'  => 'required',
        ],$messages);

        if ($validator->fails()) {
        	$error = $validator->messages()->toArray();

            foreach ($error as $er) {
                $error_msg[] = array($er);
            }

            return $this->errorResponse([], $error_msg['0']['0']['0']);
        }

        $params        = $request->all();
        $page          = isset($params['page']) ? $params['page'] : 1;
        $pageSize      = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $advertisement = Artical::with('articleCategory')->where('category_id', $params['category_id'])->where('status','Active')->orderBy('created_at', 'desc')->paginate($pageSize, ['*'], 'page', $page);
        return $this->successResponse($advertisement, 'Success');

	}

	public function getArticalCategories(Request $request)
	{
		$articalCategory = ArticalCategory::all();
		return $this->successResponse($articalCategory, '');
	}

    public function getArticleDetails($id, Request $request)
    {
        $artical = Artical::with('articleCategory')->where('id', $id)->first();
        
     	// Notifcation::where('receiver_id', request()->header('userId'))->where('type','artical')->where('article_id',$id)->update(['status' => 'read']);

        return $this->successResponse($artical, 'Success');
    }
}