<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\MarketPrice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Daily crop prices shown on the landing page hero.
class MarketPricesController extends Controller
{
    public function index(Request $r){
      $prices =MarketPrice::latest('price_date')->latest('id')
        ->when($r->search,fn ($q) => $q->where('name','LIKE','%'.$r->search.'%'))
        ->paginate(25)->appends($r->only('search'));

      $edit =$r->edit ? MarketPrice::find($r->edit) : null;
      $board =MarketPrice::board();
      $items =MarketPrice::select('name','name_bn','icon','unit')->latest('price_date')->get()->unique('name')->values();

      return view(adminTheme().'market-prices.index',compact('prices','edit','board','items'));
    }

    // Adds a price, or updates it when the item already has one on that date.
    public function save(Request $r,$id=null){
      $data =$r->validate([
        'name' =>'required|string|max:60',
        'name_bn' =>'nullable|string|max:60',
        'icon' =>'nullable|string|max:16',
        'unit' =>'required|string|max:20',
        'price' =>'required|numeric|min:0|max:99999999',
        'price_date' =>'required|date|before_or_equal:today',
      ]);
      $data['name'] =trim($data['name']);

      $price =$id ? MarketPrice::findOrFail($id)
        : MarketPrice::firstOrNew(['name'=>$data['name'],'price_date'=>$data['price_date']]);

      $clash =MarketPrice::where('name',$data['name'])->whereDate('price_date',$data['price_date'])
        ->when($price->exists,fn ($q) => $q->where('id','!=',$price->id))->exists();
      if($clash){
        return back()->withInput()->withErrors(['price_date'=>'This item already has a price on that date.']);
      }

      $price->fill($data);
      $price->addedby_id =$price->addedby_id ?: Auth::id();
      $price->save();

      Session()->flash('success','Market price saved.');
      return redirect()->route('admin.marketPrices');
    }

    public function delete($id){
      MarketPrice::findOrFail($id)->delete();
      Session()->flash('success','Market price deleted.');
      return back();
    }
}
