<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ProfileQuestion;
use App\Models\Categories;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class QuestionManamentController extends Controller
{
    public function index()
    {
			 
        $question   = ProfileQuestion::all();
        $categories = Categories::all();
        if(!empty($categories)) {
            $categories = $categories->toArray();
            $categories = array_column($categories, 'category', 'id');
        }
        return view('question.index', ['questions' => $question, 'categories' => $categories]); 
    }

    public function create()
    {
        $categories = Categories::all();
        return view('question.create', ['categories'  => $categories]);
    }

    public function store(Request $request)
    {
        $messages = array(
            'question.required'  => 'Question field is required.',
            'answer.required'    => 'Answer field is required.',
        );

        $request->validate([
            'question'=> 'required',
            'answer'  => 'required',
        ],$messages);

        $params = $request->all();
        $result = ProfileQuestion::addUpdateQuestion($params);

        $answer = [];
        if(isset($params['answer'])) {
            foreach ($params['answer'] as $key => $value) {
                $answer[] = [
                    'answer' => $value,
                    'question_id' => $result->id,
                ];
            }
        }

        if(!empty($answer)) {
            QuestionAnswer::insert($answer);
        }

        if($result) {
            return redirect()->route('question.index')->withSuccess('Question successfully Added.');
        }

        return redirect('question')->withErrors(__('Something went wrong!'));
    }

    public  function edit($id)
    {
         $question   = ProfileQuestion::with('questionAnswer')->where('id', $id)->first();
         $categories = Categories::all();
         return view('question.edit', ['question' => $question,'categories' => $categories]);
    }

    public function update($id, Request $request)
    {
        $messages = array(
            'question.required'  => 'Question field is required.',
        );

        $request->validate([
            'question'=> 'required',
        ],$messages);

        $params       = $request->all();
        $params['id'] = $id;
        $result       = ProfileQuestion::addUpdateQuestion($params);

        $deleteIds = [];
        if(isset($params['old_answer'])) {
            foreach ($params['old_answer'] as $key => $value) {
                $checkAnswer = QuestionAnswer::find($key);
                if($checkAnswer) {
                    $checkAnswer->answer = $value;
                    $checkAnswer->save();
                    $deleteIds[] = $key;
                }
            }
        }

        if(!empty($deleteIds)) {
            QuestionAnswer::where('question_id', $id)->whereNotIn('id', $deleteIds)->delete();
        }

        $answer = [];
        if(isset($params['answer'])) {
            foreach ($params['answer'] as $key => $value) {
                $answer[] = [
                    'answer' => $value,
                    'question_id' => $result->id,
                ];
            }
        }

        if(!empty($answer)) {
            QuestionAnswer::insert($answer);
        }

        if($result) {
            return redirect()->route('question.index')->withSuccess('Question successfully added.');
        }

        return redirect('question')->withErrors(__('Something went wrong!'));
    }

    public function deleteQuestion($id)
    {
        QuestionAnswer::where('question_id', $id)->delete();
        ProfileQuestion::where('id', $id)->delete();
        return redirect()->route('question.index')->withSuccess('Question successfully deleted.');
    }
	
	public function set_default(Request $request)
	{
		
		ProfileQuestion::where('cat_id', $request->cat_id)->update(array("is_default"=>"No"));
		
		ProfileQuestion::where('id', $request->id)->update(array("is_default"=>"Yes"));
		
		Session::flash('success','Question successfully updated.'); 
		return "success";
	}
	
}