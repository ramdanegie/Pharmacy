<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 23/02/2019
 * Time: 05.07
 */

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Master\Alamat_M;
use App\Model\Master\_MCustomer;
use App\Model\Master\M_Customer;
use App\Model\Standar\KelompokUser_S;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  CustomerController extends Controller
{
	use JsonResponse;

	public function get(Request $request)
	{
		$data = DB::table('M_Customer')
			->select('*')
			->where('Flag', true)
			->orderBy('Customer')
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

			if ($request['KdCustomer'] == null) {
				$idMax =(int) M_Customer::max('KdCustomer') + 1;
				$log = new M_Customer();
				$log->KdCustomer = $idMax;
				$log->Flag = true;
			} else {
				$log = M_Customer::where('KdCustomer', $request['KdCustomer'])->first();
			}
			$log->Customer = $request['Customer'];
			$log->Alamat = $request['Alamat'];
			$log->NoTelp = $request['NoTelp'];
			$log->Email = $request['Email'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Customer";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Customer Gagal";
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
			M_Customer::where('KdCustomer', $request['KdCustomer'])->update(
				['Flag' => false]
			);

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Customer";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Hapus Customer Gagal";
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