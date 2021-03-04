(function ($) {
    $(document).ready(function () {
            var config = {
                requestUrl: "{URL}",
                requestInterval: 1000 * {REQUEST_INTERVAL_IN_SECONDS} // calculate interval in milliseconds
            };

            var previousText = "";

            config.requestUrl = config.requestUrl.replace(/&amp;/g, "&"); // replace all occurrences
            console.log("Request url: " + config.requestUrl);

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

            function requestMessageSuccess(responseText) {
                var $container = $("#ui-uihk-exnot-displayMessage-container");

                if(responseText && responseText !== previousText) {
                    // display response text as bootstrap alert
                    $container.html("<p class='alert alert-info'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + responseText + "</p>");
                    // save text from current response for next request so the message is not shown again after dismissing it
                    previousText = responseText;
                } else if(responseText === "") {
                    // remove content if there is no text
                    $container.html("")
                }
            }
        }
    );
})(jQuery);

