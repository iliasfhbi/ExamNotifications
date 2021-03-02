<#1>
<?php
/**
 * ui_uihk_exnot_tstmsg - test messages
 */
global $DIC;
$ilDB = $DIC->database();

if ($ilDB->tableExists("ui_uihk_exnot_tstmsg")) {
    return;
}

$fields = array(
    'obj_id' => array(
        'type' => 'integer',
        'length' => 4
    ),
    'message_text' => array(
        'type' => 'text',
        'length' => 200,
        'fixed' => true
    ),
);

$ilDB->createTable("ui_uihk_exnot_tstmsg", $fields);
$ilDB->addPrimaryKey("ui_uihk_exnot_tstmsg", array("obj_id"));
?>