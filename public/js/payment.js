// const stripe = Stripe("pk_test_51KaxGsGzMoPIs4eyPR2O5rTz1PT1doxDSfVdduHNbiF8hh8uhk2tvjwz3n5Ts5KK7tUTtJgFNg4RdgYHfHfmGT2t00wovYquWE");
const stripe = Stripe(stripePublicKey);

function initialize() {
    document
        .querySelector("#payment-form")
        .addEventListener("submit", handleSubmit);

    elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create("payment");

    paymentElement.mount("#payment-element");
}

async function handleSubmit(e) {
    e.preventDefault();
    const {error} = await stripe.confirmPayment({
        elements,
        confirmParams: {
            // return_url: "{{ url('purchase_payment_success', {'id': purchase.id}) }}",
            return_url: redirectAfterSuccessUrl,
        },
    });
}

async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get(
        "payment_intent_client_secret"
    );

    if (!clientSecret) {
        return;
    }

    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

    switch (paymentIntent.status) {
        case "succeeded":
            console.log("Payment succeeded!");
            break;
        case "processing":
            console.log("Your payment is processing.");
            break;
        case "requires_payment_method":
            console.log("Your payment was not successful, please try again.");
            break;
        default:
            console.log("Something went wrong.");
            break;
    }
}

initialize();
checkStatus();
