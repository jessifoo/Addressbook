<html>

<head>
	<title>Address Book</title>
	<link rel="stylesheet" type="text/css" href="index.css">
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="addressbook.js"></script>
</head>

<body>

<div id="Layer1"> 
	<h1>Address Book</h1> 
	<div id="addContact" style="text-align: center;"> 
		<a href="#add-contact" id="add-contact-btn">Add Contact</a> 
	        <a href="#update-count" id="update-count-btn">Update Follower Counts</a> 
	        <table id="add-contact-form"> 
	              <tr><td>Name:</td><td><input type="text" name="name" id="name"  /></td></tr> 
	              <tr><td>Phone Number:</td><td><input type="text" name="phone" id="phone"  /></td></tr> 
	              <tr><td>Twitter Handle:</td><td><input type="text" name="twitter" id="twitter"  /></td></tr> 
	              <tr><td>&nbsp;</td><td> 
	              <a href="#save-contact" id="save-contact-btn">Save Contact</a>
	              <a href="#cancel" id="cancel-btn">Cancel</a> 
	              </td></tr> 
	        </table> 
	</div> 
	<div id="notice"> 
		<!--notice box -->
	</div> 

	<ul style="list-style: none;"><li>
		<table id="headerTable"><tr>
			<td width='200px'>Name</td>
			<td width='200px'>Phone</td>
			<td width='200px'>Twitter Handle</td>
			<td width='200px'>Follower Count</td>
			<td width='100px'>Admin</td>
		</tr></table>
	</li></ul>

	<ul id="contacts-lists"> 
        
	</ul> 
</div> <!-- Layer1 -->

</body>
</html>