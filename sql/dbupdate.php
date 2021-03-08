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
<#2>
<?php
if($ilDB->tableColumnExists("ui_uihk_exnot_tstmsg", "message_type")){
    return;
}

$ilDB->addTableColumn(
    'ui_uihk_exnot_tstmsg',
    'message_type',
    array(
        'type' => 'integer',
        'length' => 4,
        'notnull' => true,
        'default' => 0
    )
);
?>
<#3>
<?php
/**
 * ui_uihk_exnot_config - configuration
 */
global $DIC;
$ilDB = $DIC->database();

if ($ilDB->tableExists("ui_uihk_exnot_config")) {
    // reset configuration to default
    $ilDB->update("ui_uihk_exnot_config", ["polling_interval" => ["integer", 30]], ["1" => ["text", 1]]);
    return;
}

$fields = array(
    'polling_interval' => array(
        'type' => 'integer',
        'length' => 4
    )
);

$ilDB->createTable("ui_uihk_exnot_config", $fields);
// insert initial config entry
$ilDB->insert("ui_uihk_exnot_config", ["polling_interval" => [false, 30]]);
?>