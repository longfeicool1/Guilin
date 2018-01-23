<?php
/**
 * 模型生成器
 * @author  liuweilong
 * +2016-03-18 created
 **/
class ModelCreater
{   

    protected $_model = '';

    protected $_base = 'MY_Model';

    protected $_counter = 0;

    protected $_tableCounter = 0;

    protected $_makeFilePath = array();

    /**
     * 取出db名字
     * @return [type] [description]
     */
    public function getDBList()
    {
        require APPPATH.'config'.DIRECTORY_SEPARATOR.ENVIRONMENT.DIRECTORY_SEPARATOR.'database.php';
        return array_keys($db);
    }

    /**
     * 运行
     * @param  string $dbName [description]
     * @return string
     */
    public function run($dbName = 'default')
    {
        $this->_makeFilePath = array();
        $this->_counter = 0;
        $content = '';
        $dbNamePath = $this->_rename($dbName);
        $path = APPPATH.'models'.DIRECTORY_SEPARATOR.$dbNamePath.DIRECTORY_SEPARATOR;
        $basePath = APPPATH.'models'.DIRECTORY_SEPARATOR.$dbNamePath.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR;
        $content .= '<pre>';
        $content .= 'current:'. $dbName. "\n";
        $content .= 'created path:'. $path. "\n";
        $content .= 'created base path:'.$basePath. "\n";
        $this->_makeDir($path);
        $this->_makeDir($basePath);
        $this->_fetchTable($dbName, $path, $basePath);
        $content .= join("\n", $this->_makeFilePath);
        $content .= "\n".'res: done! create file:'. $this->_counter. ' table num:'.$this->_tableCounter."\n";
        $content .= '</pre>';
        return $content;
    }

    /**
     * 生成模型
     * @param  string $database [description]
     * @param  string $dbName   [description]
     * @param  string $path     [description]
     * @param  string $basePath [description]
     * @return void           [description]
     */
    protected function _fetchTable($dbName, $path, $basePath)
    {
        $db = get_instance()->load->database($dbName, true);
        foreach ($db->list_tables() as $table)
        {
            $fields = $db->list_fields($table);
            $filePath = $path.$this->_rename($table, false);
            $fileBasePath = $basePath.$this->_rename($table.'_base', false);
            $this->_makeFile($filePath.'Model.php', $this->_modelCreateCode($dbName, $table));
            $this->_makeFile($fileBasePath.'Model.php', $this->_modelBaseCreateCode($dbName, $table, $fields), true);
            $this->_tableCounter++;
            // break;
        }
    }

    /**
     * 创建目录
     * @param  string $path [description]
     * @return void       [description]
     */
    protected function _makeDir($path)
    {
        if (!is_dir($path))
        {
            @mkdir($path);
            @chmod($path, 0777);
        }
    }

    /**
     * 创建文件
     * @param  [type] $path    [description]
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    protected function _makeFile($filePath, $content, $isRewrite = false)
    {
        if (is_file($filePath) && $isRewrite)
        {
            $this->_counter++;
            $this->_makeFilePath[] = $filePath;
            file_put_contents($filePath, $content);
        }
        else if (!is_file($filePath))
        {
            $this->_counter++;
            $this->_makeFilePath[] = $filePath;
            file_put_contents($filePath, $content);
        }
    }

    /**
     * 创建代码
     * @param  [type] $dbName [description]
     * @param  [type] $table  [description]
     * @return [type]         [description]
     */
    public function _modelCreateCode($dbName, $table)
    {
        $dbName = $this->_rename($dbName);
        $tableName = $this->_rename($table, false);
        $datetime = date('Y-m-d H:i:s');
        $template = "<?php\n
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'models'.DIRECTORY_SEPARATOR.'{$dbName}'.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'{$tableName}BaseModel.php';
/**
 * @author ModelCreater
 * +{$datetime}
 */
class {$tableName}Model extends {$tableName}BaseModel
{

}
    ";
        return $template;
    }

    /**
     * 创建代码
     * @param  [type] $dbName [description]
     * @param  [type] $table  [description]
     * @param  [type] $fields [description]
     * @return [type]         [description]
     */
    public function _modelBaseCreateCode($dbName, $table, $fields)
    {
        $tableName = $this->_rename($table, false);
        $datetime = date('Y-m-d H:i:s');
        foreach ($fields as $key => $value)
        {
            $fields[$key] = "'{$value}'";
        }
        $fields = join(",\n", $fields);
        $template = "<?php\n
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author ModelCreater
 * +{$datetime}
 */
class {$tableName}BaseModel extends {$this->_base}
{
    /**
     * 表定义
     * @var string
     */
    protected \$_table = '{$table}';

    /**
     * 数据库定义
     * @var string
     */
    protected \$_dbName = '{$dbName}';

    /**
     * 字段定义
     * @var array
     */
    protected \$_fields = array(
        {$fields}
    );

    public function __construct()
    {
        parent::__construct();
        \$this->db = \$this->load->database(\$this->_dbName, true);
    }
}
    ";
        return $template;
    }

    /**
     * 改名
     * @param  [type]  $name  [description]
     * @param  boolean $isDir [description]
     * @return [type]         [description]
     */
    protected function _rename($name, $isDir = true)
    {
        $name = strtolower($name);
        $names = explode('_', $name);
        foreach ($names as $k => $v)
        {
            if ($isDir)
            {
                if ($k > 0)
                {
                    $names[$k] = ucfirst($v);
                }
            }
            else
            {
                $names[$k] = ucfirst($v);
            }
        }
        return join('', $names);
      
    }
}