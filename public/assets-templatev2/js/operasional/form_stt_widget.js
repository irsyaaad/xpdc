"use strict";
// Class definition
var FormSTT = (function () {
    var formstt;
    var formstt_btn_submit;
    var carahitung_buttons;
    var carahitung;

    // Private functions
    var initForm = function (element) {
        // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
        var invoiceDate = $(formstt.querySelector('[name="invoice_date"]'));
        invoiceDate.flatpickr({
            enableTime: false,
            dateFormat: "d M Y",
        });

        // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
        var dueDate = $(formstt.querySelector('[name="invoice_due_date"]'));
        dueDate.flatpickr({
            enableTime: false,
            dateFormat: "d M Y",
        });
    };

    var HandleButton = function () {
        // form stt button submit listener
        formstt_btn_submit.addEventListener("click", function (e) {
            e.preventDefault();

            formstt_btn_submit.setAttribute("data-kt-indicator", "on");

            // Disable button to avoid multiple click
            formstt_btn_submit.disabled = true;

            // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
            setTimeout(function () {
                // Remove loading indication
                formstt_btn_submit.removeAttribute("data-kt-indicator");

                // Enable button
                formstt_btn_submit.disabled = false;

                // Show popup confirmation
                Swal.fire({
                    text: "Form has been successfully submitted!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                });

                formstt.submit(); // Submit form
            }, 2000);

            console.log("submit");
        });

        // cara hitung button listener
        // carahitung_buttons.addEventListener("change", function (e) {
        //     console.log("cara hitung clicked " + carahitung_buttons.value);
        // });

        carahitung_buttons.forEach((elem) => {
            elem.addEventListener("change", function (e) {
                carahitung = e.target.value;
                console.log("cara hitung clicked " + e.target.value);
                const borongan_input = document.querySelector(
                    "#borongan_input_wrapper"
                );
                if (e.target.value == "borongan") {
                    borongan_input.style.display = "block";
                } else {
                    borongan_input.style.display = "none";
                }

                // call HitungNetto function
                HitungNetto();
            });
        });

        checkppn.addEventListener("change", function (e) {
            var floatingppn = document.querySelector("#floatingppn");
            if (this.checked) {
                floatingppn.removeAttribute("disabled");
                floatingppn.focus();
            } else {
                floatingppn.setAttribute("disabled", "");
            }
        });

        checkaddEventListener("change", function (e) {
            var floatingasuransi = document.querySelector("#floatingasuransi");
            if (this.checked) {
                floatingremoveAttribute("disabled");
                floatingfocus();
            } else {
                floatingsetAttribute("disabled", "");
            }
        });

        checkpacking.addEventListener("change", function (e) {
            var floatingpacking = document.querySelector("#floatingpacking");
            if (this.checked) {
                floatingpacking.removeAttribute("disabled");
                floatingpacking.focus();
            } else {
                floatingpacking.setAttribute("disabled", "");
            }
        });
    };

    var HitungNetto = function () {
        console.log("cara hitung " + carahitung);
        var bruto = 0;
        var netto = 0;

        // if (carahitung == "berat") {
        //     bruto = jmlkg.value * tarifkg.value;
        // }

        // nilaibruto.textContent = bruto;

        console.log("value " + tarifkg.value);
    };

    // form stt validator
    var form_stt_validator = function () {};

    // Public functions
    return {
        init: function () {
            formstt = document.querySelector("#form_stt");
            formstt_btn_submit = document.querySelector("#form_stt_submit");
            carahitung_buttons = document.querySelectorAll(
                'input[name="cara_hitung"]'
            );
            var checkppn = document.querySelector("#checkppn");
            var checkasuransi = document.querySelector("#checkasuransi");
            var checkpacking = document.querySelector("#checkpacking");

            var jmlkg = document.getElementById("#jmlkg");
            var tarifkg = document.getElementById("#tarif_kg");
            var jmlkgv = document.getElementById("#jmlkgv");
            var tarifkgv = document.getElementById("#tarif_kgv");
            var jmlkubik = document.getElementById("#jmlm3");
            var tarifm3 = document.getElementById("#tarif_m3");
            var nilaiborongan = document.getElementById("#borongan_input");
            var nilaibruto = document.getElementById("#harga_bruto");
            var nilainetto = document.getElementById("#harga_netto");

            // call private function
            initForm();
            HandleButton();
        },
    };
})();

// Initialization
KTUtil.onDOMContentLoaded(function () {
    FormSTT.init();
});
