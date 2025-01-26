jQuery(document).ready(function ($) {
    $("#search-flights").on("click", function (e) {
        e.preventDefault();

        const data = {
            action: "search_flights",
            nonce: flightWidgetAjax.nonce,
            departure_city: $("#departure_city").val(),
            destination: $("#destination").val(),
            departure_date: $("#departure_date").val(),
            return_date: $("#return_date").val(),
            passengers: $("#passengers").val()
        };

        $.ajax({
            url: flightWidgetAjax.ajaxUrl,
            type: "POST",
            data: data,
            beforeSend: function () {
                $("#flight-results").html("<p>Loading...</p>");
            },
            success: function (response) {
                if (response.success) {
                    let flights = response.data;
                    let html = "";

                    console.log(flights);

                    flights.forEach(flight => {
                        html += `
                            <div class="flight-card">
                                <div class="flight-date">
                                    <strong>${new Date(flight.local_departure).toLocaleDateString("en-GB", { weekday: "long", day: "numeric", month: "short" })}</strong>
                                </div>
                                <div class="flight-info">
                                    <div>
                                        <span class="time">${new Date(flight.local_departure).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}</span>
                                        <span class="airport">${flight.cityFrom} (${flight.flyFrom})</span>
                                    </div>
                                    <span class="arrow">→</span>
                                    <div>
                                        <span class="time">${new Date(flight.local_arrival).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}</span>
                                        <span class="airport">${flight.cityTo} (${flight.flyTo})</span>
                                    </div>
                                </div>
                                <div class="flight-footer">
                                    <span class="price">${flight.price} €</span>
                                    <button class="book-btn" onclick="window.open('${flight.deep_link}', '_blank')">Book</button>
                                </div>
                            </div>
                        `;
                    });

                    $("#flight-results").html(html);
                } else {
                    $("#flight-results").html(`<p>${response.data.message}</p>`);
                }
            },
            error: function () {
                $("#flight-results").html("<p>An error occurred while searching for flights.</p>");
            }
        });
    });

    // Tab Switching
    $(".tab").on("click", function () {
        $(".tab").removeClass("active");
        $(this).addClass("active");
        $(".tab-content").hide();
        $("#" + $(this).data("tab")).show();
    });

    // Datepicker Initialization
    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        minDate: 0
    });

    // mobiscroll.setOptions({
    //     locale: mobiscroll.localeEn, // Specify language like: locale: mobiscroll.localePl or omit setting to use default
    //     theme: "ios", // Specify theme like: theme: 'ios' or omit setting to use default
    //     themeVariant: "light" // More info about themeVariant: https://mobiscroll.com/docs/jquery/datepicker/api#opt-themeVariant
    // });

    // $(function () {
    //     var now = new Date();
    //     var min = now;
    //     var max = new Date(now.getFullYear(), now.getMonth() + 6, now.getDate());

    //     var booking = $("#demo-flight-booking-type-outbound")
    //         .mobiscroll()
    //         .datepicker({
    //             controls: ["calendar"], // More info about controls: https://mobiscroll.com/docs/jquery/datepicker/api#opt-controls
    //             select: "range", // More info about select: https://mobiscroll.com/docs/jquery/datepicker/api#methods-select
    //             display: "anchored", // Specify display mode like: display: 'bottom' or omit setting to use default
    //             startInput: "#demo-flight-booking-type-outbound", // More info about startInput: https://mobiscroll.com/docs/jquery/datepicker/api#opt-startInput
    //             endInput: "#demo-flight-booking-type-return", // More info about endInput: https://mobiscroll.com/docs/jquery/datepicker/api#opt-endInput
    //             min: min, // More info about min: https://mobiscroll.com/docs/jquery/datepicker/api#opt-min
    //             max: max, // More info about max: https://mobiscroll.com/docs/jquery/datepicker/api#opt-max
    //             pages: 2,
    //             dateFormat: "YYYY-MM-DD"
    //         })
    //         .mobiscroll("getInst");

    //     $(".demo-flight-type").on("change", function () {
    //         var oneWay = this.value == "oneway";
    //         $("#demo-flight-booking-type-return").mobiscroll("getInst").setOptions({ disabled: oneWay });

    //         if (oneWay) {
    //             booking.setOptions({
    //                 select: "date" // More info about select: https://mobiscroll.com/docs/jquery/datepicker/api#methods-select
    //             });
    //         } else {
    //             booking.setOptions({
    //                 select: "range" // More info about select: https://mobiscroll.com/docs/jquery/datepicker/api#methods-select
    //             });
    //         }
    //     });
    // });
});
