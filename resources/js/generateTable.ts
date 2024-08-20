export const generateTable = (formData: any, variationContainer: HTMLElement) => {
    if (!formData.variation) {
        /**
         *  TODO : Create only one field to insert the data
         */
        alert(`No Variation Found`);
        return;
    }

    const tableHeadings = [
        "Select",
        "Variation Name",
        "SKU",
        "Qty.",
        "MRP",
        "Selling Price",
        "Cost Price",
    ];

    if (formData.variation) tableHeadings.push(formData.variation_type);
    if (formData.sub_variation) tableHeadings.push(formData.sub_variation_type);

    const variationTable = document.createElement("table");
    variationTable.classList.add("table");
    variationTable.classList.add("table-striped");

    const variationHead = document.createElement("thead");

    const variationHeaderRow = document.createElement("tr");
    for (var i = 0; i < tableHeadings.length; i++) {
        const column = document.createElement("th");
        column.innerHTML = tableHeadings[i];
        variationHeaderRow.append(column);
    }

    variationHead.append(variationHeaderRow);
    variationTable.append(variationHead);
    variationContainer.append(variationTable);

    /**
     * TODO : before creating the variation table firstly get the variation & sub variation values
     * then generate the table with these cross-product values of variation & sub variation
     * TODO : generate the SKU (currently it is manually written)
     */
    const numVariations =
        formData.variation_numbers != "" ? formData.variation_numbers : 1;
    const numSubVariations =
        formData.sub_variation_numbers != ""
            ? formData.sub_variation_numbers
            : 1;
    const totalVariations = numVariations * numSubVariations;

    const fieldNames = [
        "selected",
        "name",
        "sku",
        "qty",
        "mrp",
        "selling_price",
        "cost_price"
    ];
    const fieldTypes = [
        "checkbox",
        "text",
        "text",
        "number",
        "number",
        "number",
        "number",
    ];

    if (formData.variation) {
        fieldNames.push(formData.variation_type);
        fieldTypes.push("text");
    }
    if (formData.sub_variation) {
        if (formData.sub_variation_numbers == "") {
            alert("Please select the sub variations");
            return;
        }
        fieldNames.push(formData.sub_variation_type);
        fieldTypes.push("text");
    }

    const variationTableBody = document.createElement("tbody");
    variationTable.append(variationTableBody);
    for (var i = 0; i < totalVariations; i++) {
        const tableRow = document.createElement("tr");
        for (var j = 0; j < fieldTypes.length; j++) {
            const tableCol = document.createElement("td");
            const colItem = document.createElement("input");
            colItem.setAttribute("type", fieldTypes[j]);
            colItem.setAttribute("name", `variation_${fieldNames[j]}[]`);
            colItem.classList.add(
                j === 0 ? "form-select-input" : "form-control"
            );
            tableCol.append(colItem);
            tableRow.append(tableCol);
        }
        variationTableBody.append(tableRow);
    }
};
