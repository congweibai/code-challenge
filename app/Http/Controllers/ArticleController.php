<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get all articles, paginate function could be improved
        // $articles = Article::paginate(15);

        $articles = Article::get();
        //Return collection of articles as a resource
        return ArticleResource::collection($articles);
    }

    public function getAllTitles()
    {
        //Get articles
        $articles = Article::get('title')->where('title');

        //Return collection of articles as a resource
        return ArticleResource::collection($articles);
    }

    public function getByTitle(Request $request)
    {
        $titles = $request -> input('title');
        $articles = Article::get()->where('title',$titles);

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Get picture by picture_id
        $picture_id = $request -> input("picture_id");

        if($article = Article::where
        ("picture_id",$picture_id) -> first()){
            //to ensure there is unique picure_id in database(DB)
        }else{
            //otherwise create new DB id 
            $article = new Article;
            $article->id = $request -> input("article_id");
        }

        
        $article->title = $request -> input("title");
        $article->boby = $request -> input("boby");
        $article->picture_url = $request -> input("picture_url");
        $article->picture_id = $request -> input("picture_id");

        if($article -> save()){
            return new ArticleResource($article);
        }
    }

    public function updateDescription(Request $request) {
        $picture_id = $request -> input("picture_id");
        $article = Article::where
            ("picture_id",$picture_id) -> first();

        $article->title = $request -> input("title");
        $article->boby = $request -> input("boby");
        if($article -> save()){
            return new ArticleResource($article);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Get article
        $article = Article::findOrFail($id);

        //Return single article as a resource
        return new ArticleResource($article);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //Get picture by picture_id
        $picture_id = $request -> input("picture_id");
        $article = Article::where("picture_id",$picture_id);

        if($article -> delete()){
            return new ArticleResource($article);
        }
    }
}
