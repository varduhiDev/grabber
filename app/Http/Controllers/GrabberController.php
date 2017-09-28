<?php

namespace App\Http\Controllers;

use App\Jobs\StoreArticle;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GrabberController extends Controller
{
//  homepage

    public function index()
    {
        $articles = Article::all();
        return view('grabber')->with(['articles' => $articles]);
    }

//    Get Articles

    public function store()
    {
        $this->dispatch(new StoreArticle());
    }

//  Article edit blade

    public function edit($id)
    {
        $article = Article::find($id);
        return view('edit')->with(['article' => $article]);
    }

//    Update Article

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        } else {
            $article = Article::find($id);
            if($request->hasFile('image')){
                $imageName = time().'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('images/'), $imageName);
                File::delete($article->image);
                $article->image = asset('images/'.$imageName);
            }

            $article->title = $request->title;
            $article->description = $request->description;
            $article->save();
            return redirect('/');
        }
    }

    //Delete Article

    public function destroy(Request $request)
    {
        $article = Article::find($request->id);
        if(!is_null($article)){
            if(file_exists(public_path('images/'.basename($article->image)))){
                unlink(public_path('images/'.basename($article->image)));
            }
            $article->delete();
            Session::flash('message', 'Deleted Successfully!');
            Session::flash('message-type', 'success');
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('message-type', 'danger');
        }
    }
}
