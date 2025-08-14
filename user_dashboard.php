<?php
// db.php - database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "digital_dairy";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!-- index.php -->
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- HEADER -->
<header>
    <div class="topbar">
        <div class="logo">🐄 Digital Dairy</div>
        <input type="text" placeholder="I'm shopping for...">
        <div class="contact">
            <p>📞 +91 94460 56114</p>
        </div>
        <div class="cart">🛒 ₹0.00</div>
        <div class="auth"><a href="#">Login</a> | <a href="#">Register</a></div>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-text">
        <h1>Fresh Dairy Products</h1>
        <p>Pure, Healthy, and Farm Fresh</p>
        <a href="#" class="btn">Shop Now</a>
    </div>
    <div class="hero-img">
        <img src="images/ghee.png" alt="Ghee">
    </div>
</section>

<!-- ABOUT -->
<section class="about">
    <h2>Our Farm Creates the Best Dairy Products</h2>
    <p>We are committed to delivering high-quality milk, ghee, chocolates, and other dairy products straight from our farm to your table.</p>
</section>

<!-- CATEGORIES -->
<section class="categories">
    <div class="cat">
        <img src="images/milk.png" alt="">
        <h3>Milk</h3>
        <a href="#">Shop Now</a>
    </div>
    <div class="cat">
        <img src="images/ghee.png" alt="">
        <h3>Ghee</h3>
        <a href="#">Shop Now</a>
    </div>
    <div class="cat">
        <img src="images/cake.png" alt="">
        <h3>Cake</h3>
        <a href="#">Shop Now</a>
    </div>
</section>

<!-- BEST PRODUCTS -->
<section class="products">
    <h2>Our Best Products</h2>
    <div class="product-list">
        <?php
        $sql = "SELECT * FROM products LIMIT 4";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>
                    <img src='images/{$row['image']}' alt='{$row['name']}'>
                    <h4>{$row['name']}</h4>
                    <p>₹{$row['price']}</p>
                    <button>Add to cart</button>
                  </div>";
        }
        ?>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© 2025 Digital Dairy. All Rights Reserved.</p>
</footer>

</body>
</html>
