<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 20/02/2019
 * Time: 08.20
 */

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Master\_MPegawai;
use App\Model\Master\M_Pegawai;
use App\Model\Master\PegawaiM;
use App\Model\Standar\M_LoginUser;
use App\Model\Master\_MProduk;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  MasterController extends Controller
{
	use Core;

	public function getAlamat (Request $request)
	{
		$data = DB::table('alamat_m')
			->select('*')
			->where ('statusenabled',true)
			->orderBy('alamat')
			->get();

		$result['code'] = 200;
		$result['data'] = $data;
		$result['as'] = "ramdanegie";

		return response()->json($result);
	}
	public function saveAlamat (Request $request)
	{
		DB::beginTransaction();
		try{
		$idMax = MLoginUser_::max('id') + 1;
		if($request['idUser'] == null){
			$log = new MLoginUser_();
			$log->id = $idMax;
			$log->statusenabled = true;
		}else{
			$log = MLoginUser_::where('id',$request['idUser'])->first();
		}
		if(isset($request['kataSandi'])){
			$log->katasandi= $request['kataSandi'];
		}
		$log->namauser= $request['namaUser'];
		$log->objectpegawaifk= $request['pegawai']['id'];
		$log->objectkelompokuserfk= $request['kdKelompokUser'];
		$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
		$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Pegawai";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Terjadi Kesalahan saat menyimpan data";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message'  => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result,$result['status']);
	}
	public function getDaftarPegawai (Request $request)
	{
		$data = DB::table('M_Pegawai as pg')
			->leftJoin('M_Agama as agm','agm.KdAgama','=','pg.KdAgama')
			->leftJoin('M_JenisKelamin as jk','jk.KdJenisKelamin','=','pg.KdJenisKelamin')
			->select('pg.*','agm.Agama',
				'jk.JenisKelamin')
			->where ('pg.Flag',true)
			->orderBy('pg.NamaPegawai')
			->get();

		$result['code'] = 200;
		$result['data'] = $data;
		$result['as'] = "ramdanegie";

		return response()->json($result);
	}
	public function savePegawai (Request $request)
	{
		DB::beginTransaction();
		try{
			$idMax = (int)M_Pegawai::max('KdPegawai') + 1;
			if($request['KdPegawai'] == null){
				$log = new M_Pegawai();
				$log->KdPegawai = $idMax;
				$log->Flag = true;
			}else{
				$log = M_Pegawai::where('KdPegawai',$request['KdPegawai'])->first();
			}
			$log->NamaPegawai= $request['NamaPegawai'];
			$log->KdAgama= $request['KdAgama'];
			$log->KdJenisKelamin= $request['KdJenisKelamin'];
			$log->NoTelpon= $request['NoTelpon'];
			$log->Alamat= $request['Alamat'];
			$log->NIK= $request['NIK'];

			$log->TglLahir= $request['TglLahir'];
			$log->save();

			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Simpan Pegawai";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Simpan Pegawai Gagal";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message'  => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result,$result['status']);
	}
	public function getCombo (Request $request)
	{
//		$alamat = DB::table('alamat_m')
//			->select('*')
//			->where ('statusenabled',true)
//			->orderBy('alamat')
//			->get();
		$jk = DB::table('M_JenisKelamin')
			->select('*')
			->where ('FLag',true)
			->orderBy('JenisKelamin')
			->get();
		$agama = DB::table('M_Agama')
			->select('*')
			->where ('FLag',true)
			->orderBy('Agama')
			->get();

		$result['code'] = 200;
		$result['data'] = array(
			'agama' => $agama,
			'jeniskelamin' => $jk,
		);
		$result['as'] = "ramdanegie";

		return response()->json($result);
	}
	public function deletePegawai (Request $request)
	{
		DB::beginTransaction();
		try{
			M_Pegawai::where('KdPegawai',$request['KdPegawai'])->update(
				[ 'Flag' =>  false]
			);
			$transStatus = 'true';
		} catch (\Exception $e) {
			$transStatus = 'false';
		}
		if ($transStatus == 'true') {
			$transMessage = "Hapus Pegawai";
			DB::commit();
			$result = array(
				'status' => 200,
				'message' => $transMessage,
				'as' => 'ramdanegie',
			);
		} else {
			$transMessage = "Hapus Pegawai Gagal";
			DB::rollBack();
			$result = array(
				'status' => 500,
				'message'  => $transMessage,
				'as' => 'ramdanegie',
			);
		}
		return response()->json($result,$result['status']);
	}

    public function getMasterProduk (Request $request)
    {
        $data = DB::table('produk_m as pr')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.detailjenisprodukfk')
            ->leftJoin('satuanstandard_m as ss', 'ss.id', '=', 'pr.satuanstandardfk')
            ->select('pr.*', 'djp.id as djpid', 'djp.detailjenisproduk', 'djp.jenisprodukfk', 'ss.id as ssis', 'ss.satuanstandard')
            ->where ('pr.statusenabled',true)
            ->orderBy('id');
        if (isset($request['namaproduk']) && $request['namaproduk']!="" && $request['namaproduk'] != "undefined"){
            $data = $data->where('pr.namaproduk','>=', $request['namaproduk']);
        }
        if (isset($request['id']) && $request['id']!="" && $request['id'] != "undefined"){
            $data = $data->where('pr.id','>=', $request['id']);
        }
        $data = $data->get();
        $result = array(
            'status' => 200,
            'data' => $data,
            'as' => "{ng-SitepuMan}"
        );
        return response()->json($result);
    }
    public function saveMasterProduk (Request $request)
    {
        DB::beginTransaction();
        try{
            $idMax = _MProduk ::max('id') + 1;
            if($request['id'] == null){
                $log = new _MProduk();
                $log->id = $idMax;
                $log->statusenabled = true;
            }else{
                $log = _MProduk::where('id',$request['id'])->first();
            }
            $log->namaproduk= $request['namaProduk'];
            $log->kdexternal= $request['kdExternal'];
            $log->detailjenisprodukfk= $request['detailJenisProduk'];
            $log->satuanstandardfk= $request['satuanStandard'];
            $log->statusenabled= $request['statusEnabled'];
            $log->save();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                'status' => 200,
                'message' => $transMessage,
                'as' => '{ng-SitepuMan}',
            );
        } else {
            $transMessage = "Terjadi Kesalahan saat menyimpan data";
            DB::rollBack();
            $result = array(
                'status' => 500,
                'message'  => $transMessage,
                'as' => '{ng-SitepuMan}',
            );
        }
        return response()->json($result,$result['status']);
    }
    public function deleteProduk (Request $request)
    {
        DB::beginTransaction();
        try{
            _MProduk::where('id',$request['id'])->update(
                [ 'statusenabled' =>  false]
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
                'as' => '{ng-SitepuMan}',
            );
        } else {
            $transMessage = "Terjadi Kesalahan";
            DB::rollBack();
            $result = array(
                'status' => 500,
                'message'  => $transMessage,
                'as' => '{ng-SitepuMan}',
            );
        }
        return response()->json($result,$result['status']);
    }
}