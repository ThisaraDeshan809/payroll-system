<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;
use DataTables;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function index()
    {
        return view('pages.category.index');
    }
    public function get(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::select('id', 'name')->get();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function show()
    {
        return view('pages.category.newAndUpdate');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->all();
            $data['slug'] = Str::of($data['name'])->slug('-');
            $result = $this->categoryRepository->createCategory($data);
            if ($result) {
                session()->flash('success', 'Category Create Successful');
                return redirect()->back();
            }
            session()->flash('success', 'Category Create Unsuccessful');
            return redirect()->back();
        } catch (\Exception $ex) {
            report($ex);
            session()->flash('success', 'Something went wrong');
            return redirect()->back();
        }
    }
}
