(function ($) {
    $(document).ready(function () {
            var config = {
                requestUrl: "{URL}",
                requestInterval: 1000 * {REQUEST_INTERVAL_IN_SECONDS} // calculate interval in milliseconds
            };

            var previousMessage = null;

            config.requestUrl = config.requestUrl.replace(/&amp;/g, "&"); // replace all occurrences

            // initial request on page load
            requestMessage();

            // start timer
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

                // make sure that there is a message with text. if there is no previous message set, display the message received from the response. otherwise make sure that the text or the type of the message differs from the previous message
                if (message && message.text &&
                    (!previousMessage || (message.text !== previousMessage.text || message.type !== previousMessage.type))) {

                    var alertType;
                    // map message type to alert type
                    switch (message.type) {
                        case 0:
                            alertType = "info";
                            break;
                        case 1:
                            alertType = "warning";
                            break;
                        default:
                            alertType = "danger"; // default is danger
                    }

                    // alert title
                    var messageTimestamp = new Date(message.timestamp);
                    var alertTitleText = message.sender + " - " + messageTimestamp.toLocaleTimeString();

                    // display message as bootstrap alert
                    var closeButton = "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
                    $container.html("<p class='alert alert-" + alertType + "'>" + closeButton + "<strong class='alert-title'>" + alertTitleText + "</strong>" + message.text + "</p>");
                } else if (!message || message.text === "") {
                    // remove content if there is no message text
                    $container.html("")
                }
                // save message from current response for next request so the message is not shown again after dismissing it while allowing to display the same message again after resetting it
                previousMessage = message;
            }
        }
    );
})(jQuery);

