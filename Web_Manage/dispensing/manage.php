<?php
include_once('_config.php');
include_once('formconfig.php');
$errorhandle = new coderErrorHandle();
$id = get('id', 1);
$manageinfo = "";
/* ## coder [initData] --> ## */

/* ## coder [initData] <-- ## */
try {

    if ($id != "") {
        coderAdmin::vaild($auth, 'edit');
        $db = Database::DB();
        $row = $db->query_prepare_first("select * from $table  WHERE {$colname['id']}=:id", array(':id' => $id));
        if (empty($row)) {
            throw new Exception("查無相關資料!");
        }
        $manageinfo = '  管理者 : ' . $row[$colname['manager']] . ' | 建立時間 : ' . $row[$colname['create_time']] . ' | 上次修改時間 : ' . $row[$colname['update_time']];
        $fhelp->bindData($row);
        $method = 'edit';
        $active = '編輯';

        //$db->close();
    } else {
        $fhelp->setAttr($colname['user_id'], 'validate', array('required' => 'yes'));
        $fhelp->setAttr($colname['money'], 'validate', array('required' => 'yes','maxlength' => '11','digits'=>'yes'));
        $fhelp->setAttr($colname['bank_card_id'], 'validate', array('required' => 'yes','maxlength' => '50'));
        $fhelp->setAttr($colname['bank_id'], 'validate', array('required' => 'yes','maxlength' => '50'));
        $fhelp->setAttr($colname['num'], 'validate', array('required' => 'yes','maxlength' => '50','digits'=>'yes'));        
        $fhelp->setAttr($colname['contents'], 'validate', array('maxlength' => '255'));
        coderAdmin::vaild($auth, 'add');
        $method = 'add';
        $active = '新增';
    }
} catch (Exception $e) {
    $db->close();
    $errorhandle->setException($e);
}
if ($errorhandle->isException()) {
    $errorhandle->showError();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include('../head.php'); ?>
    <link rel="stylesheet" type="text/css" href="../assets/dropzone/downloads/css/dropzone.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/jcrop/jquery.Jcrop.min.css"/>

</head>
<body>
<!-- BEGIN Container -->
<div class="container" id="main-container">
    <!-- BEGIN Content -->
    <div id="main-content">
        <!-- BEGIN Page Title -->
        <div class="page-title">
            <div>
                <h1><i class="<?php echo $mainicon ?>"></i> <?php echo $page_title ?>管理</h1>
                <h4><?php echo $page_desc ?></h4>
            </div>
        </div>
        <!-- END Page Title -->
        <?php if ($manageinfo != '') { ?>
            <div class="alert alert-info">
                <button class="close" data-dismiss="alert">&times;</button>
                <strong>系統資訊 : </strong> <?php echo $manageinfo ?>
            </div>
        <?php } ?>
        <!-- BEGIN Main Content -->
        <div class="row">
        <form class="form-horizontal" action="save.php" id="myform" name="myform" method="post">
            <?php echo $fhelp->drawForm($colname['id']) ?>
            <input type="hidden" name="nowstatus" id="nowstatus" value="<?php echo ($method == 'edit')?$row[$colname['is_pay']]:'0'?>">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-title">
                        <h3><i class="<?php echo getIconClass($method) ?>"></i> <?php echo $page_title . $active ?>
                        </h3>
                        <div class="box-tool">
                            <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                            <a data-action="close" href="#"><i class="icon-remove"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <div class="row">
                        <div class="form-group ">
                        <label class="col-sm-3 col-lg-3 control-label">
                            <?php echo $fhelp->drawLabel($colname['user_id']) ?> </label>
                        <div class="col-sm-3 " >
                            <div class="<?php echo (isset($row[$colname['user_id']]))?'control-label':'controls'?>"<?php echo (isset($row[$colname['user_id']]))?'style="text-align: left;"':''?>>
                                <?php 
                                    if(isset($row[$colname['user_id']])) {
                                        echo class_player::getName($row[$colname['user_id']]);
                                    }
                                    else{
                                        echo $fhelp->drawForm($colname['user_id']);
                                ?>
                            
                                <div class="control-label" style="text-align: left; font-size: 16px;">
                                    <span id="myuser"></span><span id="mygame"></span>
                                    &nbsp;
                                    <a class="btn btn-success" onClick="openBox('../transfers_player/index.php','95%','95%','fade',function(){})">選擇玩家</a>
                                </div>
                                <?php 
                                    }
                                ?>
                            </div>
                            <div class="<?php echo (isset($row[$colname['platform_id']]))?'control-label':'controls'?>"<?php echo (isset($row[$colname['platform_id']]))?'style="text-align: left;"':''?>>
                                <?php
                                    echo $fhelp->drawForm($colname['platform_id']);
                                ?>
                            </div>
                            
                        </div>
                    </div>
                    <?php
                        if(isset($row[$colname['platform_id']])) {
                    ?>
                    <div class="form-group ">
                        <label class="col-sm-3 col-lg-3 control-label">
                            <?php echo $fhelp->drawLabel($colname['platform_id']) ?> </label>
                        <div class="col-sm-3 " >
                        <div class="<?php echo (isset($row[$colname['platform_id']]))?'control-label':'controls'?>"<?php echo (isset($row[$colname['platform_id']]))?'style="text-align: left;"':''?>>
                            <?php
                                echo class_platform::getName($row[$colname['platform_id']]);
                            ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                    ?>

                                <div class="form-group ">
                                    <label class="col-sm-3 col-lg-3 control-label">
                                        <?php echo $fhelp->drawLabel($colname['money']) ?> </label>
                                    <div class="col-sm-3 <?php echo (isset($row[$colname['money']]))?'control-label':'controls'?>" <?php echo (isset($row[$colname['money']]))?'style="text-align: left;"':''?>>
                                        <?php
                                        if(isset($row[$colname['money']])) {
                                            echo $row[$colname['money']];
                                        }
                                        else{
                                            echo $fhelp->drawForm($colname['money']);
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-sm-3 col-lg-3 control-label">
                                        <?php echo $fhelp->drawLabel($colname['bank_id']) ?> </label>
                                    <div class="col-sm-3 <?php echo (isset($row[$colname['bank_id']]))?'control-label':'controls'?>" <?php echo (isset($row[$colname['bank_id']]))?'style="text-align: left;"':''?>>
                                        <?php
                                        if(isset($row[$colname['bank_id']])) {
                                            echo class_bank::getName($row[$colname['bank_id']]);
                                        }
                                        else{
                                            echo $fhelp->drawForm($colname['bank_id']);
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-sm-3 col-lg-3 control-label">
                                        <?php echo $fhelp->drawLabel($colname['num']) ?> </label>
                                    <div class="col-sm-3 <?php echo (isset($row[$colname['num']]))?'control-label':'controls'?>" <?php echo (isset($row[$colname['num']]))?'style="text-align: left;"':''?>>
                                        <?php
                                        if(isset($row[$colname['num']])) {
                                            echo $row[$colname['num']];
                                        }
                                        else{
                                            echo $fhelp->drawForm($colname['num']);
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 col-lg-3 control-label">
                                        <?php echo $fhelp->drawLabel($colname['contents']) ?> </label>
                                    <div class="col-sm-3 controls">
                                        <?php echo $fhelp->drawForm($colname['contents']) ?>
                                    </div>
                                </div>
                                <!-- ## coder [formScript] <- ## -->
                                <div class="form-group">
                                    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-3">
                                    <button type="button" class="btn btn-primary" onClick="$.confirm({
                                        title: '<?php echo $langary_manage['confirm_finish'].$active ?>'+'?',
                                        content: '',
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            tryAgain: {
                                            text: langary_jsall['confirm_ok'],
                                            btnClass: 'btn-red',
                                                action: function(){
                                                    $('#myform').submit();
                                                }
                                            },
                                            alphabet: {
                                                text: langary_jsall['confirm_cancel'],
                                                    action: function(){
                                                }
                                            }
                                        }
                                    });">
                                <i class="icon-ok"></i><?php echo $langary_manage['ok'];?><?php echo $active ?></button>
                            <button type="button" class="btn" onClick="$.confirm({
                                        title: '<?php echo $langary_manage['confirm_cancel'].$active ?>'+'?',
                                        content: '',
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            tryAgain: {
                                            text: langary_jsall['confirm_ok'],
                                            btnClass: 'btn-red',
                                                action: function(){
                                                    parent.closeBox();
                                                }
                                            },
                                            alphabet: {
                                                text: langary_jsall['confirm_cancel'],
                                                    action: function(){
                                                }
                                            }
                                        }
                                    });">
                            <i class="icon-remove"></i><?php echo $langary_manage['cancel'];?><?php echo $active ?></button>
                            <?php if(isset($row[$colname['is_pay']]) && $row[$colname['is_pay']] == 0){ ?>
                            <button type="button" class="btn btn-warning" id="btn-warning" onClick="$.confirm({
                                        title: '<?php echo $langary_manage['confirm_delete'].$active ?>'+'?',
                                        content: '',
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            tryAgain: {
                                            text: langary_jsall['confirm_ok'],
                                            btnClass: 'btn-red',
                                                action: function(){
                                                    document.getElementById('nowstatus').value = 3;
                                                    $('#myform').submit();
                                                }
                                            },
                                            alphabet: {
                                                text: langary_jsall['confirm_cancel'],
                                                    action: function(){
                                                }
                                            }
                                        }
                                    });">
                                <i class="icon-trash"></i> 捨棄</button>
                            <?php }?>
                                    </div>
                                </div>
                            </div>
                            <!--left end-->

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

        <!-- END Main Content -->
        <?php include('../footer.php');$db->close(); ?>
        <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="icon-chevron-up"></i></a>
    </div>
    <!-- END Content -->
</div>
<!-- END Container -->


<?php include('../js.php'); ?>
<script type="text/javascript" src="../assets/jquery-validation/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../assets/jquery-validation/dist/additional-methods.js"></script>
<script type="text/javascript" src="../assets/ckeditor/ckeditor.js"></script>
<!-- 多圖上傳 -->
<script type="text/javascript" src="../assets/dropzone/downloads/dropzone.min.js"></script>
<!-- 裁圖&傳圖 -->
<script type="text/javascript" src="../assets/jcrop/jquery.Jcrop.min.js"></script>
<!-- ## coder [includeScript] -> ## -->
<!-- ## coder [includeScript] <- ## -->
<script type="text/javascript">
    $(document).ready(function () {
        /* ## coder [jsScript] --> ## */
        /* ## coder [jsScript] <-- ## */
        <?php echo coderFormHelp::drawVaildScript();?>
        /* ## coder [jsVaildScript] --> ## */
        /* ## coder [jsVaildScript] <-- ## */
    })


</script>
</body>
</html>
