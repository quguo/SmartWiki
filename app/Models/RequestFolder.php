<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/2/14 0014
 * Time: 13:15
 */

namespace SmartWiki\Models;

use DB;
/**
 * 接口分类表
 * @property int $classify_id
 * @property int $member_id
 * @property int $classify_name
 * @property string $description
 * @property int $classify_sort
 * @property int $parent_id
 * @property int $api_count
 * Class ApiClassify
 * @package SmartWiki\Models
 */
class RequestFolder extends ModelBase
{
    protected $table = 'request_folder';
    protected $primaryKey = 'classify_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['classify_id'];

    public $timestamps = false;

    /**
     * 获取指定用户的接口分类列表
     * @param $memberId
     * @param int $parentId
     * @return array|static[]
     */
    public static function getApiClassifyList($memberId, $parentId = 0)
    {
        $result = DB::table('request_folder as classify')
            ->select(['classify.*','share.member_id as uid','share.role'])
            ->leftJoin('request_share as share','share.classify_id','=','classify.classify_id')
            ->where('share.member_id','=',$memberId)
            ->where('classify.parent_id','=',$parentId)
            ->orderBy('classify.classify_sort','DESC')
            ->get();

        return $result;
    }

    /**
     * 获取指定用户所有的分类
     * @param $memberId
     * @return array|static[]
     */

    public static function getApiClassifyAllList($memberId)
    {
        $result = DB::table('request_folder as classify')
            ->select(['classify.*','share.member_id as uid','share.role'])
            ->leftJoin('request_share as share','share.classify_id','=','classify.classify_id')
            ->where('share.member_id','=',$memberId)
            ->orderBy('classify.classify_sort','DESC')
            ->get();

        return $result;
    }


    /**
     * 判断是否存在编辑权限
     * @param $member_id
     * @param $classify_id
     * @return bool
     */
    public static function isHasEditRole($member_id,$classify_id)
    {
        return RequestShare::where('member_id','=',$member_id)
            ->where('classify_id','=',$classify_id)
            ->exists();
    }
}