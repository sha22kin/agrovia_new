<?php

namespace App\Http\Controllers\Admin;

use App\Models\Enquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Review of buyer enquiries: they appear on the RFQ page and to sellers only after approval.
class EnquiriesController extends Controller
{
    public function index(Request $r){
      $approval =array_key_exists($r->approval,Enquiry::APPROVALS) ? $r->approval : 'pending';

      $enquiries =Enquiry::with(['user:id,name,email,mobile','category:id,name'])->withCount('quotes')
        ->where('approval',$approval)
        ->when($r->search,fn ($q) => $q->where('title','LIKE','%'.$r->search.'%'))
        ->latest()->paginate(20)->withQueryString();

      $counts =Enquiry::selectRaw('approval, count(*) as total')->groupBy('approval')->pluck('total','approval');

      return view(adminTheme().'enquiries.index',compact('enquiries','approval','counts'));
    }

    public function approve($id){
      $enquiry =Enquiry::findOrFail($id);
      $enquiry->approval ='approved';
      $enquiry->reject_reason =null;
      $enquiry->save();

      Session()->flash('success','Enquiry approved. It is now on the RFQ page');
      return back();
    }

    public function reject(Request $r,$id){
      $r->validate(['reject_reason' => 'required|string|max:1000']);

      $enquiry =Enquiry::findOrFail($id);
      $enquiry->approval ='rejected';
      $enquiry->reject_reason =trim($r->reject_reason);
      $enquiry->save();

      Session()->flash('success','Enquiry rejected. The buyer sees your reason on their enquiry');
      return back();
    }
}
