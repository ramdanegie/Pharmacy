<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 16/02/2019
 * Time: 15.44
 */
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Standar\M_KelompokUser;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  KelompokUserController extends Controller
{
	/*
	 * https://github.com/lcobucci/jwt/blob/3.2/README.md
	 */
	use Core;
	use JsonResponse;

	public function getAll (Request $request)
	{
		// ini_set('memory_limit', '128M');
		$kelUser = M_KelompokUser::where('Flag',true)
			->select('KdKelompokUser','KelompokUser');
		$kelUser = $kelUser->get();
		// $kelUser = DB::table('produk_m')
		// 	->select('*')
		// 	->get();
		if ($kelUser->count() > 0) {
			$result['code'] = 200;
			$result['data'] = $kelUser;
			$result['as'] = "ramdanegie";
		} else {
			$result['code'] = 500;
			$result['status'] = false;
			$result['as'] = "ramdanegie";
		}
		return response()->json($result);
	}
	public function saveKelompokUser (Request $request)
	{
		DB::beginTransaction();
		try{
			$idMax = (int) M_KelompokUser::max('KdKelompokUser') + 1;
			if($request['idKelompokUser'] == null){
				$log = new M_KelompokUser();
				$log->KdKelompokUser = $idMax;
				$log->Flag = true;
			}else{
				$log = M_KelompokUser::where('KdKelompokUser',$request['idKelompokUser'])->first();
			}
			$log->KelompokUser= $request['kelompokUser'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Kelompok User";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Kelompok User Gagal";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message'  => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result,$result['status']);
	}
	public function deleteKelompokUser (Request $request)
	{
		DB::beginTransaction();
		try{

			 M_KelompokUser::where('KdKelompokUser',$request['idKelompokUser'])->update(
			 	[ 'Flag' => false ]
			 );

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Kelompok User";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Terjadi Kesalahan saat menghapus data";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message'  => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result,$result['status']);
	}
}