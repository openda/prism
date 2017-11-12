<?php
/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/29
 * Time: 下午10:02
 * Desc:
 */

namespace app\prism\controller;


use app\common\AppCode;
use app\common\Functions;
use app\prism\BaseController;
use prism\Model;
use prism\Session;

class Report extends BaseController {
    const REPORT_STATUS_VALID   = 1;
    const REPORT_STATUS_INVALID = 2;

    /**
     * @param $db_link_id
     * @param $report_type
     * @param $report_info
     *
     * @return array
     * @desc 添加一个报表
     */
    public function addReport($db_link_id, $chart_type, $chart_info, $data_options, $report_brief) {
        $now      = date("Y-m-d H:i:s");
        $userInfo = Session::get('user_info');

        $Report = Model::load('sqlite')->table('report');

        $data['report_id']    = Functions::GenIDS(2, $chart_type);
        $data['db_linkid']    = $db_link_id;
        $data['user_id']      = $userInfo['user_id'];
        $data['report_type']  = $chart_type;
        $data['report_info']  = $chart_info;
        $data['data_options'] = $data_options;
        $data['create_time']  = $now;
        $data['update_time']  = $now;
        $data['status']       = 1;
//        $data['share_link']   = $share_link;
        $data['report_brief'] = $report_brief;

        $Report->save($data);

        return $this->result;
    }

    /**
     * @param $report_id
     * @param $report_type
     * @param $report_info
     * @param $report_brief
     * @param $share_link
     *
     * @return array|int
     * @desc 更新一个报表
     */
    public function updateReport($report_id, $report_type, $report_info, $report_brief, $share_link) {
        $Report     = Model::load('sqlite')->table('report');
        $reportInfo = $Report->where('report_id = :report_id,status = :status',
            array(':report_id' => trim($report_id), ':status' => self::REPORT_STATUS_VALID))->select();
        //查询该报表是否已经存在
        if ($reportInfo == null) {
            return AppCode::ERR_REPORT_INEXISTED;
        }
        if (!empty($report_type)) {
            $data['report_type'] = [":report_type", $report_type];
        }
        if (!empty($report_info)) {
            $data['report_info'] = [":report_info", $report_info];
        }
        if (!empty($report_brief)) {
            $data['report_brief'] = [":report_brief", $report_brief];
        }
        if (!empty($share_link)) {
            $data['share_link'] = [":share_link", $share_link];
        }
        $data['update_time'] = [":update_time", date("Y-m-d H:i:s")];

        if (!$Report->where('report_id = :report_id', array(":report_id" => $report_id))->update($data)) {
            return AppCode::ERR_UPDATE_USER;
        }

        return $this->result;
    }

    /**
     * @param $report_id
     *
     * @return array|int
     * @desc 删除一个报表
     */
    public function deleteReport($report_id) {
        $Report     = Model::load('sqlite')->table('report');
        $reportInfo = $Report->where('report_id = :report_id,status = :status',
            array(':report_id' => trim($report_id), ':status' => self::REPORT_STATUS_VALID))->select();
        //查询该报表是否已经存在
        if ($reportInfo == null) {
            return AppCode::ERR_REPORT_INEXISTED;
        }
        if (!empty($report_type)) {
            $data['status'] = [":status", self::REPORT_STATUS_INVALID];
        }
        $data['update_time'] = [":update_time", date("Y-m-d H:i:s")];

        if (!$Report->where('report_id = :report_id', array(":report_id" => $report_id))->update($data)) {
            return AppCode::ERR_DELETE_REPORT;
        }

        return $this->result;
    }
}