<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 16/02/2019
 * Time: 01.45
 */

namespace App\Http\Controllers\Core;

use App\Model\Master\LoginUser_S;
use Illuminate\Support\Facades\DB;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Hmac\Sha384;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use App\Traits\message;
use App\Helper\FileHelper;

class  LoginController extends Controller
{
	/*
	 * https://github.com/lcobucci/jwt/blob/3.2/README.md
	 */
	use Core;
	use JsonResponse;
	public function signIn(Request $request)
	{
		$loginUser = DB::table('M_LoginUser')
			->where('KataSandi', '=', $this->generateSHA1($request->input('kataSandi')))
			->Where('NamaUser','=',$request->input('namaUser'))
			->where('Flag',true);
		$loginUser = $loginUser->get();
		if($loginUser->count() > 0){
			$idPegawai = $loginUser[0]->KdPegawai;
			$result['kdUser'] = $loginUser[0]->KdUser;
			$result['namaUser'] = $namaUser = $request->input('namaUser');
			$result['kataSandi'] = $password = $this->generateSHA1($request->input('kataSandi'));
			$result['pegawai'] = $this->getPegawai($idPegawai,null);
			$result['kelompokUser'] = $this->getKelompokUser($idPegawai, null);
			$result['code'] = 200;
			$result['token'] = $this->createToken($result['kdUser'], $result['namaUser'], $idPegawai).'';
			$result['as'] = "ramdanegie";
		}else{
			$result['code'] = 500;
			$result['status'] = false;
			$result['message'] = "User not exist. Please Sign Up.";
			$result['as'] = "ramdanegie";
		}
		return response()->json($result);
	}
	public function createToken($kdUser, $namaUser, $idPegawai){
		$class = new Builder();
		$signer = new Sha256();
		$token = $class->setHeader('alg','HS512')
			// ->setIssuedAt(time())// Configures the time that the token was issued (iat claim)
			// ->setNotBefore(time() + 1)// Configures the time that the token can be used (nbf claim)
			// ->setExpiration(time() + 36000)// Configures the expiration time of the token (exp claim)
			->set('kdUser', $kdUser)  // Configures a new claim, called "kdUser"
			->set('namaUser', $namaUser)
			->set('kdPegawai', $idPegawai)
			->sign($signer, "RUdJRVJBTURBTg==")//base64
			->getToken(); // Retrieves the generated token
		return $token;
	}

	protected function generateSHA1($pass)
	{
		return sha1($pass);
	}
	public function getPegawai($idPegawai, $kdProfile = null){
		$pegawai = DB::table('M_Pegawai')
			->select('*')
			->where('KdPegawai','=',$idPegawai);
		if($kdProfile){
			$pegawai->where('kdprofile','=', $kdProfile);
		}
		$pegawai = $pegawai->first();
		return $pegawai;
	}
	public function signOut (Request $request)
	{
		$result['code'] = 500;
		$result['message'] = 'You have not login';
		$queryLogin = DB::table('M_LoginUser')
            ->where('KataSandi', '=', $request->input('encrypted'))
			->where('NamaUser', '=', $request->input('namaUser'));
		$loginUser = $queryLogin->get();
		if(count($loginUser) > 0){
			$result['message'] = 'Logout Success';
			$result['id'] = $loginUser[0]->KdUser;
			$result['namaUser'] = $loginUser[0]->NamaUser;
			$result['code'] = 200;
			$result['as'] = 'ramdanegie';
		}
		return response()->json($result);
	}
	public function changePassword (Request $request)
	{
		try{
			LoginUser_S::where('id',$request['id'])->update(
				[
					'katasandi' => $this->generateSHA1($request->input('kataSandi')),
					'objectkelompokuserfk' => $request['kelompokUser']['id'],
					'namauser' => $request['namaUser']
					//'katasandi' => $this->encryptSHA1($request->input('kataSandi'))
				]
			);
			$transStatus = true;
		} catch (\Exception $e) {
			$transStatus = false;
		}

		if ($transStatus) {
			$transMessage = "Sukses";
			DB::commit();
			$result = array(
				"status" => 201,
				"as" => 'ramdanegie'
			);
		} else {
			$transMessage = "Failed";
			DB::rollBack();
			$result = array(
				"status" => 400,
				"as" => 'ramdanegie'
			);
		}
		return $this->setStatusCode($result['status'])->respond($result, $transMessage);
	}
	public function getKelompokUser($idPegawai, $kdProfile = null){
		$kelompokuser = DB::table('M_KelompokUser as kl')
			->join('M_LoginUser as log','log.KdKelompokUser','=','kl.KdKelompokUser')
			->select('kl.*')
			->where('log.KdPegawai','=',$idPegawai);
		if($kdProfile){
			$kelompokuser->where('kl.kdprofile','=', $kdProfile);
		}
		$kelompokuser = $kelompokuser->first();
		return $kelompokuser->KelompokUser;
	}

}