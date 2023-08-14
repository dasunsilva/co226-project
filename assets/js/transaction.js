document.addEventListener('DOMContentLoaded', function() {
    var btnDetails = document.querySelectorAll('.btn-detail');
    
    btnDetails.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var orderDetailsJSON = this.getAttribute('data-order-details');
            var orderDate = this.getAttribute('data-order-date');
            var orderDetails = JSON.parse(orderDetailsJSON);
            var orderDetailsContainer = document.getElementById('orderDetailsContainer');
            
            var billingDetails = `
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <strong>Billing Detail:</strong><br>
                            ${orderDetails["billing"]["firstName"]} ${orderDetails["billing"]["lastName"]}<br>
                            ${orderDetails["billing"]["address"]}<br>
                            ${orderDetails["billing"]["city"]}<br>
                            ${orderDetails["billing"]["state"]}<br>
                            ${orderDetails["billing"]["postcode"]}<br>
                            ${orderDetails["billing"]["email"]}<br>
                            ${orderDetails["billing"]["phone"]}<br>
                            ${orderDetails["billing"]["note"]}<br>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>Payment Method:</strong><br>
                            Direct Transfer to<br>
                            Bank: Samapath<br>
                            Acc No.: 72133236179
                        </p>
                        <p>
                            <strong>Date</strong><br>
                            ${orderDate}
                        </p>
                    </div>
                </div>
            `;
            
            var orderItems = '';
            orderDetails["orders"].forEach(function(order) {
                var totalValue = parseFloat(order["ItemPrice"]) * parseInt(order["ItemQty"]); 
                orderItems += `
                    <tr>
                        <td>
                            ${order["ItemBrand"]} ${order["ItemName"]} x${order["ItemQty"]}
                        </td>
                        <td class="text-right">
                            Rs.${totalValue}.00
                        </td>
                    </tr>
                `;
            });

            var orderTotal = parseFloat(orderDetails["subTotal"]) + 200.00;

            var orderTable = `
                <div class="row">
                    <div class="col-md-12">
                        <p>
                            <strong>Your Order:</strong>
                        </p>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Products</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${orderItems}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <strong>Cart Subtotal</strong>
                                        </td>
                                        <td class="text-right">
                                            Rs.${orderDetails["subTotal"]}.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Shipping</strong>
                                        </td>
                                        <td class="text-right">
                                            Rs.200.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>ORDER TOTAL</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong>Rs.${orderTotal.toFixed(2)}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            orderDetailsContainer.innerHTML = billingDetails + orderTable;
        });
    });
});
