<?php
/**
 * 表单生成器
 * @author  liuweilong
 * +2016-03-04 created
 *
 *
 *
 *
 *
 *
 *
 *常用MIME类型
 *参考文件格式对应头
 *.3gpp audio/3gpp, video/3gpp  3GPP Audio/Video
*.ac3   audio/ac3   AC3 Audio
*.asf   allpication/vnd.ms-asf  Advanced Streaming Format
*.au    audio/basic AU Audio
*.css   text/css    Cascading Style Sheets
*.csv   text/csv    Comma Separated Values
*.doc   application/msword  MS Word Document
*.dot   application/msword  MS Word Template
*.dtd   application/xml-dtd Document Type Definition
*.dwg   image/vnd.dwg   AutoCAD Drawing Database
*.dxf   image/vnd.dxf   AutoCAD Drawing Interchange Format
*.gif   image/gif   Graphic Interchange Format
*.htm   text/html   HyperText Markup Language
*.html  text/html   HyperText Markup Language
*.jp2   image/jp2   JPEG-2000
*.jpe   image/jpeg  JPEG
*.jpeg  image/jpeg  JPEG
*.jpg   image/jpeg  JPEG
*.js    text/javascript, application/javascript JavaScript
*.json  application/json    JavaScript Object Notation
*.mp2   audio/mpeg, video/mpeg  MPEG Audio/Video Stream, Layer II
*.mp3   audio/mpeg  MPEG Audio Stream, Layer III
*.mp4   audio/mp4, video/mp4    MPEG-4 Audio/Video
*.mpeg  video/mpeg  MPEG Video Stream, Layer II
*.mpg   video/mpeg  MPEG Video Stream, Layer II
*.mpp   application/vnd.ms-project  MS Project Project
*.ogg   application/ogg, audio/ogg  Ogg Vorbis
*.pdf   application/pdf Portable Document Format
*.png   image/png   Portable Network Graphics
*.pot   application/vnd.ms-powerpoint   MS PowerPoint Template
*.pps   application/vnd.ms-powerpoint   MS PowerPoint Slideshow
*.ppt   application/vnd.ms-powerpoint   MS PowerPoint Presentation
*.rtf   application/rtf, text/rtf   Rich Text Format
*.svf   image/vnd.svf   Simple Vector Format
*.tif   image/tiff  Tagged Image Format File
*.tiff  image/tiff  Tagged Image Format File
*.txt   text/plain  Plain Text
*.wdb   application/vnd.ms-works    MS Works Database
*.wps   application/vnd.ms-works    Works Text Document
*.xhtml application/xhtml+xml   Extensible HyperText Markup Language
*.xlc   application/vnd.ms-excel    MS Excel Chart
*.xlm   application/vnd.ms-excel    MS Excel Macro
*.xls   application/vnd.ms-excel    MS Excel Spreadsheet
*.xlt   application/vnd.ms-excel    MS Excel Template
*.xlw   application/vnd.ms-excel    MS Excel Workspace
*.xml   text/xml, application/xml   Extensible Markup Language
*.zip   aplication/zip  Compressed Archive
 */
class FormHelper
{
    protected $_formBodyConfig = array();

    protected $_formHeaderConfig = array();

    protected $_method = array(
        'post',
        'get',
    );

    protected $_enctype = array(
        'application/x-www-form-urlencoded',
        'multipart/form-data',
        'text/plain',
    );

    protected $_list = array();

    /**
     * 表单创建
     * @param  [type] $formHeaderConfig [description]
     * @param  [type] $formBodyConfig   [description]
     * @return object self
     */
    public function formCreater($formHeaderConfig, $formBodyConfig)
    {   
        $hs = array();
        $hs['type'] = 'form';
        $hs['attr'] = $formHeaderConfig;
        $this->_formHeaderConfig = $hs;
        $this->_formBodyConfig = $formBodyConfig;
        return $this;
    }

    public function form($attr)
    {
        return '<form '.$this->_attrs($attr).'>';
    }

    public function select($key, $data, $value, $attr)
    {
        // $value = 1;
        $select = '<select name="'.$key.'" '.$this->_attrs($attr).' data-toggle="selectpicker">';
        foreach ($data as $k => $v)
        {
            $checked = $value == $k ? ' selected = "selected"' : '';
            $select .= '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public function show($key, $value, $attr)
    {
        return '<span '.$this->_attrs($attr).'>'.$value.'</span>';
    }

    public function date($key, $value, $attr)
    {
        return '<input data-toggle="datepicker" type="text" name="'.$key.'" value="'.$value.'" '.$this->_attrs($attr).'>';
    }

    public function hidden($key, $value, $attr)
    {
        return '<input type="hidden" name="'.$key.'" value="'.$value.'" '.$this->_attrs($attr).'>';
    }

    public function text($key, $value, $attr)
    {
        return '<input type="text" name="'.$key.'" value="'.$value.'" '.$this->_attrs($attr).'>';
    }

    public function password($key, $value, $attr)
    {
        return '<input type="password" name="'.$key.'" value="'.$value.'" '.$this->_attrs($attr).'>';
    }

    public function textarea($key, $value, $attr)
    {
        return '<textarea name="'.$key.'" '.$this->_attrs($attr).'>'.$value.'</textarea>';
    }

    // multiple 多个上传 single 单个上传
    public function file($key, $value, $attr)
    {
        return '<input type="file" name="'.$key.'" '. $this->_attrs($attr).'>';
    }

    public function imgfile($key, $value, $attr)
    {
        return '<div style="display: inline-block; vertical-align: middle;">
                            <div id="j_custom_pic_up" data-toggle="upload" '. $this->_attrs($attr).'
                                data-file-size-limit="1024000000"
                                data-multi="true"
                                data-on-upload-success="pic_upload_success"
                                data-icon="cloud-upload"></div>
                            <input type="hidden" name="'.$key.'"  id="j_custom_pic">
                            <span id="j_custom_span_pic"></span>
                        </div>';
    }

    public function mutiCheckbox($key, $data, $value, $attr)
    {
        $radio = '';
        $value = (array) $value;
        foreach ($data as $k => $v)
        {
            $checked = in_array($k, $value) ? ' checked = "checked"' : '';
            $radio .= '<input type="checkbox" name="'.$key.'[]" value="'.$k.'" '.$this->_attrs($attr).' '.$checked.'/><label>'.$v.'</label>&nbsp;';
        }
        return $radio;
    }

    public function checkbox($key, $data, $value, $attr)
    {
        $radio = '';
        foreach ($data as $k => $v)
        {
            $checked = $value == $k ? ' checked = "checked"' : '';
            $radio .= '<input type="checkbox" name="'.$key.'" value="'.$k.'" '.$this->_attrs($attr).' '.$checked.'/>'.$v.'&nbsp;';
        }
        return $radio;
    }

    public function button($key, $label, $attr)
    {
        return '<button name="'.$key.'" '.$this->_attrs($attr).'>'.$label.'</button>';
    }

    public function radio($key, $data, $value, $attr)
    {
        $radio = '';
        foreach($data as $k => $v)
        {
            $checked = $value == $k ? ' checked = "checked"' : '';
            $radio .= '<input type="radio" name="'.$key.'" value="'.$k.'" '.$this->_attrs($attr).' '.$checked.'/>'.$v.'&nbsp;';
        }
        return $radio;
    }
    
    public function addr($key, $data, $value, $attr)
    {
        $d = array();
        get_instance()->load->Model("default/OtCityInfoModel", "OtCityInfoModel");

        if (!empty($value))
        {
            $d = array();
            $d[] = get_instance()->OtCityInfoModel->getRow('city_id='.$value, 'city_id, city_name, parent_city_id');
            if (!empty($d[0]) && $d[0]['parent_city_id'] != 0)
            {
                $d[] = get_instance()->OtCityInfoModel->getRow('city_id='.$d[0]['parent_city_id'], 'city_id, city_name, parent_city_id');
            }

            if (!empty($d[1]) && $d[1]['parent_city_id'] != 0)
            {
                $d[] = get_instance()->OtCityInfoModel->getRow('city_id='.$d[1]['parent_city_id'], 'city_id, city_name, parent_city_id');
            }

            $d = array_reverse($d);
        }
        
        $cityDataValue = !empty($d) && !empty($d[1]) ? 'data-val="'.$d[1]['city_id'].'"' : '';
        $areaDataVal = !empty($d) && !empty($d[2]) ? 'data-val="'.$d[2]['city_id'].'"' : '';
        $ctData = get_instance()->OtCityInfoModel->getList('parent_city_id=0', 'city_id, city_name');
        $ctString = array();
        foreach ($ctData as $k => $v)
        {
            $selected = '';
            if (!empty($d) && !empty($d[0]) && $d[0]['city_id'] == $v['city_id'])
            {
                $selected = ' selected = "selected" ';

            }
            $ctString[] = '<option value="'.$v['city_id'].'" '.$selected.'>'.$v['city_name'].'</option>';
        }
        $ctString = join("\n", $ctString);
        $html = '
        <select name="'.$key.'_province" data-toggle="selectpicker" data-nextselect="#j_form_city" data-refurl="/common/addr?pid={value}">
            <option value="all">--省市--</option>
            '.$ctString.'
        </select>
        <select '.$cityDataValue.' name="'.$key.'_city" id="j_form_city" data-toggle="selectpicker" data-nextselect="#j_form_area" data-refurl="/common/addr?pid={value}" data-emptytxt="--城市--">
            <option value="all">--城市--</option>
        </select>
        <select  '.$areaDataVal.' name="'.$key.'_area" id="j_form_area" data-toggle="selectpicker" data-emptytxt="--区县--">
            <option value="all">--区县--</option>
        </select>';
        return $html;
    }

    /**
     * 生成元素
     * @param  array $data [description]
     * @return string
     */
    protected function _element($data)
    {
        $c = '';
        $data['attr'] = isset($data['attr']) ? $data['attr'] : '';
        switch ($data['type'])
        {
            case 'text':
                $c = $this->text($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'password':
                $c = $this->password($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'textarea':
                $c = $this->textarea($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'file':
                $c = $this->file($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'imgfile':
                $c = $this->imgfile($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'checkbox':
                $c = $this->checkbox($data['key'], $data['data'], $data['defaultValue'], $data['attr']);
                break;
            case 'muticheckbox':
                $c = $this->mutiCheckbox($data['key'], $data['data'], $data['defaultValue'], $data['attr']);
                break;
            case 'radio':
                $c = $this->radio($data['key'], $data['data'], $data['defaultValue'], $data['attr']);
                break;
            case 'select':
                $c = $this->select($data['key'], $data['data'], $data['defaultValue'], $data['attr']);
                break;
            case 'date':
                $c = $this->date($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'button':
                $c = $this->button($data['key'], $data['label'], $data['attr']);
                break;
            case 'submit':
                $data['attr'] = is_array($data['attr']) ? $data['attr'] + array('type' => 'submit') : $data['attr'].' type="submit"';
                $c = $this->button($data['key'], $data['label'], $data['attr']);
                break;
            case 'form':
                $c = $this->form($data['attr']);
                break;
            case 'hidden':
                $c = $this->hidden($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'editor':
                $c = $this->form($data['attr']);
                break;
            case 'counter':
                $c = $this->form($data['attr']);
                break;
            case 'block':
                $c = $data['content'];
                break;
            case 'show':
                $c = $this->show($data['key'], $data['defaultValue'], $data['attr']);
                break;
            case 'addr':
                $c = $this->addr($data['key'], $data['data'], $data['defaultValue'], $data['attr']);
                break;
        }
        return $c;
    }

    public function render($layoutType = 'table', $args = array())
    {
        switch (strtolower($layoutType))
        {
            case 'formsearch':
                return $this->_formSearchRender($args);
                break;
            case 'table':
                return $this->_formTableRender($args);
                break;
            case 'tablelayout':
                return $this->_formTableLayoutRender($args);
                break;
            case 'tablemuti':
                return $this->_formTableMutiRender($args);
                break;
            case 'array':
                return $this->_formArrayRender($args);
                break;
        }
    }

    /**
     * 合并数据(修改时调用)
     * @param  array $configData [description]
     * @param  array $data       [description]
     * @return array
     */
    public function mergeData($configData, $data)
    {
        foreach ($configData as $k => $v)
        {
            if (isset($v['key']) && isset($data[$v['key']]))
            {
                $configData[$k]['defaultValue'] = $data[$v['key']];
            }
        }
        return $configData;
    }

    protected function _formTableRender($args)
    {
        $form ='<div class="pageFormContent"><fieldset><legend>'.(isset($args['title']) ? $args['title'] : '填写表单').'</legend>';
        $form .= $this->_element($this->_formHeaderConfig);
        $form .= '<table '.$this->_attrs(isset($args['attr']) ? $args['attr'] : array('class' => 'table table-condensed table-hover')).'>';
        foreach($this->_sort($this->_formBodyConfig) as $key => $val)
        {
            if ($val['type'] != 'hidden' && $val['type'] != 'block')
            {
                $form .= '<tr>';
                $form .= $val['type'] != 'button' &&  $val['type'] != 'submit' ? '<td align="right">'.$this->label($val['key'].':', $val['label']).'</td><td>'.$this->_element($val).'</td>' : '<td colspan="2" align="center">'.$this->_element($val).'</td>';
                $form .= '</tr>';
            }
            else
            {
                $form .= $this->_element($val);
            }
        }
        $form .= '</table></form></fieldset></div>';
        return $form;
    }

    protected function _formTableMutiRender($args)
    {
        $form = $this->_element($this->_formHeaderConfig);
        $form .='<div class="bjui-pageContent">';
        $form .= '<table '.$this->_attrs(isset($args['attr']) ? $args['attr'] : array('class' => 'table table-condensed table-hover')).'>';
        $muti = isset($args['size']) ? $args['size'] : 2;
        $trEnd = 0;
        $form .= '<tbody><tr><td colspan="'.($muti * 2).'" align="center"><h3>'.(isset($args['title']) ? $args['title'] : '填写表单').'</h3></td></tr>';
        foreach($this->_sort($this->_formBodyConfig) as $key => $val)
        {
            if ( (($key + 1) % $muti) == 1)
            {
                $trEnd++;
                $form .= '<tr>';
            }

            if ($val['type'] != 'hidden'  && $val['type'] != 'block')
            {
                // $form .= '<tr>';
                $form .= $val['type'] != 'button' ? '<td align="right">'.$this->label($val['key'].':', $val['label']).'</td><td>'.$this->_element($val).'</td>' : '<td colspan="2">'.$this->_element($val).'</td>';
                // $form .= '</tr>';
            }
            else
            {
                $form .= $this->_element($val);
            }

            if ( (($key + 1) % $muti) == 0)
            {
                $trEnd--;
                $form .= '</tr>';
            }
        }

        if ( $trEnd > 0)
        {
            $form .= '</tr>';
        }

        $form .= '</tbody></table></div>
        <div class="bjui-pageFooter">
            <ul>
                <li><button type="button" class="btn-close">关闭</button></li>
                <li><button type="submit" class="btn-default">保存</button></li>
            </ul>
        </div></form>
        ';
        return $form;
    }

    protected function _formTableLayoutRender($args)
    {
        $form ='<div class="pageFormContent clearfix">';
        $form = $this->_element($this->_formHeaderConfig);
        $form .= '<table '.$this->_attrs(isset($args['attr']) ? $args['attr'] : array('class' => 'table table-condensed table-hover')).'>';
        $formBodyConfig = $this->_sort($this->_formBodyConfig);
        $counter = 0;
        foreach ($args['layout'] as $row)
        {
            $form .= '<tr>';
            foreach($row as $v)
            {
                if(!isset($formBodyConfig[$counter]))
                {
                    continue;
                }

                if ($formBodyConfig[$counter]['type'] == 'hidden')
                {
                    $form .= $this->_element($formBodyConfig[$counter]);
                    $counter++;
                }

                $colspan = isset($v['size']) ? 'colspan="'.$v['size'].'"' : '';
                $form .= '<td '.$colspan.'>'.($formBodyConfig[$counter]['type'] != 'button' ? $this->label($formBodyConfig[$counter]['key'], $formBodyConfig[$counter]['label'].':', array('class' => 'control-label x85')) : '').$this->_element($formBodyConfig[$counter]).'</td>';
                $counter++;
            }
            $form .= '</tr>';
        }
        $form .= '</table></form></div>';
        return $form;
    }

    protected function _formSearchRender($args)
    {  
        $form = '<div class="bjui-pageHeader">'. (isset($args['topButton']) ? $args['topButton'] : '');
        $form .= $this->_element($this->_formHeaderConfig);
        foreach($this->_sort($this->_formBodyConfig) as $key => $val)
        {
            if ($val['type'] != 'hidden' && $val['type'] != 'block')
            {
                $form .= ($val['type'] != 'button' ? $this->label($val['key'], $val['label']).':' : '').$this->_element($val).'&nbsp;';
            }
            else
            {
                $form .= $this->_element($val);
            }
        }
        $form .= '</form></div>';
        return $form;
    }

    protected function _formArrayRender($args)
    {   
        $form = array();
        $form[]= $this->_element($this->_formHeaderConfig);
        foreach($this->_sort($this->_formBodyConfig) as $key => $val)
        {
            if ($val['type'] != 'hidden' && $val['type'] != 'block')
            {
                $form[]= ($val['type'] != 'button' ? $this->label($val['key'], $val['label']).':' : '').$this->_element($val).'&nbsp;';
            }
            else
            {
                $form[]= $this->_element($val);
            }
        }
        $form[]= '</form>';
        return $form;
    }
    
    public function label($key, $label, $attr = array('class' => 'label-control'))
    {
        return '<label for="'.$key.'" '.$this->_attrs($attr).'>'.$label.'</label>';
    }

    protected $_js = array();

    protected $_css = array();

    public function loadJs($jsPath)
    {
        $this->_js[] = $jsPath;
    }

    public function loadCss($cssPath)
    {
        $this->_css[] = $cssPath;
    }

    public function renderJsAndCss()
    {
        $output = array();
        foreach($this->_js as $val)
        {
            if(is_array($val))
            {
                $output[] = count($val) == 1 ? current($val) : '';
            }
            else
            {
                $output[] = '<script src="'.$val.'"></script>';
            }
        }

        foreach($this->_css as $val)
        {
            if(is_array($val))
            {
                $output[] = count($val) == 1 ? current($val) : '';
            }
            else
            {
                $output[] = '<link href="'.$val.'" rel="stylesheet">';
            }
        }
        return join('', $output);
    }

    public function resetJsAndCss()
    {
        $this->_js = array();
        $this->_css = array();
    }
    
    protected function _sort($formBodyConfig)
    {
        $orders = array();
        foreach ($formBodyConfig as $key => $val)
        {
            $orders[] = isset($val['order']) ? $val['order'] : 0;
        }
        array_multisort($orders, $formBodyConfig, SORT_NUMERIC, SORT_ASC);
        return $formBodyConfig;
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
            return '';
        }

        if (is_array($data))
        {
            $attrs = [];
            foreach ($data as $key => $val)
            {
                $attrs[] = "{$key} = \"{$val}\"";
            }
            return join(' ', $attrs);
        }
        return $data;
    }

    /**
     * 取地址ID
     * @param  array $data [description]
     * @param  string $key  [description]
     * @return integer
     */
    public function getAddrId(&$data, $key)
    {   
        $id = 0;
        if (isset($data[$key.'_area']) && is_numeric($data[$key.'_area']))
        {
            $id = $data[$key.'_area'];
        }
        elseif (isset($data[$key.'_city']) && is_numeric($data[$key.'_city']))
        {
            $id = $data[$key.'_city'];
        }
        elseif (isset($data[$key.'_province']) && is_numeric($data[$key.'_province']))
        {
            $id = $data[$key.'_province'];
        }
        unset($data[$key.'_province']);
        unset($data[$key.'_city']);
        unset($data[$key.'_area']);
        return (int) $id;
    }
}

// $formHeaderConfig = array('method' => 'POST', 'action' => '');
// $formBodyConfig = array(
//     array('type' => 'radio', 'key' => 'sex', 'label' => '性别', 'data' => array(0, 1) ,'defaultValue' => 1, 'order' => 11, 'valid' => array()),
//     array('type' => 'text', 'key' => 'name', 'label' => '姓名', 'data' => null ,'defaultValue' => 1, 'order' => 21),
//     array('type' => 'checkbox', 'key' => 'staffs', 'label' => '员工选择', 'data' => array('张三'=>10, '李四' => 20, '王五' => 30) ,'defaultValue' => 10, 'order' => 31),
//     array('type' => 'select', 'key' => 'years', 'label' => '年份选择', 'data' => array('1990年' => 1990, '1991年' => 1991) ,'defaultValue' => 1990, 'order' => 41),
//     array('type' => 'file', 'key' => 'file', 'label' => '文件上传', 'defaultValue' => 'xxx.png', 'order' => 51),
//     array('type' => 'textarea', 'key' => 'content', 'label' => '内容填写', 'data' => null ,'defaultValue' => '哇哈哈', 'order' => 61),
//     array('type' => 'hidden', 'key' => 'hidden', 'label' => null, 'data' => null ,'defaultValue' => '1000', 'order' => 71),
//     array('type' => 'date', 'key' => 'date', 'label' => '日期', 'data' => null ,'defaultValue' => '2015/06/30', 'order' => 81),
//     array('type' => 'button', 'key' => 'submit', 'label' => '提交', 'order' => 999),
// );

// $fh = new FormHelper;
// $fh->formCreater($formHeaderConfig, $formBodyConfig)->render();