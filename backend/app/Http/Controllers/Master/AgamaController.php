<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 19/03/2019
 * Time: 20.26
 */


namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Master\DetailJenisProduk_M;
use App\Model\Master\_MJenisKelamin;
use App\Model\Master\JenisProduk_M;
use App\Model\Master\JenisTransaksi_M;
use App\Model\Master\_MSatuan;
use App\Model\Master\M_Agama;
use App\Model\Master\Toko_M;
use App\Model\Standar\KelompokUser_S;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  AgamaController extends Controller
{
	use JsonResponse;

	public function get(Request $request)
	{
		$data = DB::table('M_Agama')
			->select('*')
			->where('Flag', true)
			->orderBy('Agama')
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
			if ($request['KdAgama'] == null) {
				$idMax = (int) M_Agama::max('KdAgama') + 1;
				$log = new M_Agama();
				$log->KdAgama = $idMax;
				$log->Flag = true;
			} else {
				$log = M_Agama::where('KdAgama', $request['KdAgama'])->first();
			}
			$log->Agama = $request['Agama'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Agama";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Agama Gagal";
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
			M_Agama::where('KdAgama', $request['KdAgama'])->update(
				['Flag' => false]
			);

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Agama";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Hapus Agama Gagal";
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