// 0
(function($) {
  $(document).ready(function() {

    if ($('#ui-uihk-exnot-MessageContainer').length === 0){
        //.il-layout-page-content
        //.il-system-infos
        //#ilContentContainer
        $('.il-layout-page-content').prepend('<div id="ui-uihk-exnot-MessageContainer"></div>');
    }

    ui_uihk_exnot_requestMessage();

    setInterval(ui_uihk_exnot_requestMessage, 60000);

    function ui_uihk_exnot_requestMessage() {
      $.ajax({
          type: 'GET',
          url: 'ilias.php?baseClass=iluipluginroutergui&cmd=get&cmdClass=ExamNotificationsGUI',
          dataType: 'text',
          timeout: 60000
        })
        .done(ui_uihk_exnot_requestMessageSuccess)
    }

    function ui_uihk_exnot_requestMessageSuccess(messages) {
        var $container = $("#ui-uihk-exnot-MessageContainer");
        $container.html(messages);
    }
    
  });

})(jQuery);

    function ui_uihk_exnot_closeExamNotification(closeButtonID) {
        let examNotificationID = closeButtonID.replace("ui_uihk_exnot_closeButton_ExamNotificationID_", "");      
          $.ajax({
          type: 'GET',
          url: 'ilias.php?baseClass=iluipluginroutergui&cmd=close&cmdClass=ExamNotificationsGUI&ref_id='+examNotificationID,
          dataType: 'text',
          timeout: 60000
        });
        $('#ui_uihk_exnot_closeButton_ExamNotificationID_'+examNotificationID).remove();
    }
