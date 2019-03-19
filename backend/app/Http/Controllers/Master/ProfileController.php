<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 19/03/2019
 * Time: 05.01
 */

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Model\Master\Alamat_M;
use App\Model\Standar\KelompokUser_S;
use Illuminate\Http\Request;
use App\Traits\Core;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\DB;

class  ProfileController extends Controller
{
    use Crud;
    use Core;
    public function uploadPhoto(Request $request){   
        $helper = new FileHelper;
        if($request->file('photo')){
            try{
                $extension = $request->file('photo')->extension();
                $fileName = date('Ymdhis').$request->input('KdPegawai');
                $helper->upload(
                    $request->file('photo'),
                    storage_path('images/user'),
                    $fileName.'.'.$extension
                );
                $data['FileName']=$fileName;
                $data['ext']=$extension;
                $result = message::uploadSuccessResponse($data);
                return $result;
            }
            catch(\Exception $e){
                $result = message::uploadFailResponse();
                return $result;
            }
        }
        $result = message::uploadFailResponse();
        return $result;
    }
    public function UploadPhotoProfile(Request $request)
    {
        $path = storage_path();
        $result = array(
            "status" => 400,
            "message" => 'Upload Gagal !',
        );
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $file->move($path."/images/user", $fileName);
        $result = array(
            "status" => 201,
            "message" => 'Upload '.$fileName.' Sukses !',
        );
        return response()->json($result, $result["status"]);

    }


}