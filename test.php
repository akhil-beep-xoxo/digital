<?php
echo "PHP Version: " . phpversion() . "<br><br>";

echo "<pre>";
print_r(PDO::getAvailableDrivers());
echo "</pre>";

phpinfo();
?>
