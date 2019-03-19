<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 18/02/2019
 * Time: 20.51
 */
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Standar\M_LoginUser;
use App\Model\Standar\KelompokUser_S;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  LoginUserController extends Controller
{
	/*
	 * https://github.com/lcobucci/jwt/blob/3.2/README.md
	 */
	use Core;
	use JsonResponse;

	public function getDaftarLoginUser (Request $request)
	{
		$data = DB::table('M_LoginUser as log')
			->join('M_KelompokUser as kl','log.KdKelompokUser','=','kl.KdKelompokUser')
			->join('M_Pegawai as pg','log.KdPegawai','=','pg.KdPegawai')
			->select('log.KdUser','log.NamaUser','log.KataSandi','log.KdKelompokUser','log.KdPegawai',
				'kl.KelompokUser','pg.NamaPegawai')
			->orderBy('pg.NamaPegawai')
			->get();

		$result['code'] = 200;
		$result['data'] = $data;
		$result['as'] = "ramdanegie";

		return response()->json($result);
	}
	protected function generateSHA1($pass)
	{
		return sha1($pass);
	}
	public function saveLoginUser (Request $request)
	{
		DB::beginTransaction();
		try{
			$idMax = (int) M_LoginUser::max('KdUser') + 1;
			if($request['idUser'] == null){
				$exist = M_LoginUser::where('NamaUser',$request['namaUser'])->get()->count();
				if($exist > 1){
					$result = array(
						'status' => 500,
						'message'  => 'Nama User sudah ada',
						'as' => 'ramdanegie',
					);
					return response()->json($result,$result['status']);
				}
				$log = new M_LoginUser();
				$log->KdUser = $idMax;
				$log->Flag= true;
			}else{
				$log = M_LoginUser::where('KdUser',$request['idUser'])->first();
			}
			if(isset($request['kataSandi'])){
				$log->KataSandi= $this->generateSHA1( $request['kataSandi']);
			}
			$log->NamaUser= $request['namaUser'];
			$log->KdPegawai= $request['pegawai']['KdPegawai'];
			$log->KdKelompokUser= $request['kdKelompokUser'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Login User";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Login User Gagal";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message'  => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result,$result['status']);
	}
	public function deleteLoginUser (Request $request)
	{
		DB::beginTransaction();
		try{

			 M_LoginUser::where('id',$request['idUser'])->update(
			 	['Flag' =>  false]
			 );

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Login User";
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