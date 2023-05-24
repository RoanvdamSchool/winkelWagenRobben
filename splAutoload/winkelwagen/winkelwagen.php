<?php
include("openDB.php");
function doAutoLoad($Class)
{
    if (!file_exists('class/' . $Class . '.php')) {
        die("Cannot find " . $Class . '.php');
    }
    include 'class/' . $Class . '.php';
}

spl_autoload_register('doAutoLoad');

$cart = new cart();

if (isset($_GET['plus']) ) {
    $cart->addItem($_GET['plus']);
} 
elseif (isset($_GET['min']) ) {
    $cart->deleteItem($_GET['min']);
} 
else {
}


$inhoud = $cart->getCart();
foreach ($inhoud as $key => $value) {
    echo "productId $key heeft $value stuks <a href='winkelwagen.php?plus=$key'>+</a> <a href='winkelwagen.php?min=$key'>-</a><br>";
}

echo "<form method='post' action='winkelwagen.php'>
    <input type='submit' name='flush' value='flush your cart'>
</form>";
function getTotalPrice($cart, $conn) {
    $total_price = 0.0;
        foreach ($cart as $id => $quantity) {
            
            $stmt = $conn->prepare("SELECT prijs FROM product WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $productPrice = $result['prijs'];
                    $total_price += $productPrice * $quantity;
                }
    }
    return $total_price;
}

echo "the total price is €" . getTotalPrice($inhoud, $conn);
if (isset($_POST['flush']) ) {
    $cart->flush();
}
