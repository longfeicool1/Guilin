<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 首页控制器
 * @author  liuweilong
 * +2016-03-03
 */
class Test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createImage()
    {
        require_once ('jpgraph/jpgraph.php');
        require_once ('jpgraph/jpgraph_radar.php');
        $titles=array('急刹车(次)','急加速(次)','急转弯(次)','行驶里程(x100km)','平均速度(x100km/h)');
        $titles = array_map(function ($v){
            return iconv("UTF-8","GB2312//IGNORE",$v);
        }, $titles);
        $data=array(1, 2, 2, 4.20, 1);

        $graph = new RadarGraph (720,480);

        $graph->title->Set(iconv("UTF-8","GB2312//IGNORE","驾驶评分: 83↑+3
        您的驾驶安全系数已高于75%的用户"));
        $graph->title->SetFont(FF_SIMSUN,FS_NORMAL,16);
        $graph->title->SetMargin(40);
        $graph->footer->right->Set(iconv("UTF-8","GB2312//IGNORE","最近更新时间：2018-03-22 14:58"));
        $graph->footer->SetMargin(0,260,30);

        $graph->SetTitles($titles);
        $graph->SetCenter(0.5,0.55);
        $graph->HideTickMarks();
        $graph->SetColor('#fbf0ed@0.1');
        $graph->axis->SetColor('darkgray');
        $graph->grid->SetColor('#fcb28a');
        $graph->grid->Show();

        $graph->axis->title->SetFont(FF_SIMSUN,FS_NORMAL);
        $graph->axis->title->SetMargin(4);
        $graph->SetGridDepth(DEPTH_BACK);
        $graph->SetSize(0.6);

        $plot = new RadarPlot($data);
        $plot->SetColor('#fcb28a@0.1');
        $plot->SetLineWeight(0.5);
        $plot->SetFillColor('#fcb28a@0.1');

        $plot->mark->SetType(MARK_IMG_SBALL,'orange');

        $graph->Add($plot);
        $graph->Stroke();
    }
}