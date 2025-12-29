<?php
  session_start();
  // Check if the user is logged in
  if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login2.php'); // Redirect to the login page if not logged in
    exit;
  }

  // Logout functionality
  if (isset($_GET['logout'])) {
    session_destroy(); // Destroy session to log out the user
    header('Location: index.php'); // Redirect to index.php
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Product Management</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f7fc;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 80%;
      margin: 50px auto;
      padding: 30px;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    h1 {
      font-size: 28px;
      color: #333;
      margin-bottom: 20px;
      text-align: center;
    }

    .logout-button {
      text-align: right;
      margin-bottom: 20px;
    }

    .logout-button button {
      padding: 10px 20px;
      background-color: #f44336;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .logout-button button:hover {
      background-color: #e53935;
    }

    form {
      display: grid;
      gap: 15px;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      margin-bottom: 30px;
    }

    form input, form textarea, form button {
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 16px;
    }

    form input, form textarea {
      width: 100%;
    }

    form button {
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    form button:hover {
      background-color: #45a049;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      text-align: center;
      font-size: 16px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    td img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 5px;
    }

    .delete-btn {
      padding: 8px 16px;
      background-color: #f44336;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
      background-color: #e53935;
    }

    .error-message {
      color: red;
      font-size: 14px;
      text-align: center;
      margin-bottom: 20px;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .container {
        width: 90%;
        padding: 15px;
      }

      form {
        grid-template-columns: 1fr;
      }
    }

  </style>
</head>
<body>

<div class="container">
  <div class="logout-button">
    <a href="?logout=true"><button>Logout</button></a>
  </div>

  <h1>Admin Product Management</h1>

  <!-- Error Message for Failed Actions -->
  <div class="error-message" id="errorMessage"></div>

  <!-- Form to Add Product -->
  <form id="addProductForm">
    <input type="text" id="prodName" placeholder="Product Name" required>
    <input type="text" id="prodImage" placeholder="Image URL" required>
    <textarea id="prodDesc" placeholder="Product Description"></textarea>
    <input type="text" id="type" placeholder="Product Type">
    <input type="number" id="price" placeholder="Price" required>
    <input type="text" id="bigImageNames" placeholder="Additional Images (optional)">
    <button type="submit">Add Product</button>
  </form>

  <!-- Table to Display Products -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Image</th>
        <th>Type</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="productTable"></tbody>
  </table>
</div>

<script>
  // Fetch products from the database
  const fetchProducts = () => {
    fetch('getProducts.php')
      .then(res => res.json())
      .then(data => {
        const table = document.getElementById('productTable');
        table.innerHTML = ''; // Clear the existing table data
        data.forEach(product => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${product.prodId}</td>
            <td>${product.prodName}</td>
            <td><img src="${product.prodImage}" alt="${product.prodName}"></td>
            <td>${product.type}</td>
            <td>$${product.price}</td>
            <td><button class="delete-btn" onclick="deleteProduct(${product.prodId})">Delete</button></td>
          `;
          table.appendChild(row);
        });
      })
      .catch(err => {
        showError('Failed to fetch products. Please try again later.');
      });
  };

  // Add a new product
  const addProduct = event => {
    event.preventDefault();
    const newProduct = {
      prodName: document.getElementById('prodName').value,
      prodImage: document.getElementById('prodImage').value,
      prodDesc: document.getElementById('prodDesc').value,
      type: document.getElementById('type').value,
      price: parseInt(document.getElementById('price').value),
      bigImageNames: document.getElementById('bigImageNames').value,
    };

    fetch('insertProduct.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(newProduct),
    })
      .then(res => res.json())
      .then(() => {
        fetchProducts();
        clearForm();
      })
      .catch(err => {
        showError('Failed to add product. Please try again later.');
      });
  };

  // Delete a product
  const deleteProduct = prodId => {
    fetch('deleteProduct.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ prodId }),
    })
      .then(res => res.json())
      .then(() => fetchProducts())
      .catch(err => {
        showError('Failed to delete product. Please try again later.');
      });
  };

  // Show error messages
  const showError = (message) => {
    const errorMessageElement = document.getElementById('errorMessage');
    errorMessageElement.textContent = message;
  };

  // Clear form fields
  const clearForm = () => {
    document.getElementById('addProductForm').reset();
  };

  // Event listener for form submission
  document.getElementById('addProductForm').addEventListener('submit', addProduct);

  // Fetch products on page load
  window.onload = fetchProducts;
</script>

</body>
</html>
