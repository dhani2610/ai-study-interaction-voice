<?php

namespace App\Http\Controllers\Backend;

use App\Models\Materi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class MateriController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('admin')->check() == null) {
                return redirect()->route('admin.login');
            }
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['page_title'] = 'Materi';
        $data['materi'] = Materi::orderBy('created_at', 'desc')->get();
        
        return view('backend.pages.materi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['page_title'] = 'Tambah Data Materi';
        return view('backend.pages.materi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = new Materi();
            $data->id_user = Auth::guard('admin')->user()->id;
            $data->judul = $request->judul;
            $data->slug = Str::slug($request->judul, '-'); 
            $data->status = $request->status;
            $data->content = $request->content;
            $data->description = $request->description;
            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $name = time() . '.' . $cover->getClientOriginalExtension();
                $destinationPath = public_path('assets/img/cover/');
                $cover->move($destinationPath, $name);
                $data->image_cover = $name;
            }
            $data->save();

            session()->flash('success', 'Data Berhasil Disimpan!');
            return redirect()->route('materi');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->route('materi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['page_title'] = 'Edit Data Materi';
        $data['materi'] = Materi::find($id);

        return view('backend.pages.materi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Materi::find($id);
            $data->judul = $request->judul;
            $data->slug = Str::slug($request->judul, '-'); 
            $data->status = $request->status;
            $data->content = $request->content;
            $data->description = $request->description;
            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $name = time() . '.' . $cover->getClientOriginalExtension();
                $destinationPath = public_path('assets/img/cover/');
                $cover->move($destinationPath, $name);
                $data->image_cover = $name;
            }
            $data->save();

            session()->flash('success', 'Data Berhasil Disimpan!');
            return redirect()->route('materi');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->route('materi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $data = Materi::find($id);
            $data->delete();

            session()->flash('success', 'Data Berhasil dihapus!');
            return redirect()->route('materi');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect()->route('materi');
        }
    }
}
