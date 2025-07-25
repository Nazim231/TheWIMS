const searchField = document.getElementById("search_text");
const searchedResult = document.getElementById("searched-item");
const btnCheckout = document.getElementById("btnCheckout");
const btnFinalizeCheckout = document.getElementById("btnFinalizeCheckout");
const btnSearchCustomer = document.getElementById("btnSearchCustomer");
const btnClearCart = document.getElementById("btnClearCart");

const cartItems = new Map();
let totalCartCost = 0;
let customerExists = false;
let customerMobileNum, customerName;

searchField.addEventListener("keypress", function (e) {
    const url = this.dataset.url;
    const keyword = this.value;

    if (keyword.length < 2) return;

    $.ajax({
        url: url,
        data: { keyword: keyword },
        success: function (response) {
            $("#searched-item").empty();
            response.forEach((data) => createSearchedItemElem(data));
        },
    });
});

searchField.addEventListener("keyup", (e) => {
    if (
        (e.key == "Backspace" && e.target.value.length <= 2) ||
        e.key == "Escape"
    ) {
        clearAndHideSearchedItems();
    }
});

btnCheckout.addEventListener("click", () => {
    $("#confirm-selected-items").empty();

    let i = 0;
    let totalQty = 0;
    let totalAmt = 0;

    if (cartItems.size == 0) {
        $("#errorMessage").text("Cart is empty.");
        showModal("errorModal");
        return;
    }

    cartItems.forEach((value) => {
        const itemRow = `
            <tr>
                <td>${++i}</td>
                <td>${value.product_name}</td>
                <td>${value.quantity}</td>
                <td>${value.total_price}</td>
            </tr>
        `;
        $("#confirm-selected-items").append(itemRow);
        totalQty += value.quantity;
        totalAmt += value.total_price;
    });

    $("#total-items").text(i);
    $("#total-qty").text(totalQty);
    $("#total-amount").text(totalAmt);
    showModal("confirmCheckoutDialog");
});

btnSearchCustomer.addEventListener("click", function (e) {
    const url = this.dataset.ref;
    customerMobileNum = $("#customerMobileNum").val();
    const length = customerMobileNum.length;
    customerMobileNum = Number(customerMobileNum);

    if (customerMobileNum == NaN || length != 10) {
        $("#errorMessage").text("Please enter a valid mobile number");
        showModal("errorModal");
        return;
    }

    $.ajax({
        url: url,
        data: {
            mobile_number: customerMobileNum,
        },
        success: function (response) {
            $("#finalizeCheckout").empty();
            customerName = response.name;
            customerExists = true;
            const content = `
                <p class="text-muted">Mobile Number: <span class="fw-semibold text-black">${customerMobileNum}</span></p>
                <p class="text-muted mb-0">Customer Name: <span class="fw-semibold text-black">${customerName}</span></p>
            `;
            $("#finalizeCheckout").append(content);
        },
        error: function (err) {
            if (err.status == 404) {
                customerExists = false;
                $("#finalizeCheckout").empty();

                const content = `
                    <p class="text-muted">Mobile Number: <span class="fw-semibold text-black">${customerMobileNum}</span><span class="fs-7 fst-italic"> (Customer not found)</span></p>
                    <input type="text" class="form-control" name="customer_name" id="customerName"
                        placeholder="Enter New Customer Name" focused required>
                    <p class="text-muted fs-7 fst-italic">
                        Customer name & mobile number will be saved in records for further invoice generations
                    </p> 
                `;
                $("#finalizeCheckout").append(content);
            }
        },
        complete: function (xhr) {
            $("#finalAmount").text(totalCartCost);
            showModal("finalizeCheckoutModal");
        },
    });
});

btnFinalizeCheckout.addEventListener("click", function (e) {
    const url = this.dataset.url;
    const token = this.dataset.ref;

    const finalizeData = {};
    cartItems.forEach((value) => (finalizeData[value.id] = value));

    const checkoutData = {
        _token: token,
        data: finalizeData,
        total_amount: totalCartCost,
        customer_exists: customerExists,
        customer_name: customerExists ? customerName : $("#customerName").val(),
        customer_mobile: customerMobileNum,
        total_amount: totalCartCost,
    };

    $.ajax({
        url: url,
        type: "POST",
        data: checkoutData,
        success: function (response) {
            cartItems.clear();
            $("#selected-items").empty();
            $("#checkout-total-cost").text("0.00");
            $("#errorMessage").text(response.message);
            showModal("errorModal");
        },
        error: function (err) {
            // iterating over each error occured in processing the request
            for (let [key, value] of Object.entries(err.responseJSON.errors)) {
                if (typeof value == "string") {
                    console.log(value);
                    continue;
                }
                value.forEach((v) => console.log(v));
            }
        },
    });
});

btnClearCart.addEventListener("click", () => {
    const clearCartBtnId = "confirmClearCart";
    $("#errorMessage").text("Are you sure you want to clear cart?");
    if (document.getElementById(clearCartBtnId) == null) {
        $("#errorModal .modal-footer").append(
            `<button type="button" class="btn btn-danger" id="${clearCartBtnId}" data-bs-dismiss="modal">Confirm</button>`
        );
        document
            .getElementById(clearCartBtnId)
            .addEventListener("click", () => clearCart(clearCartBtnId), false);
    }
    showModal("errorModal");
});

/**
 * Removes all element from the cart and reset the cart values to its default.
 *
 * @param {string} buttonId id of button that needs to be removed from BS Modal
 */
function clearCart(buttonId) {
    cartItems.clear();
    $("#selected-items").empty();
    $("#checkout-total-cost").text("0.00");
    totalCartCost = 0;
    $(`#${buttonId}`).remove();
}

/**
 * Creates selectable searched product element
 *
 * @param {Object} result product received while searching
 */
function createSearchedItemElem(result) {
    const container = document.createElement("div");
    const prodName = document.createElement("h5");
    const sku = document.createElement("p");
    sku.style.marginBottom = 0;

    container.addEventListener("click", () => addItemToCart(result));

    prodName.textContent = result.product_name;
    sku.textContent = result.SKU;

    container.appendChild(prodName);
    container.appendChild(sku);
    searchedResult.appendChild(container);
    searchedResult.style.visibility = "visible";
}

/**
 * Add product to the cartItems map
 * and creates new row of item in cart table.
 *
 * @param {Object} data Object containing details of product.
 */
function addItemToCart(data) {
    if (cartItems.get(data.id)) {
        updateItemQuantity(data.id);
        setSearchFieldFocused();
        return;
    }

    const itemPositionInUI = $("#selected-items").children().length;

    const rowCode = `
        <tr>
            <td>${itemPositionInUI + 1}</td>
            <td>${data.id}</td>
            <td>${data.product_name}</td>
            <td>${data.SKU}</td>
            <td>${1}</td>
            <td>${data.price}</td>
            <td>${data.price}</td>
            <td>
                <i class="fa-solid fa-trash" 
                    style="color: #e10526; cursor: pointer;" 
                    data-id="${data.id}">
                </i>
            </td>
        </tr>
    `;

    // implementing some additional properties to the item object and adding it to the table
    data.position = itemPositionInUI;
    data.quantity = 1;
    data.total_price = data.price;
    cartItems.set(data.id, data);
    $("#selected-items").append(rowCode);

    totalCartCost += data.price;
    $("#checkout-total-cost").text(totalCartCost);

    // adding a function to item for deleting it from the cart list and table
    $(`[data-id="${data.id}"]`).on("click", () => {
        // removing respective item row from table and cart list and updating the total cost
        $("#selected-items").children().eq(data.position).remove();
        totalCartCost -= cartItems.get(data.id).total_price;
        $("#checkout-total-cost").text(totalCartCost);
        cartItems.delete(data.id);

        // updating each item position in the cart list
        let position = 0;
        cartItems.forEach((value) => {
            if (value.position != position) value.position = position;
            position++;
        });
    });

    clearAndHideSearchedItems();
    setSearchFieldFocused();
}

/**
 * Clear items and hide search results after an item is selected
 */
function clearAndHideSearchedItems() {
    $("#searched-item").empty();
    searchedResult.style.visibility = "hidden";
}

/**
 * Update item quantity in cart, and also in table.
 *
 * @param {Number} itemId
 */
function updateItemQuantity(itemId) {
    const cartProduct = cartItems.get(itemId);

    cartProduct.quantity += 1;
    cartProduct.total_price += cartProduct.price;
    cartItems.set(itemId, cartProduct);

    // updating quantity in GUI
    $("#selected-items")
        .children()
        .eq(cartProduct.position)
        .children()
        .eq(4) // 4 is the quantity column index
        .html(cartProduct.quantity);

    // updating totalPrice in GUI
    $("#selected-items")
        .children()
        .eq(cartProduct.position)
        .children()
        .eq(6) // 5 is the total price column index
        .html(cartProduct.total_price);

    // updating overall total cost
    totalCartCost += cartProduct.price;
    $("#checkout-total-cost").text(totalCartCost);
    clearAndHideSearchedItems();
}

/**
 * Sets focus to the product search input field
 */
function setSearchFieldFocused() {
    $("#search_text").val("");
    $("#search_text").focus();
}

/**
 * Shows bootstrap modal dynamically
 *
 * @param {string} modalId ID of the modal to be shown
 */
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    const instance = new bootstrap.Modal(modal);
    instance.show(modal);
}

document.addEventListener("click", () => {
    /**
     *  TODO : If there are items in cart
     * & user is changing the page, show a confirm dialog for Discarding Cart.
     */
});
