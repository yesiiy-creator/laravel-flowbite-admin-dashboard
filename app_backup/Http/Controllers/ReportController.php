<?php
namespace App\Http\Controllers;
use App\Models\{ActivityLog,Product,StockIn,StockOut}; use Illuminate\Http\Request;
class ReportController extends Controller { public function index(Request $r){$from=$r->input('from',now()->startOfMonth()->toDateString());$to=$r->input('to',now()->toDateString());return view('reports.index',['from'=>$from,'to'=>$to,'products'=>Product::with('category')->orderBy('name')->get(),'incoming'=>StockIn::whereBetween('date',[$from,$to])->sum('quantity'),'outgoing'=>StockOut::whereBetween('date',[$from,$to])->sum('quantity'),'activities'=>ActivityLog::with('user')->latest()->take(20)->get()]);} }
