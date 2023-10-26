<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use Illuminate\Support\Carbon;

class CategoryController extends Controller
{
    public function AllCat(){
        $categories = Category::latest()->paginate(5);
        $softdelete = Category::onlyTrashed()->latest()->paginate(3);

        return view('admin.category.index', compact('categories', 'softdelete'));
    }

    public function AddCat(Request $request){
        $validated = $request->validate([
            'category_name' => 'required|unique:categories|max:255',
        ],
        [
            'category_name.required' => 'Please input category name',
        ],
    );

    // Category::insert([
    //     'category_name' => $request->category_name,
    //     'user_id' => Auth::user()->id,
    //     'created_at' => Carbon::now()
    // ]);

    $category = new Category;
    $category->category_name = $request->category_name;
    $category->user_id = Auth::user()->id;
    $category->created_at = Carbon::now();
    $category->save();

    

    return Redirect()->back()->with('success', 'Category added successfully!');
    }

    public function Edit($id){
        $categories = Category::find($id);
        return view('admin.category.edit', compact('categories'));
    }

    public function Update(Request $request, $id){
        $update = Category::find($id)->update([
            'category_name' => $request->category_name,
            'user_id' => Auth::user()->id
        ]);
        return Redirect()->route('all.category')->with('success','Category updated successfully!');
    }

    public function SoftDelete($id){
        $softdelete = Category::find($id)->delete();
        return Redirect()->back()->with('success','Category soft deleted successfully!');
    }

    public function Restore($id){
        $restore = Category::withTrashed()->find($id)->restore();
        return Redirect()->back()->with('success','Category restored successfully!');
    }

    public function Delete($id){
        $delete = Category::onlyTrashed()->find($id)->forceDelete();
        return Redirect()->back()->with('success','Category deleted successfully!');
    }
}
