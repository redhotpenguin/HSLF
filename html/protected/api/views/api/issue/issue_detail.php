<?php
//the output of this file will be presented in the issue detail payload

// candidate_issue structure: name,  value, detail
// accessing to the original candidate: $candidate_issue->candidate->full_name


 echo $candidate_issue->candidate->full_name.' has voted '. $candidate_issue->value .' on ' . $candidate_issue->name;
