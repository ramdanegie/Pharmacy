<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Model\Test;
class  TesIqbalController extends Controller
{
	use JsonResponse;

	public function getDaftarTes( Request $request){
		$data = DB::table('Test')
			->select('*');
		// $data = Test::select('nama')->get();
		if(isset($request['nama']) &&$request['nama']!= '' && $request['nama']!= 'null'){
			$data = $data->where('Nama','like', '%'.$request['nama'].'%');
		}
		if(isset($request['nik']) &&$request['nik']!= '' && $request['nik']!='null'){
			$data = $data->where('NIK','like', '%'.$request['nik'].'%');
		}	
		$data = $data->get();
		
		$result = array(
			'data' => $data,
			'as' => 'iqbal'

			);
		return response()->json($result);

	}


}