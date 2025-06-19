<?php

namespace App\Http\Controllers\Admin\Variant;

use App\Models\Color;
use App\Models\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductVariant extends Controller
{
    //
    public function GetAllProductVariant(){
        try {
            $colors = Color::orderBy('id', 'desc')->get();
            $storages = Storage::orderBy('id', 'desc')->get();
            return view('admin.product.product-variant.variant.list-variant', compact('colors', 'storages'));

        } catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
    
    public function CreateColorVariant(){
        return view('admin.product.product-variant.variant.create-color');
    }

    public function CreateStorageVariant(){
        return view('admin.product.product-variant.variant.create-storage');
    }

    public function StoreColorVariant(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Color::create($request->all());
        return redirect()->route('admin.product.product-variant.variant.list-variant')->with('success', 'Color variant created successfully');
    }

    public function StoreStorageVariant(Request $request){
        $request->validate([
            'capacity' => 'required|string|max:255',
        ]);
        Storage::create($request->all());
        return redirect()->route('admin.product.product-variant.variant.list-variant')->with('success', 'Storage variant created successfully');
    }

    public function EditColorVariant($id){
        $color = Color::find($id);
        return view('admin.product.product-variant.variant.edit-color', compact('color'));
    }

    public function EditStorageVariant($id){
        $storage = Storage::find($id);
        return view('admin.product.product-variant.variant.edit-storage', compact('storage'));
    }

    public function UpdateColorVariant(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $color = Color::find($id);
        $color->update($request->all());
        return redirect()->route('admin.product.product-variant.variant.list-variant')->with('success', 'Color variant updated successfully');
    }

    public function UpdateStorageVariant(Request $request, $id){
        $request->validate([
            'capacity' => 'required|string|max:255',
        ]);
        $storage = Storage::find($id);
        $storage->update($request->all());
        return redirect()->route('admin.product.product-variant.variant.list-variant')->with('success', 'Storage variant updated successfully');
    }

    public function DeleteColorVariant($id){
        $color = Color::find($id);
        $color->delete();
        return redirect()->back()->with('success', 'Color variant deleted successfully');
    }

    public function DeleteStorageVariant($id){
        $storage = Storage::find($id);
        $storage->delete();
        return redirect()->back()->with('success', 'Storage variant deleted successfully');
    }
    
    
}