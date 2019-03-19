<?php
/**
 * Created by PhpStorm.
 * User: Egie Ramdan
 * Date: 06/02/2019
 * Time: 22.36
 */

namespace App\Model\Master;

use Illuminate\Database\Eloquent\Model;

class M_Agama extends Model
{
    protected $table = 'M_Agama';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'KdAgama';
//    protected $fillable = [
//        'id',
//        'kdprofile',
//        'agama',
//        'reportdisplay',
//        'kodeexternal',
//        'namaexternal',
//        'statusenabled',
//        'norec'
//    ];
    public static function queryTable($request){
        $table = 'M_Agama';
        $param['table_from']= $table;
        $param['select']= array($table.'.*');
        $param['label'] = array();

        $param['where'][0]['fieldname']= $table.'.Flag';
        $param['where'][0]['operand']= '=';
        $param['where'][0]['is']= true;

        return $param;
    }
}