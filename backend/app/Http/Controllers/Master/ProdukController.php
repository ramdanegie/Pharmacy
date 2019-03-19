<?php
/**
 * Created by IntelliJ IDEA.
 * User: ng-SitepuMan
 * Date: 18/02/2019
 * Time: 20.27
 */
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Master\M_Produk;
use App\Model\Master\ProdukControler;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  ProdukController extends Controller
{
	use JsonResponse;

	public function get(Request $request)
	{
		$data = DB::table('M_Produk')
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

			if ($request['KdProduk'] == null) {
				$idMax =(int) M_Produk::max('KdProduk') + 1;
				$log = new M_Produk();
				$log->KdProduk = $idMax;
				$log->Flag = true;
			} else {
				$log = M_Produk::where('KdProduk', $request['KdProduk'])->first();
			}
			$log->NamaProduk = $request['NamaProduk'];
			$log->KdJenisProduk = $request['KdJenisProduk'];
			$log->KdGolonganProduk = $request['KdGolonganProduk'];
			$log->KdExternal = $request['KdExternal'];
			$log->NamaExternal = $request['NamaExternal'];
			$log->KdPabrik = $request['KdPabrik'];
			$log->StatusKronis = $request['StatusKronis'];
			$log->KdSatuanJual= $request['KdSatuanJual'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Produk";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Produk Gagal";
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
			M_Produk::where('KdProduk', $request['KdProduk'])->update(
				['Flag' => false]
			);

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Produk";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Hapus Produk Gagal";
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