const navEL = document.querySelector(".navbar");
window.addEventListener("scroll", () => {
    if (window.scrollY >= 206) {
        navEL.classList.add("navbar-scrolled");
    } else if (window.scrollY < 206) {
        navEL.classList.remove("navbar-scrolled");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // make it as accordion for smaller screens
    if (window.innerWidth > 992) {
        document
            .querySelectorAll(".navbar .nav-item")
            .forEach(function (everyitem) {
                everyitem.addEventListener("mouseover", function (e) {
                    let el_link = this.querySelector("a[data-bs-toggle]");

                    if (el_link != null) {
                        let nextEl = el_link.nextElementSibling;
                        el_link.classList.add("show");
                        nextEl.classList.add("show");
                    }
                });
                everyitem.addEventListener("mouseleave", function (e) {
                    let el_link = this.querySelector("a[data-bs-toggle]");

                    if (el_link != null) {
                        let nextEl = el_link.nextElementSibling;
                        el_link.classList.remove("show");
                        nextEl.classList.remove("show");
                    }
                });
            });
    }
    // end if innerWidth
});
// DOMContentLoaded  end

const workSection = document.querySelector(".overview");

const workSectionObserve = (entries) => {
    const [entry] = entries;
    if (!entry.isIntersecting) return;
    console.log(entries);

    const counterNum = document.querySelectorAll(".counter");
    // console.log(counterNum);
    const speed = 200;

    counterNum.forEach((curNumber) => {
        const updateNumber = () => {
            const targetNumber = parseInt(curNumber.dataset.number);
            // console.log(targetNumber);
            const initialNumber = parseInt(curNumber.innerText);
            // console.log(initialNumber);
            const incrementNumber = Math.trunc(targetNumber / speed);
            // i am adding the value to the main number
            // console.log(incrementNumber);

            if (initialNumber < targetNumber) {
                curNumber.innerText = `${initialNumber + incrementNumber}`;
                setTimeout(updateNumber, 10);
            } else {
                curNumber.innerText = `${targetNumber}`;
            }
        };
        updateNumber();
    });
};

const workSecObserver = new IntersectionObserver(workSectionObserve, {
    root: null,
    threshold: 0,
});

// workSecObserver.observe(workSection);

const toTop = document.querySelector(".to-top");

window.addEventListener("scroll", () => {
    if (window.scrollY > 390) {
        toTop.classList.add("active");
    } else {
        toTop.classList.remove("active");
    }
});


class PayPalHandler {
    constructor() {
        this.clientId =
            "AVMFTyq3FE5MbjT86Pyf75LPkRb5xiCEo3UOCb_9ADWHxtk_yWmMGl2O3_om4DCYxoBaALX8L2n66fQU";
        this.loadPayPalScript();
    }

    loadPayPalScript() {
        const script = document.createElement("script");
        script.src = `https://www.paypal.com/sdk/js?client-id=${this.clientId}&currency=USD`;
        script.async = true;
        script.onload = () => this.initializePayPalButton();
        document.body.appendChild(script);
    }

    initializePayPalButton() {
        const paypalButtonContainer = document.getElementById(
            "paypal-button-container"
        );
        if (!paypalButtonContainer) return;

        paypal
            .Buttons({
                createOrder: (data, actions) => {
                    const cartTotal = cart.calculateTotal();
                    return actions.order.create({
                        purchase_units: [
                            {
                                amount: {
                                    value: cartTotal.toFixed(2),
                                },
                            },
                        ],
                    });
                },
                onApprove: (data, actions) => {
                    return actions.order.capture().then((details) => {
                        localStorage.removeItem("cart");
                        cart.items = [];
                        cart.updateCartCount();
                    });
                },
                onError: (err) => {
                    console.error("PayPal Error:", err);
                    alert(
                        "There was an error processing your payment. Please try again."
                    );
                },
            })
            .render("#paypal-button-container");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const paypalHandler = new PayPalHandler();
});
if (document.getElementById("paypal-button-container")) {
    paypal
        .Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [
                        {
                            amount: {
                                value: cart.total.toFixed(2),
                            },
                        },
                    ],
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    localStorage.removeItem("cart");
                    cart.items = [];
                    cart.updateCartCount();
                });
            },
        })
        .render("#paypal-button-container");
}
