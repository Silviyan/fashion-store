document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        const addButton = event.target.closest(".ajax-add-to-cart");
        const removeButton = event.target.closest(".ajax-remove-from-cart");

        if (addButton) {
            event.preventDefault();

            const productId = addButton.getAttribute("data-id");
            const quantityInput = document.querySelector("#quantity");

            let quantity = 1;

            if (quantityInput) {
                if (!quantityInput.checkValidity()) {
                    quantityInput.reportValidity();
                    return;
                }

                quantity = quantityInput.value;
            }

            fetch(
                "/fashion-store/add_to_cart.php?id=" +
                productId +
                "&quantity=" +
                quantity +
                "&ajax=1"
            )
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCart(data);

                        addButton.textContent = "Добавено ✓";

                        setTimeout(() => {
                            addButton.textContent = "Добави в количката";
                        }, 1200);
                    } else {
                        alert(data.message);
                    }
                });
        }

        if (removeButton) {
            event.preventDefault();
            event.stopPropagation();

            const productId = removeButton.getAttribute("data-id");
            const cartToggle = document.querySelector(".cart-menu");

            fetch("/fashion-store/remove_from_cart.php?id=" + productId + "&ajax=1")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCart(data);

                        if (cartToggle) {
                            const dropdown = bootstrap.Dropdown.getOrCreateInstance(cartToggle);
                            dropdown.show();
                        }
                    }
                });
        }
    });

    document.addEventListener("click", function (event) {
        const cartDropdown = document.querySelector(".cart-dropdown");
        const cartMenu = document.querySelector(".cart-menu");

        if (cartDropdown && cartDropdown.contains(event.target)) {
            event.stopPropagation();
        }

        if (cartMenu && cartMenu.contains(event.target)) {
            event.stopPropagation();
        }
    });

    function updateCart(data) {
        const cartBadge = document.querySelector(".cart-menu .badge");
        const cartContent = document.querySelector("#cart-dropdown-content");

        if (cartBadge) {
            cartBadge.textContent = data.cart_count;
        }

        if (cartContent) {
            cartContent.innerHTML = data.cart_html;
        }
    }
});