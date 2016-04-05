<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\Merchant;

class MerchantController extends Controller
{
	public function validateInput(Request $request){
		$rules = array(
            'nama'		=> 'required',
            'alamat'	=> 'required',
            'telepon'	=> 'required',
            'email'		=> 'required|email'
        );
        return Validator::make(Input::all(), $rules);
	}


    public function getView(){
        if(isMerchant()){
            return view('merchant.dashboard');
        } else {
            return view('merchant.welcome');
        }
    }

	/*
	 * Status tidak diperlukan untuk pendaftaran
	 * Password tidak diperlukan untuk edit oleh Software Engineer
	 */

	public function validatePassword($password){
		if ($password.length()>=8){
			return 1;
		} else {
			return "Password must be at least 8 characters";
		}
	}

    public function __construct(){
        //$this->middleware('admin');
   	}

	public function index(){
		//return Auth::guard('admin')->user()->nama;
        return view('merchant.dashboard');
    }

    /**
     * Display all Merchants
     *
     * @return Response
     */
    public function getAllMerchant(){
    	if(!isAdmin())
            return "";
    	return Merchant::orderBy('id','asc')->get();
    }

    /**
     * Get a Merchant by ID
     *
     * @param  int  $id
     * @return Response
     */
	public function getMerchant($id) {
		if(!isAdmin())
            return "";
        return Merchant::find($id);
    }

    /**
     * Add a Merchant
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
	public function addMerchant(Request $request) {
       /* No authentication. Public can access */

       /* Validation with Laravel built-in validator*/
       $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return $validator->messages()->first();
        } else {
        	/* Check password */
        	$passwordValidator = validatePassword($password);
        	if($passwordValidator!=1){
        		return $passwordValidator;
        	}

        	/* Validate phone number */
            if (!preg_match('/^[0-9]+$/', Input::get('no_telp'))){
                return "No. Telp tidak valid. Hanya boleh mengandung angka";
            }

            $merchant = new Merchant;
            $merchant->nama         		= Input::get('nama');
            $merchant->alamat       		= Input::get('alamat');
            $merchant->telepon      		= Input::get('telepon');
            $merchant->email      			= Input::get('email');
            $merchant->password  			= Input::get('password');
            $merchant->status  				= "Diproses";
            $merchant->save();
            return 1;
        }
    }

    /**
     * Edit a Merchant by Software Engineer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function editMerchant(Request $request, $id) {
        if(!isAdmin())
            return "Not allowed";

       /* Authentication. Only software engineer can access */

       /* Validation with Laravel built-in validator*/
       $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return $validator->messages()->first();
        } else {

        	/* Validate phone number */
            if (!preg_match('/^[0-9]+$/', Input::get('no_telp'))){
                return "No. Telp tidak valid. Hanya boleh mengandung angka";
            }

            $merchant = Merchant::find($id);
            if(!$merchant)
                return "Not found";

            $merchant->nama         		= Input::get('nama');
            $merchant->alamat       		= Input::get('alamat');
            $merchant->telepon      		= Input::get('telepon');
            $merchant->email      			= Input::get('email');
            $merchant->status  				= "Diproses";
            $merchant->save();
            return 1;
        }
    }

    /**
     * Remove a Merchant by Software Engineer
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!isAdmin())
            return "Not allowed";

    	//$this->middleware('admin');
        $merchant = Merchant::find($id);
        if(!$merchant)
                return "Not Found";

        /* Check inTransaction dan inPermohonan */
        /* Belum diimplementasikan */
        
		$merchant->delete();
		return 1;
    }



    /**
     * Edit a Merchant by Merhant
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function editMyMerchant(Request $request, $id) {
        if(!isMerchant())
            return "Not allowed";

        //$this->middleware('admin');
       /* Authentication. Only the merchant owner can access */


       /* Validation with Laravel built-in validator*/
       $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return $validator->messages()->first();
        } else {

            /* Validate phone number */
            if (!preg_match('/^[0-9]+$/', Input::get('no_telp'))){
                return "No. Telp tidak valid. Hanya boleh mengandung angka";
            }

            $merchant = Merchant::find($id);
            if(!$merchant = Merchant::find($id))
                return "Not found";

            $id_merchant_auth = Auth::guard('merchant')->user()->id;
            $id_merchant = $merchant->id;
            if($id_merchant_auth!=$id_merchant)
                return "You're not authorized";

            $merchant->nama                 = Input::get('nama');
            $merchant->alamat               = Input::get('alamat');
            $merchant->telepon              = Input::get('telepon');
            $merchant->email                = Input::get('email');
            $merchant->status               = "Diproses";
            $merchant->save();
            return 1;
        }
    }
}