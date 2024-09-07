<?php
require_once("incc/header.php");
require_once("incc/nav.php");

if (isset($_GET['homepage'])) {
    require_once("incc/homepage.php");
} 
else if (isset($_GET['addElectionPage'])) {
    require_once("incc/add_elections.php");
} 
else if (isset($_GET['addCandidatePage'])) {
    require_once("incc/add_candidates.php");
} 
else if (isset($_GET['viewResult'])) {
    require_once("incc/viewResults.php");
}
?>

<?php
require_once("incc/footer.php");
?>
