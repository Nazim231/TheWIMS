import { generateTable } from "./generateTable";

const btnGenerate = document.getElementById("generateVTable");
const variationContainer = document.getElementById("variationContainer");

btnGenerate.addEventListener("click", function () {
    while (variationContainer.hasChildNodes()) {
        variationContainer.removeChild(variationContainer.firstChild);
    }
    const form = document.getElementById("addVariationForm");
    const formData = Object.fromEntries(new FormData(form));
    formData.variation = true;
    generateTable(formData, variationContainer);
});
