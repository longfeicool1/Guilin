<?php // content="text/plain; charset=utf-8"总
require_once (__DIR__.'jpgraph.php');
require_once (__DIR__.'jpgraph_radar.php');
$titles=array('急刹车','急加速','急转弯','行驶里程','平均速度');
$data=array(18, 40, 70, 90, 42);

$graph = new RadarGraph (300,280);

$graph->title->Set('网志博客
                   →信息统计表');
$graph->title->SetFont(FF_SIMSUN,FS_NORMAL,12);


$graph->SetTitles($titles);
$graph->SetCenter(0.5,0.55);
$graph->HideTickMarks();
$graph->SetColor('lightgreen@0.8');
$graph->axis->SetColor('darkgray');
$graph->grid->SetColor('darkgray');
$graph->grid->Show();

// $graph->axis->title->SetFont(FF_BIG5,FS_NORMAL,12);
$graph->axis->title->SetMargin(4);
$graph->SetGridDepth(DEPTH_BACK);
$graph->SetSize(0.6);

$plot = new RadarPlot($data);
$plot->SetColor('red@0.2');
$plot->SetLineWeight(1);
$plot->SetFillColor('red@0.7');

$plot->mark->SetType(MARK_IMG_SBALL,'red');

$graph->Add($plot);
$graph->Stroke();
?>
