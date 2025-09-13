<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index()
    {
        $data['page_title'] = 'Article';
        $role = Auth::guard('admin')->user()->getRoleNames()->first();
        if ($role != 'superadmin') {
            $data['article'] = Article::where('created_by', $this->user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $data['article'] = Article::orderBy('created_at', 'desc')->get();
        }
        return view('backend.pages.article.index', $data);
    }

    public function create()
    {
        $data['page_title'] = 'Tambah Article';
        $role = Auth::guard('admin')->user()->getRoleNames()->first();
        if ($role != 'superadmin') {
            $data['topics'] = Topic::where('created_by', $this->user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $data['topics'] = Topic::orderBy('created_at', 'desc')->get();
        }
        return view('backend.pages.article.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'id_topic' => 'required',
            'tanggal' => 'required',
            'content' => 'required',
            'cover' => 'required|image'
        ]);

        try {
            $data = new Article();
            $data->judul = $request->judul;
            $data->id_topic = $request->id_topic;
            $data->tanggal = $request->tanggal;
            $data->content = $request->content;
            $cover = null;
            if ($request->hasFile('cover')) {
                $image = $request->file('cover');
                $name = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('assets/img/cover_article/');
                $image->move($destinationPath, $name);
                $data->cover = $name;
            }
            $data->created_by = $this->user->id;
            $data->save();

            session()->flash('success', 'Data Berhasil Disimpan!');
            return redirect()->route('article');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['page_title'] = 'Edit Article';
        $data['article'] = Article::findOrFail($id);
        $role = Auth::guard('admin')->user()->getRoleNames()->first();
        if ($role != 'superadmin') {
            $data['topics'] = Topic::where('created_by', $this->user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $data['topics'] = Topic::orderBy('created_at', 'desc')->get();
        }
        return view('backend.pages.article.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'id_topic' => 'required',
            'tanggal' => 'required',
            'content' => 'required',
        ]);

        try {
            $data = Article::findOrFail($id);
            $data->judul = $request->judul;
            $data->id_topic = $request->id_topic;
            $data->tanggal = $request->tanggal;
            $data->content = $request->content;

            if ($request->hasFile('cover')) {
                // delete old cover file
                $oldPath = public_path('assets/img/cover_article/' . $data->cover);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }

                $image = $request->file('cover');
                $name = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('assets/img/cover_article/');
                $image->move($destinationPath, $name);
                $data->cover = $name;
            }

            $data->save();
            session()->flash('success', 'Data Berhasil Diupdate!');
            return redirect()->route('article');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $data = Article::findOrFail($id);

            $coverPath = public_path('assets/img/cover_article/' . $data->cover);
            if (file_exists($coverPath)) {
                @unlink($coverPath);
            }

            $data->delete();

            session()->flash('success', 'Data Berhasil Dihapus!');
            return redirect()->route('article');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->back();
        }
    }
    public function search(Request $request)
    {
        $query = Article::where('id_topic',$request->topic_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'articles' => $query,
            'id_topic' => $request->topic_id
        ]);
    }

    public function indexArticle()
    {
        return view('backend.pages.article.search');
    }
}
