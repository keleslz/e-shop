 // Create an instance of the Stripe object with your publishable API key
 var stripe = Stripe("pk_test_TYooMQauvdEDq54NiTphI7jx");
 var checkoutButton = document.getElementById("checkout-button");

 checkoutButton.addEventListener("click", function (e) {
   e.preventDefault();

   fetch("/public/stripe/createCheckoutSession", {
     method: "POST",
   })
     .then(function (response) {
      //  console.log(response)
       return response.json();
     })
     .then(function (session) {
       return stripe.redirectToCheckout({ sessionId: session.id });
     })
     .then(function (result) {
       // If redirectToCheckout fails due to a browser or network
       // error, you should display the localized error message to your
       // customer using error.message.
       if (result.error) {
        //  console.log(result)
         alert(result.error.message);
       }
     })
     .catch(function (error) {
        // console.error(error);
     });
 });

