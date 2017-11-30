<?php
class class_users_group{
    public static function getList(){ //
        global $db;
        $colname = coderDBConf::$col_player_group;
        $sql = "select {$colname['title']} as name,{$colname['id']} as value
                from ".coderDBConf::$player_group."
                ORDER BY `{$colname['ind']}` DESC";
        return $db->fetch_all_array($sql);
    }

    public static function getName($_val){
        $ary = self::getList();
        return coderHelp::getArrayPropertyVal($ary, 'value', $_val, 'name');
    }

    public static function getList_one($id){ //
        global $db;
        $colname = coderDBConf::$col_users_group;
        $sql = "select {$colname['title']} as name,{$colname['id']} as value
                from ".coderDBConf::$users_group."
                where `{$colname['id']}` = $id
                ORDER BY `{$colname['id']}` DESC";
        return $db->query_prepare_first($sql);
    }
}