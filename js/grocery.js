
var elList, addLink, counter;      // Declare variables
var $ = function(id) { return document.getElementById(id); };
elList  = $('list');               // Get <ul> list                   
addLink = $('add');				   // Get add item button
counter = $('counter');            // Get item counter

function updateCount() { 		   // Define updateCount function
  var listItems;								
  listItems = elList.getElementsByTagName('li').length;  // Get total of <li>s
  counter.innerHTML = listItems;                         // Update counter
}

var selectedIngredients = [];

//import { getIngredients } from "./ingredients.js"

function addNewIngredient(x) {
	// search through the array for the product
	//getIngredients();
	console.log("addingNewIngredient");
	var valid = false;
	var arr = getIngredients();
	console.log(x);
	for(var i = 0; i < arr.length; i++) {
  		if(arr[i].includes(x)) {
    	valid = true;
    	break;
  		}
	}
	
	if (valid == false){
	alert(x + "was not found in the ingredients list")
  	}
	else { // if found in the array continue as normal
	selectedIngredients.push();
	
    var newEl = document.createElement('li');
    var deleteButton = document.createElement('a');
    deleteButton.setAttribute('href', '#');
    deleteButton.setAttribute('class', 'delete');
    deleteButton.textContent = "Delete";
   
    newEl.textContent = x;  
    newEl.appendChild(deleteButton);
    elList.appendChild(newEl);
    updateCount();
	}
}

// create a function that remove <li> element which's delete button is clicked
function removeItem(e) {
    // Check if the clicked element is a delete button
    if (e.target.nodeName.toLowerCase() == "a") {
        e.preventDefault(); // Prevent the default action for the anchor tag
        let temp = e.target.parentNode.textContent; // Get all text contents for the li element 
        let displayText = temp.replace('Delete', '').trim(); // Replace "Delete" with nothing and trim spaces
        let result = confirm("Are you sure you want to delete " + displayText + " from your ingredients?");
        if (result) {
            let elListItem = e.target.parentNode; // Get the li element
            let elList = elListItem.parentNode; // Get the ul element
            elList.removeChild(elListItem); // Remove list item from the list

			// delete the ingredient from the array
			selectedIngredients = selectedIngredients.filter(ingredient => ingredient !== displayText);
			console.log("Updated Ingredients After Delete: " + selectedIngredients);

			// update the URL for the PHP file
            const ingredientsParam = selectedIngredients.map(ingredient => `ingredient[]=${encodeURIComponent(ingredient)}`).join('&');
            const generateButton = document.getElementById('generateButton');
            generateButton.setAttribute('onclick', `window.location.href='./showRecipes.php?${ingredientsParam}'`);

            alert(displayText + " was deleted!");
        } else {
            alert(displayText + " was not deleted.");
        }
    }
	console.log("Selected Ingredients is currently: " + selectedIngredients)
    updateCount(); // Always update the count after any action
}

// Event delegation for deleting items
var el = document.getElementById('list'); // Get the shopping list
el.addEventListener('click', removeItem, false); // Directly attach the removeItem function                            		


