<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Questions;
use App\Models\Categories;
use App\Models\QuestionAnswer;
use App\Models\GeneralQuestionAnswer;

class GeneralQuestionsController extends Controller
{
	public function index()
    {
        $question   = Questions::all();
        $categories = Categories::all();
        if(!empty($categories)) {
            $categories = $categories->toArray();
            $categories = array_column($categories, 'category', 'id');
        }
        return view('general_questions.index', ['questions' => $question, 'categories' => $categories]); 
    }

    public function create()
    {
        $categories = Categories::all();
        return view('general_questions.create', ['categories'  => $categories]);
    }

    public function store(Request $request)
    {
        $messages = array(
            'question.required'  => 'Question field is required.',
        );

        $request->validate([
            'question'=> 'required',
        ],$messages);

        $params = $request->all();
        $params['category_id'] = $params['cat_id'];
        $result = Questions::addUpdateQuestion($params);

        if($result) {
            return redirect()->route('general_question.index')->withSuccess('General question successfully Added.');
        }

        return redirect('general_question')->withErrors(__('Something went wrong!'));
    }

    public  function edit($id)
    {
         $question   = Questions::where('id', $id)->first();
         $categories = Categories::all();
         return view('general_questions.edit', ['question' => $question,'categories' => $categories]);
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
        $result       = Questions::addUpdateQuestion($params);

        if($result) {
            return redirect()->route('general_question.index')->withSuccess('General question successfully updated.');
        }

        return redirect('general_question')->withErrors(__('Something went wrong!'));
    }

    public function deleteQuestion($id)
    {
        GeneralQuestionAnswer::where('question_id', $id)->delete();
        Questions::where('id', $id)->delete();
        return redirect()->route('general_question.index')->withSuccess('General question successfully deleted.');
    }
}