<?php

include_once("./DbCredentials.php");

class DbUtils {
	private $errorReporting = false;

	//Make a connection to the DB
	private function Connect() {
		//Connect to the DB
		$con = mysqli_init();
		mysqli_ssl_set($con, NULL, NULL, "./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
		if(mysqli_real_connect($con, GetDbHostname(), GetDbUsername(), GetDbPassword(), GetDbName(), 3306, MYSQLI_CLIENT_SSL)) {
			//We have connection to the database in this if block
			return $con;
		}else {
			echo "Could not connect to the Database. Please check your conenction and try agin.";
			return null;
		}
	}

	//Don't force the DB to time the session out PLEASE
	private function Disconnect($con) {
		mysqli_close($con);
		$con = null;
	}

	//Sets the error reporting mode for SQL Queries
	public function SetErrorReporting($reportErrors) {
		if($reportErrors) {
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$this->errorReporting = true;
		}else {
			mysqli_report(MYSQLI_REPORT_OFF);
			$this->errorReporting = false;
		}
	}

	//Queries recipes that must contain ALL of the listed ingredients.
	public function QueryRecipesWithIngredients($ingredients) {
		//Check the input on $ingredients and return null if it isn't valid
		if($ingredients == null || !is_array($ingredients) || count($ingredients) <= 0) {
			echo "Ingredients must be a populated array of strings!";
			return null;
		}

		//Prepare the $ingredients array to be injected into an SQL Query
		for($i = 0; $i < count($ingredients); $i++) {
			//Make all $ingredients entries lowercase
			$ingredients[$i] = strtolower($ingredients[$i]);
			//Add % to the beginning and end of all entries to search for any string surrounding each ingredient entry
			$ingredients[$i] = '%' . $ingredients[$i] . '%';
		}

		//Perform the connection
		$connection = $this->Connect();

		//s is used to mark that a "?" in an SQL statement should be filled with a string
		$paramString = "s";
		//The start of the statement where we select all columns from recipe table and match ingredient entries with lowercase columns from the db
		$queryString = "SELECT * FROM dinner.recipe WHERE ( LOWER(ingredients) LIKE ?";

		for($i = 1; $i < count($ingredients); $i++) {
			//add another s to the $paramString so we know that strlen($paramString) is the number of "?" to replace from the SQL string.
			$paramString .= "s";
			//Add another ingredient AND case for each present ingredient
			$queryString .= " AND LOWER(ingredients) LIKE ?";
		}
		//Finish the SQL statement
		$queryString .= " );";

		//prepare the statement as is
		$query = mysqli_prepare($connection, $queryString);

		//$query will be false if an error occurs - report it and return null
		if($query == false) {
			if($this->errorReporting) {
				echo "Couldn't prepare statement!";
				var_dump(mysqli_error_list($connection));
			}
			return null;
		}

		//Bind the parameters to the query. This is where we're actually replacing the "?" in the $queryString.
		//Using mysqli_stmt_bind_param ensures that all $ingredients entries are taken as plaintext strings to prevent SQL injection.
		if(!mysqli_stmt_bind_param($query, $paramString, ...$ingredients)) {
			//If we can't bind the ingredients, report it and return null;
			echo "Error binding ingredients";
			return null;
		}

		//Execute our query - send it to the DB and get the results.
		mysqli_stmt_execute($query);
		//If we report errors, report any errors that the query execution made.
		if($this->errorReporting) {
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

		$queryResult = [];
		$queryResult["id"] = [];
		$queryResult["title"] = [];
		$queryResult["instructions"] = [];
		$queryResult["ingredients"] = [];
		$queryResult["img_name"] = [];
		//Bind the results to temp variables - These will be updated as mysqli_stmt_fetch is iterated through.
		mysqli_stmt_bind_result($query, $idStore, $titleStore, $instructionsStore, $ingredientsStore, $img_nameStore);

		//mysqli_stmt_fetch essentially stores a list of returned results and stores it into the passed temp variables as its called, then it returns true if variables were stored, false otherwise.
		while(mysqli_stmt_fetch($query)) {
			array_push($queryResult["id"], $idStore);
			array_push($queryResult["title"], $titleStore);
			array_push($queryResult["instructions"], $instructionsStore);
			array_push($queryResult["ingredients"], $ingredientsStore);
			array_push($queryResult["img_name"], $img_nameStore);
		}

		//We need to close the statement and the connection.
		mysqli_stmt_close($query);
		$this->Disconnect($connection);

		//Return the 2d associative arrays we're stored the data in.
		return $queryResult;
	}

	public function QueryRecipesIncludingIngredients($ingredients) {
		if($ingredients == null || !is_array($ingredients) || count($ingredients) <= 0) {
			echo "Ingredients must be a populated array of strings!";
			return null;
		}

		for($i = 0; $i < count($ingredients); $i++) {
			$ingredients[$i] = strtolower($ingredients[$i]);
			$ingredients[$i] = '%' . $ingredients[$i] . '%';
		}

		$connection = $this->Connect();

		$paramString = "s";
		$queryString = "SELECT * FROM dinner.recipe WHERE ( LOWER(ingredients) LIKE ?";

		for($i = 1; $i < count($ingredients); $i++) {
			$paramString .= "s";
			$queryString .= " OR LOWER(ingredients) LIKE ?";
		}

		$queryString .= " );";

		$query = mysqli_prepare($connection, $queryString);

		if($query == false) {
			if($this->errorReporting) {
				echo "Couldn't prepare statement!";
				var_dump(mysqli_error_list($connection));
			}
			return null;
		}

		if(!mysqli_stmt_bind_param($query, $paramString, ...$ingredients)) {
			echo "Error binding ingredients";
			return null;
		}

		mysqli_stmt_execute($query);
		if($this->errorReporting) {
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

		$queryResult = [];
		$queryResult["id"] = [];
		$queryResult["title"] = [];
		$queryResult["instructions"] = [];
		$queryResult["ingredients"] = [];
		$queryResult["img_name"] = [];
		mysqli_stmt_bind_result($query, $idStore, $titleStore, $instructionsStore, $ingredientsStore, $img_nameStore);

		while(mysqli_stmt_fetch($query)) {
			array_push($queryResult["id"], $idStore);
			array_push($queryResult["title"], $titleStore);
			array_push($queryResult["instructions"], $instructionsStore);
			array_push($queryResult["ingredients"], $ingredientsStore);
			array_push($queryResult["img_name"], $img_nameStore);
		}

		mysqli_stmt_close($query);
		$this->Disconnect($connection);

		return $queryResult;
	}
}



?>