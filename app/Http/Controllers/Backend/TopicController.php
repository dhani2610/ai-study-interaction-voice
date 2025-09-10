<?php

namespace App\Http\Controllers\Backend;

use App\Models\Topic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['page_title'] = 'Topic';
        $data['topic'] = Topic::orderBy('created_at', 'desc')->get();

        return view('backend.pages.topic.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['page_title'] = 'Tambah Data Topic';
        return view('backend.pages.topic.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = new Topic();
            $data->topic = $request->topic;
            $data->save();

            session()->flash('success', 'Data Berhasil Disimpan!');
            return redirect()->route('topic');
        } catch (\Throwable $th) {

            session()->flash('failed', $th->getMessage());
            return redirect()->route('topic');
        }
    }

  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['page_title'] = 'Edit Data Topic';
        $data['topic'] = Topic::find($id);

        return view('backend.pages.topic.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Topic::find($id);
            $data->topic = $request->topic;
            $data->save();

            session()->flash('success', 'Data Berhasil Disimpan!');
            return redirect()->route('topic');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->route('topic');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $data = Topic::find($id);
            $data->delete();

            session()->flash('success', 'Data Berhasil dihapus!');
            return redirect()->route('topic');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->route('topic');
        }
    }
}
