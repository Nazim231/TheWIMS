import { generateTable } from "./generateTable";

const btnGenerate = document.getElementById("generateVTable");
const variationContainer = document.getElementById("variationContainer");

btnGenerate.addEventListener("click", function () {
    while (variationContainer.hasChildNodes()) {
        variationContainer.removeChild(variationContainer.firstChild);
    }
    const form = document.getElementById("addProductForm");
    const formData = Object.fromEntries(new FormData(form));
    generateTable(formData, variationContainer);
});
