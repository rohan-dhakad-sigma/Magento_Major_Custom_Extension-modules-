var config = {
config: {
    mixins: {
        'Magento_Checkout/js/action/place-order': {
            'Task_OrderComments/js/order/place-order-mixin': true
        },
        'Magento_Checkout/js/action/set-payment-information': {
            'Task_OrderComments/js/order/set-payment-information-mixin': true
        }
    }
}
};