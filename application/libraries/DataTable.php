<?php
/**
 * 表格生成类
 * @author  liuweilong
 * +2016-03-03
 */
class DataTable
{
    /**
     * 表格标题
     * @var string
     */
    protected $_title = '';

    /**
     * 头数据
     * @var array
     */
    protected $_header = array();

    /**
     * 表格属性
     * @var array
     */
    protected $_attr = array();

    /**
     * 前置表头数据
     * @var array
     */
    protected $_beforeHeader = array();

    /**
     * 默认显示值
     * @var string
     */
    protected $_defaultValue = '';

    /**
     * 表格外部上的内容
     * @var string
     */
    protected $_topContent = '';

    /**
     * 表格外部下的内容
     * @var string
     */
    protected $_bottomContent = '';

    /**
     * 表脚内容
     * @var string
     */
    protected $_footer = '';

    /**
     * 表数据
     * @var array
     */
    protected $_data = array();

    /**
     * 当前页码
     * @var integer
     */
    protected $_pageCurrent = 1;

    /**
     * 当前数据长度
     * @var integer
     */
    protected $_pageSize = 30;

    /**
     * 生成排序
     * @param string $fieldName
     * @return string attr
     */
    protected function _orderField($fieldName)
    {
        return ' data-order-field="'.$fieldName.'" ';
    }

    /**
     * 标题设置
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * 表格属性设置
     * @param array $attr
     * @return $this
     */
    public function setAttr($attr = array())
    {
        $this->_attr = $attr;
        return $this;
    }

    /**
     * 设定计算器
     * @param integer $pageCurrent 
     * @param integer $pageSize
     * @return $this
     */
    public function setRowCounter($pageCurrent, $pageSize)
    {
        $this->_pageCurrent = $pageCurrent;
        $this->_pageSize = $pageSize;
        return $this;
    }

    /**
     * 前置表头数据设置
     * @param array $beforeHeader
     * @return $this
     */
    public function setBeforeHeader($beforeHeader)
    {
        $this->_beforeHeader = $beforeHeader;
        return $this;
    }

    /**
     * 设置表头
     * @param array $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->_header = $header;
        return $this;
    }

    /**
     * 设置默认值
     * @param string $val
     * @return $this
     */
    public function setDefaultValue($val)
    {
        $this->_defaultValue = $val;
        return $this;
    }

    /**
     * 设置上部内容
     * @param string $html
     * @return $this
     */
    public function setTopContent($html)
    {
        $this->_topContent = $html;
        return $this;
    }

    /**
     * 设置下部内容
     * @param string $html
     * @return $this
     */
    public function setBottomContent($html)
    {
        $this->_bottomContent = $html;
        return $this;
    }

    /**
     * 设置表脚
     * @param string $html
     * @return $this
     */
    public function setFooter($html)
    {
        $this->_footer = $html;
        return $this;
    }

    /**
     * 设置数据
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    // /**
    //  * 生成表格数据
    //  * @return string
    //  */
    // public function render($type = 'default', $args = array())
    // {
    //     switch($type)
    //     {
    //         case 'js':
    //             return $this->jsRender($args);
    //         break;

    //         case 'default':
    //         default:
    //             return $this->defaultRender($args);
    //         break;
    //     }
    // }

    // /**
    //  * 
    //  *
    //  */
    // public function jsRender()
    // {

    // }

    /**
     * 生成表格数据
     * @return string
     */
    public function render()
    {
        $html = '<table '.$this->_attrs($this->_attr).'>';

        $header = '<head><tr>';

        // create before header
        if (!empty($this->_beforeHeader) && is_array($this->_beforeHeader))
        {
            foreach($this->_beforeHeader as $key => $val)
            {
                $name = is_array($val) ? $val['name'] : $val;
                $header .= '<th'.$this->_attrs(isset($v['attrs']) ? $v['attrs'] : '').'>'.$name.'</th>';
            }
            $header .= '</tr><tr>';
        }

        // create header
        foreach ($this->_header as $key => $val)
        {
            if ($key == 'rowCounter')
            {
                $header .= '<th'.$this->_attrs(isset($v['attrs']) ? $v['attrs'] : '').'></th>';
            }
            else if ($key == 'checkboxs')
            {
                $header .= '<th'.$this->_attrs(isset($v['attrs']) ? $v['attrs'] : '').'><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>';
            }
            else
            {
                $name = is_array($val) ? $val['name'] : $val;
                $order = isset($val['order']) && $val['order'] == true ? $this->_orderField($key) : '';
                $header .= '<th'.$this->_attrs(isset($v['attrs']) ? $v['attrs'] : '').$order.'>'.$name.'</th>';
            }
        }
        $header .= '</tr></head>';

        $tbody = '<tbody>';
        // rows
        $row = 0;

        // create tbody content
        if (!empty($this->_data) && is_array($this->_data))
        {
            foreach ($this->_data as $key => $val)
            {
                $tbody .= '<tr'.$this->_attrs(isset($v['trAttrs']) ? $v['trAttrs'] : '').'>';
                foreach($this->_header as $k => $v)
                {
                    if ($k == 'rowCounter')
                    {
                        $tbody .= '<td'.$this->_attrs(isset($v['attrs']) ? $v['attrs'] : '').'>'.(($row + 1) + (($this->_pageCurrent - 1) * $this->_pageSize)) .'</td>';
                    }
                    else if ($k == 'checkboxs')
                    {
                        $tbody .= '<td'.$this->_attrs(isset($v['attrs']) ? $v['attrs'] : '').'><input type="checkbox" value="'.$val[$v['field']].'" name="ids" data-toggle="icheck" data-label=""></td>';
                    }
                    else
                    {
                        $tbody .= '<td '.$this->_attrs(isset($v['tdAttrs']) ? $v['tdAttrs'] : '').'>';
                        // get value
                        $value = isset($val[$k]) ? $val[$k] : (isset($v['defaultValue']) ? $v['defaultValue'] : $this->_defaultValue);
                        // exec callback
                        $value = isset($v['callback']) ? $v['callback']($row, $val, $k) : $value;
                        $tbody .= $value;
                        $tbody .= '</td>';
                    }
                }
                $tbody .= '</tr>';
                $row++;
            }
        }
        $tbody .= '</tbody>';

        // create footer
        $tfooter = !empty($this->_footer) ? '<tfoot>'.$this->_footer.'</tfoot>' : '';
        $html .= !empty($this->_title) ? '<caption>'.$this->_title.'</caption>' : '';
        $html .= $header;
        $html .= $tbody;
        $html .= $tfooter;
        $html .= '</table>';
        $html = $this->_topContent.$html.$this->_bottomContent;
        return $html;
    }

    /**
     * 生成属性
     * @param  mixed $data 
     * @return string
     */
    protected function _attrs($data)
    {
        if (empty($data))
        {
            return $data;
        }

        if (is_array($data))
        {
            $attrs = array();
            foreach ($data as $key => $val)
            {
                $attrs[] = "{$key} = \"{$val}\"";
            }
            return join(' ', $attrs);
        }
        return $data;
    }
}



