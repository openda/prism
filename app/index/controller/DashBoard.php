<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/29
 * Time: 下午10:02
 * Desc:
 */

namespace app\index\controller;


use app\common\AppCode;
use app\common\Functions;
use app\index\BaseController;
use prism\Model;

class DashBoard extends BaseController {
    const DASHBOARD_STATUS_VALID   = 1;
    const DASHBOARD_STATUS_INVALID = 2;

    /**
     * @param $db_link_id
     * @param $dash_type
     * @param $dash_info
     *
     * @return array
     * @desc 添加一个报表
     */
    public function addDashBoard($dash_name, $dash_info, $report_ids, $dash_brief, $share_link) {
        $now = date("Y-m-d H:i:s");

        $Report = Model::load('sqlite')->table('dashboard');

        $data['dash_id']     = Functions::GenIDS(4);
        $data['dash_name']   = $dash_name;
        $data['user_id']     = '';
        $data['report_ids']  = $report_ids;
        $data['dash_info']   = $dash_info;
        $data['create_time'] = $now;
        $data['update_time'] = $now;
        $data['status']      = 1;
        $data['share_link']  = $share_link;
        $data['dash_brief']  = $dash_brief;

        $Report->save($data);

        return $this->result;
    }

    /**
     * @param $dash_id
     * @param $dash_type
     * @param $dash_info
     * @param $dash_brief
     * @param $share_link
     *
     * @return array|int
     * @desc 更新一个报表
     */
    public function updateReport($dash_id, $dash_name, $dash_info, $report_ids, $dash_brief, $share_link) {
        $Report     = Model::load('sqlite')->table('dashboard');
        $dashInfo = $Report->where('dash_id = :dash_id,status = :status',
            array(':dash_id' => trim($dash_id), ':status' => self::DASHBOARD_STATUS_VALID))->select();
        //查询该报表是否已经存在
        if ($dashInfo == null) {
            return AppCode::ERR_DASHBOARD_INEXISTED;
        }
        if (!empty($dash_name)) {
            $data['dash_name'] = [":dash_name", $dash_name];
        }
        if (!empty($dash_info)) {
            $data['dash_info'] = [":dash_info", $dash_info];
        }
        if (!empty($dash_brief)) {
            $data['dash_brief'] = [":dash_brief", $dash_brief];
        }
        if (!empty($share_link)) {
            $data['share_link'] = [":share_link", $share_link];
        }
        if (!empty($report_ids)) {
            $data['report_ids'] = [":report_ids", $report_ids];
        }
        $data['update_time'] = [":update_time", date("Y-m-d H:i:s")];

        if (!$Report->where('dash_id = :dash_id', array(":dash_id" => $dash_id))->update($data)) {
            return AppCode::ERR_UPDATE_DASHBOARD;
        }

        return $this->result;
    }

    /**
     * @param $dash_id
     *
     * @return array|int
     * @desc 删除一个报表
     */
    public function deleteReport($dash_id) {
        $Report     = Model::load('sqlite')->table('dashboard');
        $dashInfo = $Report->where('dash_id = :dash_id,status = :status',
            array(':dash_id' => trim($dash_id), ':status' => self::DASHBOARD_STATUS_VALID))->select();
        //查询该报表是否已经存在
        if ($dashInfo == null) {
            return AppCode::ERR_DASHBOARD_INEXISTED;
        }
        if (!empty($dash_type)) {
            $data['status'] = [":status", self::DASHBOARD_STATUS_INVALID];
        }
        $data['update_time'] = [":update_time", date("Y-m-d H:i:s")];

        if (!$Report->where('dash_id = :dash_id', array(":dash_id" => $dash_id))->update($data)) {
            return AppCode::ERR_DELETE_DASHBOARD;
        }

        return $this->result;
    }
}