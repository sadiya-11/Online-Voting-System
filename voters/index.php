<?php
require_once("inc/header.php");
require_once("inc/nav.php");
?>
<div class="row my-3">
    <div class="col-12">
        <h2 style="color: white;">Voters Panel</h2>

        <?php
$fetchingActiveElections=mysqli_query($db,"SELECT * FROM elections WHERE status='Active'")or die(mysqli_error($db));
$totalActiveElections = mysqli_num_rows($fetchingActiveElections);
if($totalActiveElections>0)
{
    while($data=mysqli_fetch_assoc($fetchingActiveElections))
    {
        $election_id= $data['id'];
        $election_topic=$data['election_topic'];
    
?>
<table class="table">
<thead>
    <tr>
        <th colspan="4" class="bg-green text-white"><h5>ELECTION TOPIC: <?php echo strtoupper($election_topic); ?></h5></th>
    </tr>
    <tr>
        <th style="color: white;">Photo</th>
        <th style="color: white;">Candidate Details</th>
        <th style="color: white;">No. of Votes</th>
        <th style="color: white;">Action</th>

</thead>
<tbody>
    <?php
    $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id='".$election_id."'") or die(mysqli_error($db));

    while($candidateData = mysqli_fetch_assoc($fetchingCandidates))
    {
        $candidate_id = $candidateData['id'];
        $candidate_photo=$candidateData['candidate_photo'];

        //fetching candidate votes
        $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candidate_id ='".$candidate_id."'") or die (mysqli_error($db));
        $totalVotes= mysqli_num_rows($fetchingVotes);
?>
<tr>
    <td><img src="<?php echo $candidate_photo;?>" class="candidate_photo"></td>
    <td style="color: white;"><?php echo "<b>". $candidateData['candidate_name'] . "</b><br />" .  $candidateData['candidate_details'];?></td>
    <td style="color: white;"><?php echo $totalVotes; ?></td>
    <td>
        <?php
        $checkIfVoteCasted=mysqli_query($db,"SELECT * FROM votings WHERE voters_id ='".$_SESSION['user_id']."'AND election_id='".$election_id."'") or die(mysqli_error($db));

        $isVoteCasted= mysqli_num_rows($checkIfVoteCasted);
       
        if($isVoteCasted>0)
        {
            $voteCastedData=mysqli_fetch_assoc($checkIfVoteCasted);
            $voteCastedToCandidate=$voteCastedData['candidate_id'];

            if($voteCastedToCandidate==$candidate_id)
            {

                ?>
<img src="../assets/img/vt.gif" width="100px;">
                <?php
            }

        }else{
          ?>
              <button class="btn btn-md btn-success" onclick="CastVote(<?php echo $election_id; ?>, <?php echo $candidate_id; ?>, <?php echo $_SESSION['user_id']; ?>)">Vote</button>
          
          <?php
        }
?>
</td>
</tr>

<?php
        
    }
    ?>
</tbody>
        </table>

<?php
    }
}else{
    echo '<p style="color: white;">No any Active Election';
}
?>
        
    </div>
</div>

<script>
const CastVote =(election_id, customer_id, voters_id) =>
{
  $.ajax({
    type:"POST",
    url:"inc/ajaxCalls.php",
    data:"e_id=" + election_id+ "&c_id=" + customer_id + "&v_id=" + voters_id,
    success: function(response){
       if(response=="success")
       {
        location.assign("index.php?voteCasted=1");
       }else{
        location.assign("index.php?voteNotCasted=1");
       }
        
    }

  });
}

</script>

<?php
require_once("inc/footer.php");

?>                 
