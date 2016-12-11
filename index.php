
<?php 

$pageTitle = "Welcome to our website";
$section = null;

include ("includes/header.php"); 
include("includes/functions.php");

 ?>

		<div class="section catalog random">

			<div class="wrapper">

				<h2>May we suggest something?</h2>								
				<ul class="items">
					<?php 
					$random = get_random_catalog();
					foreach($random as $item){
			echo get_item_html($item);
			} ?>								
				</ul>

			</div>

		</div>

<?php include ("includes/footer.php"); ?>