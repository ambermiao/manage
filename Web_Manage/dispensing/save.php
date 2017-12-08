<?php
include_once('_config.php');
include_once('formconfig.php');

$errorhandle = new coderErrorHandle();
try {
    $db = Database::DB();
    $id = post($colname['id'], 1);
    if ($id != "") {
        coderAdmin::vaild($auth, 'edit');
        $method = 'edit';
        $active = '編輯';
    } else {
        coderAdmin::vaild($auth, 'add');
        $method = 'add';
        $active = '新增';
    }

    $data = $fhelp->getSendData();
    $error = $fhelp->vaild($data);
    if (count($error) > 0) {
        $msg = implode('<br/>', $error);
        throw new Exception($msg);
    }

    //多圖圖片
    //$picgroup = $data[$colname['picgroup']];
    //$data[$colname['picgroup']] = json_encode($picgroup);

    /* ## coder [beforeModify] --> ## */
    /* ## coder [beforeModify] <-- ## */

    $nowtime = datetime();
    $data[$colname['manager']] = $adminuser['username'];
    $data[$colname['update_time']] = $nowtime;

    $nowstatus = post("nowstatus");
    /*if ($data[$colname['status']] === '' || $nowstatus > 0) {
        unset($data[$colname['status']]);
    } else if ($data[$colname['status']] > 0) {
        $data[$colname['statustime']] = $nowtime;
    }*/

    if ($method == 'edit') {
        $db->query_update($table, $data, " {$colname['id']}='{$id}'");
    } else {
        $data[$colname['create_time']]= $nowtime;
        $data[$colname['user_id']] = post($colname['user_id'],1);  
        $data[$colname['game_id']] = post($colname['game_id'],1);          
        $data[$colname['bank_card_id']] = post($colname['bank_card_id'],1);          
        $data[$colname['bank']] = post($colname['bank'],1);  
        $data[$colname['num']] = post($colname['num'],1);          
        $data[$colname['money']] = post($colname['money'],1);
        $data[$colname['contents']] = post($colname['contents'],1);
        $id = $db->query_insert($table, $data);
    }


    $admin_title = isset($data[$colname['id']]) ? $data[$colname['id']] : '';
    coderAdminLog::insert($adminuser['username'], $main_auth_key, $fun_auth_key, $method, "id:{$id}");


    $db->close();

    echo showParentSaveNote($page_title, $active, $admin_title, "manage.php?id=" . $id);
} catch (Exception $e) {
    $errorhandle->setException($e); // 收集例外
}

if ($errorhandle->isException()) {
    $errorhandle->showError();
}
?>