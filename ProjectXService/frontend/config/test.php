<?php
include('Net/SSH2.php');
$uname=$_POST['username'];
$password = $_POST['password'];
$ssh = new Net_SSH2('10.10.73.16');
$ssh->login('root', 'minimum8') or die("Login failed");

//User Creation
$eee= $ssh->exec("htpasswd -b /etc/subconf/svn-auth-file $uname $password");
error_log("eeeeeeeeeeeeeeee=========".$eee);
echo $eee;
$file = '/etc/subconf/people.txt';
$projectName="ProjectX";
$current = file_get_contents($file);
//$NewProjectGroup = "[groups]\n".$projectName."_RW =";
if (preg_match('/'.$projectName.'_RW=/',$current)){
    echo 'true';
error_log("==if==");
searchWord($file, 'ProjectX_RW=', 'pra');
}else{error_log("==es==");
echo 'false';
searchWord($file, '[groups]', 'ProjectX_RW=');
}



// Search Function
function searchWord($fileName, $str, $addThis = 'string') {
    if (file_exists($fileName)) {
        $lines = file($fileName);
        foreach ($lines as $lineNo => $lineStr) {
            
            if (false !== strpos($lineStr, $str)) {
                error_log($lineNo."=before===".$lineStr);
                if($lineNo == 0){
                      $lines[$lineNo] = $lineStr . $addThis;
                }else{
                   
                     if(stripos($lines[$lineNo], $addThis) === FALSE){
                        $lines[$lineNo] = $lineStr .",". $addThis;
                        $lines[$lineNo] = str_replace("=,", "=", $lines[$lineNo]);
                     }
                }
              
                 error_log($lineNo."==after==".$lineStr);
		break;

            }
        }
        //file_put_contents($fileName, $lines);
            file_put_contents($fileName, implode("", $lines));
    }
}
//searchWord($file, '[groups]', '\nProjectX_RW');




/* --------groups logic start---------*/
/*$current1 = "[groups]\n".$projectName."_RW =";
if (preg_match('/'.$projectName.'_RW/',$current))
    echo 'true';
else{
echo 'false';
  //$current1 = "[groups]\nProjectX_RW = ";
  if (preg_match('/[groups]/',$current)){
  $holdcontents2 = str_replace('[groups]', $current1, $current); 
  file_put_contents($file, $holdcontents2); 
 }
//file_put_contents($file, $current1, FILE_APPEND | LOCK_EX);
}*/
/* --------groups logic end---------*/
?>