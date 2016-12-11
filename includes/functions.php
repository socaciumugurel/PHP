<?php

function get_number_of_elements($category = null, $search = null){
	
	include("connection.php");

	$category = strtolower($category);

	try{
		$sql = "SELECT COUNT(media_id) FROM Media ";
		 if(!empty($search)){
		 	$result = $db->prepare($sql . " WHERE title LIKE ? ;");
		 	$result->bindValue(1, "%" . $search . "%", PDO::PARAM_STR);
		 }
		 else if(!empty($category)){
			$result = $db->prepare($sql .= "WHERE category = ? ");
			$result->bindParam(1,$category, PDO::PARAM_STR);
		}else{
			$result = $db->prepare($sql);
		}
		$result->execute();
	} catch (Exception $e){
		echo "There was an error getting the number of elements";
	}
	return $result->fetchColumn(0);
}

function get_element_by_id($id)
{
	include("connection.php");
	try{
	 	
	 		$sql = "SELECT *
			FROM media
			INNER JOIN books
			INNER JOIN genres
			INNER JOIN people
			INNER JOIN media_people
			ON (genres.genre_id = media.genre_id AND media_people.media_id = media.media_id AND media_people.people_id = people.people_id)
			WHERE media.media_id = ?";
			$result = $db->prepare($sql);
			$result->bindParam(1, $id, PDO::PARAM_INT);
	 	
	}
	 catch( Exception $e){
		echo "There was an error getting yout data from data Base";
		exit;
	}
	$result->execute();
	return $result->fetchAll(PDO::FETCH_ASSOC);
}

function get_full_catalog($offset = null, $elements_per_page = null){
	include("connection.php");

	try {

		$sql = "SELECT *
			FROM media
			/*INNER JOIN genres
			INNER JOIN people
			INNER JOIN media_people
			 ON (genres.genre_id = media.genre_id AND media_people.media_id = media.media_id AND media_people.people_id = people.people_id)*/
			ORDER BY
			REPLACE(
				REPLACE(
					REPLACE(media.title, 'The ', ''),
					 'An ', ''),
					 'A ', '');";
		if(isset($offset)){
			$result = $db->prepare($sql . " LIMIT ? OFFSET ?");
			$result->bindParam(1, $elements_per_page, PDO::PARAM_INT);
			$result->bindParam(2, $offset, PDO::PARAM_INT);
		}else{
			$result = $db->prepare($sql);
		}
		$result->execute();

	} catch( Exception $e){
		echo "There was an error getting yout data from data Base";
		exit;
	}

	return $result->fetchAll(PDO::FETCH_ASSOC);
}


function get_element($id){
	include("connection.php");

	try {

		$sql = "SELECT *
			FROM media
			INNER JOIN books
			INNER JOIN genres
			INNER JOIN people
			INNER JOIN media_people
			 ON (media.media_id = books.media_id AND genres.genre_id = media.genre_id AND media_people.media_id = media.media_id AND media_people.people_id = people.people_id)
			WHERE media.media_id = ?";
	
			$result = $db->prepare($sql);
			$result->bindParam(1, $id, PDO::PARAM_INT);
	
		$result->execute();

	} catch( Exception $e){
		echo "There was an error getting yout data from data Base";
		exit;
	}

	return $result->fetchAll(PDO::FETCH_ASSOC);
}



function get_random_catalog(){
	include("connection.php");

	try {
		$result = $db->query("
			SELECT media_id, title, category, img 
			FROM media 
			ORDER BY RAND()
			LIMIT  8;");
	} catch( Exception $e){
		echo "There was an error getting yout data from data Base";
		exit;
	}

	return $result->fetchAll(PDO::FETCH_ASSOC);
}

function get_category_catalog($category, $offset, $elements_per_page){
	include("connection.php");
	$category = strtolower($category);
	try {
		$sql = "
			SELECT media_id, title, category, img 
			FROM media
			WHERE LOWER(category) = ?
			ORDER BY
			REPLACE(
				REPLACE(
					REPLACE(title, 'The ', ''),
					 'An ', ''),
					 'A ', '')";

		if (isset($offset)){
			$result = $db->prepare($sql . " LIMIT ? OFFSET ?");
			$result->bindParam(1, $category, PDO::PARAM_INT);
			$result->bindParam(2, $elements_per_page, PDO::PARAM_INT);
			$result->bindParam(3, $offset, PDO::PARAM_INT);
		} else{
			$result = $db->prepare($sql);
			$result->bindParam(1, $category, PDO::PARAM_INT);
		}
		$result->execute();
	} catch( Exception $e){
		echo "There was an error getting yout data from Data Base";
		exit;
	}

	return $result->fetchAll(PDO::FETCH_ASSOC);
}


function get_search_results($search, $offset, $elements_per_page){
	include("connection.php");
	$search = strtolower($search);
	try {
		$sql = "
			SELECT media_id, title, category, img 
			FROM media
			WHERE title LIKE ?
			ORDER BY
			REPLACE(
				REPLACE(
					REPLACE(title, 'The ', ''),
					 'An ', ''),
					 'A ', '')";

		if (isset($offset)){
			$result = $db->prepare($sql . " LIMIT ? OFFSET ?");
			$result->bindValue(1, "%" . $search . "%", PDO::PARAM_STR);
			$result->bindParam(2, $elements_per_page, PDO::PARAM_INT);
			$result->bindParam(3, $offset, PDO::PARAM_INT);
		} else{
			$result = $db->prepare($sql);
			$result->bindValue(1, "%" . $search . "%", PDO::PARAM_STR);
		}
		$result->execute();
	} catch( Exception $e){
		echo "There was an error getting yout data from Data Base";
		exit;
	}

	return $result->fetchAll(PDO::FETCH_ASSOC);
}





function get_item_html($item){
	$output = "<li>
				<a href='details.php?id=". $item['media_id'] . "''>
					<img src='" . $item['img'] . "'
						alt='" . $item['title'] . "' />" . $item["title"] . "</a>
				</li>";

			return $output;
};

function array_category($catalog, $category){

	$output = array();
	$sort = null;

	foreach($catalog as $id => $item){
		if ($category == null OR strtolower($item["category"]) == strtolower($category)){
			$sort = $item["title"];
			$sort = ltrim($sort, "The ");
			$sort = ltrim($sort, "An ");
			$sort = ltrim($sort, "A ");
			$output[$id] = $sort;

		}
	}
	asort($output);

	return array_keys($output);
};


function get_genre($category){
	include("connection.php");
	try {
		$sql = "SELECT genres.genre FROM genres INNER JOIN genre_categories ON(genre_categories.genre_id = genres.genre_id) WHERE category = ? ORDER BY category";
		$result = $db->prepare($sql);
		$result->bindParam(1, $category, PDO::PARAM_INT);
		$result->execute();
	} catch (Exception $e) {
		echo "Problems!";
		exit;
	}
	return $result->fetchAll(PDO::FETCH_ASSOC);

}

function get_category(){
	include("connection.php");
	try {
		$sql = "SELECT category AS cat FROM genre_categories Group by category";
		$result = $db->query($sql);
	} catch (Exception $e) {
		echo "Problems!";
		exit;
	}
	return $result->fetchAll(PDO::FETCH_ASSOC);

}


?>