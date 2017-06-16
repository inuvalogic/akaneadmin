<?php
if (!defined('_INC')) { die('404 Not Found'); }
$web->admin();
$web->set_heading(MENU_HOME);
load_model('article');

$news = $web->article->all();
$jml_news = count($news);

?>
<div class="row">
    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?php echo $jml_news; ?>
                </h3>
                <p>
                    News
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-phone"></i>
            </div>
            <a href="<?php echo SITEURL; ?>?content=article" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>
                    <?php echo $jml_news; ?>
                </h3>
                <p>
                    Article
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <a href="<?php echo SITEURL; ?>?content=article" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>
                    <?php echo $jml_news; ?>
                </h3>
                <p>
                    News &amp; Information
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-newspaper-o"></i>
            </div>
            <a href="<?php echo SITEURL; ?>?content=article" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    <?php echo $jml_news; ?>
                </h3>
                <p>
                    News
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-question"></i>
            </div>
            <a href="<?php echo SITEURL; ?>?content=article" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
