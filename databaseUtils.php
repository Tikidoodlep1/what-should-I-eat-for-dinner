<?php

include_once("./DbCredentials.php");

$errorReporting = false;

function Connect() {
	//Connect to the DB
	$con = mysqli_init();
	mysqli_ssl_set($con, NULL, NULL, "./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
	if(mysqli_real_connect($conn, GetDbHostname(), GetDbUsername(), GetDbPassword(), GetDbName(), 3306, MYSQLI_CLIENT_SSL)) {
		//We have connection to the database in this if block
		return $con;
	}else {
		echo "Could not connect to the Database. Please check your conenction and try agin."
		return null;
	}
}

function Disconnect($con) {
	mysqli_close($con);
}

function SetErrorReporting($reportErrors) {
	if($reportErrors) {
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_INDEX);
		$errorReporting = true;
	}else {
		mysqli_report(MYSQLI_REPORT_OFF);
		$errorReporting = false;
	}
}

function QueryRecipesWithIngredients($ingredients) {
	if($ingredients == null || !is_array($ingredeints) || count($ingredients) <= 0) {
		echo "Ingredients must be a populated array of strings!"
		return null;
	}

	$connection = Connect();

	$queryString = "SELECT * FROM dinner.recipe WHERE ( ingredients LIKE '%?%'"

	for(var i = 1; i <= count($ingredients); i++) {
		$queryString .= " AND ingredients LIKE '%?%'"
	}

	$query = mysqli_prepare($connection, $queryString);

	if($query == false) {
		if($errorReporting) {
			var_dump(mysqli_error_list($connection));
		}
		return null;
	}

	for(var i = 1; i <= count($ingredients); i++) {
		if(!mysqli_stmt_bind_param($query, "s", $ingredients[i-1])) {
			echo "Error binding ingredient " . $ingredients[i-1] . " into SQL query. Parameter " . i . " of " . count($ingredients) . ".";
		}
	}

	mysqli_stmt_execute($query);
	if($errorReporting) {
		$warnings = mysqli_stmt_get_warnings($query);
		if($warnings) {
			while($warnings != false) {
				echo "Warning Error Number: " . $warnings->errno;
				echo "Warning Message: " . $warnings->message;
				echo "SQL State: " . $warnings->sqlstate;
				$warnings::next();
			}
		}

		if(count(mysqli_stmt_error_list($query)) > 0) {
			var_dump(mysqli_stmt_error_list($query));
		}
	}

	$queryResult;
	mysqli_stmt_bind_result($query, $queryResult);

	return $queryResult;
}

function QueryRecipesIncludingIngredients($ingredients) {
	if($ingredients == null || !is_array($ingredeints) || count($ingredients) <= 0) {
		echo "Ingredients must be a populated array of strings!"
		return null;
	}

	$connection = Connect();

	$queryString = "SELECT * FROM dinner.recipe WHERE ( ingredients LIKE '%?%'"

	for(var i = 1; i <= count($ingredients); i++) {
		$queryString .= " OR ingredients LIKE '%?%'"
	}

	$query = mysqli_prepare($connection, $queryString);

	if($query == false) {
		if($errorReporting) {
			var_dump(mysqli_error_list($connection));
		}
		return null;
	}

	for(var i = 1; i <= count($ingredients); i++) {
		if(!mysqli_stmt_bind_param($query, "s", $ingredients[i-1])) {
			echo "Error binding ingredient " . $ingredients[i-1] . " into SQL query. Parameter " . i . " of " . count($ingredients) . ".";
		}
	}

	mysqli_stmt_execute($query);
	if($errorReporting) {
		$warnings = mysqli_stmt_get_warnings($query);
		if($warnings) {
			while($warnings != false) {
				echo "Warning Error Number: " . $warnings->errno;
				echo "Warning Message: " . $warnings->message;
				echo "SQL State: " . $warnings->sqlstate;
				$warnings::next();
			}
		}

		if(count(mysqli_stmt_error_list($query)) > 0) {
			var_dump(mysqli_stmt_error_list($query));
		}
	}

	$queryResult;
	mysqli_stmt_bind_result($query, $queryResult);

	return $queryResult;
}

?>