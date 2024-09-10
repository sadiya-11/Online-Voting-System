<?php
require_once("incc/config.php");

// Handle Candidate Edit
if (isset($_GET['candidate_id'])) {
    $id = mysqli_real_escape_string($db, $_GET['candidate_id']);
    $query = "SELECT * FROM candidate_details WHERE id='$id'";
    $result = mysqli_query($db, $query);
    $candidate = mysqli_fetch_assoc($result);
}

if (isset($_POST['updateCandidateBtn'])) {
    $candidate_id = mysqli_real_escape_string($db, $_POST['candidate_id']);
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
    $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);
    $candidate_photo = $candidate['candidate_photo']; // Default to existing photo

    if (!empty($_FILES['candidate_photo']['name'])) {
        // Handle file upload
        $targetted_folder = "../assets/img/candidate_photos/";
        $photoName = rand(1111111111, 9999999999) . "_" . rand(1111111111, 9999999999) . "_" . basename($_FILES['candidate_photo']['name']);
        $targetFile = $targetted_folder . $photoName;
        $photoTmpName = $_FILES['candidate_photo']['tmp_name'];

        // Move uploaded file and update the candidate photo path
        if (move_uploaded_file($photoTmpName, $targetFile)) {
            $candidate_photo = $targetFile; // Update photo path
        }
    }

    // Prepare the SQL query to update the candidate details
    $query = "UPDATE candidate_details SET election_id='$election_id', candidate_name='$candidate_name', candidate_details='$candidate_details', candidate_photo='$candidate_photo' WHERE id='$candidate_id'";
    mysqli_query($db, $query) or die(mysqli_error($db)); // Execute the query and handle any errors

    // Redirect to the index page with a success indicator
    header("Location: index.php?addCandidatePage=1&edit=success");
    exit(); // Stop further script execution
}
?>

<!-- Candidate Edit Form -->
<?php if (isset($_GET['candidate_id'])): ?>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="candidate_id" value="<?php echo htmlspecialchars($candidate['id']); ?>" />
    <div class="form-group">
        <select class="form-control" name="election_id" required>
            <?php
            // Fetch all elections to populate the dropdown
            $fetchingElections = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
            while ($row = mysqli_fetch_assoc($fetchingElections)) {
                $selected = ($candidate['election_id'] == $row['id']) ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['election_topic']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control" value="<?php echo htmlspecialchars($candidate['candidate_name']); ?>" required />
    </div>
    <div class="form-group">
        <input type="file" name="candidate_photo" class="form-control" />
        <!-- Existing photo -->
        <img src="<?php echo htmlspecialchars($candidate['candidate_photo']); ?>" class="candidate_photo" style="max-width: 200px; max-height: 150px; display: block; margin-top: 10px;" />

    </div>
    <div class="form-group">
        <input type="text" name="candidate_details" placeholder="Candidate Details" class="form-control" value="<?php echo htmlspecialchars($candidate['candidate_details']); ?>" required />
    </div>
    <input type="submit" value="Update Candidate" name="updateCandidateBtn" class="btn btn-success" />
</form>
<?php endif; ?>
