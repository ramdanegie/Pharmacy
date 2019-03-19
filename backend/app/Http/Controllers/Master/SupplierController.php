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
use App\Model\Master\JenisTransaksi_M;
use App\Model\Master\_MSatuan;
use App\Model\Master\_MSupplier;
use App\Model\Master\M_Supplier;
use App\Model\Master\Toko_M;
use App\Model\Standar\KelompokUser_S;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  SupplierController extends Controller
{
	use JsonResponse;

	public function get(Request $request)
	{
		$data = DB::table('M_Supplier')
//			->leftJoin('alamat_m as alm','alm.id','=','tk.alamatfk')
			->select('*')
			->where('Flag', true)
			->orderBy('NamaSupplier')
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
			if ($request['KdSupplier'] == null) {
				$idMax = (int) M_Supplier::max('KdSupplier') + 1;
				$log = new M_Supplier();
				$log->KdSupplier = $idMax;
				$log->Flag = true;
			} else {
				$log = M_Supplier::where('KdSupplier', $request['KdSupplier'])->first();
			}
			$log->NamaSupplier = $request['NamaSupplier'];
			$log->Alamat = $request['Alamat'];
			$log->Email = $request['Email'];
			$log->Owner = $request['Owner'];
			$log->NoTelepon = $request['NoTelepon'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Supplier";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Supplier Gagal";
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
			M_Supplier::where('KdSupplier', $request['KdSupplier'])->update(
				['Flag' => false]
			);

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Supplier";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Hapus Supplier Gagal";
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