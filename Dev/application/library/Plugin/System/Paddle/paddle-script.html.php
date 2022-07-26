<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script type="text/javascript">
    Paddle.Setup({ vendor: ${vendor_id} });
</script>

<script>
    $(document).ready(function() {

        $('.add-payment-method').click(function() {
            Paddle.Checkout.open({
                product: 568152,
                email: "dc@mclogics.com",
                message: "Add your payment details to start your subscription",
                title: "WebNGIN",
                //country: "IN",
                //postcode: "700015",
                locale: "en",
                allowQuantity: false,
                disableLogout: true,
                passthrough: "{\"subscription\": \"88132a42-35c9-4fd6-8e3a-2a470d30a72b\"}",
                successCallback: function() {

                }
            });
            return false;
        });
    });
</script>