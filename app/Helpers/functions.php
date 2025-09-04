<?php

function check_is_ajax($request){
    if(!$request->isXmlHttpRequest()){
      return true;
    }
    return false;
}

function getOS() { 

    global $user_agent;
	$user_agent=$_SERVER['HTTP_USER_AGENT'];
    $os_platform    =   "Unknown OS Platform";
	$os_array = array(
		'/windows nt 10.0/i' => 'Windows 10',
		'/windows nt 6.3/i' => 'Windows 8.1',
		'/windows nt 6.2/i' => 'Windows 8',
		'/windows nt 6.1/i' => 'Windows 7',
		'/windows nt 6.0/i' => 'Windows Vista',
		'/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
		'/windows nt 5.1/i' => 'Windows XP',
		'/windows xp/i' => 'Windows XP',
		'/windows nt 5.0/i' => 'Windows 2000',
		'/windows me/i' => 'Windows ME',
		'/win98/i' => 'Windows 98',
		'/win95/i' => 'Windows 95',
		'/win16/i' => 'Windows 3.11',
		'/macintosh|mac os x/i' => 'Mac OS X',
		'/mac_powerpc/i' => 'Mac OS 9',
		'/linux/i' => 'Linux',
		'/ubuntu/i' => 'Ubuntu',
		'/iphone/i' => 'iPhone',
		'/ipod/i' => 'iPod',
		'/ipad/i' => 'iPad',
		'/android/i' => 'Android',
		'/blackberry/i' => 'BlackBerry',
		'/webos/i' => 'Mobile'
	);

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser() {
	
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
	$useragent=$_SERVER['HTTP_USER_AGENT'];

	$deviceType='Desktop';
	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
		$deviceType='Mobile';
	}
	if($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10') {
    	$deviceType='Tablet';
	}
	if(stristr($_SERVER['HTTP_USER_AGENT'], 'Mozilla/5.0(iPad;')) {
		$deviceType='Tablet';
	}

	//$detect = new Mobile_Detect();
	
	
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'IE'; 
        $ub = "MSIE"; 
    } 
    else if(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla'; 
        $ub = "Firefox"; 
    } 
    else if(preg_match('/Chrome/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent))) 
    { 
        $bname = 'Chrome'; 
        $ub = "Chrome"; 
    } 
    else if(preg_match('/Safari/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent))) 
    { 
        $bname = 'Safari'; 
        $ub = "Safari"; 
    } 
    else if(preg_match('/Opera/i',$u_agent) || preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    else if(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
	else if((isset($u_agent) && (strpos($u_agent, 'Trident') !== false || strpos($u_agent, 'MSIE') !== false)))
	{
		$bname = 'Internet Explorer'; 
        $ub = 'Internet Explorer'; 
	} 
	

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= @$matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern,
		'device'=>$deviceType
    );

} 

function getUserImage($user_image,$type=false){
    $httpPath = asset('assets/storage/avtar/');

	if($user_image!="" and file_exists(storage_path('app/public/avtar/'.$user_image))){
        if($type=='thumb'){
            $image_url = str_replace('-', 'thumb_-', $user_image);
        }
        else{
            $image_url = $user_image;
        }
    }
	else{
		$image_url = "default.png";
	}
	switch($full_url){		
		default: return  $image_url = $httpPath.'/'.$image_url; break;
	}
}
function getmonthdates()
{
	for($i=29;$i>=0;$i--)
	{
		$month_dates[]=date("Y-m-d", strtotime("-".$i." days"));
	}
	return $month_dates;
}
function getlastsevenday()
{
	for($i=6;$i>=0;$i--)
	{
		$seven_dates[]=date("Y-m-d", strtotime("-".$i." days"));
	}
	return $seven_dates;
}

function checkSocialLink($validUrl,$type){
	if($type=='facebook')
		$UrlCheck = '/^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/';
	else if($type=='twitter')
		$UrlCheck = '/^(https?:\/\/)?(www\.)?twitter.com\/[a-zA-Z0-9(\.\?)?]/';
	else if($type=='instagram')
		$UrlCheck = '/^(https?:\/\/)?(www\.)?instagram.com\/[a-zA-Z0-9(\.\?)?]/';
	else if($type=='linkedin')
		$UrlCheck = '/^(https?:\/\/)?(www\.)?linkedin.com\/[a-zA-Z0-9(\.\?)?]/';		
	else if($type=='youtube')
		$UrlCheck = '/^(https?:\/\/)?(www\.)?youtube.com\/[a-zA-Z0-9(\.\?)?]/';		
	if(preg_match($UrlCheck, $validUrl) == 1) {
		return 1;
	} else {
		return 0;
	}
}

function strcleaner($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function encodeProfileUrl($profileName){
	$profileName = str_replace(' ', '+', $profileName);
	$profileName=preg_replace('/[^A-Za-z0-9_+\-]/', '', $profileName);
	return ($profileName);
}

function prn($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function pr($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function prd($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	die;
}

function gcm($var){
	if (is_object($var))
		$var = get_class($var);
	echo '<pre>';
	prn(get_class_methods($var));
	echo '</pre>';
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function captchaChecker($user_response, $secret_key) {
    $fields_string = '';
    $fields = array(
        'secret' => $secret_key,
        'response' => $user_response
    );
    foreach($fields as $key=>$value)
    $fields_string .= $key . '=' . $value . '&';
    $fields_string = rtrim($fields_string, '&');

    // prd($fields_string);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

function wordkeeper($string){
    $s = substr($string, 0, 100);
    $result = substr($s, 0, strrpos($s, ' '));
    return $result;
}

function getNameOnTypeBasis($user,$fullOrFirst='first'){
    if($user->signup_type=='individual'){
        if($fullOrFirst=='first'){
            return $user->first_name;
        }
        else{
            return $user->first_name.' '.$user->last_name;
        }
    }
    else{
        if($user->signup_type=='business'){
            $firstManager = DB::table('users_managers')->where('user_id',$user->id)->get()->first();
            if($fullOrFirst=='first'){
                $firstManagerName = $firstManager->first_name;
            }
            else{
                $firstManagerName = $firstManager->first_name.' '.$firstManager->last_name;
            }
            
            if($user->type=='client'){
                return $user->business_name;
            }
            else{
                return $firstManagerName;
            }
        }
        elseif($user->signup_type=='home'){
            return $user->retirement_home;
        }
    }
}

function getNameOnTypeUserIdBasis($userId,$fullOrFirst='first'){
    $user = DB::table('users')->where('users.id',$userId)->get()->first();
    if($user->signup_type=='individual'){
        if($fullOrFirst=='first'){
            return $user->first_name;
        }
        else{
            return $user->first_name.' '.$user->last_name;
        }
    }
    else{
        if($user->signup_type=='business'){
            $firstManager = DB::table('users_managers')->where('user_id',$user->id)->get()->first();
            if($fullOrFirst=='first'){
                $firstManagerName = $firstManager->first_name;
            }
            else{
                $firstManagerName = $firstManager->first_name.' '.$firstManager->last_name;
            }
            
            if($user->type=='client'){
                return $user->business_name;
            }
            else{
                return $firstManagerName;
            }
        }
        elseif($user->signup_type=='home'){
            return $user->retirement_home;
        }
    }
}

function getDobOnTypeBasis($user){
    if($user->signup_type=='individual'){
        return $user->dob;
    }
    else{
        $firstManager = DB::table('users_managers')->where('user_id',$user->id)->get()->first();
        $firstManagerDob = $firstManager->dob;
        return $firstManagerDob;
    }
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

function profilePercentageCalculator($userData){
    $percentage = '';
    $leverageValPerKey = 0;
    if($userData->type=='client'){
        $leverageValPerKey = 20;
    }
    else{
        $leverageValPerKey = 12.50;
    }

    $counter = 0;
    $allKeys = [
        'bg_check_status'   => 0,
        'email_verified'    => 0,
        'phone_number'      => 0,
        'profile_image'     => 0,
        'stripe_account_id' => 0,
        'id_proof'          => 0
    ];
    if($userData->bg_check_status=='1'){
        $counter = $counter + 1;
        $allKeys['bg_check_status'] = 1;
    }

    if($userData->email_verified=='1'){
        $counter = $counter + 1;
        $allKeys['email_verified'] = 1;
    }

    if(!empty($userData->phone_number)){
        $counter = $counter + 1;
        $allKeys['phone_number'] = 1;
    }    

    if(!empty($userData->profile_image)){
        $counter = $counter + 1;
        $allKeys['profile_image'] = 1;
    }

    //prd($userData);

    if(!empty($userData->bg_legal_file) && $userData->id_check_status=='1'){
        $counter = $counter + 1;
        $allKeys['id_proof'] = 1;
    }

    if($userData->type=='provider'){
        $allKeys['services']    = 0;
        $allKeys['experience']  = 0;
        $allKeys['stripe_account_id'] = 0;

        if(!empty($userData->services)){
            $counter = $counter + 1;
            $allKeys['services'] = 1;
        }

        if(!empty($userData->experience)){
            $counter = $counter + 1;
            $allKeys['experience'] = 1;
        }

        if(!empty($userData->stripe_account_id)){
            $counter = $counter + 1;
            $allKeys['stripe_account_id'] = 1;
        }
    }

    /* Multiply counter with leverage value */
    $final = ceil($leverageValPerKey * $counter);
    if($final > 100){
       $final = 100; 
    }
    $allKeys['percentage'] = $final;
    
    return $allKeys;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    }
    else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return number_format((float)($miles * 1.609344), 2, '.', '');
        } else if ($unit == "N") {
            return number_format((float)($miles * 0.8684), 2, '.', '');
        } else {
            return number_format((float)$miles, 2, '.', '');
        }
    }
}

function convertObjectToArray($data) {
    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }

    return $data;
}

function pinGenerator($strength = 16) {
    $input = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}

function getSiteConfigs($group=false){
    $configData = DB::table('config')->select()->get();
    foreach($configData as $key=>$configs){
        $site_configs[$configs->config_key] = $configs->value ;
        $config_groups[$configs->config_group][$configs->config_key] = $configs->value;
    }
    if(empty($group)){
        return $site_configs;
    }
    else{
        return $config_groups;
    }
}

function userAverageReview($userId){
    $allReviews = DB::table('user_reviews')->where('ur_user_reviewed_id',$userId);
    
    $allReviews = $allReviews->select(
        'ur_user_id',
        DB::raw('AVG(ur_hygiene_rating) as hygieneAvg'),
        DB::raw('AVG(ur_comm_rating) as communicationAvg'),
        DB::raw('AVG(ur_recommend_rating) as recommendedAvg')
    );

    $allReviews = $allReviews->get()->first();

    $sendArr = array(
        'hygiene_average'       => $allReviews->hygieneAvg,
        'communication_average' => $allReviews->communicationAvg,
        'recommended_average'   => $allReviews->recommendedAvg,
    );

    $allReviewCount = DB::table('user_reviews')->where('ur_user_reviewed_id',$userId)->get()->count();

    $mainAverage = ($allReviews->hygieneAvg + $allReviews->communicationAvg + $allReviews->recommendedAvg) / 3;
    
    $sendArr['complete_average'] = $mainAverage;
    $sendArr['total_reviews']    = $allReviewCount;
    
    return $sendArr;
}

function avg($sum=0,$count=0){
    return ($count)? $sum / $count: NAN;
}

function userAvgCalculator($id,$type=false){
    $reviews = DB::table('user_reviews')->where('ur_user_reviewed_id',$id)->get()->all();

    $hygieneAverage = $communiAverage = $recommeAverage = $overallReview = '';
    if(!empty($reviews)){
        $hygieneReviewSum       = array_sum(array_column($reviews, 'ur_hygiene_rating'));
        $communicationReviewSum = array_sum(array_column($reviews, 'ur_comm_rating'));
        $recommendReviewSum     = array_sum(array_column($reviews, 'ur_recommend_rating'));
        
        $hygieneAverage = avg($hygieneReviewSum,count($reviews));
        $communiAverage = avg($communicationReviewSum,count($reviews));
        $recommeAverage = avg($recommendReviewSum,count($reviews));

        $sumOfAverages  = $hygieneAverage + $communiAverage + $recommeAverage;
        $overallReview  = round(avg($sumOfAverages,3),'2');
    }

    if(empty($type)){
        return $overallReview;
    }
    else{
        $retunrArr = array(
            'hygiene'       =>  $hygieneAverage,
            'communiction'  =>  $communiAverage,
            'recommendation'=>  $recommeAverage,
            'overall'       =>  $overallReview,
            'count'         =>  count($reviews)
        );
        return $retunrArr;
    }
}

function get_quarter($i=0) {
    $y = date('Y');
    $m = date('m');
    if($i > 0) {
        for($x = 0; $x < $i; $x++) {
            if($m <= 3) { $y--; }
            $diff = $m % 3;
            $m = ($diff > 0) ? $m - $diff:$m-3;
            if($m == 0) { $m = 12; }
        }
    }
    switch($m) {
        case $m >= 1 && $m <= 3:
            $start = $y.'-01-01 00:00:01';
            $end = $y.'-03-31 00:00:00';
            break;
        case $m >= 4 && $m <= 6:
            $start = $y.'-04-01 00:00:01';
            $end = $y.'-06-30 00:00:00';
            break;
        case $m >= 7 && $m <= 9:
            $start = $y.'-07-01 00:00:01';
            $end = $y.'-09-30 00:00:00';
            break;
        case $m >= 10 && $m <= 12:
            $start = $y.'-10-01 00:00:01';
            $end = $y.'-12-31 00:00:00';
                break;
    }
    return array(
        'start' => $start,
        'end' => $end,
        'start_nix' => strtotime($start),
        'end_nix' => strtotime($end)                            
    );
}

function getWalletBalance($userId=false){
    $walletRecords  = DB::table('user_wallet')->where('uw_user_id',$userId)->orderBy('uw_txn_date','desc')->get()->all();

    $earnings = $withdrawals = 0;
    foreach($walletRecords as $record){
        if($record->uw_transaction_type=='credit'){
            $earnings+= $record->uw_txn_amt;
        }
        else if($record->uw_transaction_type=='debit' && ( $record->uw_txn_status=='accepted' || $record->uw_txn_status=='requested' )){
            $withdrawals+= $record->uw_txn_amt;
        }
    }

    $remainingInWallet = $earnings - $withdrawals;
    return $remainingInWallet;
}

function getUserSettingsViaEmail($userId){
    $getUser = DB::table('users')->where('id',$userId)->select('id')->get()->first();
    $notificationSettings = DB::table('user_notification_settings')->where('un_user_id',$getUser->id)->get()->all();

    $newArr = [];
    foreach ($notificationSettings as $key => $setting) {
        $newArr[$setting->un_setting_type]['email'] = $setting->un_send_email;
        $newArr[$setting->un_setting_type]['msg'] = $setting->un_send_message;
    }
    return $newArr;
}

function sendSMS($message,$recipient){
    $account_sid = getenv("TWILIO_SID");
    $auth_token = getenv("TWILIO_AUTH_TOKEN");
    $twilio_number = getenv("TWILIO_NUMBER");
    $client = new Client($account_sid, $auth_token);
    
    try{
        $client->messages->create($recipient, 
            ['from' => $twilio_number, 'body' => $message] );
    }
    catch(Exception $e){
        prd($e->getMessage());
    }
    
    return true;
}

function getKeywords($type=false){
    $keywords = DB::table('keywords')->get()->all();
    $langArr = [];
    foreach($keywords as $key => $word){
        if($type=='en'){
            $langArr[$word->name_key] = $word->name_en;
        }
        else if($type=='fr'){
            $langArr[$word->name_key] = $word->name_fr;
        }
    }
    return $langArr;
}

function slugify($text, string $divider = '-')
{
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function spainPriceFormat($price){
    return  number_format($price, 2, ',', '.');
}

function priceFormat($number){
    return number_format((float)$number, 2, '.', '');   
}

function check_base64_image($base64) {
    $img = imagecreatefromstring(base64_decode($base64));
    if (!$img) {
        return false;
    }

    imagepng($img, 'tmp.png');
    $info = getimagesize('tmp.png');

    unlink('tmp.png');

    if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
        return true;
    }

    return false;
}

function isValidTimezoneId($timezoneId) {
    try{
        new DateTimeZone($timezoneId);
    }catch(\Exception $e){
        return FALSE;
    }
    return TRUE;
} 

/** -- */
function getYoutubeEmbedUrl($url){
    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
    $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}

function getZipcode($address){
    if(!empty($address)){
        //Formatted address
        $formattedAddr = str_replace(' ','+',$address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false'); 
        $output1 = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
		$address_components=$output1->results[0]->address_components;
		for($i=0;$i<count($address_components);++$i){
		  if(array("administrative_area_level_1", "political")==$address_components[$i]->types){
			$state=$address_components[$i]->long_name;
		  }
		  if(array("country", "political")==$address_components[$i]->types){
			$country=$address_components[$i]->long_name;
		  }
		  }
		  return($state.', '.$country);
		}  
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
    $pieces = array();
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

/** ---- Date time functions */

/* Return Difference Between Two Dates */
function getDifference($date1 ,$date2 , $in ='d'){
  $diff = abs(strtotime($date2) - strtotime($date1));
  $years = floor($diff / (365*60*60*24));
  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
  $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
  $hours = floor($diff / 60 /  60);
  $minites = floor($diff / 60 );
  
  switch($in){
        case 'd':
           $op_days = $years*365 + $months*30 + $days; 
          return $op_days ;
           /* Return No of Days Between Two Days*/
      break;
      
      case 'h':
           $hours = $hours % 24;
          
          if($minites!=0){
              return  $hours +1;
          }
          return $hours ;
      /* Return No of Hours After Day */
      break;
  }
   
  if($years){
      return $years." years " ;
  }
  elseif($months){
      return $months." months "; 
  }
  elseif($days){
      return $days." days ";
  }
  elseif($hours){
      return $hours." hours ";
  }
  else{
      return $minites." minites ";
  } 
}

function getMonthName($date , $short = false){
    $monthNames  = array("January", "February", "March", "April", "May", "June",  "July", "August", "September", "October", "November", "December" );
   $name  = $monthNames[date("m", strtotime($date))-1] ;
   if($short)
       return substr($name,0,3);
   return $name ;
}

function getDayName($date , $short = false){
     switch(date("D", strtotime($date))){
        case 'Mon': return ucwords('monday');
       case 'Tue': return ucwords('tuesday');
       case 'Wed': return ucwords('wednesday');
       case 'Thu': return ucwords('thursday');
       case 'Fri': return ucwords('friday') ;
       case 'Sat': return ucwords('saturday');
       case 'Sun': return ucwords('sunday');
   }
   return ;
}

function getDMYFormat($data , $saperater="."){
   $timestamp = strtotime($data); 
   $format ="d".$saperater."m".$saperater."Y" ;
   $new_date = date($format , $timestamp);
   return $new_date ;
}

function hoursToMinutes($hours){
	$separatedData = explode('.', $hours);	
	$minutesInHours    = $separatedData[0] * 60;	
	if($separatedData[1]=='5')
	{
		$minutesInDecimals = $separatedData[1]*6;			
	}	
	$totalMinutes = $minutesInHours + $minutesInDecimals;
	return $totalMinutes;
}

function getDatesFromRange($start, $end){
    $dates = array($start);
    while(end($dates) < $end){
        $dates[] = date('Y-m-d H:i:s', strtotime(end($dates).' +1 day'));
    }
    return $dates;
}

function getMonthsInRange($startDate, $endDate) {
	$months = array();
	while (strtotime($startDate) <= strtotime($endDate)) {
		$months[] = array('year' => date('Y', strtotime($startDate)), 'month' => date('M', strtotime($startDate)), 'date' => date('d', strtotime($startDate)));
		$startDate = date('d M Y', strtotime($startDate.
			'+ 1 month'));
	}

	return $months;
}

function getTimeDifference($timedata){
	$years1=0;
	$months1=0;
	$days1=0;
	$day_difference1="";
	$hours1=0;
	$minutes1=0;
	
	$diff1 = strtotime(date("Y-m-d H:i:s"))-strtotime($timedata);
	$day_difference1 = ceil($diff1 / (60*60*24));
	$years = floor($diff1 / (365*60*60*24));
	$months = floor(($diff1 - $years1 * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24)/ (60*60*24));
	$hours = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24- $days1* 60*60*24)/(60*60));
	$minutes = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24- $days1* 60*60*24- $hours1*60*60)/(60));
	$seconds = floor(($diff1 - $years1 * 365*60*60*24 - $months1*30*60*60*24- $days1* 60*60*24- $hours1*60*60)/(3600));
	return array($years,$months,$days,$hours,$minutes,$seconds);	
}

function timeAgo($time_ago) {
    $time_ago =  strtotime($time_ago) ? strtotime($time_ago) : $time_ago;
	$time  = time() - $time_ago;

	switch($time):
	// seconds
	case $time <= 60;
	return 'less than a minute ago';
	// minutes
	case $time >= 60 && $time < 3600;
	return (round($time/60) == 1) ? 'a minute' : round($time/60).' minutes ago';
	// hours
	case $time >= 3600 && $time < 86400;
	return (round($time/3600) == 1) ? 'a hour ago' : round($time/3600).' hours ago';
	// days
	case $time >= 86400 && $time < 604800;
	return (round($time/86400) == 1) ? 'a day ago' : round($time/86400).' days ago';
	// weeks
	case $time >= 604800 && $time < 2600640;
	return (round($time/604800) == 1) ? 'a week ago' : round($time/604800).' weeks ago';
	// months
	case $time >= 2600640 && $time < 31207680;
	return (round($time/2600640) == 1) ? 'a month ago' : round($time/2600640).' months ago';
	// years
	case $time >= 31207680;
	return (round($time/31207680) == 1) ? 'a year ago' : round($time/31207680).' years ago' ;

	endswitch;
}

function getStartAndEndDate($week, $year){
	$week-=1;
    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-m-d', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-m-d', $time);
    return $return;
	
}

function timerange(){
	$startTime = strtotime('08:00am');   /* Find the timestamp of start time */
	$endTime = strtotime('08:00pm');       /* Find the timestamp of end time */
	$input = array();
	/* Run a loop from start timestamp to end timestamp */
	for ($i = $startTime; $i < $endTime; $i+=7200) {
		$input[] = array(
			"start_time" => date("h:i a", $i), 
			"end_time" => date("h:i a", ($i+7200))
		);
		
		$timeslot[] = date("H:i", $i).' - '.date("H:i", ($i+7200));
	}

	return $timeslot;
}

/** --  */
function getYoutubeIdFromUrl($url){
  
	preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

	if($match){
		return $match[1];
	}
	return false;
}

/** Map */

function distanceLatLong($lat1, $lon1, $lat2, $lon2, $unit) {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
	if ($unit == "K") 
	{
		return ($miles * 1.609344);
	} 
	else if ($unit == "N") 
	{
		return ($miles * 0.8684);
	} else 
	{
		$miles  = ($round == true ? round($miles) : round($miles, '1'));      	
		return $miles;
	}
}

function calculateDistanceBetweenTwoPoints($latitudeOne='', $longitudeOne='', $latitudeTwo='', $longitudeTwo='',$distanceUnit ='',$round=false,$decimalPoints=''){
	if (empty($decimalPoints)) 
	{
		$decimalPoints = '2';
	}
	if (empty($distanceUnit)) {
		$distanceUnit = 'KM';
	}
	$distanceUnit = strtolower($distanceUnit);
	$pointDifference = $longitudeOne - $longitudeTwo;
	$toSin = (sin(deg2rad($latitudeOne)) * sin(deg2rad($latitudeTwo))) + (cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * cos(deg2rad($pointDifference)));
	$toAcos = acos($toSin);
	$toRad2Deg = rad2deg($toAcos);

	$toMiles  =  $toRad2Deg * 60 * 1.1515;
	$toKilometers = $toMiles * 1.609344; //1.609344
	$toNauticalMiles = $toMiles * 0.8684;
	$toMeters = $toKilometers * 1000;
	$toFeets = $toMiles * 5280;
	$toYards = $toFeets / 3;

	switch (strtoupper($distanceUnit)) 
	{
		case 'ML'://miles
				$toMiles  = ($round == true ? round($toMiles) : round($toMiles, $decimalPoints));
				return $toMiles;
			break;
		case 'KM'://Kilometers
			$toKilometers  = ($round == true ? round($toKilometers) : round($toKilometers, $decimalPoints));
			return $toKilometers;
			break;
		case 'MT'://Meters
			$toMeters  = ($round == true ? round($toMeters) : round($toMeters, $decimalPoints));
			return $toMeters;
			break;
		case 'FT'://feets
			$toFeets  = ($round == true ? round($toFeets) : round($toFeets, $decimalPoints));
			return $toFeets;
			break;
		case 'YD'://yards
			$toYards  = ($round == true ? round($toYards) : round($toYards, $decimalPoints));
			return $toYards;
			break;
		case 'NM'://Nautical miles
			$toNauticalMiles  = ($round == true ? round($toNauticalMiles) : round($toNauticalMiles, $decimalPoints));
			return $toNauticalMiles;
			break;
	}
}

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
	// convert from degrees to radians
	$latFrom = deg2rad($latitudeFrom);
	$lonFrom = deg2rad($longitudeFrom);
	$latTo = deg2rad($latitudeTo);
	$lonTo = deg2rad($longitudeTo);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	return $angle * $earthRadius;
}

/** 
 * create time range by CodexWorld
 *  
 * @param mixed $start start time, e.g., 7:30am or 7:30 
 * @param mixed $end   end time, e.g., 8:30pm or 20:30 
 * @param string $interval time intervals, 1 hour, 1 mins, 1 secs, etc.
 * @param string $format time format, e.g., 12 or 24
 */ 
function create_time_range($start, $end, $interval = '30 mins', $format = '12') {
    $startTime = strtotime($start); 
    $endTime   = strtotime($end);
    $returnTimeFormat = ($format == '12')?'g:i A':'G:i';

    $current   = time(); 
    $addTime   = strtotime('+'.$interval, $current); 
    $diff      = $addTime - $current;

    $times = array(); 
    while ($startTime < $endTime) { 
        $times[] = date($returnTimeFormat, $startTime); 
        $startTime += $diff; 
    } 
    $times[] = date($returnTimeFormat, $startTime); 
    return $times; 
}

/* google recaptcha 3 */
function post_captcha($user_response, $secret_key) {
    $fields_string = '';
    $fields = array(
        'secret' => $secret_key,
        'response' => $user_response
    );
    foreach($fields as $key=>$value)
    $fields_string .= $key . '=' . $value . '&';
    $fields_string = rtrim($fields_string, '&');

    // prd($fields_string);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}


/*** Check Ios Device */
function checkIos(){
	//Detect special conditions devices
	$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
	$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$Mac     = stripos($_SERVER['HTTP_USER_AGENT'],"Mac");
	$Win     = stripos($_SERVER['HTTP_USER_AGENT'],"Win");
	
	if( $iPod || $iPhone ||  $iPad  ||  $Mac ){
		return true;
	}else{
		return false;
	}
}

function displayTextWithLinks($s) {
    return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
}