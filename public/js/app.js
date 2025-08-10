document.addEventListener("alpine:init", () => {
    Alpine.store("attendanceModal", false);
});

document.addEventListener("DOMContentLoaded", function () {
    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
    });

    flatpickr(".datepicker", {
        enableTime: false,
        dateFormat: "Y-m-d",
    });

    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
    });
});
