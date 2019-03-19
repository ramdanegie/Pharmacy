<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 23/02/2019
 * Time: 19.40
 */
namespace App\Model\Master;

use Illuminate\Database\Eloquent\Model;

class M_MapingSatuan extends Model
{
	protected $table = 'M_MapingSatuan';
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'Kode';
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
	public static function queryTable($request)
	{
		$table = 'M_MapingSatuan';
		$param['table_from'] = $table;
		$param['select'] = array($table . '.*');
		$param['label'] = array();

		$param['where'][0]['fieldname'] = $table . '.statusenabled';
		$param['where'][0]['operand'] = '=';
		$param['where'][0]['is'] = true;

		return $param;
	}
}
