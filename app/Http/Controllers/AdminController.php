<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Balance;
use App\lotshare;
use App\Share;
use DB;
class AdminController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'admin']);
    }
    public function index(){
        $data['totaluser'] = DB::table('users')->where('is_admin', 0 )->count();
        $data['latestUser'] = DB::table('users')->orderBy('created_at', 'desc')->limit(6)->get();
       // dd($latestList);
       // dd($data);
        return view('pages.admin.index',  $data);
    }
    public function depositIndex(){
        //$data["cariUser"] = User::find();
        $data["depoRequest"] = Share::orderBy('created_at', 'desc')->paginate(20);
        return view('pages.admin.deposit', $data);
    }
    public function depositEdit($id){
        $data["user"] = Share::findOrFail($id);
        return view('pages.admin.depositEdit', $data);
    }
    public function depositUpdate(Request $request, $id){
        $this->validate($request, [
            'status' => 'required',
            'remark' => 'required'
        ]);
        $updateStatus = Share::findOrFail($id);
        $updateStatus->update([
            'status' => $request->status,
            'remark' => $request->remark,
       ]);
        if($request->status == 2){
           $shareID = Share::findOrFail($id);
            $getTheFuckingID = Share::findOrFail($id)->user_id;
            $getBalance = Balance::where('user_id', $getTheFuckingID)->first();
        //    dd($getBalance);
            if($getBalance ==  null){
                $sumALL = $shareID->total_share;
            }else{
                $sumALL = $getBalance->balance + $shareID->total_share;
            }

            Balance::updateOrCreate(
                ['user_id' => $shareID->user_id],
                ['balance' => $sumALL ]
            );
        }
        return redirect()->action('AdminController@depositIndex');
    }
    //Share add
    public function shareIndex(){
        $shareLot = DB::table('lotshares')->where('id', '1')->first();
        return view('pages.admin.lotshare', compact('shareLot'));
    }
    public function shareUpdate(Request $request){
        $this->validate($request, [
            'lotshare' => 'required|numeric'
        ]);

        $updateStatus = lotshare::findOrFail(1);
        $updateStatus->update([
            'lotshare' => $request->lotshare,
        ]);

        return back();
    }
}
