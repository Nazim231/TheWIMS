const searchField = document.getElementById("search_text");
const searchedResult = document.getElementById("searched-item");
const btnCheckout = document.getElementById("btnCheckout");

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

btnCheckout.addEventListener("click", function (e) {
    const url = this.dataset.url;
    const token = this.dataset.ref;

    const finalizeData = {};
    let i = 0;
    cartItems.forEach((value, key) => {
        finalizeData[i++] = value;
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: token,
            data: finalizeData,
        },
        success: function (response) {
            console.log(response);
        }
    })
});

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

function addItemToCart(data) {
    if (cartItems.get(data.id)) {
        updateItemQuantity(data.id);
        setSearchFieldFocused();
        return;
    }

    const row = document.createElement("tr");
    const col = document.createElement("td");
    const colId = document.createElement("td");
    const colName = document.createElement("td");
    const colSKU = document.createElement("td");
    const colQty = document.createElement("td");
    const colPrice = document.createElement("td");
    const colTotalPrice = document.createElement("td");

    const itemPositionInUI = cartItems.size;

    col.textContent = itemPositionInUI + 1;
    row.appendChild(col);

    colId.textContent = data.id;
    row.appendChild(colId);

    colName.textContent = data.name;
    row.appendChild(colName);

    colSKU.textContent = data.SKU;
    row.appendChild(colSKU);

    colQty.textContent = 1;
    row.appendChild(colQty);

    colPrice.textContent = data.price;
    row.appendChild(colPrice);

    colTotalPrice.textContent = data.price;
    row.appendChild(colTotalPrice);

    data.position = itemPositionInUI;
    data.quantity = 1;
    data.totalPrice = data.price;
    cartItems.set(data.id, data);

    $("#selected-items").append(row);
    totalCartCost += data.price;
    $("#checkout-total-cost").text(totalCartCost);
    clearAndHideSearchedItems();
    setSearchFieldFocused();
}

function clearAndHideSearchedItems() {
    $("#searched-item").empty();
    searchedResult.style.visibility = "hidden";
}

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
        .eq(6)  // 5 is the total price column index
        .html(cartProduct.totalPrice);

    // updating overall total cost
    totalCartCost += cartProduct.price;
    $("#checkout-total-cost").text(totalCartCost);
    clearAndHideSearchedItems();
}

function setSearchFieldFocused() {
    $("#search_text").val('');
    $("#search_text").focus();
}