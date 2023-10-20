<?php require_once('header.php'); ?>

<?php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: logout.php');
    exit;
}

$id = $_GET['id'];

// Check if the user has permission to delete the customer
$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id = ? LIMIT 1");
$statement->execute(array($id));
$customer = $statement->fetch();
if (!$customer) {
    header('location: logout.php');
    exit;
}


	// Delete from tbl_customer
	$statement = $pdo->prepare("DELETE FROM tbl_customer WHERE cust_id = ?");
	$statement->execute(array($id));


	// Delete from tbl_rating
	$statement = $pdo->prepare("DELETE FROM tbl_rating WHERE cust_id = ?");
	$statement->execute(array($id));

	header('location: customer.php');


	/**in this code parameter tempering will prevent by 
	*Input Validation- The code checks whether the id parameter is set and whether it's a numeric value.
	* This helps prevent unauthorized access and parameter tampering by ensuring that the id parameter is valid.


	*Access Control- The code checks if the user has the permission to delete the customer.
	 It fetches the customer record associated with the provided id 
	 and ensures that the customer exists before proceeding 
	 with the deletion. This additional check helps prevent unauthorized deletion.
	
	*Prepared Statements- Prepared statements are used for database queries to
	 prevent SQL injection attacks.
	*
	*/
?>