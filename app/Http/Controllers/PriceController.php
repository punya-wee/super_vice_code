<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceRecord;
use Carbon\Carbon;

class PriceController extends Controller
{
    // GET /api/prices?product_name=xxx
    public function index(Request $request)
    {
        $query = PriceRecord::query();

        if ($request->filled('product_name')) {
            $query->where('product_name', $request->product_name);
        }

        $records = $query->orderBy('recorded_date', 'desc')->get();

        return response()->json(['success' => true, 'data' => $records]);
    }

    // GET /api/prices/products — รายชื่อพืชผลที่มีราคา
    public function products()
    {
        $names = PriceRecord::distinct()->pluck('product_name');
        return response()->json(['success' => true, 'data' => $names]);
    }

    // GET /api/prices/trends?product_name=xxx — trend 6 เดือน
    public function trends(Request $request)
    {
        $query = PriceRecord::selectRaw('product_name, DATE_FORMAT(recorded_date, "%Y-%m") as month, AVG(price) as avg_price, MAX(price) as max_price, MIN(price) as min_price')
            ->groupBy('product_name', 'month')
            ->orderBy('month', 'asc');

        if ($request->filled('product_name')) {
            $query->where('product_name', $request->product_name);
        }

        $data = $query->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    // GET /api/prices/summary — สรุปราคาทุกพืช
    public function summary()
    {
        $summary = PriceRecord::selectRaw('
                product_name,
                category,
                MAX(CASE WHEN recorded_date = (SELECT MAX(recorded_date) FROM price_records p2 WHERE p2.product_name = price_records.product_name) THEN price END) as latest_price,
                AVG(price) as avg_price,
                MAX(price) as max_price,
                MIN(price) as min_price
            ')
            ->groupBy('product_name', 'category')
            ->get();

        return response()->json(['success' => true, 'data' => $summary]);
    }

    // POST /api/prices — เพิ่มข้อมูลราคา
    public function store(Request $request)
    {
        $v = $request->validate([
            'product_name' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'recorded_date' => 'required|date',
            'source' => 'nullable|string',
        ]);

        $record = PriceRecord::create($v);
        return response()->json(['success' => true, 'message' => 'บันทึกราคาสำเร็จ', 'record' => $record], 201);
    }

    // DELETE /api/prices/{id}
    public function destroy($id)
    {
        PriceRecord::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'ลบข้อมูลราคาสำเร็จ']);
    }
}
