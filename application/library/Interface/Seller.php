<?php

/**
 * 销售接口
 *
 * @author jyh 2016-05-20
 */
class Interface_Seller extends CallInterface
{

    /**
     * 获取销售人员信息
     */
    public function getSellers()
    {
        $list = array();
        $send_data = array(
            'type' => 'getAdminList',
        );
        $this->setUrl('user_url', 'ht');
        $data = $this->run($send_data);
        foreach ($data['list'] as $val) {
            $list[$val['id']] = $val['realname'];
        }
        return $list;
    }

    /**
     * 获取指定销售id下所有的子销售id
     * @param int $seller_id 指定的销售id
     * @return mixed false:该指定id不是销售id array:指定的销售id下的子销售id集合
     */
    public function getSellerIds($seller_id)
    {
        $send_data = array(
            'type' => 'getSaleList',
        );
        $this->setUrl('user_url', 'ht');
        $data = $this->run($send_data);
        $isSeller = $this->checkIsSeller($seller_id, $data);
        if (!$isSeller) {
            return false;
        }
        $seller_ids = array();
        if (!empty($data)) {
            $seller_ids = $this->getSellerIdsRecursive($data, $seller_id);
        }
        return $seller_ids;
    }

    /**
     * 检测该id是否是一个销售账号id
     * @param int $seller_id 指定的销售id
     * @param array 销售列表
     * @return boolean true|false
     */
    protected function checkIsSeller($seller_id, $list = array())
    {
        $is_seller = false;
        if(!empty($list)) {
        foreach ($list as $item) {
            if ($seller_id == $item['id']) {
                $is_seller = true;
                break;
            }
        }}
        return $is_seller;
    }

    /**
     * 递归的调用获取子销售id
     * @param array $list 销售列表
     * @param int $parent_id 父销售id
     * @param array $seller_ids 子销售id集合
     * @return array 子销售id集合
     */
    protected function getSellerIdsRecursive($list, $parent_id, $seller_ids = array())
    {
        foreach ($list as $item) {
            if ($item['parent_id'] == $parent_id) {
                $seller_ids[] = $item['id'];
                $seller_ids = $this->getSellerIdsRecursive($list, $item['id'], $seller_ids);
            }
        }
        return $seller_ids;
    }

}
