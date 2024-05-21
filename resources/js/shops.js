const employeeList = document.getElementById("employee");

employeeList.addEventListener("focusin", function () {
    const url = this.dataset.id;

    $.ajax({
        url: url,
        success: function (response) {
            setEmployees(response);
        },
    });
});

function setEmployees(response) {
    $("#employee").empty();

    employeeList.appendChild(
        makeOption(null, "Please choose an employee", true, true)
    );
    response.employees.forEach((employee) => {
        employeeList.appendChild(
            makeOption(employee.id, employee.name, false, false)
        );
    });
}

function makeOption(value, content, isDisabled, isSelected) {
    const option = document.createElement("option");

    if (value) option.value = value;
    option.textContent = content;
    option.disabled = isDisabled;
    option.selected = isSelected;

    return option;
}
