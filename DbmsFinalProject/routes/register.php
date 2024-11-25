<!DOCTYPE html>
<html>
    <head>
        <title>Online Voting System - Registration</title>
        <link rel="stylesheet" href="../css/stylesheet.css">  <!-- Correct CSS path -->
    </head>
    <body>
        <center>
            <div id="headerSection">
                <h1>Online Voting System</h1>  
            </div>
            <hr>

            <h2>Registration</h2>
            <form action="../api/register.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Name" required>&nbsp
                <input type="number" name="mob" placeholder="Mobile" required><br><br>
                <input type="password" name="pass" placeholder="Password" required>&nbsp
                <input type="password" name="cpass" placeholder="Confirm Password" required><br><br>
                <input style="width: 31%" type="text" name="add" placeholder="Address" required><br><br>
                <div id="upload1" style="width: 30%">
                    Upload image: <input type="file" id="profile" name="image" required for="upload1">
                </div><br>
                <div id="upload" style="width: 30%">
                    Select your role:
                    <select name="role">
                        <option value="1">Voter</option>
                        <option value="2">Group</option>
                    </select><br>                   
                </div><br>
                <button id="loginbtn" type="submit" name="registerbtn">Register</button><br><br>
                Already a user? <a href="../">Login here</a>
            </form>
        </center>
    </body>
</html>
