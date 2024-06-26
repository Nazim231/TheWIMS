const searchField = document.getElementById("search_text");
const searchedResult = document.getElementById("searched-item");
const btnCheckout = document.getElementById("btnCheckout");
const btnFinalizeCheckout = document.getElementById("btnFinalizeCheckout");

const cartItems = new Map();
let totalCartCost = 0;

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
    cartItems.forEach((value) => {
        const itemRow = `
            <tr>
                <td>${++i}</td>
                <td>${value.name}</td>
                <td>${value.quantity}</td>
                <td>${value.totalPrice}</td>
            </tr>
        `;
        $("#confirm-selected-items").append(itemRow);
        totalQty += value.quantity;
        totalAmt += value.totalPrice;
    });

    $("#total-items").text(i);
    $("#total-qty").text(totalQty);
    $("#total-amount").text(totalAmt);

    const checkoutModalInstance = new bootstrap.Modal(
        document.getElementById("confirmCheckoutDialog")
    );
    const checkoutModal = document.getElementById("confirmCheckoutDialog");
    checkoutModalInstance.show(checkoutModal);
});

btnFinalizeCheckout.addEventListener("click", function (e) {
    const url = this.dataset.url;
    const token = this.dataset.ref;

    const finalizeData = {};
    cartItems.forEach((value) => (finalizeData[value.id] = value));

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: token,
            data: finalizeData,
        },
        success: function (response) {
            cartItems.clear();
            $("#selected-items").empty();
            $("#confirm-selected-items").empty();
            console.log(response, cartItems.size());
        },
    });
});

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

    prodName.textContent = result.name;
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
 *
 */
function addItemToCart(data) {
    if (cartItems.get(data.id)) {
        updateItemQuantity(data.id);
        setSearchFieldFocused();
        return;
    }

    const itemPositionInUI = cartItems.size;

    const rowCode = `
        <tr>
            <td>${itemPositionInUI + 1}</td>
            <td>${data.id}</td>
            <td>${data.name}</td>
            <td>${data.SKU}</td>
            <td>${1}</td>
            <td>${data.price}</td>
            <td>${data.price}</td>
        </tr>
    `;

    data.position = itemPositionInUI;
    data.quantity = 1;
    data.totalPrice = data.price;
    cartItems.set(data.id, data);

    $("#selected-items").append(rowCode);
    totalCartCost += data.price;
    $("#checkout-total-cost").text(totalCartCost);
    clearAndHideSearchedItems();
    setSearchFieldFocused();
}

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
    cartProduct.totalPrice += cartProduct.price;
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
        .html(cartProduct.totalPrice);

    // updating overall total cost
    totalCartCost += cartProduct.price;
    $("#checkout-total-cost").text(totalCartCost);
    clearAndHideSearchedItems();
}

function setSearchFieldFocused() {
    $("#search_text").val("");
    $("#search_text").focus();
}

document.addEventListener("click", () => {
    /**
     *  TODO : If there are items in cart
     * & user is changing the page, show a confirm dialog for Discarding Cart.
     */
});
