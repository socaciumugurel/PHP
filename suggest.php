<?php 
include("includes/functions.php");
if ($_SERVER["REQUEST_METHOD"] == "POST"){

	$name = trim(filter_input(INPUT_POST,"name", FILTER_SANITIZE_STRING));
	$email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
	$category = trim(filter_input(INPUT_POST,"category", FILTER_SANITIZE_STRING));
	$details = trim(filter_input(INPUT_POST, "details", FILTER_SANITIZE_SPECIAL_CHARS));

//Security validation to make sure that a spammer bot is not hijacking our form to send spam to other people
	foreach( $_POST as $value ){
	    if( stripos($value,'Content-Type:') !== FALSE ){
	       $error_message = "There was a problem with the information you have entered.";
	        exit;
    	}
	}

	if($name =="" || $email == "" || $details == ""){
		$error_message = "Please fill Name, Email and Details";
	}

	if($_POST["adress"] != ""){
		$error_message = "You are a robot";
		exit;
	}




//   https://github.com/PHPMailer/PHPMailer
	require("includes/phpmailer/class.phpmailer.php");

	$mail = new PHPMailer;


//Validate the email Adress
	if(!$mail->ValidateAddress($email)){
		$error_message = "Invalid Email Adress;";
	}

	if(!isset($error_message)){



		//SENDING THE EMAIL
		$mail->setFrom('socaciu_mugurel@yahoo.com', 'Mailer');
		$mail->addAddress('mugurel.socaciu@gmail.com', 'Joe User');     // Add a recipient
		
		/*$mail->addAddress('ellen@example.com');               // Name is optional
		$mail->addReplyTo('info@example.com', 'Information');
		$mail->addCC('cc@example.com');
		$mail->addBCC('bcc@example.com');
		$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/
		

		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Here is the subject';
		$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if($mail->send()) {
			header("location:suggest.php?name=$name");
			exit;
			}
	    $error_message = 'Message could not be sent.';
	    $error_message .= 'Mailer Error: ' . $mail->ErrorInfo;
	}   
}

$pageTitle = "Suggest a media item";
$section = 'suggest';
include ("includes/header.php");
?>

	<div class="section page">
		<div class="wrapper">

			<h1>Suggest Media Item</h1>

			<?php 

			if(isset($_GET["name"])){
				echo "<p class='message'>Thank you <b>" . $_GET['name'] . "</b> for your email! I will review your suggestion and I will come back with an answer!</p>";
			}  else { ?>

			<p>If you think there is something I&rsquo;m missing, let meknow! Complete the form to send me an email;</p>
			
			<?php 
				if(isset($error_message)){
					echo "<p>There was an error: <b> $error_message</b></p>";
				}
			?>
	<form method="post" action="suggest.php">
				<table>
					<tr>
						<th><label for="name" >Name (required)</label></th>
						<td><input type="text" id="name" name="name" value="<?php if(isset($name)){echo $name;} ?>"></td>
					</tr>

					<tr>
						<th><label for="email" >Email (required)</label></th>
						<td><input type="text" id="email" name="email" value="<?php if(isset($email)){echo $email;} ?>"></td>
					</tr>

					<tr>
					   <th>
					   <label for="category">Category (required)</label></th> 
					    <td> 
						    <select id="category" name="category">
						        <option value="">Select one</option>
						        <?php
						        	foreach(get_category() as $item){
						        		$option = "<option value='" . $item["cat"] . "'";
						        	if(isset($category) && $category== $item["cat"])
						        		$option .= " selected";
						        	$option .= ">" . $item['cat'] . "</option>";
						        	echo $option;
						        	$option = "";

						        	}
						        ?>
						    </select>
						</td>
					</tr>

					<tr>
					   <th>
					   <label for="genre">Genre</label></th> 
					    <td> 
						    <select id="genre" name="genre">
						        <optgroup label="Books">
    								<?php
						        		foreach(get_genre("Books") as $item){
						        				echo "<option value='". $item["genre"] . "'>" . $item['genre'] . "</option>";
						        	}
						        ?>
  								</optgroup>

  								<optgroup label="Movies">
    								<?php
						        		foreach(get_genre("Movies") as $item){
						        				echo "<option value='". $item["genre"] . "'>" . $item['genre'] . "</option>";
						        	}
						        ?>
  								</optgroup>

  								<optgroup label="Music">
    								<?php
						        		foreach(get_genre("Music") as $item){
						        			echo "<option value='". $item["genre"] . "'>" . $item['genre'] . "</option>";
						        	}
						        ?>
  								</optgroup>
						        
						    </select>
						</td>
					</tr>

			        <tr>
			        	<th><label for="title">Title (required)</label></th> 
			       		<td><input type="text" name="title" id="title" /></td>
			    	</tr>
			        
			        <tr>
			        	<th><label for="format">Format</label></th> 
			       		<td> 
			       			<select id="format" name="format">
				         		<option value="">Select one</option>
				          		<optgroup label="Books">
				            		<option value ="Audio">Audio</option>
				             		<option value ="Ebook">Ebook</option>
				             		<option value ="Paperbag">Paperbag</option>
			            		</optgroup>
			         			<optgroup label="Movies">
			            			<option value ="western">western</option>
			             				<option value ="classic">classic</option>
			             				<option value ="thriller">thriller</option>     
			            		</optgroup>
			            	</select>
			            </td>
			        </tr>

					<tr>
						<th><label for="details" >Details</label></th>
						<td><textarea type="text" id="details" name="details"></textarea></td>
					</tr>

					<tr style="display:none">
						<th><label for="adress" >Adress</label></th>
						<td><textarea type="text" id="adress" name="adress"></textarea></td>
					</tr>
					
				</table>
			<input type="submit" value="Send">
			</form> <?php } ?>
		</div>
	</div>


<?php include ("includes/footer.php") ?>