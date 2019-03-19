<?php
/**
 * Created by IntelliJ IDEA.
 * User: Egie Ramdan
 * Date: 16/02/2019
 * Time: 15.45
 */
namespace App\Model\Standar;

use Illuminate\Database\Eloquent\Model;

class M_KelompokUser extends Model
{
	protected $table = 'M_KelompokUser';
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'KdKelompokUser';
//	protected $fillable = [
//
//	];

	public static function queryTable($request, $KdProfile)
	{
		$table = 'M_KelompokUser';
		$param['table_from'] = $table;
		$param['select'] = array($table . '.*');
		$param['label'] = array();

		$param['where'][0]['fieldname'] = $table . '.Flag';
		$param['where'][0]['operand'] = '=';
		$param['where'][0]['is'] = true;
		return $param;
	}
}
