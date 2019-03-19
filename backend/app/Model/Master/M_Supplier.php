<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 23/02/2019
 * Time: 10.31
 */
namespace App\Model\Master;

use Illuminate\Database\Eloquent\Model;

class M_Supplier extends Model
{
	protected $table = 'M_Supplier';
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'KdSupplier';
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
		$table = 'supplier_m';
		$param['table_from'] = $table;
		$param['select'] = array($table . '.*');
		$param['label'] = array();

		$param['where'][0]['fieldname'] = $table . '.statusenabled';
		$param['where'][0]['operand'] = '=';
		$param['where'][0]['is'] = true;

		return $param;
	}
}
