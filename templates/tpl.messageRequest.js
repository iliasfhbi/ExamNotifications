(function ($) {
    $(document).ready(function () {
            var config = {
                requestUrl: "{URL}",
                requestInterval: 1000 * {REQUEST_INTERVAL_IN_SECONDS} // calculate interval in milliseconds
            };

            var previousMessage = "";

            config.requestUrl = config.requestUrl.replace(/&amp;/g, "&"); // replace all occurrences

            setInterval(requestMessage, config.requestInterval);

            function requestMessage() {

                $.ajax({
                    type: 'GET',
                    url: config.requestUrl,
                    dataType: 'text',
                    timeout: config.requestInterval
                })
                    .done(requestMessageSuccess)
            }

            function requestMessageSuccess(messageJson) {
                var $container = $("#ui-uihk-exnot-displayMessage-container");

                var message = JSON.parse(messageJson);

                if(message && message.text && message !== previousMessage) {
                    var alertType;
                    // map message type to alert type
                    if(message.type === 1) {
                        alertType = "warning"
                    } else {
                        alertType = "info"; // default is information (0)
                    }
                    // display message as bootstrap alert
                    $container.html("<p class='alert alert-" + alertType +"'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + message.text + "</p>");
                    // save message from current response for next request so the message is not shown again after dismissing it
                    previousMessage = message;
                } else if(!message || message.text === "") {
                    // remove content if there is no message text
                    $container.html("")
                }
            }
        }
    );
})(jQuery);

