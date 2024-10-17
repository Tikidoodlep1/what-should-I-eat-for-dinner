<?php

include_once("./DbCredentials.php");

class DbUtils {
	private $errorReporting = false;

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

	private function Disconnect($con) {
		mysqli_close($con);
		$con = null;
	}

	public function SetErrorReporting($reportErrors) {
		if($reportErrors) {
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_INDEX);
			$this->errorReporting = true;
		}else {
			mysqli_report(MYSQLI_REPORT_OFF);
			$this->errorReporting = false;
		}
	}

	public function QueryRecipesWithIngredients($ingredients) {
		if($ingredients == null || !is_array($ingredients) || count($ingredients) <= 0) {
			echo "Ingredients must be a populated array of strings!";
			return null;
		}

		for($i = 0; $i < count($ingredients); $i++) {
			$ingredients[$i] = strtolower($ingredients[$i]);
			$ingredients[$i] = '%' . $ingredients[$i] . '%';
		}

		//var_dump($ingredients);

		$connection = $this->Connect();

		$paramString = "s";
		$queryString = "SELECT * FROM dinner.recipe WHERE ( LOWER(ingredients) LIKE ?";

		for($i = 1; $i < count($ingredients); $i++) {
			$paramString .= "s";
			$queryString .= " AND LOWER(ingredients) LIKE ?";
		}

		$queryString .= " );";

		//echo $queryString;

		$query = mysqli_prepare($connection, $queryString);

		if($query == false) {
			if($this->errorReporting) {
				var_dump(mysqli_error_list($connection));
			}
			return null;
		}

		if(!mysqli_stmt_bind_param($query, $paramString, ...$ingredients)) {
			echo "Error binding ingredients";
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
			$queryResult["id"] += $idStore;
			$queryResult["title"] += $titleStore;
			$queryResult["instructions"] += $instructionsStore;
			$queryResult["ingredients"] += $ingredientsStore;
			$queryResult["img_name"] += $img_nameStore;
		}

		mysqli_stmt_close($query);
		$this->Disconnect($connection);

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

		//echo $queryString;

		$query = mysqli_prepare($connection, $queryString);

		if($query == false) {
			if($this->errorReporting) {
				var_dump(mysqli_error_list($connection));
			}
			return null;
		}

		if(!mysqli_stmt_bind_param($query, $paramString, ...$ingredients)) {
			echo "Error binding ingredients";
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
		mysqli_stmt_bind_result($query, $queryResult["id"], $queryResult["title"], $queryResult["instructions"], $queryResult["ingredients"], $queryResult["img_name"]);
		while(mysqli_stmt_fetch($query)) {
			$queryResult["id"] += $idStore;
			$queryResult["title"] += $titleStore;
			$queryResult["instructions"] += $instructionsStore;
			$queryResult["ingredients"] += $ingredientsStore;
			$queryResult["img_name"] += $img_nameStore;
		}

		mysqli_stmt_close($query);
		$this->Disconnect($connection);

		return $queryResult;
	}
}



?>