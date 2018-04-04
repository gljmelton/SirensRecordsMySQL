<?php
include'connection.php';

echo 'Hello world';

$sql = "SELECT * from CD";
$result = mysql_query($sql);
$row = mysql_fetch_array($result, MYSQL_ASSOC);

var_dum($row);

function AddCD(){
  //Take values from forms in index
  $title = $_POST['CD Title'];
  $UPC = $_POST['UPC'];
  $artist = $_POST['Artist'];


  $maxID = 0; //Used to find highest CDID
  $query="SELECT CDID FROM CD ORDER BY ";
  $mysql=mysql_query($query);
  $row=mysql_fetch_assoc($result);
  $newID = $row+1;

//Insert new CD into the table
  $query="INSERT INTO CD (CDID, CDTitle, UPC, Artist)
          VALUES('$newID', '$title', '$UPC', '$artist')";
  $sql=mysql_query($query) or die("cd into cd table insert failed");

//Fetch all distributors and dates from Sales table
$query="SELECT DistID FROM Sales";
$result=mysql_query($query) or die("sales distID query failed");
$distIds=mysql_fetch_array($result, MYSQL_ASSOC);

$query="SELECT DateID FROM Sales";
$result=mysql_query($query) or die("Sales DateID query failed");
$dateIds=mysql_fetch_array($result, MYSQL_ASSOC);

//Loop through each date and dist and place new entries for the new CD
foreach($dist in $distIds){
  foreach($date in $dateIds){
    $query="INSERT INTO Sales (DistID, CDID, DateID, Units)
            VALUES('$dist', '$newID','$date', 0)"
  }
}
}

function AddDistributor(){
  //Values from form
  $distName = $_POST['DistName'];
  $CDCost = $_POST['CDCost'];

//Sort distributors by ID and select the one with the highest ID
  $query = "SELECT DistID FROM Distributor ORDER BY DistID DESC LIMIT 1";
  $mysql=mysql_query($query);
  $row=mysql_fetch_assoc($result);
  $newID = $row+1;

//Insert row to distributor
  $query="INSERT INTO Distributor (CD_Cost, DistName, DistID)
          VALUES('$distName', '$CDCost', '$newID')";

//Update sales table with new distributor
  $mysql=mysql_query($query);
  $query="SELECT CDID FROM Sales";
  $result=mysql_query($query) or die("CDID fetch failed");
  $CDIDs=mysql_fetch_array($result, MYSQL_ASSOC);

  $query="SELECT DateID FROM Sales";
  $result=mysql_query($query) or die("DateID fetch failed");
  $DateIDs=msql_fetch_array($result, MYSQL_ASSOC);

  foreach ($CDIDs as $cdid) {
    foreach($Dateid in $DateIDs){
      $query="INSERT INTO Sales (DistID, CDID, DateID, Units)
              VALUES('$newID', '$cdid', '$Dateid', 0)";
    }
  }
  }

function AddTime(){
  //Assign start and end month variables based on form
  $months=$_POST['months'];
  if ($months=="JanJun"){
    $startMonth = "January";
    $endMonth = "June";
  }
  elseif ($months=="JulDec") {
    $startMonth = "July";
    $endMonth = "December";
  }
  $year = $_POST['year'];

//Sort and select highest date ID
  $query="SELECT DateID FROM Time ORDER BY DateID DESC LIMIT 1";
  $result=mysql_query($query) or die("Max dateid get failed");
  $row = mysql_fetch_assoc($result);
  $newID = $row+1;


  $result = mysql_query("INSERT INTO Time (DateID, StartMonth, EndMonth, YR)
                          VALUES('$newID','$startMonth','$endMonth','$year')");

}

  function RemoveCD(){
    //Get values from form
    $cdName=$_POST['Remove CD Name'];
    $cdArtist=$_POST['Remove CD Artist'];
    $UPC=$_POST['Remove CD UPC'];

    //Select the desired cd id
    $query="SELECT CDID FROM CD WHERE CDTitle='$cdName', Artist='$cdArtist', UPC='$UPC'";
    $id=mysql_query($query) or die("Storing desired cd id failed");

    //Delete cd tuples from sales and cd tables where the id matches
    $query="DELETE FROM CD WHERE CDID='$id'";
    $result=mysql_query($query) or die("Remove CD from cd table failed");
    $query="DELETE FROM Sales WHERE CDID='$id'";
    $result=mysql_query($query) or die("Remove cd entries from Sales table failed")
  }

  function RemoveDistibutor(){
    //Basically the same as RemoveCD()
    $distName=$_POST['RemoveDistName'];
    $distCDCost=$_POST['RemoveDistCDCost'];

    $query="SELECT DistID FROM Distributor WHERE DistName='$distName', CD_Cost='$distCDCost'";
    $id=mysql_query($query) or die ("Storing desired Distributor id failed");

    $query="DELETE FROM Distributor WHERE DistID='$id'";
    $result=mysql_query($query) or die("Deleting distributor from distributor table failed");
    $query="DELETE FROM Sales WHERE DistID='$id'";
    $result=mysql_query($query) or die ("Deleting distributor");

  }

 ?>
