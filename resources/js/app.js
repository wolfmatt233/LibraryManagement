import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

window.onload = () => {
    let url = window.location.href;
    url = url.split("/");
    url.forEach((split) => {
        if (split == "editBook") {
            editListeners();
        } else if (split == "loans") {
            messageListener();
        }
    });
};

function editListeners() {
    $("#imageChangeBtn").on("click", () => {
        $("#fileInput").html(`
            <label for="image" class="block font-medium text-sm text-gray-700">Image</label>
            <input class="mb-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="file" name="image" required />
        `);

        $("#imageChangeBtn").attr("id", "imageCancelBtn").html("Cancel");
        editListeners();
    });

    $("#imageCancelBtn").on("click", () => {
        $("#fileInput").html("");

        $("#imageCancelBtn").attr("id", "imageChangeBtn").html("Change Image?");
        editListeners();
    });
}

function messageListener() {
    $("#errorMsg").on("click", () => {
        $("#errorMsg").css("display", "none");
    });
}
