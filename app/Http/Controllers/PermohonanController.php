<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\Permohonan;
use App\Merchant;

class PermohonanController extends Controller
{
	public function __construct(){
		/* Hanya surveyor */
        if(!isAdmin())
        	return Redirect::to('/')->send();
   	}

    public function getView(){
    	/* Hanya surveyor */
        return view('permohonan.index');
    }

    /**
     * Display all Permohonan
     *
     * @return Response
     */
    public function getAllPermohonan($status = null){
    	/* Hanya surveyor */
    	if($status == null){
			return Permohonan::orderBy('id','asc')->get();
    	} else {
    		return Permohonan::where('status','=',$status)->orderBy('id','asc')->get();
    	}
    	
    }

    /**
     * Get a Permohonan by ID
     *
     * @param  int  $id
     * @return Response
     */
	public function getPermohonan($id) {
		/* Hanya surveyor */
        return Permohonan::find($id);
    }

    /**
     * Edit a Permohonan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function approveRejectPermohonan(Request $request, $type, $id) {
    	/* Hanya surveyor */

       	if(($type!=0) && ($type!=1))
       		return "";

       	$permohonan = Permohonan::find($id);
       	if(!$permohonan)
       		return "not found";

       	$merchant_id=$permohonan->merchant_id;
       	$merchant = Merchant::find($merchant_id);
       	if(!$merchant)
       		return "merchant not found";

       	$status="";
       	if($type==0){//ditolak
       		$status="Ditolak";
       	} else if($type==1){//diterima
       		$status="Diterima";
       	}
       	$permohonan->status=$status;
       	$merchant->status=$status;
       	$permohonan->save();
       	$merchant->save();
    }
}