<?php

namespace App\Http\Controllers\Admin\Product\Variant;

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

        // Check if color is being used in any product variants
        if($color->productVariants()->exists()) {
            return redirect()->back()->with('error', 'Cannot update color variant that is being used by products');
        }

        $color->update($request->all());
        return redirect()->route('admin.product.product-variant.variant.list-variant')->with('success', 'Color variant updated successfully');
    }

    public function UpdateStorageVariant(Request $request, $id){
        $request->validate([
            'capacity' => 'required|string|max:255',
        ]);
        $storage = Storage::find($id);

        // Check if storage is being used in any product variants
        if($storage->productVariants()->exists()) {
            return redirect()->back()->with('error', 'Cannot update storage variant that is being used by products');
        }

        $storage->update($request->all());
        return redirect()->route('admin.product.product-variant.variant.list-variant')->with('success', 'Storage variant updated successfully');
    }   
    public function DeleteColorVariant($id){
        $color = Color::findOrFail($id);
        try {
            // Check if color is being used in any product variants
            if($color->productVariants()->exists()) {
                return redirect()->back()->with('error', 'Cannot delete color variant that is being used by products');
            }
            
            $color->delete();
            return redirect()->back()->with('success', 'Color variant deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete color variant');
        }
    }

    public function DeleteStorageVariant($id){
        $storage = Storage::findOrFail($id);
        try {
            // Check if storage is being used in any product variants  
            if($storage->productVariants()->exists()) {
                return redirect()->back()->with('error', 'Cannot delete storage variant that is being used by products');
            }

            $storage->delete();
            return redirect()->back()->with('success', 'Storage variant deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete storage variant');
        }
    }
}