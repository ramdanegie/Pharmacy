<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 16/02/2019
 * Time: 03.23
 */

namespace App\Model\Standar;

use Illuminate\Database\Eloquent\Model;

class M_LoginUser extends Model
{
	protected $table = 'M_LoginUser';
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'KdUser';
//	protected $fillable = [
//
//	];

//	public static function queryTable($request, $KdProfile)
//	{
//		$table = 'loginuser_s';
//		$param['table_from'] = $table;
//		$param['select'] = array($table . '.*');
//		$param['label'] = array();
//
//		$param['where'][0]['fieldname'] = $table . '.statusenabled';
//		$param['where'][0]['operand'] = '=';
//		$param['where'][0]['is'] = true;
//		return $param;
//	}
}