<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 23/02/2019
 * Time: 09.26
 */

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Master\DetailJenisProduk_M;
use App\Model\Master\_MJenisKelamin;
use App\Model\Master\JenisProduk_M;
use App\Model\Master\M_JenisKelamin;
use App\Model\Standar\KelompokUser_S;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  JenisKelaminController extends Controller
{
	use JsonResponse;

	public function get(Request $request)
	{
		$data = DB::table('M_JenisKelamin')
			->select('*')
			->where('Flag', true)
			->orderBy('JenisKelamin')
			->get();

		$result['code'] = 200;
		$result['data'] = $data;
		$result['as'] = "ramdanegie";

		return response()->json($result);
	}

	public function save(Request $request)
	{
		DB::beginTransaction();
		try {
			if ($request['KdJenisKelamin'] == null) {
				$idMax =(int) M_JenisKelamin::max('KdJenisKelamin') + 1;
				$log = new M_JenisKelamin();
				$log->KdJenisKelamin = $idMax;
				$log->Flag = true;
			} else {
				$log = M_JenisKelamin::where('KdJenisKelamin', $request['KdJenisKelamin'])->first();
			}
			$log->JenisKelamin = $request['JenisKelamin'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Jenis Kelamin";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Jenis Kelamin Gagal";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result, $result['status']);
	}

	public function delete(Request $request)
	{
		DB::beginTransaction();
		try {
			M_JenisKelamin::where('KdJenisKelamin', $request['KdJenisKelamin'])->update(
				['Flag' => false]
			);

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Jenis Kelamin";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Hapus Jenis Kelamin Gagal";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result, $result['status']);
	}
}