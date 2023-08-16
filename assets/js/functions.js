function updateCart(itemDescription, itemID, itemName, itemBrand, itemPrice, customerID, itemPhoto) {
    var cartItemDiv = document.createElement('div');
    cartItemDiv.classList.add('media');

    var cartItemImage = document.createElement('img');
    cartItemImage.classList.add('d-flex', 'mr-3');
    cartItemImage.src = 'data:image/jpeg;base64,' + itemPhoto;
    cartItemImage.width = '60';

    var cartItemBody = document.createElement('div');
    cartItemBody.classList.add('media-body');

    var cartItemTitle = document.createElement('h5');
    cartItemTitle.textContent = itemBrand + ' ' + itemName;

    var cartItemPrice = document.createElement('p');
    cartItemPrice.classList.add('price');
    cartItemPrice.innerHTML = '<span>Rs. ' + itemPrice + '</span>';

    var cartItemQuantity = document.createElement('p');
    cartItemQuantity.classList.add('text-muted');
    cartItemQuantity.textContent = 'Qty: 1';

    cartItemBody.appendChild(cartItemTitle);
    cartItemBody.appendChild(cartItemPrice);
    cartItemBody.appendChild(cartItemQuantity);

    cartItemDiv.appendChild(cartItemImage);
    cartItemDiv.appendChild(cartItemBody);

    var shoppingCartList = document.querySelector('.shopping-cart-list');

    shoppingCartList.appendChild(cartItemDiv);
    console.log('Item added to the cart: ' + itemName);
    console.log('Customer ID:'+customerID);

    const formData = new FormData();
      formData.append('Customer_ID', customerID);
      formData.append('Item_ID', itemID);
      formData.append('ItemName', itemName);
      formData.append('ItemBrand', itemBrand);
      formData.append('ItemPrice', itemPrice);
      formData.append('ItemPhoto', itemPhoto);
      formData.append('ItemQty', 1);


      fetch('http://localhost/project/update.php', {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => console.log(data))
        .catch((error) => console.error(error));
}

