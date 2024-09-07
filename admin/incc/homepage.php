<div class="row my-3">
    <div class="col-12">
        <h3 style="color: white;">Elections</h3>
        <table class="table">
  <thead>
    <tr>
      <th scope="col" style="color: white;">S.No</th>
      <th scope="col" style="color: white;">Election Name</th>
      <th scope="col" style="color: white;"> No. Of Candicates</th>
      <th scope="col" style="color: white;">Starting Date</th>
      <th scope="col" style="color: white;">Ending Date</th>
      <th scope="col" style="color: white;">Status</th>
      <th scope="col" style="color: white;">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
  $fetchingData = mysqli_query($db,"SELECT * FROM elections") or die(mysqli_error($db));
$isAnyElectionAdded=mysqli_num_rows($fetchingData);

if($isAnyElectionAdded>0)
{
    $sno=1;
while($row =mysqli_fetch_assoc($fetchingData))
{
  $election_id=$row['id'];
?>
<tr>
    <td style="color: white;"> <?php  echo $sno++;  ?>  </td>
    <td style="color: white;"> <?php  echo $row['election_topic'];?></td>
    <td style="color: white;"> <?php  echo $row['no_of_candidates']; ?></td>
    <td style="color: white;"> <?php  echo $row['starting_date']; ?></td>
    <td style="color: white;"> <?php  echo $row['ending_date']; ?></td>
    <td style="color: white;"> <?php  echo $row['status']; ?></td>
    <td>
        <a href="index.php?viewResult=<?php echo $election_id; ?>" class="btn btn-sm btn-success">View Results</a>
    </td>
    
</tr>
    <?php
}
}else{
    ?>
<tr>
<td colspan="7" style="color: white;">No any election added yet! </td>

</tr>
    <?php
}
  ?>
  </tbody>
</table>
    </div>
</div>
