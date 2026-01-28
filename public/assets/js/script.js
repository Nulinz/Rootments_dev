// Aside Icons Toggle Funtion
$(document).ready(function () {
    var collapse = $(".collapse");
    collapse.on("show.bs.collapse", function () {
        $(this)
            .prev("button")
            .find(".toggle-icon")
            .removeClass("fa-angle-right")
            .addClass("fa-angle-down");
    });

    collapse.on("hide.bs.collapse", function () {
        $(this)
            .prev("button")
            .find(".toggle-icon")
            .removeClass("fa-angle-down")
            .addClass("fa-angle-right");
    });
});

// Contact Number / Age Validation / Pincode Validation / Year Validation / Aadhar Validation
function validate(input) {
    const value = input.value;

    if (value.length > 10) {
        input.value = value.slice(0, 10);
    }
}
function validate_age(input) {
    const value = input.value;

    if (value.length > 3) {
        input.value = value.slice(0, 3);
    }
}
function validate_pin(input) {
    const value = input.value;

    if (value.length > 6) {
        input.value = value.slice(0, 6);
    }
}
function validate_year(input) {
    const value = input.value;

    if (value.length > 4) {
        input.value = value.slice(0, 4);
    }
}
// function validate_aadhar(input) {
//     const value = input.value;

//     if (value.length > 12) {
//         input.value = value.slice(0, 12);
//     }
// }
function validate_aadhar(input) {
    let value = input.value.replace(/\D/g, "");
    if (value.length !== 12) {
        input.setCustomValidity("Aadhar number must be exactly 12 digits.");
    } else {
        input.setCustomValidity("");
    }
    input.value = value;
}

// List Filter
$(document).ready(function () {
    var table = $(".example").DataTable();
    $(".example thead th").each(function (index) {
        var headerText = $(this).text();
        if (headerText != "" && headerText.toLowerCase() != "action") {
            $(".headerDropdown").append(
                '<option value="' + index + '">' + headerText + "</option>"
            );
        }
    });
    $(".filterInput").on("keyup", function () {
        var selectedColumn = $(".headerDropdown").val();
        if (selectedColumn !== "All") {
            table.column(selectedColumn).search($(this).val()).draw();
        } else {
            table.search($(this).val()).draw();
        }
    });
    $(".headerDropdown").on("change", function () {
        $(".filterInput").val("");
        table.search("").columns().search("").draw();
    });
});

// Go Back Buttons
function getLastPage() {
    return localStorage.getItem("lastPage");
}
function goBack() {
    var lastPage = localStorage.getItem("lastPage");
    if (lastPage) {
        window.location.href = lastPage;
    } else {
        window.history.back();
    }
}
function saveLastPage() {
    localStorage.setItem("lastPage", window.location.href);
}
window.addEventListener("beforeunload", saveLastPage);

window.addEventListener("unload", function () {
    window.removeEventListener("beforeunload", saveLastPage);
});

// // Select 2 Script
// $('.select2input').select2({
//     placeholder: "Select Options",
//     allowClear: true,
//     width: '100%',
//     height: '100%',
// }).prop('required', true);
