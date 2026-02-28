<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private function wsId()
    {
        return session('current_workspace_id');
    }

    private function log($action)
    {
        try {
            DB::table('activity_logs')->insert([
                'workspace_id' => $this->wsId(),
                'user_id' => auth()->id(),
                'action' => $action,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
        }
    }

    // POST /products
    public function store(Request $request)
    {
        $v = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'category.required' => 'กรุณาเลือกหมวดหมู่',
            'unit.required' => 'กรุณากรอกหน่วย',
            'quantity.required' => 'กรุณากรอกจำนวน',
        ]);

        $productId = DB::table('products')->insertGetId([
            'workspace_id' => $this->wsId(),
            'name' => $v['name'],
            'category' => $v['category'],
            'unit' => $v['unit'],
            'description' => $v['description'] ?? null,
            'created_at' => now(),
        ]);

        DB::table('inventory')->insert([
            'product_id' => $productId,
            'quantity' => $v['quantity'],
            'min_stock_level' => $v['min_stock'] ?? 0,
        ]);

        $this->log("เพิ่มสินค้า: {$v['name']} จำนวน {$v['quantity']} {$v['unit']}");

        return redirect()->route('dashboard', ['section' => 'stock'])
            ->with('success', 'เพิ่มสินค้าสำเร็จ!');
    }

    // POST /products/{id}/update
    public function update(Request $request, $id)
    {
        $product = DB::table('products')
            ->where('id', $id)->where('workspace_id', $this->wsId())->first();

        abort_if(!$product, 403);

        $v = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        DB::table('products')->where('id', $id)->update([
            'name' => $v['name'],
            'category' => $v['category'],
            'unit' => $v['unit'],
            'description' => $v['description'] ?? null,
        ]);

        $inv = DB::table('inventory')->where('product_id', $id)->first();
        if ($inv) {
            DB::table('inventory')->where('product_id', $id)->update([
                'quantity' => $v['quantity'],
                'min_stock_level' => $v['min_stock'] ?? 0,
            ]);
        } else {
            DB::table('inventory')->insert([
                'product_id' => $id,
                'quantity' => $v['quantity'],
                'min_stock_level' => $v['min_stock'] ?? 0,
            ]);
        }

        $this->log("แก้ไขสินค้า: {$v['name']}");

        return redirect()->route('dashboard', ['section' => 'stock'])
            ->with('success', 'อัปเดตสินค้าสำเร็จ!');
    }

    // POST /products/{id}/delete
    public function destroy($id)
    {
        $product = DB::table('products')
            ->where('id', $id)->where('workspace_id', $this->wsId())->first();

        abort_if(!$product, 403);

        DB::table('inventory')->where('product_id', $id)->delete();
        DB::table('products')->where('id', $id)->delete();

        $this->log("ลบสินค้า: {$product->name}");

        return redirect()->route('dashboard', ['section' => 'stock'])
            ->with('success', 'ลบสินค้าสำเร็จ!');
    }
}
