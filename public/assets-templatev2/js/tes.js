"use strict";

// Class definition
var KTTest = (function () {
    var tesButton;
    var tesButton2;
    var tesTextarea;

    var Tampilkanlog = function () {
        console.log("ini js plugin");
    };

    var setText = function (text) {
        tesTextarea.textContent = text;
    };

    var HandleButton = function () {
        var i = 0;
        tesButton.addEventListener("click", function (e) {
            e.preventDefault();

            setText("ini text dari tombol tes 1");
            console.log("tombol tes 1 ditekan");
        });

        tesButton2.addEventListener("click", function (e) {
            e.preventDefault();
            i += 1;

            setText("ini " + i);

            tesButton.classList.remove("btn-primary");
            tesButton.classList.add("btn-warning");

            console.log("tombol tes ditekan" + i);
        });
    };

    return {
        init: function () {
            // inisiasi tombol
            tesButton = document.querySelector("#btn_tes");
            tesButton2 = document.querySelector("#btn_tes2");
            tesTextarea = document.querySelector("#tes_text");

            HandleButton();
            Tampilkanlog();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTTest.init();
});
