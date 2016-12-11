<?php 
 include("includes/functions.php");
 

$pageTitle = "Full Catalog";
$section = null;
$search = null;
$elements_per_page = 8;



if (isset($_GET["cat"])){
	if ($_GET["cat"] == "books"){
		$pageTitle = "Books";
		$section = "books";
	}
	else if ($_GET["cat"] == "movies"){
		$pageTitle = "Movies";
		$section = "movies";
	}
	else if ($_GET["cat"] == "music"){
		$pageTitle = "Music";
		$section = "music";
	}
}

if(isset($_GET["s"])){
	$search = filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING);
	$pageTitle = "Search results for \"" . $search . "\"";
}

$total_elements = get_number_of_elements($section, $search);

if($total_elements > 0){
	$current_page = 1;
	$total_pages = ceil($total_elements / $elements_per_page);
	$offset = null;




	if(isset($_GET["pg"])){
		$current_page = filter_input(INPUT_GET, "pg", FILTER_SANITIZE_NUMBER_INT);

		if($current_page <= 1){
			$offset = 0;
			$current_page = 1;
		}
		if($current_page > $total_pages)
			$current_page = $total_pages;
		$offset = $elements_per_page * ($current_page - 1);	
	}
	else{$offset = 0;}

	if(isset($search)){
		$catalog = get_search_results($search, $offset, $elements_per_page);
		if(count($catalog) == 0){
			$pageTitle = "No results found";
		}

	} else if(!isset($section)){
			$catalog = get_full_catalog($offset, $elements_per_page);
		}
	else {
		$catalog = get_category_catalog($section, $offset, $elements_per_page);
	}

	$pagination = "<div class='pagination'>Pages:"; 
	for($i = 1; $i <= $total_pages; $i++){
		if($current_page == $i){
			$pagination .= "<span>$i</span>";
		} else { 
			$pagination .= " <a href='catalog.php?pg=$i";
			if(isset($search)){
				$pagination .= "&s=" . urlencode(htmlspecialchars($search)) . "'>$i</a> ";
			} else if(isset($section)){
				$pagination .= "&cat=$section'>$i</a> ";
			} else {
				$pagination .= "'>$i</a> ";
			}
		}
	}
	$pagination .= "</div>";

} else {$catalog = null;}
include ("includes/header.php"); ?>

<div class="section catalog page">
	<div class = "wrapper">
		<h1>
			<?php 
				
					if($section != null){
						echo "<a href='catalog.php'>Full Catalog</a><span> > </span>";
					}
				echo $pageTitle;
				if($total_elements < 1){
					echo "<p>No result found</p>";
				}
			?> 
		</h1>
		<?php if($total_elements > 0 )
			echo $pagination;
		?>		
		<ul class = "items">
			<?php 
				if($total_elements > 0)
					{
						foreach($catalog as $item){
							echo get_item_html($item);
					} 
				}
			?>
		</ul>
		<br/><br/>
		<?php if($total_elements > 0 )
			echo $pagination;
		?>	
	</div>
</div>



<?php include("includes/footer.php") ?>