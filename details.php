<?php 
 include("includes/data.php");
 include("includes/functions.php");


if (isset($_GET["id"]))
{
	$id = $_GET["id"];
	$catalog = get_element_by_id($id);
	if (isset($catalog))
		$item = $catalog;

	//var_dump($item);  die;
}

if(!isset($item)){
	header("location:catalog.php");
	exit;
}

$pageTitle = $item[0]['title'];
$section = null;
include ("includes/header.php"); 



?>

<div class="section page">
	<div class = "wrapper">
	<div class="breadcrumbs">
		<a href="catalog.php">Full Catalog</a>
		&gt;
		<a href="catalog.php?cat=<?= $item[0]['category']?>">  <?= $item[0]['category'] ?> </a>
		&gt;
		<?= $item[0]['title'] ?>
	</div>
		<div class="media-picture">
			<span>
				<img src ="<?=$item[0]['img'] ?>" alt="<?=$item[0]['title'] ?>"  />
			</span>
		</div>

		<div class="media-details">
			<h1><?= $item[0]['title'] ?></h1>
			<table>
				<tr>
					<th>Category</th>
					<td><?= $item[0]['category'] ?></td>
				</tr>

				<tr>
					<th>Genre</th>
					<td><?= $item[0]['genre'] ?></td>
				</tr>

				<tr>
					<th>Format</th>
					<td><?= $item[0]['format'] ?></td>
				</tr>

				<tr>
					<th>Year</th>
					<td><?= $item[0]['year'] ?></td>
				</tr>
				<?php if(strtolower($item[0]["category"]) == "books"){ ?>
				<tr>
					<th>Authors</th>
					<td><?= $item[0]['fullname']; ?></td>
				</tr>

				<tr>
					<th>Publisher</th>
					<td><?= $item[0]['publisher'] ?></td>
				</tr>

				<tr>
					<th>ISBM</th>
					<td><?= $item[0]['isbn'] ?></td>
				</tr>

				<?php } else if(strtolower($item[0]["category"]) == "movies"){ ?>

				<tr>
					<th>Director</th>
					<td>
						<?php 
							foreach ($item as $value) {
								if($value['role'] == 'director'){
									echo $value['fullname'];
									break;
								}
							} ?>
								
					</td>
				</tr>

				<tr>
					<th>Writer</th>
					<td>
						<?php 
							foreach ($item as $value) {
								if($value['role'] == 'writer'){
									echo $value['fullname'];
									break;
								}
							} ?>
					</td>
				</tr>

				

				<?php } else if(strtolower($item[0]["category"]) == "music"){ ?>

				<tr>
					<th>Artist</th>
					<td>
						<?php 
							foreach ($item as $value) {
								if($value['role'] == 'artist'){
									echo $value['fullname'];
									break;
								}
							} ?>
					</td>
				</tr>

				<?php } ?>

			</table>
		</div>
	</div>
</div>
<?php include("includes/footer.php") ?>