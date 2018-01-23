<?php
/**
 * 分页类
 * @author  liuweilong
 * +2016-03-04 created
 **/
class BjuiPager
{
    public static function get($currPage, $total, $size)
    {
        $html ='
        <div class="bjui-pageFooter">
            <div class="pages">
                <span>每页&nbsp;</span>
                <div class="selectPagesize">
                    <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                        <option value="30">30</option>
                        <option value="60">60</option>
                        <option value="120">120</option>
                        <option value="150">150</option>
                    </select>
                </div>
                <span>&nbsp;条，共 '.$total.' 条</span>
            </div>
            <div class="pagination-box" data-toggle="pagination" data-total="'.$total.'" data-page-size="'.$size.'" data-page-current="'.$currPage.'">
            </div>
        </div>';
        return $html;
    }
}