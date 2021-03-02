(function ($) {
    $(document).ready(function () {
            var config = {
                requestUrl: "{URL}",
                requestInterval: 1000 * {REQUEST_INTERVAL_IN_SECONDS} // calculate interval in milliseconds
            };

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

                if(responseText) {
                    // display response text as bootstrap alert
                    $container.html("<p class='alert alert-info'>" + responseText + "</p>")
                } else {
                    // remove content if there is no text
                    $container.html("")
                }
            }
        }
    );
})(jQuery);

