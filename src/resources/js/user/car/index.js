import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener("DOMContentLoaded", function () {
    const startInput = document.getElementById("start_datetime");
    const endInput = document.getElementById("end_datetime");

    // 現在＋30分
    const now = new Date();
    now.setMinutes(now.getMinutes() + 30);

    const pad = (n) => n.toString().padStart(2, "0");
    const toDatetimeString = (d) => {
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(
            d.getDate()
        )} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
    };

    let startPicker = flatpickr(startInput, {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: now,
        defaultDate: toDatetimeString(now),
        onChange: function (selectedDates) {
            const startDate = selectedDates[0];
            if (startDate) {
                // 終了は開始の30分後
                const minEnd = new Date(startDate.getTime());
                minEnd.setMinutes(minEnd.getMinutes() + 30);

                endPicker.set("minDate", minEnd);
                if (!endInput.value || new Date(endInput.value) < minEnd) {
                    endPicker.setDate(minEnd);
                }
            }
        },
    });

    let endPicker = flatpickr(endInput, {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: new Date(now.getTime() + 30 * 60000), // 開始+30分
    });
});
