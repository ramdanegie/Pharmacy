<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 18/02/2019
 * Time: 20.28
 */

namespace App\Model\Master;

use Illuminate\Database\Eloquent\Model;

class M_Pegawai extends Model
{
	protected $table = 'M_Pegawai';
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'KdPegawai';
//	protected $fillable = [
//		'id',
//		'kdprofile',
//		'agama',
//		'reportdisplay',
//		'kodeexternal',
//		'namaexternal',
//		'statusenabled',
//		'norec'
//	];
	public static function queryTable($request){
		$table = 'M_Pegawai';
		$param['table_from']= $table;
		$param['select']= array($table.'.*');
		$param['label'] = array();

		$param['where'][0]['fieldname']= $table.'.statusenabled';
		$param['where'][0]['operand']= '=';
		$param['where'][0]['is']= true;

		return $param;
	}
}