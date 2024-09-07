<?php
$election_id = $_GET['viewResult'];
?>

<div class="row my-3">
    <div class="col-12">
        <h2 style="color: white;">Election Result</h2>

        <?php
        $fetchingActiveElections = mysqli_query($db, "SELECT * FROM elections WHERE id='" . $election_id . "'") or die(mysqli_error($db));
        $totalActiveElections = mysqli_num_rows($fetchingActiveElections);
        if ($totalActiveElections > 0) {
            while ($data = mysqli_fetch_assoc($fetchingActiveElections)) {
                $election_id = $data['id'];
                $election_topic = $data['election_topic'];
                ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-green text-white">
                                <h5>ELECTION TOPIC:<?php echo strtoupper($election_topic); ?></h5>
                            </th>
                        </tr>
                        <tr>
                            <th style="color: white;">Photo</th>
                            <th style="color: white;">Candidate Details</th>
                            <th style="color: white;">No. of Votes</th>
                           <!-- <th>Action</th>-->
                    </thead>

                    <tbody>
                        <?php
                        $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id='" . $election_id . "'") or die(mysqli_error($db));

                        while ($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                            $candidate_id = $candidateData['id'];
                            $candidate_photo = $candidateData['candidate_photo'];

                            //fetching candidate votes
                            $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candidate_id ='" . $candidate_id . "'") or die(mysqli_error($db));
                            $totalVotes = mysqli_num_rows($fetchingVotes);
                            ?>
                            <tr>
                                <td style="color: white;"><img src="<?php echo $candidate_photo; ?>" class="candidate_photo"></td>
                                <td style="color: white;">
                                    <?php echo "<b>" . $candidateData['candidate_name'] . "</b><br />" . $candidateData['candidate_details']; ?>
                                </td>
                                <td style="color: white;"><?php echo $totalVotes; ?></td>
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php

            }
        } else {
            echo "No any Active Election";
        }
        ?>

        <!--admin vote checking-->
        <h3 style="color: white;">Voting Details</h3>

        <?php
        $fetchingVoteDetails = mysqli_query($db, "SELECT * FROM votings WHERE election_id ='" . $election_id . "'");
        $number_of_votes = mysqli_num_rows($fetchingVoteDetails);

        if ($number_of_votes > 0) {
            $sno = 1;
            ?>

            <table class="table">
                <tr>
                    <th style="color: white;">S.No.</th>
                    <th style="color: white;">Voter Name</th>
                    <th style="color: white;">Contact Number</th>
                    <th style="color: white;">Voted To</th>
                    <th style="color: white;">Date</th>
                    <th style="color: white;">Time</th>
                </tr>
                <?php
                while ($data = mysqli_fetch_assoc($fetchingVoteDetails)) {
                    $voters_id = $data['voters_id'];
                    $candidate_id = $data['candidate_id'];
                    $fetchingUserName = mysqli_query($db, "SELECT * FROM users WHERE id ='" . $voters_id . "'") or die(mysqli_error($db));
                    $isDataAvailable = mysqli_num_rows($fetchingUserName);
                    $userData = mysqli_fetch_assoc($fetchingUserName);

                    if ($isDataAvailable > 0) {

                        $username = $userData['username'];
                        $contact_no = $userData['contact_number'];
                    } else {
                        $username = "No_Data";
                        $contact_no = "No_Data";
                    }


                    $fetchingCandidateName = mysqli_query($db, "SELECT * FROM candidate_details WHERE id ='" . $candidate_id . "'") or die(mysqli_error($db));
                    $isDataAvailable = mysqli_num_rows($fetchingCandidateName);
                    $candidateData = mysqli_fetch_assoc($fetchingCandidateName);

                    if ($isDataAvailable > 0) {

                        $candidate_name = $candidateData['candidate_name'];

                    } else {

                        $candidate_name = "No_Data";
                    }
                    ?>
                    <tr>
                        <td style="color: white;"><?php echo $sno++; ?></td>
                        <td style="color: white;"><?php echo $username; ?></td>
                        <td style="color: white;"><?php echo $contact_no ?></td>
                        <td style="color: white;"><?php echo $candidate_name ?></td>
                        <td style="color: white;"><?php echo $data['vote_date']; ?></td>
                        <td style="color: white;"><?php echo $data['vote_time']; ?></td>
                    </tr>

                    <?php
                }
                echo "</table >";
        } else {
            echo '<p style="color: white;">Vote details not available</p>';
        }
        ?>
        </table>
        <hr>
    </div>
</div>
