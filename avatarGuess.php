<?php
function guess_avatar_from_email($email, $bestguess = false) {
    $email       = strtolower(trim($email));
    $emailParts  = explode('@', $email);
    $username    = urlencode($emailParts[0]);
    $domainParts = explode(".", $emailParts[1]);
    $uri = "";
    if($domainParts[0] == 'gmail') {
        $uri = get_profile_img_google_profile($username);
    }
    if(!$uri) {
        $uri = get_profile_img_gravatar($email);
    }
    if($bestguess) {
        if(!$uri && strlen($username) > 7) {
            // this is a really cheap trick, that may or may not give us a useful image
            // it works on the assumption that screen names are often the same across many services
            $uri = get_profile_img_facebook($username);
            if(!$uri) {
                $uri = get_profile_img_skype($username);
            }
        }
    }
    return($uri);
}

function get_profile_img_facebook($username) {
    $uri = "https://graph.facebook.com/".$username."/picture?width=100&height=100";
    return(get_remote_img_src($uri, 390));
}

function get_profile_img_google_profile($username) {
    $uri = 'https://www.google.com/s2/photos/profile/' . $username . '?sz=100';
    return(get_remote_img_src($uri));
}

function get_profile_img_skype($username) {
    $uri = "http://api.skype.com/users/".$username."/profile/avatar";
    return(get_remote_img_src($uri, 937));
}

function get_profile_img_gravatar($email) {
    $hash = md5($email);
    $uri  = 'http://www.gravatar.com/avatar/' . $hash . '?s=100&d=404';
    return(get_remote_img_src($uri));
}

function get_remote_img_src($uri, $bytecheck=null) {
    $headers    = @get_headers($uri, true);
    if(preg_match("|302|", $headers['0'])) {
        if(!(isset($headers['Location']) && strlen($headers['Location'])>1)) {
            $uri="";
        }
    } elseif (!preg_match("|200|", $headers[0])) {
        $uri = "";
    }
    if($uri && $bytecheck && $headers && isset($headers['Content-Length'])) {
        if(is_array($headers['Content-Length'])) {
            foreach($headers['Content-Length'] as $length) {
                if($length == $bytecheck) {
                    $uri = "";
                    break;
                }
            }
        } elseif((int)$headers['Content-Length'] == $bytecheck) {
            $uri = "";
        }
    }
    return($uri);
}
?>